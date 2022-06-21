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
<title> Details about the venue </title> 
</head>

<body>
</br>
<h1 align="center"> Details about the venue </h1>
</br>
<table class="table">
<?php

$host='localhost';
$dataBaseName='coa123wdb';
$username='coa123wuser';
$password='grt64dkh'; 
$dsn = "mysql://$username:$password@$host/$dataBaseName";

require_once('MDB2.php'); // API package
$dataBase = & MDB2::connect($dsn); // connecting to the database

if (PEAR::isError($dataBase)) { // alert for errors or use global error handler
die($dataBase->getMessage());
}

//setting the fetchmode
$dataBase->setFetchMode(MDB2_FETCHMODE_ASSOC);

//fetching the ID of the venue
$venue = $_GET['venueId'];

//replacing the letters and space of the venue's ID variable
$venue = preg_replace('/[^0-9]/', '', $venue);

//checking whether the variable is correct
$check = true;
if ($venue=="" || intval($venue)<0 || intval($venue)>10){
	$check = false;
}

//if correct the query will be executed and the table will be created
if ($check==true){
	echo '<table class="table">';
	
	//execution of the query
	$sql = 'select * from venue where venue_id=' . $venue;
	$result = & $dataBase ->query($sql);

	echo "<tr>";
	echo "<td class='cell1'>ID</td>";
	echo "<td class='cell1'>Name</td>";
	echo "<td class='cell1'>Capacity</td>";
	echo "<td class='cell1'>Weekend Price</td>";
	echo "<td class='cell1'>Weekday Price</td>";
	echo "<td class='cell1'>Licensed</td>";
	echo '</tr>';
	
	//created to show if the shop is licensed or not
	$license = "";
			
	while ($row = $result->fetchRow()) {
		echo "<tr>";
		echo '<td class="cell2">'.$row["venue_id"].'</td>';
		
		echo '<td class="cell2">'.$row["name"].'</td>';
		
		echo '<td class="cell2">'.$row["capacity"].'</td>';
		
		echo '<td class="cell2">'.$row["weekend_price"].'</td>';
		
		echo '<td class="cell2">'.$row["weekday_price"].'</td>';
		
		//checks whether the shop is licensed or not
		if ($row["licensed"]==1){
			$license = "Yes";
		} else{
			$license = "No";
		}
		echo '<td class="cell2">'.$license.'</td>';
		echo "</tr>";
	}
	echo '</table>';
}
if ($check==false){
	//if the variable is incorrect this message is shown
	echo '<h2 align="center"> The variable inputted is wrong </h2>';
}
?>

</body>
</html>