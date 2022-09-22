<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once 'inc/sqlinc.php';

if(!hasPermission("usermanagement_permissions_manage")){
    header("Location: usermanegement.php?uuid=".$_POST['uuid']);
    die();
}

if(isset($_POST['submit-addgroup'])){
    $groupid = $_POST['group'];
    $timeVal = $_POST['dur-val'];
    $timeType = $_POST['dur-type'];
    $time = addToCurrentUnix($timeVal, $timeType);
    $weight = $_POST['weight'];

    $stmt = getMySQL()->prepare("INSERT INTO prime_perms_ranking VALUES (id,:uuid,:group,:time,:weight)");
    $stmt->bindParam(":uuid", $_POST['uuid']);
    $stmt->bindParam(":group", $groupid);
    $stmt->bindParam(":time", $time);
    $stmt->bindParam(":weight", $weight);
    $stmt->execute();

    header("Location: usermanegement.php?uuid=".$_POST['uuid']);
}
