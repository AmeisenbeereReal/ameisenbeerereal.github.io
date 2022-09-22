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
                    <h1><i class="fas fa-user"></i> <?php echo $player->name ?></h1>
                    <hr>
                    <p style="font-size: 1rem;">
                        <b><i class="fas fa-coins"></i> Coins:</b> <?php echo $player->getCoins() ?>
                        <br>
                        <b><i class="far fa-clock"></i> Spielzeit:</b> <?php echo $player->getOnMinsAsString() ?>
                        <br>
                        <b><i class="far fa-handshake"></i></i> Freunde online:</b> <?php echo $player->getOnlineFriendsCoins()."/".$player->getFriendsCount() ?>
                    </p>
                </div>
            </div>
            <div class="col mycol">
                <div class="window-child">
                    <h1><i class="fas fa-chart-pie"></i> Statistiken</h1>
                    <hr>
                    <p style="font-size: 1rem;">
                        <b><i class="fas fa-table"></i> Registrierte Spieler: </b> <?php echo getCountPlayers(); ?>
                        <br>
                        <b><i class="fas fa-globe"></i> Spieler online: </b> <?php echo getCountOnline(); ?>
                        <br>
                        <b><i class="fas fa-coins"></i> Coins insgesamt: </b> <?php echo getCountCoins(); ?>
                        <br>
                        <b><i class="fas fa-handshake"></i> Freundschaften: </b> <?php echo getCountFriends(); ?>
                        <br>
                        <b><i class="fas fa-users"></i> Clans: </b> <?php echo getCountClans(); ?>
                        <br>
                        <b><i class="fas fa-hourglass-half"></i> Spielzeit insgesamt: </b> <?php echo getCountPlaytimeString(); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/colorcodes.js"></script>
<script src="js/bootstrap.js"></script>

</body>
</html>