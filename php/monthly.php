<?php 
include "connect.php";
$pid = $_GET["pid"];
$name = $_GET["name"];

function table2html($pid, $name)
{
    global $mysqli;
    global $data;
    global $pid;
    $fields = array('PNAME', 'MONTH', 'YEAR', 'TOTAL_QUANTITY', 'TOTAL_PRICE', 'AVERAGE_PRICE');


    //var_dump($fields);

    $cnum = count($fields);
    $result = "";

    $result = $result."<table><caption style='font-weight:normal;'>".strtoupper($name)."&nbsp&nbsp</caption>";

    $result = $result."<tr id=\"fields\">";
    foreach($fields as $name)
        $result = $result."<th>".strtoupper($name)."</th>";
    $result = $result."</tr>";

    $res = mysqli_query($mysqli, "call report_monthly_sale('".$pid."');");

    while($row = $res->fetch_assoc())
    {
        $result = $result."<tr>";
        foreach($fields as $name)
        {
            $result = $result."<td>";
            if($row[$name]!=null)
                $result = $result.$row[$name];
            $result = $result."</td>";
        }

        $result = $result."</tr>";
    }
    $result = $result."</table>";         
    return $result;
}

echo table2html($pid, $name);
?>