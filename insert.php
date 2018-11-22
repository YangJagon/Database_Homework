<?php
$tablename=$_POST["tablename"];

include "initial.php";
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
        $str = $str."null";
    else{
        if(stripos("%".$types[$i], "date") || stripos("%".$types[$i], "char"))
            $argv = "'".$argv."'";
        $str = $str.$argv;
    }
}

$res = mysqli_query($mysqli, "INSERT INTO ".$tablename." VALUES(".$str.")");
header("Location: http://127.0.0.1/database/index.php?".$tablename);  
?>