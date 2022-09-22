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
    if(!hasPermission("usermanagement_punishment_view")){
        header("Location: home.php");
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
    <?php
    include_once 'sanctions_home.php';
    ?>
</div>

<script src="js/colorcodes.js"></script>
<script src="js/bootstrap.js"></script>

</body>
</html>