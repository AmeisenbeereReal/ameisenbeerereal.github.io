<?php


class Player
{

    public $name;
    public $uuid;

    public static function fromName($name){
        require_once 'sql.php';
        $name = strtolower($name);
        $stmt = getMySQL()->prepare("SELECT * FROM core_players where name = :name");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $uuid = $row['uuid'];
            $name = $row['realname'];
            $player = new Player();
            $player->name = $name;
            $player->uuid = $uuid;
            return $player;
        }
        return null;
    }

    public static function fromUUID($uuid){
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM core_players where uuid = :uuid");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $name = $row['realname'];
            $player = new Player();
            $player->name = $name;
            $player->uuid = $uuid;
            return $player;
        }
        return null;
    }

    function getAvatarUrl(){
        return "https://cravatar.eu/helmavatar/".$this->uuid."/42";
    }

    public function getCoins(){
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM core_players where uuid = :uuid");
        $uuid = $this->uuid;
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            return $row['coins'];
        }
        return null;
    }
    public function getOnMins(){
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM core_players where uuid = :uuid");
        $uuid = $this->uuid;
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            return $row['playtime'];
        }
        return null;
    }
    public function getOnMinsAsString()
    {
        $mins = $this->getOnMins();
        $hours = floor($mins / 60);
        $mins = $mins - ($hours * 60);
        return "$hours Stunden und $mins Minuten";
    }

    public function getFriendsCount()
    {
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_friends where uuid = :uuid");
        $uuid = $this->uuid;
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public function getOnlineFriendsCoins()
    {
        require_once 'sql.php';
        $count = 0;
        $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_friends where uuid = :uuid");
        $uuid = $this->uuid;
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        while ($row = $stmt->fetch()){
            $exists = getMySQL()->prepare("SELECT * FROM prime_bungee_online WHERE uuid = :uuid");
            $frienduuid = $row['friend'];
            $exists->bindParam(":uuid", $frienduuid);
            $exists->execute();
            if($exists->rowCount() >= 1){
                $count++;
            }
        }
        return $count;
    }

    function getHighestGroup(){
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_perms_ranking WHERE uuid = :uuid ORDER BY potency DESC");
        $stmt->bindParam(":uuid", $this->uuid);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            return PermissionGroup::fromId($row['group']);
        }else{
            return PermissionGroup::fromName("default");
        }
    }

    function getClan(){
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE uuid = :uuid");
        $stmt->bindParam(":uuid", $this->uuid);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $clanid = $row['clan'];
            return Clan::fromId($clanid);
        }
        return null;
    }

    function getBan(){
        require_once 'SQLBan.php';
        return SQLBan::fromPlayerActive($this->uuid);

    }
    function getMute(){
        require_once 'SQLMute.php';
        return SQLMute::fromPlayerActive($this->uuid);

    }

    function getServer(){
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_online WHERE uuid = :uuid");
        $stmt->bindParam(":uuid", $this->uuid);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            return $row['server'];
        }
        return null;
    }
    function getAFK(){
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_online WHERE uuid = :uuid");
        $stmt->bindParam(":uuid", $this->uuid);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            return $row['afk'];
        }
        return null;
    }
    function getPartyLeader(){
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_online WHERE uuid = :uuid");
        $stmt->bindParam(":uuid", $this->uuid);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            return $row['party'];
        }
        return null;
    }

    function getMLGRushStats()
    {
        include_once 'MLGRushStats.php';
        return MLGRushStats::fromUUID($this->uuid);
    }

    function getBuildFFAStats()
    {
        include_once 'BuildFFAStats.php';
        return BuildFFAStats::fromUUID($this->uuid);
    }


    function getClanRank()
    {
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE uuid = :uuid");
        $stmt->bindParam(":uuid", $this->uuid);
        $stmt->execute();
        if ($stmt->rowCount() >= 1) {
            return $stmt->fetch()['rank'];
        }
        return -1;
    }
    function getClanRankInClan($clanID){
        require_once 'sql.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE uuid = :uuid AND clan = :clan");
        $stmt->bindParam(":uuid", $this->uuid);
        $stmt->bindParam(":clan", $clanID);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            return $stmt->fetch()['rank'];
        }
        return -1;
    }

    function getSkyWarsStats(){
        include_once 'SkyWarsStats.php';
        SkyWarsStats::fromUUID($this->uuid);
    }
    function getBedWarsStats(){
        include_once 'BedWarsStats.php';
        BedWarsStats::fromUUID($this->uuid);
    }


    function getFriendRequstAmount(){
        include_once 'sqlinc.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_requests WHERE uuid = :uuid");
        $stmt->bindParam(":uuid", $this->uuid);
        $stmt->execute();
        return $stmt->rowCount();
    }
}