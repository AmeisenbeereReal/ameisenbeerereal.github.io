<?php
include_once 'inc/sqlinc.php';
if(!isset($_GET['clan'])) {
    echo 'INVALID';
}else{
$clan = Clan::fromName(strtolower($_GET['clan']));
if($clan == null){
    echo 'INVALID';
    return;
}
?>

    <div class="row">
        <div class="col-sm-12 mycol">
            <div class="window-child">
                <h1><i class="fas fa-crown"></i> Administratoren</h1>
                <hr>
                <div class="row">
                    <?php
                    $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE `rank` >= 2 AND clan = :clan ORDER BY prime_clan_players.`rank` DESC ");
                    $stmt->bindParam(":clan", $clan->id);
                    $stmt->execute();
                    while ($row = $stmt->fetch()){
                        $currPlayer = Player::fromUUID($row['uuid']);
                        ?>
                            <div class="col-sm">
                                <div class="clan-user">
                                    <?php
                                    if($row['rank'] >= 3){
                                        echo '<i class="clan-user-owner far fa-star"></i>';
                                    }
                                    ?>
                                    <img src="<?php echo $currPlayer->getAvatarUrl() ?>">
                                    <span><?php echo $currPlayer->name ?></span>
                                </div>
                            </div>
                        <br>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 mycol">
            <div class="window-child">
                <h1><i class="fas fa-user-shield"></i> Moderatoren</h1>
                <hr>
                <div class="row">
                    <?php
                    $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE `rank` = 1 AND clan = :clan ORDER BY prime_clan_players.`rank` DESC ");
                    $stmt->bindParam(":clan", $clan->id);
                    $stmt->execute();
                    while ($row = $stmt->fetch()){
                        $currPlayer = Player::fromUUID($row['uuid']);
                        ?>
                        <div class="col-sm">
                            <div class="clan-user">
                                <?php
                                if($row['rank'] >= 3){
                                    echo '<i class="clan-user-owner far fa-star"></i>';
                                }
                                ?>
                                <img src="<?php echo $currPlayer->getAvatarUrl() ?>">
                                <span><?php echo $currPlayer->name ?></span>
                            </div>
                        </div>
                        <br>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 mycol">
            <div class="window-child">
                <h1><i class="far fa-user"></i> Member</h1>
                <hr>
                <div class="row">
                    <?php
                    $stmt = getMySQL()->prepare("SELECT * FROM prime_clan_players WHERE `rank` = 0 AND clan = :clan ORDER BY prime_clan_players.`rank` DESC ");
                    $stmt->bindParam(":clan", $clan->id);
                    $stmt->execute();
                    while ($row = $stmt->fetch()){
                        $currPlayer = Player::fromUUID($row['uuid']);
                        ?>
                        <div class="col-sm">
                            <div class="clan-user">
                                <?php
                                if($row['rank'] >= 3){
                                    echo '<i class="clan-user-owner far fa-star"></i>';
                                }
                                ?>
                                <img src="<?php echo $currPlayer->getAvatarUrl() ?>">
                                <span class="clan-name"><?php echo $currPlayer->name ?></span>
                            </div>
                        </div>
                        <br>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    if(isset($_COOKIE['auth'])){
        $auth = json_decode($_COOKIE['auth']);
        $player = Player::fromUUID($auth->uuid);
        if($player->getClanRankInClan($clan->id) >= 1){
    ?>
    <div id="userpopup"></div>


    <script>
        $('.clan-user').click(function (event) {
            let elem = $(this);
            let playerName = $('span', elem).html();
            $('#userpopup').html($.ajax({
                type: "GET",
                url: "clan_playerActions.php?player=" + playerName,
                async: false
            }).responseText);
        });
    </script>


<?php
        }
    }
}