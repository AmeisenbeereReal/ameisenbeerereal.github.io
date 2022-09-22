<?php
include_once 'inc/sqlinc.php';

if(!isset($_GET['action'])){
    header("Code 404: action not defined", true, 404);
    return;
}
if($_GET['action'] == "clans"){
    if(!isset($_GET['name'])){
        header("Code 404: name not defined", true, 404);
        return;
    }
    $name = "%".strtolower($_GET['name'])."%";
    $name = str_replace(";", "", $name);
    $name = str_replace("SELECT", "", $name);
    $name = str_replace("DELETE", "", $name);
    $name = str_replace("FROM", "", $name);
    $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_clans WHERE `name` LIKE '".$name."' LIMIT 5");
    $stmt->execute();
    $list = array();
    while ($row = $stmt->fetch()){
        $data = array(
            "name"=>$row['name'],
            "realname"=>$row['realname'],
            "tag"=>$row['tag'],
        );
        $list[$row['name']] = $data;
    }

    echo json_encode($list);

}
if($_GET['action'] == "players"){
    if(!isset($_GET['name'])){
        header("Code 404: name not defined", true, 404);
        return;
    }
    $name = "%".strtolower($_GET['name'])."%";
    $name = str_replace(";", "", $name);
    $name = str_replace("SELECT", "", $name);
    $name = str_replace("DELETE", "", $name);
    $name = str_replace("FROM", "", $name);
    $stmt = getMySQL()->prepare("SELECT * FROM core_players WHERE `name` LIKE '".$name."' LIMIT 5");
    $stmt->execute();
    $list = array();
    while ($row = $stmt->fetch()){
        $data = array(
            "name"=>$row['name'],
            "realname"=>$row['realname'],
            "coins"=>$row['coins'],
            "playtime"=>$row['playtime'],
        );
        $list[$row['name']] = $data;
    }

    echo json_encode($list);

}
if($_GET['action'] == "getUUID"){
    if(!isset($_GET['name'])){
        header("Code 404: name not defined", true, 404);
        return;
    }
    $name = strtolower($_GET['name']);
    $name = str_replace(";", "", $name);
    $name = str_replace("SELECT", "", $name);
    $name = str_replace("DELETE", "", $name);
    $name = str_replace("FROM", "", $name);
    $stmt = getMySQL()->prepare("SELECT * FROM core_players WHERE `name` = '".$name."'");
    $stmt->execute();
    if($stmt->rowCount() >= 1){
        $row = $stmt->fetch();
        echo $row['uuid'];
    }

}