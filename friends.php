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
    echo '<title>' . PAGETITLE . '</title>';
    session_start();

    if (isset($_SESSION['username'])) {
        $player = new Player();
        $player->name = $_SESSION['username'];
        $player->uuid = $_SESSION['uuid'];
    }else{
        header("Location: index.php");
        return;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['POST'] = $_POST;
        unset($_POST);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }


    if(isset($_GET['message'])){
        if(isset($_GET['color'])){
            sendAlert($_GET['message'],$_GET['color']);
        }else {
            sendAlert($_GET['message']);
        }
    }

    if(isset($_SESSION['POST']['submit-invite'])){
        $target = Player::fromName($_SESSION['POST']['player']);
        if($player->uuid == $target->uuid){
            sendAlert("Du kannst nicht mit dir selbst befreundet sein!");
        }else {
            $time = getUnixTimestamp();
            $stmt = getMySQL()->prepare("INSERT INTO prime_bungee_requests VALUES (id, :uuid, :reqeuster, :time)");
            $stmt->bindParam(":uuid", $target->uuid);
            $stmt->bindParam(":reqeuster", $player->uuid);
            $stmt->bindParam(":time", $time);
            $stmt->execute();
            sendAlert("Deine Einladung wurde erfolgreich versand!", "green");
        }

        unset($_SESSION['POST']);
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
            <div class="col-sm-3 mycol">
                <div class="col mycol">
                    <div class="window-child">
                        <h1><i class="fas fa-list-ul"></i> Deine Freunde</h1>
                        <hr>
                        <div class="clan-nav">
                            <div id="list" class="clan-nav-entry active">
                                Freundesliste
                            </div>
                            <div id="requests" class="clan-nav-entry">
                                <div class="row">
                                    <div class="col">Freundschaftsanfragen</div>
                                    <div class="col"><span class="badge bg-primary"><?php echo $player->getFriendRequstAmount() ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 mycol">
                <div id="content"></div>
            </div>
        </div>
    </div>
</div>
<script src="js/colorcodes.js"></script>
<script src="js/bootstrap.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script>
    const uuid = "<?php echo $player->uuid ?>";
    loadList();

    $('#list').click(function (event){
        loadList();
    });

    $('#requests').click(function (event){
        loadReqeusts();
    });

    function loadList(){
        $('#list').addClass("active")
        $('#requests').removeClass("active")
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            let elem = $('#content');
            if(this.readyState === 4 && this.status === 200){
                elem.html(this.response);
            }
        }
        ajax.open("GET", "friends_friendlist.php?uuid=" + uuid)
        ajax.send();
    }

    function loadReqeusts(){
        $('#list').removeClass("active")
        $('#requests').addClass("active")
        let ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function (){
            let elem = $('#content');
            if(this.readyState === 4 && this.status === 200){
                elem.html(this.response);
            }
        }
        ajax.open("GET", "friends_requests.php?uuid=" + uuid)
        ajax.send();
    }
</script>

</body>
</html>