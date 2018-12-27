<?php 
$tablename=$_GET["tablename"];
include "connect.php";
include "functions.php";

global $mysqli;
$fields = get_table_fields($tablename);
$types = get_table_types($tablename);
$data = get_table_data($tablename);
$num=0;
$idn=null;
$idd=null;
foreach ($_GET as $i => $value) {
    $num++;
    if($num==2)
    {
        $idn=$i;
        $idd=$value;
    }
}

show_table_up($tablename);
?>