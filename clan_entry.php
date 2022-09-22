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
    $player = Player::fromUUID($_GET['uuid']);
}
?>
<div class="container">
    <div class="row">
        <div class="col-sm-3 mycol">
            <div class="col mycol">
                <div class="window-child">
                    <h1><i class="fas fa-bars"></i> <?php echo $clan->realname." [".$clan->tag."]"?></h1>
                    <hr>
                    <div class="clan-nav">
                        <div id="members" class="clan-nav-entry active">
                            Members
                        </div>
                        <div id="stats" class="clan-nav-entry">
                            Statistiken
                        </div>
                        <?php
                        if($player != null){
                        if($player->getClanRankInClan($clan->id) >= 2) {
                            echo '
                        <div id="manage" class="clan-nav-entry">
                            Verwalten
                        </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 mycol">
            <div id="clan-content"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script>
        loadMembers();

    $('#members').on('click',function () {
            loadMembers()
        });
    $('#stats').on('click',function () {
            loadStats()
        });

    $('#manage').on('click',function () {
            loadManage()
        });


    function loadMembers(){
        $('#stats').removeClass("active")
        $('#manage').removeClass("active")
        $('#members').addClass("active")
        var clan = "<?php echo $clan->name?>";
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            let elem = $('#clan-content');
            if(this.readyState === 4 && this.status === 200){
                elem.html(this.response);
            }
        }
        ajax.open("GET", "clan_entry_memers.php?clan=" + clan)
        ajax.send();
    }
    function loadStats(){
        $('#stats').addClass("active")
        $('#manage').removeClass("active")
        $('#members').removeClass("active")
        var clan = "<?php echo $clan->name?>";
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            let elem = $('#clan-content');
            if(this.readyState === 4 && this.status === 200){
                elem.html(this.response);
            }
        }
        ajax.open("GET", "clan_entry_stats.php?clan=" + clan)
        ajax.send();
    }
    function loadManage(){
        $('#manage').addClass("active")
        $('#stats').removeClass("active")
        $('#members').removeClass("active")
        var clan = "<?php echo $clan->name?>";
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            let elem = $('#clan-content');
            if(this.readyState === 4 && this.status === 200){
                elem.html(this.response);
            }
        }
        ajax.open("GET", "clan_entry_manage.php?clan=" + clan)
        ajax.send();
    }
</script>
<?php
}