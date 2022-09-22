<?php


class BuildFFAStats
{
    public $kills = 0;
    public $deaths = 0;
    public $blocks = 0;

    public static function fromUUID($uuid)
    {
        require_once 'sqlinc.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_mlgrush_players WHERE uuid = :uuid");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        if ($stmt->rowCount() >= 1) {
            $row = $stmt->fetch();
            $stats = new BuildFFAStats();
            $stats->kills = self::getAmount($uuid, 'KILL');
            $stats->deaths = self::getAmount($uuid, 'DEATH');
            $stats->blocks = self::getAmount($uuid, 'BLOCK');
            return $stats;
        }

        return new MLGRushStats();
    }

    private static function getAmount($uuid, $type)
    {
        $stmt = getMySQL()->prepare("SELECT SUM(amount) AS count FROM prime_buildffa_stats WHERE uuid = :uuid AND type = :type");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->bindParam(":type", $type);
        $stmt->execute();
        if ($stmt->rowCount() >= 1) {
            return $stmt->fetch()['count'];
        }
        return -1;
    }


}