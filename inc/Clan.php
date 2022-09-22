<?php


class Clan
{

    public $id;
    public $name;
    public $realname;
    public $tag;
    public $coins;

    public static function fromId($id){
        include_once 'sqlinc.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_clans WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $clan = new Clan;
            $clan->id = $row['id'];
            $clan->name = $row['name'];
            $clan->realname = $row['realname'];
            $clan->tag = $row['tag'];
            $clan->coins = $row['coins'];
            return $clan;
        }
        return null;
    }
    public static function fromName($name){
        include_once 'sqlinc.php';
        $name = strtolower($name);
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_clans WHERE name = :name");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $clan = new Clan;
            $clan->id = $row['id'];
            $clan->name = $row['name'];
            $clan->realname = $row['realname'];
            $clan->tag = $row['tag'];
            $clan->coins = $row['coins'];
            return $clan;
        }
        return null;
    }

    public function getMLGRushStats(){
        include_once 'sqlinc.php';
        $stats = new MLGRushStats();
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE clan = :clan");
        $stmt->bindParam(":clan", $this->id);
        $stmt->execute();
        while ($row = $stmt->fetch()){
            $player = Player::fromUUID($row['uuid']);
            $currStats = $player->getMLGRushStats();
            $stats->kills += $currStats->kills;
            $stats->deaths += $currStats->deaths;
            $stats->wins += $currStats->wins;
            $stats->looses += $currStats->looses;
        }

        return $stats;
    }

    public function getBuildFFAStats()
    {
        include_once 'sqlinc.php';
        $stats = new BuildFFAStats();
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE clan = :clan");
        $stmt->bindParam(":clan", $this->id);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $player = Player::fromUUID($row['uuid']);
            $currStats = $player->getBuildFFAStats();
            $stats->kills += $currStats->kills;
            $stats->deaths += $currStats->deaths;
            $stats->blocks += $currStats->blocks;
        }

        return $stats;
    }


    public function getBedWarsStats()
    {
        include_once 'sqlinc.php';
        $stats = new BedWarsStats();
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE clan = :clan");
        $stmt->bindParam(":clan", $this->id);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $currStats = BedWarsStats::fromUUID($row['uuid']);
            $stats->kills += $currStats->kills;
            $stats->deaths += $currStats->deaths;
            $stats->wins += $currStats->wins;
            $stats->rounds += $currStats->rounds;
            $stats->points += $currStats->points;
        }
        return $stats;
    }
    public function getSkywarsStats(){
        include_once 'sqlinc.php';
        $stats = new SkyWarsStats();
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE clan = :clan");
        $stmt->bindParam(":clan", $this->id);
        $stmt->execute();
        while ($row = $stmt->fetch()){
            $currStats = SkyWarsStats::fromUUID($row['uuid']);
            $stats->kills += $currStats->kills;
            $stats->deaths += $currStats->deaths;
            $stats->wins += $currStats->wins;
            $stats->rounds += $currStats->rounds;
            $stats->points += $currStats->points;
        }
        return $stats;
    }

    public function getCoins(){
        include_once 'sqlinc.php';
        $stats = 0;
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE clan = :clan");
        $stmt->bindParam(":clan", $this->id);
        $stmt->execute();
        while ($row = $stmt->fetch()){
            $player = Player::fromUUID($row['uuid']);
            $stats += $player->getCoins();
        }
        return $stats;
    }
    public function getOnTime(){
        include_once 'sqlinc.php';
        $stats = 0;
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE clan = :clan");
        $stmt->bindParam(":clan", $this->id);
        $stmt->execute();
        while ($row = $stmt->fetch()){
            $player = Player::fromUUID($row['uuid']);
            $stats += $player->getOnMins();
        }
        return $stats;
    }


}