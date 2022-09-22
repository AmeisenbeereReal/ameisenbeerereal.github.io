<html lang="en">
<head>

    <meta name="description" content="Dein Minecraft Server">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="scss/styles.css">
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
    if (isset($_SESSION['username'])) {
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

    if (!hasPermission("usermanagement_view")) {
        header("Location: home.php");
        die();
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
    if (isset($_GET['search'])) {
        if ($_GET['search'] == "") {
            $_GET['search'] = " ";
        }
        include_once 'usermanagement_search.php';
        include_once 'usermanagement_searchresult.php';
    } else if (isset($_GET['uuid'])) {
        include_once 'usermanagement_search.php';
        include_once 'usermanagement_entry.php';
    } else {
        include_once 'usermanagement_search.php';
    }
    ?>
</div>

<script src="js/colorcodes.js"></script>
<script src="js/bootstrap.js"></script>

</body>
</html>