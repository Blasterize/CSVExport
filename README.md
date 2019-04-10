# CSVExport

**CSVExport** is a utility class written in PHP7 for generating CSV files from multi-dimensional arrays.

## Getting started

To install CSVExport, you just need to download the main PHP class _CSVExport.php_ and import it in your project.

```php
require_once "path/to/CSVExport.php";
```


## Using CSVExport

### Data formatting

CSVExport can reformat any multi-dimensional PHP array into a CSV file. You just have to be sure to keep the different array keys in the same order for each array, for example :

```php
$data = array(
    "0" => array(
        "key1" => 1,
        "key2" => "value",
        "array1" => array(
            "0" => array(
                "array_key1" => "bar",
                "array_key2" => "foo"
            ),
            "1" => array(
                "array_key1" => "foo",
                "array_key2" => "bar"
            )
        )
    ),
    "1" => array(
        "key1" => 2,
        "key2" => "value2",
        "array1" => array(
            "0" => array(
                "array_key1" => "foo",
                "array_key2" => "bar",
            )
        )
    )
);
```

By default, the first line of the generated file will contain the existing key names (with additional indexes if needed) for each value. Each array doesn't have to be the same size ; CSVExport will add blank values to keys not existing in all arrays.


### CSV generation

#### Default configuration

Once the class is initialized, one single call to the _generate()_ method is needed to generate the CSV file from your data. Only two parameters are required :

* **filename** _(string)_ - The name to give to the generated file.
* **data** _(array)_ - The data you want to insert in the generated file.

Here is a short example :

```php
$csv = new CSVExport();
$csv->generate("your_file.csv",$data);
```

Your CSV file is now generated and ready to download !

#### Optional parameters

Several optional parameters are available, if you need them. Here they are :

* **delimiter** _(string, default : ";")_ - Allows you to change the delimiter between the values in the file.
* **keys** _(array, default : array())_ - Allows you to change the key names to bind to the first line of the file.
* **output_stream** _(boolean, default : true)_ - Specifies if the file should be opened on the output stream or if it already exists.
* **enclose** _(boolean, default : false)_ - Specifies if all values should be enclosed by double quotes. Values containing spaces will be enclosed no matter what.
* **charset** _(string, default : "utf-8")_ - Allows you to change the charset of the output file.

Here is another example :

```php
$keys = array(
    "key1" => "My new key name 1",    
    "key2" => "Awesome key name 2"   
);

$csv = new CSVExport();
$csv->generate("your_file.csv",$data,",",$keys,true,true,"iso-8859-1");
```

The newly generated file will now have all values enclosed by double quotes and separated by commas, with modified keys on the first line and will be encoded in the "ISO-8859-1" charset. 

#### Use cases

For different cases of use, see examples in the [examples](https://github.com/Blasterize/CSVExport/blob/master/examples) folder.



## Project information

### Author

* **Guillaume QUINETTE** / **Blasterize** - [dev.gquinette@gmail.com](mailto:dev.gquinette@gmail.com)


### License

This project is copyrighted under the MIT license. See the [LICENSE](https://github.com/Blasterize/CSVExport/blob/master/LICENSE) file for further information. 