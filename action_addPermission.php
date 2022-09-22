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
    $permission = $_POST['permission'];

    $stmt = getMySQL()->prepare("INSERT INTO prime_perms_userpermissions VALUES (id,:uuid,:permission,0)");
    $stmt->bindParam(":uuid", $_POST['uuid']);
    $stmt->bindParam(":permission", $permission);
    $stmt->execute();

    header("Location: usermanegement.php?uuid=".$_POST['uuid']);
}
