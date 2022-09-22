<html lang="en">
<head>

    <meta name="description" content="Dein Minecraft Server">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/styles.css">
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once './inc/sqlinc.php';
    require_once './inc/websocket_client.php';
    echo '<title>' . PAGETITLE . '</title>';
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
    }
    if(isset($_SESSION['username'])){
        $player = new Player();
        $player->name = $_SESSION['username'];
        $player->uuid = $_SESSION['uuid'];
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['POST'] = $_POST;
        unset($_POST);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    ?>

</head>

<body>

<?php
include_once 'navbar.php';
?>

<div class="background"></div>

<div class="window-container">
    <div class="container">
        <div class="row">
            <div class="col mycol">
                <div class="window-child">
                    <h1><i class="fas fa-search"></i> Spieler Suchen</h1>
                    <hr>
                    <form method="get" autocomplete="off" id="searchForm">
                        <div class="row g-3">
                            <div class="col">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Name</span>
                                    <input id="player-serach" list="datalistOptions"  name="search" type="text" class="form-control" placeholder="Spielername" aria-label="Spielername" aria-describedby="basic-addon1">
                                    <datalist id="datalistOptions">
                                    </datalist>
                                    <div class="invalid-feedback">
                                        Dieser Spieler existiert nicht!
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
    <div id="stats-content"></div>
</div>

<script src="js/colorcodes.js"></script>
<script src="js/bootstrap.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script>

    let input = $('#player-serach');
    let elem = $('#stats-content');
    input.keyup(function (event) {
        updateOptions();
    })
    loadMembers("<?php
        if(isset($_GET['uuid'])){
            echo $_GET['uuid'];
        }else{
            echo $player->uuid;
        }
        ?>");
    function loadMembers(uuid){
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            if(this.readyState === 4 && this.status === 200){
                if(this.response === "INVALID"){
                    invalidate();
                }else {
                    elem.html(this.response);
                    input.removeClass("is-invalid");
                }
            }
        }
        ajax.open("GET", "stats_entry.php?uuid=" + uuid)
        ajax.send();
    }
    function updateOptions(){
        var name = input.val();
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            let list = $('#datalistOptions');
            list.html("");
            if(this.readyState === 4 && this.status === 200){
                let res = this.response;
                console.log(this.response)
                let json = JSON.parse(res);
                for(let key in json){
                    list.append($("<option></option>").val(json[key].realname));
                }
            }
        }

        ajax.open("GET", "api.php?action=players&name=" + name)
        ajax.send();
    }

    function invalidate(){
        input.addClass("is-invalid");
        elem.html("");
    }

    $('#searchForm').submit(function (event){
        event.preventDefault();
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            let list = $('#datalistOptions');
            list.html("");
            if(this.readyState === 4 && this.status === 200) {
                let res = this.response;
                loadMembers(res);
            }
        }

        ajax.open("GET", "api.php?action=getUUID&name=" + input.val());
        ajax.send();
    });
</script>

</body>
</html>