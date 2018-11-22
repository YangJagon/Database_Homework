<?php
$tablename=$_GET["tablename"];
include "initial.php";
global $mysqli;
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

function show_table_up($tn)
{
    global $mysqli;
    global $fields;
    global $data;
    global $tablename;
    global $types;
    global $idn;
    global $idd;

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
        if(stripos("%".$types[0], "date") || stripos("%".$types[0], "char"))
            $str = "\\'".$str."\\'";

        echo "<td>
        <input type=\"button\"
        class=\"button2\" 
        value=\"del\"
        name =\"delete\"
        onclick=\"javascript:window.location.
        href='http://127.0.0.1/database/delete.php?tablename=".$tablename."&".$fields[0]."=".$str."'\">
         &#124 
        <input type=\"button\"
        class=\"button2\" 
        value=\"up\"
        name =\"update\"
        onclick=\"javascript:window.location.
        href='http://127.0.0.1/database/update.php?tablename=".$tablename."&".$fields[0]."=".$str."'\">
        </td>";
        echo "</tr>";

        if(stripos("%".$idd, $row[$idn]))
        {
            echo "<form method=\"post\" action=\"rupdate.php\">";

            echo "<input type=\"hidden\" name=\"tablename\" id=\"tablename\" value= \"".$tablename."\">";
            echo "<input type=\"hidden\" name=\"idname\" id=\"idname\" value= \"".$idn."\">";
            echo "<input type=\"hidden\" name=\"iddata\" id=\"iddata\" value= \"".$idd."\">";

            echo "<tr>";
            for($i=0; $i<$cnum; $i++)
            {
                echo "<td><input type=\"text\" size=\"1\" class=\"input2\" name=\"".$fields[$i]."\" id=\"".$fields[$i]."\" value=\"".$row[$fields[$i]]."\"</td>";
            }
            echo "<td> <input type=\"submit\" name=\"usubmit\" id=\"usubmit\" value=\"change\" class=\"button2\" style=\"width:80px\"> </td>";
            echo "</tr></form>";
        }
    }
    echo "</table>";         
}
?>


<!DOCTYPE html>
<html lang="en">
    <?php
        if($tablename==null)
            $tablename="employees";
        include_once "initial.php";
        $fields = get_table_fields($tablename);
        $types = get_table_types($tablename);
        $data = get_table_data($tablename);
    ?>
    <head>
        <title>Database Management</title>
        <meta charset="utf-8">
        <?php
            echo "<style>
             #".$tablename."{
                color: rgb(120, 186, 162); 
                font-size: 1.24em;
                }
            </style>";
        ?>

        <link rel="stylesheet" href="database.css" type="text/css">
    </head>
    <body>
        <div id="wrapper">
            <header>
                <h1>Database Management System</h1>
            </header>

            <nav>
                <ul>
                    <li> <a href="/database/home.php">HOME</a> </li>
                    <li style="margin-top: 20px"> <a href="/database/index.php">CONTROL</a> </li>
                </ul>
            </nav>

            <main>
                <div style="margin-bottom: 50px; min-height: 400px;">
                <h2>TABLE</h2>
                <div>
                    <ul>
                        <li><a href="/database/index.php?employees" id="employees">Employees</a></li>
                        <li><a href="/database/index.php?customers" id="customers">Customers</a></li>
                        <li><a href="/database/index.php?suppliers" id="suppliers">Suppliers</a></li>
                        <li><a href="/database/index.php?products" id="products">Products</a></li>
                        <li><a href="/database/index.php?purchases" id="purchases">Purchases</a></li>
                        <li><a href="/database/index.php?logs" id="logs">Logs</a></li>
                    </ul>
                </div>

                <hr>
                <?php
                show_table_up($tablename);
                ?>
            </div>
                    
            <hr>
            
            <h2>INSERT</h2>
            <form method="post" action="insert.php">
            <input type="hidden" name="tablename" id="tablename" value= "<?php echo $tablename?>">

            <?php showForm($tablename); ?>
            <div style="width: 170px; margin:auto; margin-top: 30px;">
                <input type="reset" name="reset" id="reset" value="Reset" class="button">
                <input type="submit" name="submit" id="submit" value="Submit" class="button">
            </div>
            </form>

            </main>
            <footer>
                Copyright &copy; 2018 Jagon
            </footer>
        </div>
    </body>

</html>