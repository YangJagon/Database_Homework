<?php
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


function table2html($tn)
{
    global $mysqli;
    global $fields;
    global $data;
    global $tablename;
    global $types;

    //var_dump($fields);

    $cnum = count($fields);
    $result = "";

    $result = $result."<table><caption style='font-weight:normal;'>".strtoupper($tn)."</caption>";

    $result = $result."<tr id=\"fields\">";
    foreach($fields as $name)
        $result = $result."<th>".strtoupper($name)."</th>";

    $result = $result."<th></th>";
    $result = $result."</tr>";

    $res = mysqli_query($mysqli, "SELECT * FROM ".$tn);

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

        $str=$row[$fields[0]];
        $str2 = $str;
        if(stripos("%".$types[0], "date") || stripos("%".$types[0], "char"))
            $str = "\\'".$str."\\'";

        $result = $result."<td>
        <input type=\"button\"
        class=\"button2\" 
        value=\"del\"
        name =\"delete\"
        onclick=\"deleteTuple('".$tablename."', '".$fields[0]."', '".$str."');\">
         &#124 
        <input type=\"button\"
        class=\"button2\" 
        value=\"up\"
        name =\"update\"
        onclick=\"updateTable('".$tablename."', '".$fields[0]."', '".$str2."');\">
        </td>";
        $result = $result."</tr>";
    }
    $result = $result."</table>";         
    return $result;
}

function form2html($tn)
{
    global $mysqli;
    global $fields;
    global $types;
    $cnum = count($fields);
    $result = "";

    $result = $result."<table><tr>";
    for($i=0; $i<$cnum; $i++)
    {
        $result = $result."<th style=\"font-weight:normal; font-size: small; line-height:1.5em;\">".strtoupper($fields[$i])."<br>";
        $result = $result."<span style=\"color: #c28756;\">".strtoupper($types[$i])."</span></th>";
    }
    $result = $result."</tr>";

    $result = $result."<tr id=\"insertData\">";
    for($i=0; $i<$cnum; $i++)
    {
        $result = $result."<td><input type=\"text\" class=\"input\" name=\"".$fields[$i]."\" id=\"".$fields[$i]."\"</td>";
    }
    $result = $result."</tr>";
    $result = $result."</table>"; 
    return $result;
}

function show_table_up($tn)
{
    global $mysqli;
    global $fields;
    global $data;
    global $tablename;
    global $types;
    global $idn;
    global $idd;
    $param="";

    $cnum = count($fields);

    // echo "<form method=\"post\" action=\"php/update.php\">";
    // echo "<input type=\"hidden\" name=\"tablename\" id=\"tablename\" value= \"".$tablename."\">";
    // echo "<input type=\"hidden\" name=\"idname\" id=\"idname\" value= \"".$idn."\">";
    // echo "<input type=\"hidden\" name=\"iddata\" id=\"iddata\" value= \"".$idd."\">";

    echo "<table><caption style='font-weight:normal;'>".strtoupper($tn)."</caption>";
    echo "<tr id=\"fields\">";
    foreach($fields as $name)
        echo "<th>".strtoupper($name)."</th>";
    echo "<th></th>";
    echo "</tr>";

    $res = mysqli_query($mysqli, "SELECT * FROM ".$tn);

    while($row = $res->fetch_assoc())
    {
        echo "<tr id=\"".$row[$fields[0]]."\">";
        foreach($fields as $name)
        {
            echo "<td>";
            if($row[$name]!=null)
                echo $row[$name];
            echo "</td>";
        }

        $str=$row[$fields[0]];
        $str2=$str;
        if(stripos("%".$types[0], "date") || stripos("%".$types[0], "char"))
            $str = "\\'".$str."\\'";

        echo "<td>
        <input type=\"button\"
        class=\"button2\" 
        value=\"del\"
        name =\"delete\"
        onclick=\"deleteTuple('".$tablename."', '".$fields[0]."', '".$str."');\">
         &#124 
        <input type=\"button\"
        class=\"button2\" 
        value=\"up\"
        name =\"update\"
        onclick=\"updateTable('".$tablename."', '".$fields[0]."', '".$str2."');\">
        </td>";
        echo "</tr>";

        if(stripos("%".$idd, $row[$idn]))
        {
            echo "<tr>";
            for($i=0; $i<$cnum; $i++)
            {
                echo "<td><input type=\"text\" size=\"1\" class=\"input2\" name=\"".$fields[$i]."\" id=\"".$fields[$i]."\" value=\"".$row[$fields[$i]]."\"</td>";
            }
            echo "<td> <input type=\"button\" name=\"uSubmit\" id=\"uSubmit\" value=\"change\" class=\"button2\" style=\"width:80px\" 
            onclick=\"updateTuple('".$tablename."', '".$idn."', '".$idd."');\"> </td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    //echo "</form>";   
}

function purchase($purid, $cid, $eid, $pid, $count)
{
    global $mysqli;
    $param = "CALL add_purchase('".$purid."', ";
    $param = $param."'".$cid."', ";
    $param = $param."'".$eid."', ";
    $param = $param."'".$pid."', ";
    $param = $param.$count.")";
    //echo $param;

    $res = mysqli_query($mysqli, $param);
    return $res;
}
?>