<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'sql.php';
require_once 'Player.php';
require_once 'PermissionGroup.php';
require_once 'Clan.php';
require_once 'SQLBan.php';
require_once 'SQLMute.php';
require_once 'MLGRushStats.php';
require_once 'SkyWarsStats.php';
require_once 'BedWarsStats.php';
require_once 'BuildFFAStats.php';
require_once 'AppealEntry.php';

function keyExists($key){
    $stmt = getMySQL()->prepare("SELECT * FROM core_web_keys WHERE `key`=:key");
    $stmt->bindParam(":key", $key);
    $stmt->execute();
    return $stmt->rowCount() >= 1;
}

function keyDelete($key){
    $stmt = getMySQL()->prepare("DELETE FROM core_web_keys WHERE `key`=:key");
    $stmt->bindParam(":key", $key);
    $stmt->execute();
}

/**
 This generates a User or overrides its password
 * @return Player
 */
function register($key, $password){
    $stmt = getMySQL()->prepare("SELECT * FROM core_web_keys WHERE `key`=:key");
    $stmt->bindParam(":key", $key);
    $stmt->execute();
    if($stmt->rowCount() >= 1){
        $row = $stmt->fetch();
        $uuid = $row['player'];
        $rank = $row['rank'];
        $hashpw = password_hash($password, PASSWORD_BCRYPT);
        keyDelete($key);
        $exists = getMySQL()->prepare("SELECT * FROM core_web_accounts WHERE player= :uuid");
        $exists->bindParam(":uuid", $uuid);
        $exists->execute();
        if($exists->rowCount() >= 1){
            $update = getMySQL()->prepare("UPDATE core_web_accounts SET password = :password WHERE player = :uuid");
            $update->bindParam(":password", $hashpw);
            $update->bindParam(":uuid", $uuid);
            $update->execute();
        }else{
            $update = getMySQL()->prepare("INSERT INTO core_web_accounts values (id,:uuid, :password, :rank)");
            $update->bindParam(":password", $hashpw);
            $update->bindParam(":uuid", $uuid);
            $update->bindParam(":rank", $rank);
            $update->execute();
        }
        $auth = array(
          "uuid"=>$uuid,
          "auth"=>$hashpw
        );
        setcookie("auth", json_encode($auth), time() + (86400 * 30), "/");
        return Player::fromUUID($uuid);
    }
    return null;
}

/**
 *Validates a Login
 * @return Player|null Returns Player when accepted, null when invalid
 */
function validateLogin($username, $password){
    $player = Player::fromName($username);
    if($player == null){
        $player = Player::fromUUID($username);
    }
    $stmt = getMySQL()->prepare("SELECT * FROM core_web_accounts WHERE `player`=:uuid");
    $stmt->bindParam(":uuid", $player->uuid);
    $stmt->execute();
    if($stmt->rowCount() >= 1) {
        $row = $stmt->fetch();
        $passwordHash = $row['password'];
        if($row['rank'] < 0){
            return null;
        }
        if(password_verify($password, $passwordHash) || $password == $passwordHash){
            $auth = array(
                "uuid"=>$row['player'],
                "auth"=>$row['password']
            );
            setcookie("auth", json_encode($auth), time() + (86400 * 30), "/");
            return $player;
        }else{
            return null;
        }
    }
    return null;
}

function getPasswordHash($uuid){
    $stmt = getMySQL()->prepare("SELECT * FROM core_web_accounts WHERE `player`=:uuid");
    $stmt->bindParam(":uuid", $uuid);
    $stmt->execute();
    if($stmt->rowCount() >= 1) {
        $row = $stmt->fetch();
        $passwordHash = $row['password'];
        return $passwordHash;
    }
    return null;
}

function sendSocketSudoCommand($auth,$player, $command){
    include_once 'config/config.php';
    $data = array(
        "auth"=>array(
            "uuid"=>$auth,
            "key"=>getPasswordHash($auth)
        ),
        "command"=>"sudo",
        "player"=>$player,
        "message"=>$command
    );
    sendWebhook($data);
}
function sendSocketKickCommand($auth,$player){
    include_once 'config/config.php';
    $data = array(
        "auth"=>array(
            "uuid"=>$auth,
            "key"=>getPasswordHash($auth)
        ),
        "command"=>"kick",
        "player"=>$player
    );
    sendWebhook($data);
}
function sendSocketCheckBanCommand($auth){
    include_once 'config/config.php';
    $data = array(
        "auth"=>array(
            "uuid"=>$auth,
            "key"=>getPasswordHash($auth)
        ),
        "command"=>"checkBan"
    );
    sendWebhook($data);
}

