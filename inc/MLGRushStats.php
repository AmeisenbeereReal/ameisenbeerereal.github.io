<?php


class MLGRushStats
{
    public $id = 0;
    public $wins = 0;
    public $looses = 0;
    public $kills = 0;
    public $deaths = 0;

    public static function fromUUID($uuid){
        require_once 'sqlinc.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_mlgrush_players WHERE uuid = :uuid");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $stats = new MLGRushStats();
            $stats->wins = $row['wins'];
            $stats->looses = $row['looses'];
            $stats->kills = $row['kills'];
            $stats->deaths = $row['deaths'];
            return $stats;
        }

        return new MLGRushStats();
    }
}