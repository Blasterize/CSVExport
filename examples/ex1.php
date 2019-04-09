<?php

require_once "CSVExport.php";

// Array containing the data. Be sure to put the keys in the same order for each line.
$contacts = array(
    "0" => array(
	"id" => 1,
	"name1" => "John",
	"name2" => "Doe",
	"age" => "42",
	"workplaces" => array(
	    "0" => array(
	        "address1" => "306 Baker Street",
	        "address2" => "Office 24",
	        "city" => "London",
	        "country" => "United Kingdom"
	    ),
	    "1" => array(
	        "address1" => "10 Downing Street",
	        "address2" => "",
	        "city" => "London",
	        "country" => "United Kingdom"
	    )
	)
    ),
    "1" => array(
	"id" => 2,
	"name1" => "Julia",
	"name2" => "Doe",
	"age" => "36",
	"workplaces" => array(
	    "0" => array(
	        "address1" => "306 Baker Street",
	        "address2" => "Office 67",
	        "city" => "London",
	        "country" => "United Kingdom"
	    )
	)
    ),
    "2" => array(
	"id" => 3,
	"name1" => "Cynthia",
	"name2" => "Doe",
	"age" => "61",
	"workplaces" => array(
	    "0" => array(
	        "address1" => "25 Abbey Street",
	        "address2" => "Office 44",
	        "city" => "Birmingham",
	        "country" => "United Kingdom"
	    ),
	    "1" => array(
	        "address1" => "10 Downing Street",
	        "address2" => "",
	        "city" => "London",
	        "country" => "United Kingdom"
	    ),
	    "2" => array(
	        "address1" => "852 Bowl Street",
	        "address2" => "Office 364",
	        "city" => "Manchester",
	        "country" => "United Kingdom"
	    ),
	)
    )
);

// Key names to replace in the final CSV file
$keys = array(
    "address1" => "Address",
    "address2" => "Additional address",
    "name1" => "First name",
    "name2" => "Last name",
);

// CSVExport initialization
$csv = new CSVExport();

// Call to the main method. It works like this : generate($filename,$data,$delimiter = ";",$keys = array(),$output_stream = true,$enclose = false,$charset = 'utf-8')
$csv->generate("ex1.csv",$contacts,";",$keys);


