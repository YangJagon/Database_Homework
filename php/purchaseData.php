<?php
include "connect.php";
include "functions.php";
$type = $_GET["type"];

if($type == "products")
    echo json_encode(get_table_data("products"));
else if($type == "employees")
    echo json_encode(get_table_data("employees"));
else if($type == "customers")
    echo json_encode(get_table_data("customers"));
else if($type == "purid")
{
    $res = get_table_data("purchases");
    $r = "p000";
    for($i=0; $i<count($res); $i++)
        if($res[$i]["purid"] > $r)
            $r=$res[$i]["purid"];
    echo substr($r, 1, 3);
}
?>