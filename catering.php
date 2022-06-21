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
</br>
<title> Cost per person </title> 
</head>

<body>
<h1 align="center"> Costs for your group</h1>

<?php
//gets the variables that are inputted in the form and removes any white spaces
$partySizeMin = str_replace(' ', '',$_GET["min"]);
$partySizeMax = str_replace(' ', '',$_GET["max"]);;
$cvalues = array(str_replace(' ', '', $_GET["c1"]), str_replace(' ', '', $_GET["c2"]), str_replace(' ', '', $_GET["c3"]), str_replace(' ', '', $_GET["c4"]), str_replace(' ', '', $_GET["c5"]));

//removes all letters from the avraibales inputted
$partySizeMax = preg_replace('/[^0-9]/', '', $partySizeMax);
$partySizeMin = preg_replace('/[^0-9]/', '', $partySizeMin);

for ($i=0;$i<5;$i++){
	$cvalues[$i] = preg_replace('/[^0-9]/', '', $cvalues[$i]);
}
//creates space between the title of the page displayed
echo "</br>";
$check = true;

//used to check if the variables that are inputted in the form are correct
for ($i=0;$i<5; $i++){
	if(intval($cvalues[$i])<0 || $cvalues[$i]=='' || $partySizeMax<$partySizeMin || !is_numeric($partySizeMax)<0 || is_numeric($partySizeMin)<0 || intval($partySizeMin)<0 || intval($partySizeMax)<0){
		$check = false;
		$i=100;
	}
}

$multiplier = 5;

if(($partySizeMax - ($partySizeMin))%2==0){
	$multiplier = 2;
} 
if(($partySizeMax - ($partySizeMin))%3==0){
	$multiplier = 3;
} 
if(($partySizeMax - ($partySizeMin))%7==0){
	$multiplier = 7;
}
if(($partySizeMax - ($partySizeMin))%5==0){
	$multiplier = 5;
}else {
	$multiplier = 1;
}
//if they are correct this table is produced
if($check==true){
	$i=-1;
	echo '<table class="table">';
	while($i<=$partySizeMax){
		echo '<tr>';
		for($j=0; $j < 5; $j++) {
			if ($i == -1 && $j == 0 || $partySizeMin>$partySizeMax){
				echo "<td class='cell1'>cost per person: </br> party size (column)</br>" . '</td>'; // works
			}
			elseif($i == -1 && $j >= 1) {
					echo '<td class="cell1">'.$cvalues[($j-1)].'</td>';
			}
			elseif($i >= -1 && $j == 0){
					echo '<td class="cell1">'.$i.'</td>';
			} 
			else{
					echo '<td class="cell2">'.$cvalues[($j-1)]*$i.'</td>';
			}
		}
		echo '</tr>';
		if($i==-1){
			$i = $partySizeMin;
		}else{
			$i = $i + $multiplier;
		}
	}
	echo '</table>';
} elseif ($check==false) {
	//if the variables are incorrect this message is shown
	echo '<h2 align="center"> The variables inputted are wrong </h2>';
}
?>

</body>
</html>