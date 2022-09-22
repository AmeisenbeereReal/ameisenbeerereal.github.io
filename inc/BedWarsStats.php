<?php


class BedWarsStats
{

    public $rounds = 0;
    public $wins = 0;
    public $kills = 0;
    public $deaths = 0;
    public $points = 0;

    public static function fromUUID($uuid){
        include_once 'sqlinc.php';
        try {
            $stmt = getBWMySQL()->prepare("SELECT * FROM bedwarsstats WHERE UUID = :uuid");
            $stmt->bindParam(":uuid", $uuid);
            $stmt->execute();
            $row = $stmt->fetch();
            $stats = new BedWarsStats();
            $stats->rounds = $row['PLAY'];
            $stats->wins = $row['WINS'];
            $stats->kills = $row['KILLS'];
            $stats->deaths = $row['DEATHS'];
            $stats->points = $row['POINTS'];
            return $stats;
        }catch (Exception $e){
        }
        return new BedWarsStats();
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