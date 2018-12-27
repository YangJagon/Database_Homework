<?php
    $tablename=$_GET["tablename"];

    include "connect.php";
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

    $res = mysqli_query($mysqli, "DELETE FROM ".$tablename." WHERE ".$idn."="."$idd");
    echo json_encode($res);
    //header("Location: http://127.0.0.1/database/index.php?".$tablename); 
?>