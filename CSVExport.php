<?php

/**
 * Class exporting any information from a multi-dimensional array into a CSV file.
 *
 * @author Guillaume QUINETTE / Blasterize <dev.gquinette@gmail.com>
 */
class CSVExport
{
    protected $blank;

    /**
     * Constructor of the class.
     */
    function __construct()
    {
        $this->blank = array();
    }


    /**
     * Encloses the value with double quotes. Adapted from dearsina's answer at http://www.syntaxbook.com/post/38641-forcing-fputcsv-to-use-enclosure-for-all-fields.
     *
     * @param string $value The value to enclose.
     * @return string
     */
    function enclose($value)
    {
        $value = str_replace('\\"','"',$value);

        $value = str_replace('"','\"',$value);

        return '"'.$value.'"';
    }


    /**
     * Iterates on the max depth of the data array and appends data to the results array.
     *
     * @param array $data The data to iterate on.
     * @param int|string $index (Optional) The index of the results array.
     * @return array
     */
    function iterate($data,$index = "")
    {
        $res = $index == "" ? $this->blank : array();

        foreach($data as $key => $value)
        {
            if(gettype($value) == "array")
            {
                $index2 = 1;

                foreach($value as $value2)
                {
                    $res = array_merge($res,$this->iterate($value2,$index."__".$index2));
                    $index2++;
                }
            }

            else
            {
                $res[$key.$index] = $value;
            }
        }

        return $res;
    }


    /**
     * Formats the data to put in the CSV file.
     *
     * @param array $data The data to format.
     * @param array $keys (Optional) The keys to bind to the results array.
     * @return array
     */
    function formatData($data,$keys = array())
    {
        foreach($data as $d)
        {
            if(!is_array($d))
            {
                $data = array($data);
                break;
            }
        }

        $lines = array();

        foreach($data as $k => $line)
        {
            $res = $this->iterate($line);

            if(!empty($keys))
            {
                $res2 = array();

                foreach($res as $key => $value)
                {
                    $exp = explode("__",$key);

                    if(key_exists($exp[0],$keys))
                    {
                        $res2[$keys[$exp[0]].(sizeof($exp) > 1 ? "__" : "").join("__",array_splice($exp,1))] = $value;
                    }

                    else
                    {
                        $res2[$key] = $value;
                    }
                }

                array_push($lines,$res2);
            }

            else
            {
                array_push($lines,$res);
            }
        }

        return $lines;
    }


    /**
     * Iterates on the max depth of the data array and appends data to the blank array.
     *
     * @param array $data The data to iterate on.
     * @param int|string $index (Optional) The index of the results array.
     */
    function blankIterate($data,$index = "")
    {
        foreach($data as $key => $value)
        {
            if(gettype($value) == "array")
            {
                $index2 = 1;

                foreach($value as $value2)
                {
                    $this->blankIterate($value2,$index."__".$index2);
                    $index2++;
                }
            }

            else
            {
                $this->blank[$key.$index] = "";
            }
        }

        return;
    }


    /**
     * Generates a blank array with the maximum number of data.
     *
     * @param array $data The data to insert in the CSV file.
     */
    function genBlankArray($data)
    {
        foreach($data as $d)
        {
            if(!is_array($d))
            {
                $data = array($data);
                break;
            }
        }

        foreach($data as $k => $line)
        {
            $this->blankIterate($line);
        }
    }


    /**
     * Generates a CSV file with the given data.
     *
     * @param string $filename The name to give to the file.
     * @param array $data The data to put to the file.
     * @param string $delimiter (Optional) The delimiter for the file generation.
     * @param array $keys (Optional) The key names to bind to the results array.
     * @param bool $output_stream (Optional) Specifies if the file should be opened on the output stream or if it already exists.
     * @param bool $enclose (Optional) Specifies if all values should be enclosed by double quotes.
     * @param string $charset (Optional) The charset of the output file.
     */
    function generate($filename,$data,$delimiter = ";",$keys = array(),$output_stream = true,$enclose = false,$charset = 'UTF-8')
    {
        $this->genBlankArray($data);

        $lines = $this->formatData($data,$keys);

        $exp = explode(".csv",$filename);

        $file = sizeof($exp) > 1 && $exp[sizeof($exp) - 1] == "" ? $filename : $filename.".csv";

        if($output_stream)
        {
            // CSV headers
            header('Content-Encoding: '.$charset);
            header('Content-Type: text/csv; charset='.$charset);
            header('Content-Disposition: attachment; filename='.$file);
            header("Content-Transfer-Encoding: binary");

            $output = fopen('php://output', 'w');
        }

        else
        {
            $output = fopen($file, 'w');
        }

        if($charset === "UTF-8")
        {
            fputs($output,"\xEF\xBB\xBF"); // UTF-8 BOM
        }

        if(!empty($lines) && is_array($lines[0]))
        {
            $enclose ? fputs($output, implode($delimiter, array_map(
                    function($value) // Necessary for object context
                    {
                        return $this->enclose($value);
                    }, array_keys($lines[0])))."\r\n") : fputcsv($output,array_keys($lines[0]),$delimiter);
        }

        else
        {
            $enclose ? fputs($output, implode($delimiter, array_map(
                    function($value)
                    {
                        return $this->enclose($value);
                    }, array_keys($lines)))."\r\n") : fputcsv($output,array_keys($lines),$delimiter);
        }

        foreach($lines as $line)
        {
            $enclose ? fputs($output, implode($delimiter, array_map(
                    function($value)
                    {
                        return $this->enclose($value);
                    }, $line))."\r\n") : fputcsv($output,$line,$delimiter);
        }

        fclose($output);

        $this->blank = array();
    }
}
