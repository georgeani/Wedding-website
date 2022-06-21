<?php 

$host='localhost';
$dataBaseName='coa123wdb';
$username='coa123wuser';
$password='grt64dkh'; 


//setting up the database
require_once('MDB2.php');
$dataBase = & MDB2::connect("mysql://$username:$password@$host/$dataBaseName");


if (PEAR::isError($dataBase)) {
die($dataBase->getMessage());
}

$dataBase->setFetchMode(MDB2_FETCHMODE_ASSOC);

$sql = $_GET["sql"];

//if the query is not empty execute the query
if ($sql!=""){
	
	$result = $dataBase->queryAll($sql);
if (PEAR::isError($result)) 
{
	die($result->getMessage());
}

}

echo json_encode($result);

?> 