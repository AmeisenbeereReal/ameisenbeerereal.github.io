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

if(isset($_POST['submit-unmute'])){
    $reason = $_POST['reason'];
    $uuid = $_POST['uuid'];

    $stmt = getMySQL()->prepare("UPDATE prime_bungee_mute SET revoked = 1 WHERE uuid = :uuid");
    $stmt->bindParam(":uuid", $uuid);
    $stmt->execute();

    addHistory($uuid, "UNMUTE", $reason, "", $_SESSION['uuid']);

    if(isset($_POST['deleteUnban'])){
        $stmt = getMySQL()->prepare("DELETE FROM core_web_unban WHERE player = :uuid");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
    }

    header("Location: usermanegement.php?uuid=".$_POST['uuid']);
}