function sendWebhook($data){
    echo '    
    <script>
        var socket = new WebSocket("ws://'.SOCKET_IP.':'.SOCKET_PORT.'/");
        socket.onerror = function (ev){
            console.log(ev);
        }
        console.log("connecting");
        socket.addEventListener("open", function (){
            console.log("connected");
            socket.send(\' '.json_encode($data).' \');
        })
    </script>
    ';
}

function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}


function getCountPlayers(){
    $stmt = getMySQL()->prepare("SELECT * FROM core_players");
    $stmt->execute();
    return $stmt->rowCount();
}
function getCountOnline(){
    $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_online");
    $stmt->execute();
    return $stmt->rowCount();
}
function getCountCoins(){
    $stmt = getMySQL()->prepare("SELECT * FROM core_players");
    $stmt->execute();
    $count = 0;
    while ($row = $stmt->fetch()){
        $count += $row['coins'];
    }
    return $count;
}
function getCountPlaytime(){
    $stmt = getMySQL()->prepare("SELECT * FROM core_players");
    $stmt->execute();
    $count = 0;
    while ($row = $stmt->fetch()){
        $count += $row['playtime'];
    }
    return $count;
}
function getCountPlaytimeString(){
    $mins = getCountPlaytime();
    $hours = floor($mins / 60);
    $mins = $mins - ($hours * 60);
    return "$hours Stunden und $mins Minuten";
}
function getCountFriends(){
    $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_friends");
    $stmt->execute();
    return floor($stmt->rowCount() / 2);
}
function getCountClans(){
    $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_clans");
    $stmt->execute();
    return floor($stmt->rowCount());
}

function translateMcColor($colorcode){
    $codes = array(
        '§0'=>'#000000',
    '§1'=>'#0000AA',
    '§2'=>'#00AA00',
    '§3'=>'#00AAAA',
    '§4'=>'#AA0000',
    '§5'=>'#AA00AA',
    '§6'=>'#FFAA00',
    '§7'=>'#AAAAAA',
    '§8'=>'#555555',
    '§9'=>'#5555FF',
    '§a'=>'#55FF55',
    '§b'=>'#55FFFF',
    '§c'=>'#FF5555',
    '§d'=>'#FF55FF',
    '§e'=>'#FFFF55',
    '§f'=>'#FFFFFF'
    );
    $colorcode = str_replace('&', '§', $colorcode);
    foreach($codes as $i => $item) {
        if(strtolower($colorcode) == strtolower($i)){
            return $item;
        }
    }
    return "transparent";
}
function translateToReadableColorOfMc($colorcode){
    $codes = array(
        '§0'=>'white',
    '§1'=>'white',
    '§2'=>'white',
    '§3'=>'black',
    '§4'=>'white',
    '§5'=>'white',
    '§6'=>'white',
    '§7'=>'black',
    '§8'=>'black',
    '§9'=>'white',
    '§a'=>'black',
    '§b'=>'black',
    '§c'=>'white',
    '§d'=>'black',
    '§e'=>'black',
    '§f'=>'black'
    );
    $colorcode = str_replace('&', '§', $colorcode);
    foreach($codes as $i => $item) {
        if(strtolower($colorcode) == strtolower($i)){
            return $item;
        }
    }
    return "transparent";
}

function getPanelRank($uuid){
    include_once 'config/config.php';
    $stmt = getMySQL()->prepare("SELECT * FROM core_web_accounts WHERE player = :uuid");
    $stmt->bindParam(":uuid", $uuid);
    $stmt->execute();
    if($stmt->rowCount() >= 1) {
        $row = $stmt->fetch();
        $rank = $row['rank'];
        return $rank;
    }
    return 0;
}

