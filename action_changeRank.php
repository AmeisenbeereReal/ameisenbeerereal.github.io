<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once 'inc/sqlinc.php';

if(!hasPermission("usermanagement_changerank")){
    header("Location: usermanegement.php?uuid=".$_POST['uuid']);
    die();
}

if(isset($_POST['submit-rank'])){
    $uuid = $_POST['uuid'];
    $rank = $_POST['rank'];

    $stmt = getMySQL()->prepare("UPDATE core_web_accounts SET `rank` = :rank WHERE player = :uuid");
    $stmt->bindParam(":rank", $rank);
    $stmt->bindParam(":uuid", $uuid);
    $stmt->execute();


    header("Location: usermanegement.php?uuid=".$_POST['uuid']);
}
