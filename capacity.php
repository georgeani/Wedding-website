<!DOCTYPE html>
<html>
<head>
<!-- setting up the webpages style-->
<style type="text/css">
body{
  background-color:#515cbd;}
table.table{
  font-size:1.5em;
  text-align:center;
  width:60%;
  margin-left:auto;
  margin-right:auto;
  border-style:outset;
  border-width:0.3em;
  border-color:#263369;}
td.cell1{
  border-style:inset;
  border-width:0.15em;
  border-color:#5f5f96;
  background-color:#9999ff;}
td.cell2{
  border-style:inset;
  border-width:0.15em;
  border-color:#5f5f96;
  background-color:#39d4ad;
}
td:hover{
  background-color:#c2482d;}
</style>
<title> Available Venues </title> 
</head>

<body>

<table class="table">
<?php

$host='localhost';
$dbName='coa123wdb';
$username='coa123wuser';
$password='grt64dkh';
$dsn = "mysql://$username:$password@$host/$dbName"; 
require_once('MDB2.php'); 

$db = & MDB2::connect($dsn); // make connection, & Assign By Reference

// alert if an error occur
if (PEAR::isError($db)) { 
	die($db->getMessage());
}

$db->setFetchMode(MDB2_FETCHMODE_ASSOC);

// getting the values of the variables and removing any space and letter, then converting it to strings in order to work in the query
$minCapacity = $_GET['minCapacity'];
$minCapacity = preg_replace('/[^0-9]/', '', $minCapacity);
$finalMin = strval($minCapacity);
$maxCapacity = $_GET['maxCapacity'];
$maxCapacity = preg_replace('/[^0-9]/', '', $maxCapacity);
$finalMax = strval($maxCapacity);

//checking if the inputted variables are correct
$check = true;
if($minCapacity>=$maxCapacity || $minCapacity=="" || $maxCapacity==""){
	$check=false;
}

echo "</br>";
echo "</br>";
echo "</br>";

echo '<h1 align="center"> Venue </h1>';

if ($check==true){
	
	//executing the query
	$sql = 'SELECT * FROM venue WHERE licensed = 1 and capacity between '. $minCapacity .' and '. $maxCapacity;
	$result = & $db ->query($sql);
	
	// creating the table
	echo '<table class="table">';

	echo "<tr>";
	echo "<td class='cell1'>ID</td>";
	echo "<td class='cell1'>Name</td>";
	echo "<td class='cell1'>Capacity</td>";
	echo "<td class='cell1'>Weekend Price</td>";
	echo "<td class='cell1'>Weekday Price</td>";
	echo "<td class='cell1'>Licensed</td>";
	echo '</tr>';
	
	//presultenting the resultults of the query
	while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
		echo "<tr>";
		echo '<td class="cell2">'.$row["venue_id"].'</td>';
		
		echo '<td class="cell2">'.$row["name"].'</td>';
		
		echo '<td class="cell2">'.$row["capacity"].'</td>';
		
		echo '<td class="cell2">'.$row["weekend_price"].'</td>';
		
		echo '<td class="cell2">'.$row["weekday_price"].'</td>';
		
		//makes seeing if a shop is licensed easier
		$licensed ='';
		if ($row["licensed"]==1){
			$licensed = 'Yes';
		} else{
			$licensed = 'No';
		}
		
		echo '<td class="cell2">'.$licensed.'</td>';
		echo "</tr>";
	}
	echo '</table>';
}
if ($check==false){
	//if the variables are incorrect this message is shown
	echo '<h2 align="center"> The variables inputted are wrong </h2>';
}
?>

</body>
</html>