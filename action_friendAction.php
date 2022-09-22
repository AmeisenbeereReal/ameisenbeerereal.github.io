<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'inc/sqlinc.php';
session_start();

$player = new Player();
$player->name = $_SESSION['username'];
$player->uuid = $_SESSION['uuid'];
if($player == null){
    return;
}
$target = Player::fromUUID($_GET['target']);
if($player->uuid == $target->uuid){
    header("Location: friends.php?message=Du+kannst+nicht+mit+dir+selbst+befreundet+sein!");
    return;
}

$type = $_GET['type'];
$action = $_GET['action'];
if($type == "FRIENDSHIP"){
    if($action == "REMOVE"){
        $stmt = getMySQL()->prepare("DELETE FROM prime_bungee_friends WHERE (uuid = :player1 AND friend = :player2) OR (uuid = :player2 AND friend = :player1)");
        $stmt->bindParam(":player1", $player->uuid);
        $stmt->bindParam(":player2", $target->uuid);
        $stmt->execute();
        header("Location: friends.php?message=Du+hast+die+Freundschaft+erfolgreich+aufgelöst!&color=green");
        return;
    }
}

if($type == "REQUEST"){
    if($action == "REMOVE"){
        $delete = getMySQL()->prepare("DELETE FROM prime_bungee_requests WHERE uuid = :player1 AND requester = :player2");
        $delete->bindParam(":player1", $player->uuid);
        $delete->bindParam(":player2", $target->uuid);
        $delete->execute();
        header("Location: friends.php?message=Du+hast+die+Freundschaftsanfrage+erfolgreich+abgelehnt!&color=green");
        return;
    }
    if($action == "ACCEPT"){
        $delete = getMySQL()->prepare("DELETE FROM prime_bungee_requests WHERE uuid = :player1 AND requester = :player2");
        $delete->bindParam(":player1", $player->uuid);
        $delete->bindParam(":player2", $target->uuid);
        $delete->execute();

        $time = getUnixTimestamp();
        $insert = getMySQL()->prepare("INSERT INTO prime_bungee_friends VALUES (id, :uuid, :target, :time)");
        $insert->bindParam(":uuid", $player->uuid);
        $insert->bindParam(":target", $target->uuid);
        $insert->bindParam(":time", $time);
        $insert->execute();
        $insert = getMySQL()->prepare("INSERT INTO prime_bungee_friends VALUES (id, :uuid, :target, :time)");
        $insert->bindParam(":uuid", $target->uuid);
        $insert->bindParam(":target", $player->uuid);
        $insert->bindParam(":time", $time);
        $insert->execute();
        header("Location: friends.php?message=Du+hast+die+Freundschaftsanfrage+erfolgreich+angenommen!&color=green");
        return;
    }
}