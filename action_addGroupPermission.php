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
if(isset($_POST['submit-addperm'])){
    $permissions = $_POST['permission'];
    $group = $_POST['group'];

    foreach (explode(",", $permissions) as $perm){

        $stmt = getMySQL()->prepare("INSERT INTO prime_perms_grouppermission VALUES (id,:id,:permission,0)");
        $stmt->bindParam(":id", $group);
        $stmt->bindParam(":permission", $perm);
        $stmt->execute();
    }

    header("Location: permissions.php?group=".$group);
}
