<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once 'inc/sqlinc.php';

if(!hasPermission("usermanagement_punishment_manage")){
    header("Location: usermanegement.php?uuid=".$_POST['uuid']);
    die();
}

if(isset($_POST['submit-mute'])){
    $reason = $_POST['mute-reason'];
    $uuid = $_POST['uuid'];
    $timeVal = $_POST['dur-val'];
    $timeType = $_POST['dur-type'];
    $reasonstmt = getMySQL()->prepare("SELECT * FROM prime_bungee_punishments WHERE type='MUTE' AND identifier=:iden");
    $reasonstmt->bindParam(":iden", $reason);
    $reasonstmt->execute();
    if($reasonstmt->rowCount() >= 1){
        $row = $reasonstmt->fetch();
        $reason = $row['reason'];
        $time = $row['lenght'];
        $timestring = remainingToString($time);
        $time = $time + getUnixTimestamp();
    }else{
        $time = addToCurrentUnix($timeVal, $timeType);
        $timestring = $timeVal." ".$timeType;
    }

    $timestamp = getUnixTimestamp();
    $stmt = getMySQL()->prepare("INSERT INTO prime_bungee_mute VALUES (id,:uuid,:reason,:lenght,:timestamp,:issuer,0)");
    $stmt->bindParam(":uuid", $uuid);
    $stmt->bindParam(":reason", $reason);
    $stmt->bindParam(":lenght", $time);
    $stmt->bindParam(":timestamp", $timestamp);
    $stmt->bindParam(":issuer", $_SESSION['uuid']);
    $stmt->execute();

    addHistory($uuid, "MUTE", $reason, $timestring, $_SESSION['uuid']);

    header("Location: usermanegement.php?uuid=".$_POST['uuid']);
}
