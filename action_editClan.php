<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'inc/sqlinc.php';
session_start();

$player = new Player();
$player->name = $_SESSION['username'];
$player->uuid = $_SESSION['uuid'];


if(isset($_POST['submit-clanedit'])){
    $clan = Clan::fromName($_POST['clan']);
    $rank = $player->getClanRank();
    if($rank != 3){
        header("Location: clan.php?message=Du+darfst+diese+Aktion+nicht+ausführen!");
        return;
    }
    $realname = $_POST['name'];
    $name = strtolower($realname);
    $tag = $_POST['tag'];
    if(strlen($realname) < 3 || strlen($tag) > 6 || strlen($tag) < 2){
        header("Location: clan.php?message=Deine+Werte+sind+zu+kurz/lang!");
        return;
    }

    $stmt = getMySQL()->prepare("UPDATE prime_clan_clans SET `name` = :name, realname = :realname, tag = :tag WHERE id = :id");
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":realname", $realname);
    $stmt->bindParam(":tag", $tag);
    $stmt->bindParam(":id", $clan->id);
    $stmt->execute();
    header("Location: clan.php?message=Du+hast+den+clan+erfolgreich+bearbeitet!&color=green");
    return;
}