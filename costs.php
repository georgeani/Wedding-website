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
</br>
<h1 align="center"> Available Venues </h1>
</br>
<table class="table">
<?php
$host='localhost';
$dataBaseName='coa123wdb';
$username='coa123wuser';
$password='grt64dkh'; 
$dsn = "mysql://$username:$password@$host/$dataBaseName";

//creating the connection with the database
require_once('MDB2.php');
$dataBase = & MDB2::connect($dsn);

//checking if an error has occured
if (PEAR::isError($dataBase)) {
	die($dataBase->getMessage());
}

$dataBase->setFetchMode(MDB2_FETCHMODE_ASSOC);

//used to get party size and date and remove any letters and whitespaces
$partySize = $_GET['partySize'];
$partySize = preg_replace('/[^0-9]/', '', $partySize);
$finalSize = intval($partySize);
$dateGiven = strval($_GET['date']);
$finalDate = explode('/', $dateGiven);

for($i=0;$i<3;$i++){
	$finalDate[$i] = preg_replace('/[^0-9]/', '', $finalDate[$i]);
}

$dateFormat = $finalDate[2] . "-" . $finalDate[1] . "-" . $finalDate[0];

$pricing = "";
$inTheRow = "";

//checks whether the date is on a weekend or on a weekday in order to presultent the correcr pricing
if (date('l', strtotime($dateFormat)) == 'Sunday' || date('l', strtotime($dateFormat)) == 'Saturday'){
	$pricing = "`weekend_price`";
	$inTheRow = "weekend_price";
} else {
	$pricing =  "`weekday_price`";
	$inTheRow = "weekday_price";
}

//this will be used to check whether the inputs are correct
$check=true;
if($dateFormat=="" || $finalSize<=0){
	$check=false;
}

if ($check==true){
	//executing the query
	$sql = 'SELECT `venue_id`, `name`, `capacity` , ' . $pricing . ', `licensed` FROM `venue` WHERE `capacity` >=' . $finalSize . ' AND`venue_id` NOT IN( SELECT `venue_id` FROM `venue_booking` WHERE `date_booked` = "' . $dateFormat . '" )';
	$result = & $dataBase ->query($sql);
	
	//creating the table
	echo '<table class="table">';

	echo "<tr>";
	echo "<td class='cell1'>ID</td>";
	echo "<td class='cell1'>Name</td>";
	echo "<td class='cell1'>Capacity</td>";
	echo "<td class='cell1'>Pricing</td>";
	echo "<td class='cell1'>Licensed</td>";
	echo '</tr>';
	$license = "";
	
	//presultenting the data of the query
	while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
		echo "<tr>";
		echo '<td class="cell2">'.$row["venue_id"].'</td>';
		
		echo '<td class="cell2">'.$row["name"].'</td>';
		
		echo '<td class="cell2">'.$row["capacity"].'</td>';
		
		echo '<td class="cell2">'.$row[$inTheRow].'</td>';
		
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
	//if the variables are incorrect this message is shown
	echo '<h2 align="center"> The variables inputted are wrong </h2>';
}
?>

</body>
</html>