<?php
include_once 'inc/sqlinc.php';
$player = Player::fromUUID($_GET['uuid']);

if($player == null){
    echo "INVALID";
    return;
}

?>


<div class="col mycol">
    <div class="col mycol">
        <div class="window-child">
            <h1><i class="fas fa-question"></i> Deine Anfragen</h1>
            <hr>
            <div class="row d-flex justify-content-center">
                <?php
                $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_requests WHERE uuid = :uuid");
                $stmt->bindParam(":uuid", $player->uuid);
                $stmt->execute();
                while ($row = $stmt->fetch()){
                       $friend = Player::fromUUID($row['requester']);
                       $date = getDateUnix($row['time']);
                       ?>

                        <div class="friend-entry col-4" style="padding: 0">
                            <img src="<?php echo "https://cravatar.eu/helmavatar/".$friend->uuid."/200" ?>">
                            <div class="friend-name"><?php

                                if(PERMISSIONS){
                                    $group = $friend->getHighestGroup();
                                    if($group != null) {
                                        $color = translateMcColor($group->color);
                                        $colortext = translateToReadableColorOfMc($group->color);
                                        $name = $group->displayname;
                                        echo '<span class="rankBadge" style="color: ' . $colortext . ' ;background-color: ' . $color . '">' . $name . '</span>';
                                    }
                                }
                                echo '<span class="rawName">'.$friend->name."</span>";
                                ?></div>
                            <div class="friend-items">
                                <div class="friend-item">Angefragt seit: <?php echo $date ?></div>
                            </div>
                        </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>


<div id="popup"></div>
<script>
    $('.friend-entry').click(function (event) {
        let elem = $(this);
        let name = $(".rawName", $(".friend-name", elem)).html();
        $('#popup').html($.ajax({
            type: "GET",
            url: "friends_request_entry.php?requester=" + name,
            async: false
        }).responseText);
    });
</script>
