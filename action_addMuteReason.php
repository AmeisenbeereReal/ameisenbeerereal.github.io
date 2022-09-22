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

if(isset($_POST['submit-ban'])){
    $reason = $_POST['ban-reason'];
    $ident = $_POST['ban-ident'];
    $timeVal = $_POST['dur-val'];
    $timeType = $_POST['dur-type'];
    $timeVal2 = $_POST['dur-val-2'];
    $timeType2 = $_POST['dur-type-2'];
    $lenght2 = translateToUnix($timeVal2,$timeType2);
    $sortid = $_POST['sortid'];
    $permission = $_POST['ban-permission'];
    $lenght = translateToUnix($timeVal,$timeType);
    if($permission == "" || $permission == " "){
        $permission = null;
    }

    $reasonstmt = getMySQL()->prepare("SELECT * FROM prime_bungee_punishments WHERE type='MUTE' AND identifier=:iden");
    $reasonstmt->bindParam(":iden", $ident);
    $reasonstmt->execute();

    if($reasonstmt->rowCount() == 0){
        $stmt = getMySQL()->prepare("INSERT INTO prime_bungee_punishments VALUES (id,:ident, 'MUTE', :reason, :lenght, :permission, :lenght2, :sort)");
        $stmt->bindParam(":ident", $ident);
        $stmt->bindParam(":reason", $reason);
        $stmt->bindParam(":lenght", $lenght);
        $stmt->bindParam(":permission", $permission);
        $stmt->bindParam(":lenght2", $lenght2);
        $stmt->bindParam(":sort", $sortid);
        $stmt->execute();
    }

    header("Location: sanctions.php");
}
