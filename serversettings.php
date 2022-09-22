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


    if (!hasPermission("server_settings")) {
        header("Location: home.php");
        die();
    }

    if(isset($_SESSION['POST']['submit-settings'])){
        setSettingValue("MOTD_NORMAL_1", $_SESSION['POST']['motdnormal1']);
        setSettingValue("MOTD_NORMAL_2", $_SESSION['POST']['motdnormal2']);
        setSettingValue("MOTD_WARTUNG_1", $_SESSION['POST']['motdwartung1']);
        setSettingValue("MOTD_WARTUNG_2", $_SESSION['POST']['motdwartung2']);
        setSettingValue("SLOTS", $_SESSION['POST']['slots']);
        if(isset($_SESSION['POST']['wartung'])){
            $wartung = "true";
        }else{
            $wartung = "false";
        }
        setSettingValue("WARTUNG", $wartung);
        sendAlert("Die Einstellungen wurde erfolgreich gespeichert!", "green");

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
                <div class="window-child">
                    <h1><i class="fas fa-cog"></i> Einstellungen</h1>
                    <hr>
                    <br>
                    <form method="post">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>MOTD</h3>
                                <br>
                                <h6>Normale MOTD</h6>
                                <div id="colormotdn1" class="mcColor"></div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Zeile 1</span>
                                    <input name="motdnormal1" id="motdnormal1" value="<?php echo str_replace('§', '&', getSettingValue("MOTD_NORMAL_1")); ?>" type="text" class="form-control" aria-describedby="basic-addon1">
                                </div>
                                <div id="colormotdn2" class="mcColor"></div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Zeile 2</span>
                                    <input name="motdnormal2" id="motdnormal2" value="<?php echo str_replace('§', '&', getSettingValue("MOTD_NORMAL_2")); ?>" type="text" class="form-control" aria-describedby="basic-addon1">
                                </div>
                                <br>
                                <div id="wartungs">
                                    <h6>Wartungs MOTD</h6>
                                    <div id="colormotdw1" class="mcColor"></div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">Zeile 1</span>
                                        <input name="motdwartung1" id="motdwartung1" value="<?php echo str_replace('§', '&', getSettingValue("MOTD_WARTUNG_1")); ?>" type="text" class="form-control" aria-describedby="basic-addon1">
                                    </div>
                                    <div id="colormotdw2" class="mcColor"></div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">Zeile 2</span>
                                        <input name="motdwartung2" id="motdwartung2" value="<?php echo str_replace('§', '&', getSettingValue("MOTD_WARTUNG_2")); ?>" type="text" class="form-control" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h3>Einstellung</h3>
                                <br>
                                <div class="row">
                                    <h6>Slots</h6>
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Anzahl</span>
                                            <input name="slots" id="slots" value="<?php echo str_replace('§', '&', getSettingValue("SLOTS")); ?>" type="number" class="form-control" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                    <div class="col">

                                        <h6>Wartungsmodus</h6>
                                        <label class="switch">
                                            <input name="wartung" id="wartung" type="checkbox" <?php if(getSettingValue("WARTUNG") == "true"){echo "checked";} ?>>
                                            <div>
                                                <span></span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button name="submit-settings" type="submit" class="btn btn-primary">Speichern</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap.js"></script>
<script src="js/colorcodes.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script>
    $('form').keyup(function (event) {
        updateMotds();
    });
    updateMotds();
    checkWartung;
    function updateMotds(){
        $('#colormotdn1').html(parseStyle($('#motdnormal1').val().replaceAll("&", "§")));
        $('#colormotdn2').html(parseStyle($('#motdnormal2').val().replaceAll("&", "§")));
        $('#colormotdw1').html(parseStyle($('#motdwartung1').val().replaceAll("&", "§")));
        $('#colormotdw2').html(parseStyle($('#motdwartung2').val().replaceAll("&", "§")));
    }
    $('#wartung').click(function (event) {
        checkWartung();
    });

    function checkWartung(){
        if($('#wartung').is(':checked')){
            $('#wartungs').removeClass("hide");
        }else {
            $('#wartungs').addClass("hide");
        }
    }
</script>

</body>
</html>