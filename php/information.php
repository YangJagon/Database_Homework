<?php
include "connect.php";
include "functions.php";

$tablename = $_GET["tablename"];
$type = $_GET["type"];

$fields = get_table_fields($tablename);
$types = get_table_types($tablename);
$data = get_table_data($tablename);

if($type=="table")
    echo table2html($tablename);
else if($type == "form")
    echo form2html($tablename);
?>