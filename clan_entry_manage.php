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

    <div class="window-child">
        <div class="row">
            <div class="col-8">
                <h1><i class="fas fa-list-ol"></i> Clan verwalten</h1>
            </div>
            <div class="col-4" style="position: relative">
                <button style="position: absolute; right: 5%;" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#invite">Spieler einladen</button>
            </div>
        </div>
        <hr>
        <form method="post" action="action_editClan.php">
            <input name="clan" hidden value="<?php echo $_GET['clan'] ?>">
            <div class="row">
                <div class="col-sm mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control dark-input" name="name" id="name" value="<?php echo $clan->realname ?>">
                </div>
                <div class="col-sm mb-3">
                    <label for="tag" class="form-label">Tag</label>
                    <input type="text" class="form-control dark-input" name="tag" id="tag" value="<?php echo $clan->tag ?>">
                </div>
            </div>
            <button type="submit" name="submit-clanedit" class="btn btn-primary">Speichern</button>
        </form>
    </div>
</div>


    <!-- Modal INVITE -->
    <div class="modal fade" id="invite" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <form action="action_clanInvitePlayer.php" method="post" autocomplete="off" id="inviteForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Spieler einladen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <br>
                        <div class="col-md">
                            <div class="form-floating">
                                <input id="clan" name="clan" hidden value="<?php echo $_GET['clan'] ?>">
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


    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script>
        
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

<?php

}