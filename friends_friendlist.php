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
            <div class="row">
                <div class="col-8">
                    <h1><i class="far fa-heart"></i> Deine Freunde</h1>
                </div>
                <div class="col-4" style="position: relative">
                    <button style="position: absolute; right: 5%;" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#invite">Freundschaftsanfrage versenden</button>
                </div>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <?php
                $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_friends WHERE uuid = :uuid");
                $stmt->bindParam(":uuid", $player->uuid);
                $stmt->execute();
                while ($row = $stmt->fetch()){
                       $friend = Player::fromUUID($row['friend']);
                       $date = getDateUnix($row['time']);
                        $server = $friend->getServer();
                        if($server == null) {
                            $server = '<span class="rankBadge rankBadge-l" style=";background-color: red">Offline</span>';
                        }else{
                            $server = '<span class="rankBadge rankBadge-l" style=";background-color: green">'.$server.'</span>';
                        }
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
                                <div class="friend-item">Befreundet seit: <?php echo $date ?></div>
                                <hr>
                                <div class="friend-item">Online auf: <?php echo $server ?></div>
                            </div>
                        </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Modal INVITE -->
<div class="modal fade" id="invite" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <form method="post" autocomplete="off" id="inviteForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Spieler einladen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <br>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" placeholder="Spielername" list="datalistOptions" class="form-control" id="player" name="player">
                            <label for="player" style="color: black">Spielername</label>
                            <datalist id="datalistOptions">
                            </datalist>
                            <div class="invalid-feedback">
                                Dieser Spieler ist dem System nicht bekannt!
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit-invite" class="btn btn-primary"><i class="fab fa-telegram-plane"></i> Einladung Senden</button>
                </div>
            </form>
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
            url: "friends_friendlist_entry.php?requester=" + name,
            async: false
        }).responseText);
    });

    $('#inviteForm').submit(function (event) {
        let input = $('#player');
        let found = false;
        let name = input.val();
        let json = JSON.parse($.ajax({
            type: "GET",
            url: "api.php?action=players&name=" + name,
            async: false
        }).responseText);
        for(let key in json){
            found = true
            return;
        }
        if(!found){
            event.preventDefault();
            input.addClass("is-invalid");
        }
    });

    $('#player').keyup(function (event) {
        updateOptions();
    })

    function updateOptions(){
        let input = $('#player');
        var name = input.val();
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            let list = $('#datalistOptions');
            list.html("");
            if(this.readyState === 4 && this.status === 200){
                let res = this.response;
                let json = JSON.parse(res);
                for(let key in json){
                    list.append($("<option></option>").val(json[key].realname));
                }
            }
        }

        ajax.open("GET", "api.php?action=players&name=" + name)
        ajax.send();
    }
</script>