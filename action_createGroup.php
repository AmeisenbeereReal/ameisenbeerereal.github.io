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
if(isset($_POST['submit-create'])){
    $name = strtolower($_POST['name']);
    $inherit = 0;
    $prefix = "";
    $suffix = "";
    $color = "";
    $weight = 100;
    $sql = getMySQL();

        $stmt = $sql->prepare("INSERT INTO prime_perms_groups VALUES (id, :name, :inherit, :displayname, :prefix,:suffix,:color,:weight)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":displayname", $_POST['displayname']);
        $stmt->bindParam(":inherit", $inherit);
        $stmt->bindParam(":prefix", $prefix);
        $stmt->bindParam(":suffix", $suffix);
        $stmt->bindParam(":color", $color);
        $stmt->bindParam(":weight", $weight);
        $stmt->execute();
        $group = $sql->lastInsertId();
    header("Location: permissions.php?group=".$group);
}
