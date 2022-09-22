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

    if (!hasPermission("usermanagement_permissions_view")) {
        header("Location: home.php");
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['POST'] = $_POST;
        unset($_POST);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    $passclass = "";

    if(isset($_SESSION['POST']['submit-changepw'])){
        $check = validateLogin($player->name, $_SESSION['POST']['old']);
        if($check == null){
            $passclass = "is-invalid";
        }else{
            $passhash = password_hash($_SESSION['POST']['new1'], PASSWORD_BCRYPT);
            $stmt = getMySQL()->prepare("UPDATE core_web_accounts SET password = :password WHERE player = :uuid");
            $stmt->bindParam(":password", $passhash);
            $stmt->bindParam(":uuid", $player->uuid);
            $stmt->execute();
            sendAlert("Dein Passwort wurde erfolgreich verändert!", "green");
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
            <div class="col-sm">
                <div class="window-child">
                    <h1><i class="fas fa-cogs"></i> Einstellungen</h1>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <h4><i class="fas fa-key"></i> Passwort ändern</h4>
                            <hr>
                            <form method="post" id="passwordForm">
                                <div class="row">
                                    <div class="col-sm mb-3">
                                        <label for="tag" class="form-label">Aktuelles Passwort</label>
                                        <input type="password" class="form-control dark-input <?php echo $passclass ?>" name="old" id="old">
                                        <div class="invalid-feedback">
                                            Das Passwort ist falsch!
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm mb-3">
                                        <label for="tag" class="form-label">Neues Passwort</label>
                                        <input type="password" class="form-control dark-input" name="new1" id="new1">
                                        <div class="invalid-feedback">
                                            Die Passwörter stimmen nicht überein!
                                        </div>
                                    </div>
                                    <div class="col-sm mb-3">
                                        <label for="tag" class="form-label">Neues Passwort wiederholen</label>
                                        <input type="password" class="form-control dark-input" name="new2" id="new2">
                                    </div>
                                </div>
                                <button type="submit" name="submit-changepw" class="btn btn-primary">Speichern</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/colorcodes.js"></script>
<script src="js/bootstrap.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<script>
    $('#passwordForm').submit(function (event) {
        let new1 = $('#new1');
        let new2 = $('#new2');
        if(new1.val() !== new2.val() || new1.val() === "" || new1.val() === null){
            event.preventDefault();
            new1.addClass("is-invalid");
            new2.addClass("is-invalid");
        }
    });
</script>

</body>
</html>