<?php
include "connect.php";
include "functions.php";

//var_dump($_POST);
$tablename=$_POST["tablename"];
$idn=$_POST["idname"];
$idd=$_POST["iddata"];
global $mysqli;
$fields = get_table_fields($tablename);
$types = get_table_types($tablename);
$data = get_table_data($tablename);
$str="";

for($i=0; $i < count($fields); $i++)
{
    if($i>0)
        $str=$str.", ";
    $argv=$_POST[$fields[$i]];

    if($argv == null)
        $argv = "null";
    else if(stripos("%".$types[$i], "date") || stripos("%".$types[$i], "char"))
        $argv = "'".$argv."'";

    $str = $str.$fields[$i]."=".$argv;
}

if(stripos("%".$types[0], "date") || stripos("%".$types[0], "char"))
    $idd = "'".$idd."'";

$res = mysqli_query($mysqli, "UPDATE ".$tablename." SET ".$str." WHERE ".$idn."=".$idd);
//header("Location: http://127.0.0.1/database/index.html?".$tablename);
//var_dump($res);
echo json_encode($res);  
?>