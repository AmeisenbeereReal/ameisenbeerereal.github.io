<?php

if(isset($_SESSION['username'])){
    $player = new Player();
    $player->name = $_SESSION['username'];
    $player->uuid = $_SESSION['uuid'];
}

?>

<link rel="stylesheet" href="css/styles.css">
<nav class="navbar navbar-expand-lg mynav sticky-top">
    <div class="container-fluid">
        <div class="navbar-brand mynav"><?php echo SERVERNAME?></div>
        <button class="navbar-toggler mynav" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars" style="color: white; font-size: 2rem;"></i>
        </button>
        <div  class="collapse navbar-collapse mynav" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item mynav <?php if(endsWith($_SERVER['PHP_SELF'], "home.php")){echo "active";}  ?>">
                    <a class="nav-link mynav" aria-current="page" href="home.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <?php if(STATS){ ?>
                    <li class="nav-item mynav <?php if(endsWith($_SERVER['PHP_SELF'], "stats.php")){echo "active";}  ?>">
                        <a class="nav-link mynav" aria-current="page" href="stats.php"><i class="fas fa-chart-bar"></i> Statistiken</a>
                    </li>
                <?php } ?>
                <?php if(CLAN){ ?>
                    <li class="nav-item mynav <?php if(endsWith($_SERVER['PHP_SELF'], "clan.php")){echo "active";}  ?>">
                        <a class="nav-link mynav" aria-current="page" href="clan.php"><i class="fas fa-users"></i> Clan</a>
                    </li>
                <?php } ?>
                <?php if(FRIENDS){ ?>
                    <li class="nav-item mynav <?php if(endsWith($_SERVER['PHP_SELF'], "friends.php")){echo "active";}  ?>">
                        <a class="nav-link mynav" aria-current="page" href="friends.php"><i class="fas fa-user-friends"></i> Freunde</a>
                    </li>
                <?php }
                if(UNBAN){ ?>
                    <li class="nav-item mynav <?php if(endsWith($_SERVER['PHP_SELF'], "appeal.php")){echo "active";}  ?>">
                        <a class="nav-link mynav" aria-current="page" href="appeal.php"><i class="fas fa-unlock-alt"></i> Entbannungsantrag</a>
                    </li>
                <?php }
                if(hasPermission("navbar_team")){?>
                <li class="nav-item mynav dropdown  <?php if(isTeamSite()){echo "active";}  ?>">
                    <a class="nav-link mynav dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-shield"></i> Team
                    </a>
                    <ul class="dropdown-menu mynav" aria-labelledby="navbarDropdown">
                        <?php
                        if(PUNISHMENT){
                            echo '<li><a class="dropdown-item mynav" href="sanctions.php"><i class="far fa-times-circle"></i></i> Moderation</a></li>';
                        }
                        if(PERMISSIONS){
                            echo '<li><a class="dropdown-item mynav" href="permissions.php"><i class="fas fa-users-cog"></i> Permissions</a></li>';
                        }
                        if(SETTINGS){
                            echo '<li><a class="dropdown-item mynav" href="serversettings.php"><i class="fas fa-cog"></i> Server einstellungen</a></li>';
                        }
                        ?>
                        <li><hr class="dropdown-divider mynav"></li>
                        <li><a class="dropdown-item mynav" href="usermanegement.php"><i class="far fa-user"></i> Spieler verwalten</a></li>
                    </ul>
                </li>
                    <?php } ?>
            </ul>
            <div class="d-flex" style="margin-right: 50px">
                <div class="dropdown">
                    <img src="<?php echo $player->getAvatarUrl(); ?>" style="border-radius: 50%;" role="button" class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                    <ul class="dropdown-menu dropdown-menu-lg-end mynav" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item  mynav" href="settings.php"><i class="fas fa-cogs"></i> Einstellungen</a></li>
                        <li><hr class="dropdown-divider mynav"></li>
                        <li><a class="dropdown-item  mynav red" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<?php
function isTeamSite(){
    if(
            endsWith($_SERVER['PHP_SELF'], "serversettings.php") ||
            endsWith($_SERVER['PHP_SELF'], "sanctions.php") ||
            endsWith($_SERVER['PHP_SELF'], "permissions.php") ||
            endsWith($_SERVER['PHP_SELF'], "usermanegement.php")
    ){
        return true;
    }
    return false;
}