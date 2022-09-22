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

    if(isset($_SESSION['POST']['submit-unban'])){
            AppealEntry::create($player->uuid, $_SESSION['POST']['type'], $_SESSION['POST']['type'], $_SESSION['POST']['lenght'], $_SESSION['POST']['message']);
        unset($_SESSION['POST']);
    }



    if(isset($_GET['message'])){
        if(isset($_GET['color'])){
            sendAlert($_GET['message'],$_GET['color']);
        }else {
            sendAlert($_GET['message']);
        }
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
                <?php
                $appeal = AppealEntry::fromPlayer($player->uuid);
                $ban = SQLBan::fromPlayerActive($player->uuid);
                $mute = SQLMute::fromPlayerActive($player->uuid);
                if($ban != null){
                    $reason = $ban->reason;
                    $lenght = remainingToString($ban->time - $ban->timestamp);
                }else if($mute != null){
                    $reason = $mute->reason;
                    $lenght = remainingToString($mute->time - $mute->timestamp);
                }else{
                    $reason = "";
                    $lenght = "";
                }
                if($appeal == null){
                ?>
                <div class="window-child">
                    <h1><i class="fas fa-unlock"></i> Entbannungsantrag stellen</h1>
                    <hr>
                    <form method="post">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="type">Art des Ban's</label>
                            <select name="type" required class="form-select" id="type">
                                <option <?php if($ban != null){echo "selected"; } ?>>Minecraft | Ban</option>
                                <option <?php if($ban == null && $mute != null){echo "selected"; }?>>Minecraft | Mute</option>
                                <option>Discord | Ban</option>
                                <option>Discord | Mute</option>
                                <option>Teamspeak | Ban</option>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Grund</span>
                            <input name="reason" required type="text" value="<?php echo $reason ?>" class="form-control" placeholder="Hacking..." aria-label="Hacking..." aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Länge</span>
                            <input name="lenght" required value="<?php echo $lenght ?>" type="text" class="form-control" placeholder="30 Tage" aria-label="30 Tage" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-floating">
                            <textarea name="message" required class="form-control" placeholder="Deine Nachricht" id="floatingTextarea2" style="height: 100px; color: black"></textarea>
                            <label for="floatingTextarea2" style="color: black">Deine Nachricht</label>
                        </div>
                        <br>
                        <button type="submit" name="submit-unban" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Senden</button>
                    </form>
                </div>
                <?php
                }else{
                    ?>
                    <div class="window-child">
                        <h1><i class="fas fa-hourglass-half"></i> In bearbeitung</h1>
                        <hr>
                        <p>Dein Entbannungsantrag wird aktuell bearbeitet. Bitte gedulde dich noch!</p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap.js"></script>

</body>
</html>