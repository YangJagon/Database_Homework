<?php
include "connect.php";

function get_table_data($tn)
{
    global $mysqli;
    $data=null;
    $res = mysqli_query($mysqli, "SELECT * FROM ".$tn);
    if(!$res)
        return null;

    $no=0;
    while($row = $res->fetch_assoc())
    {
        $data[$no]=$row;
        $no++;
    }
    return $data;
}

function get_table_fields($tn)
{
    global $mysqli;
    $fields=null;
    $res = mysqli_query($mysqli, "desc ".$tn);
    if(!$res)
        return null;

    for ($row_no = 0; $row_no < $res->num_rows; $row_no++)
    {
        $res->data_seek($row_no);
        $row = $res->fetch_assoc();
        $fields[$row_no] = $row['Field'];
    }
    return $fields;
}

function get_table_types($tn)
{
    global $mysqli;
    $types=null;
    $res = mysqli_query($mysqli, "desc ".$tn);
    if(!$res)
        return null;

    for ($row_no = 0; $row_no < $res->num_rows; $row_no++)
    {
        $res->data_seek($row_no);
        $row = $res->fetch_assoc();
        $types[$row_no] = $row['Type'];
    }
    return $types;
}

function get_del_data($fields, $row)
{
    $cnum = count($fields);
    $str="";

    for($i=0; $i<$cnum; $i++)
    {
        if($i > 0)
            $str = $str." and ";
        
    }
}


function show_table($tn)
{
    global $mysqli;
    global $fields;
    global $data;
    global $tablename;
    global $types;
    $cnum = count($fields);

    echo "<table><caption>".strtoupper($tn)."</caption>";

    echo "<tr>";
    foreach($fields as $name)
    {
        echo "<th>".strtoupper($name)."</th>";
    }
    echo "<th></th>";
    echo "</tr>";

    $res = mysqli_query($mysqli, "SELECT * FROM ".$tn);

    while($row = $res->fetch_assoc())
    {
        echo "<tr>";
        foreach($fields as $name)
        {
            echo "<td>";
            if($row[$name]!=null)
                echo $row[$name];
            echo "</td>";
        }

        $str=$row[$fields[0]];
        if(stripos("1".$types[0], "date") || stripos("1".$types[0], "char"))
            $str = "\\'".$str."\\'";

        echo "<td>
        <input type=\"button\"
        class=\"button2\" 
        value=\"Del\"
        name =\"delete\"
        onclick=\"javascript:window.location.
        href='http://127.0.0.1/database/delete.php?tablename=".$tablename."&".$fields[0]."=".$str."'\">
        </td>";
        echo "</tr>";
    }
    echo "</table>";         
}

function showForm($tn)
{
    global $mysqli;
    global $fields;
    global $types;
    $cnum = count($fields);

    echo "<table><tr>";
    for($i=0; $i<$cnum; $i++)
    {
        echo "<th style=\"font-weight:normal; font-size: small; line-height:1.5em;\">".strtoupper($fields[$i])."<br>";
        echo "<span style=\"color: #c28756;\">".strtoupper($types[$i])."</span></th>";
    }
    echo "</tr>";

    echo "<tr>";
    for($i=0; $i<$cnum; $i++)
    {
        echo "<td><input type=\"text\" class=\"input\" name=\"".$fields[$i]."\" id=\"".$fields[$i]."\"></td>";
    }
    echo "</tr>";
    echo "</table>"; 
}

?>