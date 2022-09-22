<?php


class SkyWarsStats
{

    public $rounds = 0;
    public $wins = 0;
    public $kills = 0;
    public $deaths = 0;
    public $points = 0;
    public $kit = 0;

    public static function fromUUID($uuid){
        include_once 'sqlinc.php';
        try {
            $stmt = getSWMySQL()->prepare("SELECT * FROM skywarsdata WHERE UUID = :uuid");
            $stmt->bindParam(":uuid", $uuid);
            $stmt->execute();
            $row = $stmt->fetch();
            $stats = new SkyWarsStats();
            $stats->rounds = $row['PLAY'];
            $stats->wins = $row['WINS'];
            $stats->kills = $row['KILLS'];
            $stats->deaths = $row['DEATHS'];
            $stats->points = $row['POINTS'];
            $stats->kit = $row['LASTKIT'];
            return $stats;
        }catch (Exception $e){
        }
        return new SkyWarsStats();
    }

    public function looses(){
        return $this->rounds - $this->wins;
    }

    public function kd(){
        if($this->deaths == 0){
            return $this->kills;
        }
        return round($this->kills / $this->deaths,2);
    }
}