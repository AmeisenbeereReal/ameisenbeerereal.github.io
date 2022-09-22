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
    $group = $_POST['group'];
    $color = str_replace('&', '§', $_POST['color']);
    $prefix = str_replace('&', '§', $_POST['prefix']);
    $suffix = str_replace('&', '§', $_POST['suffix']);


        $stmt = getMySQL()->prepare("UPDATE prime_perms_groups SET display_name = :display, inherit = :inherit, prefix = :prefix,
                              suffix = :suffix,color = :color,weight = :weight WHERE id=:id");
        $stmt->bindParam(":id", $group);
        $stmt->bindParam(":display", $_POST['displayname']);
        $stmt->bindParam(":inherit", $_POST['inherit']);
        $stmt->bindParam(":prefix", $prefix);
        $stmt->bindParam(":suffix", $suffix);
        $stmt->bindParam(":color", $color);
        $stmt->bindParam(":weight", $_POST['weight']);
        $stmt->execute();

    header("Location: permissions.php?group=".$group);
}
