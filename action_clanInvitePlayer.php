<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'inc/sqlinc.php';
session_start();

$player = new Player();
$player->name = $_SESSION['username'];
$player->uuid = $_SESSION['uuid'];

if(isset($_POST['submit-invite'])){
    $clanName = $_POST['clan'];
    $clan = Clan::fromName($clanName);
    $targetPlayer = Player::fromName($_POST['player']);
    if($player->uuid == $targetPlayer->uuid){
        header("Location: clan.php?message=Du+kannst+nicht+mit+dir+selbst+befreundet+sein!");
        return;
    }

    $check = getMySQL()->prepare("SELECT * FROM prime_clan_requests WHERE uuid = :uuid AND clan = :clan");
    $check->bindParam(":uuid", $targetPlayer->uuid);
    $check->bindParam(":clan", $clan->id);
    $check->execute();
    if($check->rowCount() >= 1){
        header("Location: clan.php?clan=".$clan->name."&message=Dieser+Spieler+wurde+bereits+eingeladen!");
        return;
    }

    $stmt = getMySQL()->prepare("INSERT INTO prime_clan_requests VALUES (id,:uuid,:clan)");
    $stmt->bindParam(":uuid", $targetPlayer->uuid);
    $stmt->bindParam(":clan", $clan->id);
    $stmt->execute();
    header("Location: clan.php?clan=".$clan->name."&message=Spieler+erfolgreich+eingeladen!&color=green");
}
