<?php
//var_dump($_POST);
include "connect.php";
include "functions.php";
$res = purchase($_POST['purid'], $_POST['cid'], $_POST['eid'], $_POST['pid'], $_POST['count']);
echo json_encode($res);
?>