function hasPermission($permission){
    include_once 'config/config.php';
    $stmt = getMySQL()->prepare("SELECT * FROM core_web_accounts WHERE player = :uuid");
    $stmt->bindParam(":uuid", $_SESSION['uuid']);
    $stmt->execute();
    if($stmt->rowCount() >= 1){
        $row = $stmt->fetch();
        $rank = $row['rank'];
        if(!isset(PERMS[strtolower($permission)])){
            return false;
        }

        $minRank = PERMS[strtolower($permission)];
        return $rank >=$minRank;
    }

    return false;
}

function getDateUnix($time){

    return date("d.m.Y H:i:s", round($time / 1000));

}
function getDateMacro($time){

    return date("d.m.Y H:i", time());

}

function getUnixTimestamp(){
    $seconds = microtime(true);
    return round( ($seconds * 1000) );
}

function addToCurrentUnix($value, $type){
    $type = strtolower($type);
    $add = 0;
    if($type == 'minuten' || $type == 'm'){
        $add = $value * 60 * 1000;
    }else if($type == 'stunden' || $type == 'h'){
        $add = $value * 60 * 60 * 1000;
    }else if($type == 'tage' || $type == 'd'){
        $add = $value * 24 * 60 * 60 * 1000;
    }else if($type == 'permanent' || $type == "p"){
        return -1;
    }

    $seconds = microtime(true);

    $millies = round( ($seconds * 1000) );

    $result = $millies + $add;

    return $result;
}
function translateToUnix($value, $type){
    $type = strtolower($type);
    $add = 0;
    if($type == 'minuten' || $type == 'm'){
        $add = $value * 60 * 1000;
    }else if($type == 'stunden' || $type == 'h'){
        $add = $value * 60 * 60 * 1000;
    }else if($type == 'tage' || $type == 'd'){
        $add = $value * 24 * 60 * 60 * 1000;
    }else if($type == 'permanent' || $type == "p"){
        return -1;
    }

    $result = $add;

    return $result;
}

function addHistory($target, $action, $reason, $lenght, $issuer){
    $time = getUnixTimestamp();
    $stmt = getMySQL()->prepare("INSERT INTO prime_bungee_actions values (id,:target,:action,:value,:valuetime,:issuer,:timestamp)");
    $stmt->bindParam(":target", $target);
    $stmt->bindParam(":action", $action);
    $stmt->bindParam(":value", $reason);
    $stmt->bindParam(":valuetime", $lenght);
    $stmt->bindParam(":issuer", $issuer);
    $stmt->bindParam(":timestamp", $time);
    $stmt->execute();
}

function remainingToString($millies){
    if($millies <= 0){
        return "Permanent";
    }
    $seconds = $millies / 1000;
    $msg = "";
    if ($seconds >= 60 * 60 * 24) {
        $days = $seconds / (60 * 60 * 24);
        $seconds = $seconds % (60 * 60 * 24);
        $msg .= $days." Tag(e) ";

        }
    if ($seconds >= 60 * 60) {
        $h = $seconds / (60 * 60);
        $seconds = $seconds % (60 * 60);
        $msg .= $h." Stunde(n) ";
        }
    if ($seconds >= 60) {
        $min = $seconds / 60;
            $seconds = $seconds % 60;
        $msg .= $min." Minute(n) ";
        }
    return $msg;
}

function sendAlert($message, $color = "#FF4A57"){
    $id = rand(0, 10);
    echo '
    <div class="alert hidden" id="alert-'.$id.'" style="background-color: '.$color.'">
        <span>'.$message.'</span>
        <button id="close-'.$id.'"><i class="fas fa-times"></i></button>
    </div>
    <script>
        document.getElementById("alert-'.$id.'").classList.remove("hidden");
    document.getElementById("close-'.$id.'").addEventListener("click", function (event){
        document.getElementById("alert-'.$id.'").classList.add("hidden");
    })
    
    setTimeout(function() {
        document.getElementById("alert-'.$id.'").classList.add("hidden");
    }, 5000)
</script>
    ';

}

function getSettingValue($ident){
    $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_settings WHERE identifier = :ident");
    $stmt->bindParam(":ident", $ident);
    $stmt->execute();
    return $stmt->fetch()['value'];
}
function setSettingValue($ident, $value){
    $value = str_replace("&", "§", $value);
    $stmt = getMySQL()->prepare("UPDATE prime_bungee_settings SET value = :value WHERE identifier = :ident");
    $stmt->bindParam(":ident", $ident);
    $stmt->bindParam(":value", $value);
    $stmt->execute();
}