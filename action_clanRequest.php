<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'inc/sqlinc.php';
session_start();

$player = new Player();
$player->name = $_SESSION['username'];
$player->uuid = $_SESSION['uuid'];

$action = $_GET['action'];
$clan = Clan::fromId($_GET['clan']);
if($action == "accept"){
    $delete = getMySQL()->prepare("DELETE FROM prime_clan_requests WHERE uuid = :uuid AND clan = :clan; DELETE FROM prime_clan_players WHERE uuid = :uuid");
    $delete->bindParam(":uuid", $player->uuid);
    $delete->bindParam(":clan", $clan->id);
    $delete->execute();

    $stmt = getMySQL()->prepare("INSERT INTO prime_clan_players VALUES (id, :uuid, :clan, 0)");
    $stmt->bindParam(":uuid", $player->uuid);
    $stmt->bindParam(":clan", $clan->id);
    $stmt->execute();

    header("Location: clan.php?message=Du+hast+die+Einladung+erfolgreich+angenommen!&color=green");
    return;
}
if($action == "deny"){
    $delete = getMySQL()->prepare("DELETE FROM prime_clan_requests WHERE uuid = :uuid AND clan = :clan;");
    $delete->bindParam(":uuid", $player->uuid);
    $delete->bindParam(":clan", $clan->id);
    $delete->execute();
    header("Location: clan.php?message=Du+hast+die+Einladung+erfolgreich+abgelehnt!&color=green");
    return;
}
header("Location: clan.php?message=Es+ist+ein+Fehler+aufgetreten!");