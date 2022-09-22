<?php

if(isset($_SESSION['username'])){
    $player = new Player();
    $player->name = $_SESSION['username'];
    $player->uuid = $_SESSION['uuid'];
}
?>
<div class="container">
    <div class="row">
        <div class="col mycol">
            <div class="window-child">
                <h1><i class="fas fa-search"></i> Clan Suchen</h1>
                <hr>
                <form method="get" id="searchForm" autocomplete="off">
                    <div class="row g-3">
                        <div class="col">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Name</span>
                                <input id="clanSearch" list="datalistOptions"  name="search" type="text" class="form-control" placeholder="Clanname" aria-label="Clanname" aria-describedby="basic-addon1">
                                <datalist id="datalistOptions">
                                </datalist>
                                <div class="invalid-feedback">
                                    Der Clan mit dem Namen existiert nicht!
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Suchen</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="clanContent"></div>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<script>


    $('#clanSearch').keyup(function (event){
        updateOptions();
    });

    function updateOptions(){
        var name = $('#clanSearch').val();
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            let list = $('#datalistOptions');
            list.html("");
            if(this.readyState === 4 && this.status === 200){
                let res = this.response;
                let json = JSON.parse(res);
                for(let key in json){
                    list.append($("<option></option>").val(key)
                    .text(json[key].realname + " [" + json[key].tag + "]"))
                }
            }
        }
        ajax.open("GET", "api.php?action=clans&name=" + name)
        ajax.send();
    }


    $('#searchForm').submit(function (event) {
        event.preventDefault();
        var name = $('#clanSearch').val();
        loadClan(name);
    })

    function loadClan(name){
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            if(this.readyState === 4 && this.status === 200){
                var res = this.response;
                if(res === "INVALID"){
                    invalidate()
                }else {
                    $('#clanContent').html(res);
                    $('#clanSearch').removeClass("is-invalid");
                }
            }
        }
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        ajax.open("GET", "clan_entry.php?clan=" + name + "&uuid=<?php echo $player->uuid?>")
        ajax.send();
    }


    function invalidate(){
        $('#clanContent').html("");
        $('#clanSearch').addClass("is-invalid");
    }
</script>
<?php
$pclan = $player->getClan();
if($pclan != null && !isset($_GET['clan'])){
    echo '<script> loadClan("'.$pclan->name.'")</script>';
}
if(isset($_GET['clan'])){
    echo '<script> loadClan("'.$_GET['clan'].'")</script>';
}else if($pclan == null){
    ?>
<script>

    let ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function (){
        if(this.readyState === 4 && this.status === 200){
            var res = this.response;
            if(res === "INVALID"){
                invalidate()
            }else {
                $('#clanContent').html(res);
                $('#clanSearch').removeClass("is-invalid");
            }
        }
    }
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    ajax.open("GET", "clan_rerquests.php?uuid=<?php echo $player->uuid ?>");
    ajax.send();
</script>
<?php
}