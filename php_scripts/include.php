<?php
	//
	// This file contains global variables and utility functions shared by other PHP scripts.
	//
	
	function setupDropdown(array $arr, $selected) {
		// Go through the array of items to be included in the dropdown
		foreach ($arr as $y) {
			// Enter the value into the dropdown
			echo "<option value='" . $y . "'";
			// If this item is what the user selected, select this item again
			if ($y == $selected) {
				echo " selected='selected'";
			}
			echo ">" . $y . "</option>";
		}
	};
	echo "<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,700,300,800' rel='stylesheet' type='text/css'>";
	echo "<link rel='shortcut icon' href='icons/logo.png' />";
	
	// MySQL Database Host
	$mysql_host = "localhost";

	// MySQL Database User
	$mysql_user = "root";

	// MySQL Database Password
	$mysql_password = "";

	// MySQL Database Name
	$mysql_database = "bike";

	// MySQL Connect string
	$link = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database);		

	// Set MySQL character set encoding to UTF-8 because the tables are UTF-8 coded.
	mysqli_set_charset($link, "utf8");	
	
	if(isset($_POST['distance'])){
		mysqli_query($link,
			"INSERT INTO Rides VALUES ('20" . 
			$_POST['year'] . "-" . 
			$_POST['month'] . "-" . 
			$_POST['day'] . "', '" . 
			$_POST['location'] . "', " . 
			$_POST['distance'] . ", " . 
			$_POST['hours'] . ", " . 
			$_POST['minutes'] . ", " . 
			$_POST['seconds'] . ", '', '');"
		);
	}

	// The following class extends the SimpleXML class so that we can save a well-indented XML document.
	// Source: http://coffeerings.posterous.com/php-simplexml-and-cdata
	// http://stackoverflow.com/questions/6260224/how-to-write-cdata-using-simplexmlelement
	class SimpleXMLExtended extends SimpleXMLElement {
		// This function refines the output from SimpleXML's saveXML() function so that it is properly indented.
		public function saveXML_fixed($file_name="")
		{
			$xml_contents = $this->saveXML();
			$dom = new DOMDocument();
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($xml_contents);
			$xml_contents = $dom->saveXML();
			// DOMDocument seems to wrongly remove the line breaks after many comment nodes, so we add them back.
			$xml_contents = preg_replace('/([^>]+)\-\-></m', "$1-->\n<", $xml_contents);
			// DOMDocument indents each level with two white spaces and does not provide a way to redefine the indentation, so we use a regular expression to replace all leading spaces before a start tag by the right number of tab characters.
			$xml_contents = preg_replace_callback('/^( +)</m', function($a) {
						return str_repeat("\t",intval(strlen($a[1]) / 2)).'<';  
					},
					$xml_contents
			);
			if (empty($file_name))
				return $xml_contents;
			else return file_put_contents($file_name, $xml_contents);
		}
	}
	
	class Ride
	{
		public $date;
		public $distance;
		public $hours;
		public $minutes;
		public $seconds;
		
		
		public function __construct($_date, $_distance, $_hours, $_minutes, $_seconds)
		{
			$date = $_date;
			$distance = $_distance;
			$hours = $_hours;
			$minutes = $_minutes;
			$seconds = $_seconds;
		}
		
		
	}
	
	//$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY date");
?>