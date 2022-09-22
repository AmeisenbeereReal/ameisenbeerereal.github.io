<?php


class SQLMute
{
    public $reason;
    public $time;
    public $timestamp;
    public $issuer;
    public $revoked;

    public static function fromId($id){
        include_once 'sqlinc.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_mute WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $ban = new SQLBan();
            $ban->reason = $row['reason'];
            $ban->time = $row['lenght'];
            $ban->timestamp = $row['timestamp'];
            $ban->issuer = $row['issuer'];
            $ban->revoked = $row['revoked'];
            return $ban;
        }

        return null;
    }

    public static function fromPlayerActive($uuid){
        include_once 'sqlinc.php';
        $time = getUnixTimestamp();
        $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_mute WHERE uuid = :uuid AND revoked = 0 AND (lenght >= :time OR lenght = -1)");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->bindParam(":time", $time);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $ban = new SQLBan();
            $ban->reason = $row['reason'];
            $ban->time = $row['lenght'];
            $ban->timestamp = $row['timestamp'];
            $ban->issuer = $row['issuer'];
            $ban->revoked = $row['revoked'];
            return $ban;
        }

        return null;
    }

    public function getIssuerName(){
        $player =  Player::fromUUID($this->issuer);
        if($player == null){
            return $this->issuer;
        }else{
            return $player->name;
        }
    }
}