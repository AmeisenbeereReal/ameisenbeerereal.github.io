<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'inc/sqlinc.php';
session_start();

$player = new Player();
$player->name = $_SESSION['username'];
$player->uuid = $_SESSION['uuid'];

$target = Player::fromUUID($_GET['target']);
$action = $_GET['action'];
if($player->getClan()->name != $target->getClan()->name){
    header("Location: clan.php?message=Du+darfst+diesen+Member+nicht+bearbeiten!");
    return;
}

if($player->getClanRank() <= $target->getClanRank()){
    header("Location: clan.php?message=Du+darfst+diesen+Member+nicht+bearbeiten!");
    return;
}

if($action == "promote"){
    $rank = $target->getClanRank();
    if($rank == 2){
        header("Location: clan.php?message=Du+darfst+diesen+Member+nicht+bearbeiten!");
        return;
    }
    if($rank + 1 >= $player->getClanRank()){
        header("Location: clan.php?message=Du+darfst+diesen+Member+nicht+bearbeiten!");
        return;
    }

    $newrank = $rank + 1;
    $stmt = getMySQL()->prepare("UPDATE prime_clan_players SET `rank` = :rank WHERE uuid = :uuid");
    $stmt->bindParam(":rank", $newrank);
    $stmt->bindParam(":uuid", $target->uuid);
    $stmt->execute();
    header("Location: clan.php?message=Du+hast+den+Spieler+erfolgreich+befördert!&color=green");
    return;
}
if($action == "demote"){
    $rank = $target->getClanRank();
    if($rank == 0){
        header("Location: clan.php?message=Du+musst+diesen+spieler+kicken!");
        return;
    }

    $newrank = $rank - 1;
    $stmt = getMySQL()->prepare("UPDATE prime_clan_players SET `rank` = :rank WHERE uuid = :uuid");
    $stmt->bindParam(":rank", $newrank);
    $stmt->bindParam(":uuid", $target->uuid);
    $stmt->execute();
    header("Location: clan.php?message=Du+hast+den+Spieler+erfolgreich+degradiert!&color=green");
    return;
}
if($action == "kick"){
    $rank = $target->getClanRank();
    if($rank == 3){
        header("Location: clan.php?message=Du+darfst+diesen+Member+nicht+bearbeiten!");
        return;
    }
    $stmt = getMySQL()->prepare("DELETE FROM prime_clan_players WHERE uuid = :uuid");
    $stmt->bindParam(":uuid", $target->uuid);
    $stmt->execute();
    header("Location: clan.php?message=Du+hast+den+Spieler+erfolgreich+gekickt!&color=green");
    return;
}