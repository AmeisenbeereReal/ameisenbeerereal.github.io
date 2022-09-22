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
    $id = $_POST['id'];
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

    $reasonstmt = getMySQL()->prepare("SELECT * FROM prime_bungee_punishments WHERE id = :id");
    $reasonstmt->bindParam(":id", $id);
    $reasonstmt->execute();

    if($reasonstmt->rowCount() >= 1){
        $existing = $reasonstmt->fetch();
        $stmt = getMySQL()->prepare("UPDATE prime_bungee_punishments SET identifier = :ident, type = :type,reason = :reason, lenght =  :lenght,
                                    permission =:permission, secondlenght = :lenght2, sortid = :sort WHERE id = :id");
        $stmt->bindParam(":ident", $ident);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":type", $existing['type']);
        $stmt->bindParam(":reason", $reason);
        $stmt->bindParam(":lenght", $lenght);
        $stmt->bindParam(":permission", $permission);
        $stmt->bindParam(":permission", $permission);
        $stmt->bindParam(":lenght2", $lenght2);
        $stmt->bindParam(":sort", $sortid);
        $stmt->execute();
    }

    header("Location: sanctions.php");
}
