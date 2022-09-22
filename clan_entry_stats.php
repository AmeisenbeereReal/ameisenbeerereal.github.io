<?php
include_once 'inc/sqlinc.php';
if (!isset($_GET['clan'])) {
    echo 'INVALID';
} else {
    $clan = Clan::fromName(strtolower($_GET['clan']));
    if ($clan == null) {
        echo 'INVALID';
        return;
    }
    ?>

        <div class="window-child">
            <h1><i class="fas fa-chart-pie"></i> Statistiken (insg.)</h1>
            <hr>
            <div class="row d-flex justify-content-center">

                <div class="stats-card col-4">
                    <div class="stats-header">
                        <h1 class="stats-title">Allgemeines</h1>
                        <img class="stats-img" src="img/playtime.jpg">
                    </div>
                    <div class="stats-entries">
                        <?php
                        $time = $clan->getOnTime();
                        $days = floor($time / 60 / 24);
                        $hours = floor(($time - ($days * 60 * 24)) / 60);
                        $mins = $time - ($hours * 60) - ($days * 60 * 24);
                        /**
                        if($days >= 1){
                        ?>
                        <div class="stats-entry">
                        <span class="stats-entry-title"><i class="fas fa-bed"></i> Tage</span>
                        <span class="stats-entry-value"><?php echo $days ?></span>
                        </div>
                        <hr>
                        <?php
                        }
                        if($days >= 1 || $hours >= 1){
                        ?>
                        <div class="stats-entry">
                        <span class="stats-entry-title"><i class="fas fa-bed"></i> Stunden</span>
                        <span class="stats-entry-value"><?php echo $hours ?></span>
                        </div>
                        <hr>
                        <?php
                        }

                         */
                        ?>

                        <div class="stats-entry">
                            <span class="stats-entry-title"><i class="far fa-clock"></i> Spielzeit</span>
                            <span class="stats-entry-value"><?php echo $days."d ".$hours. "h ". $mins. "m" ?></span>
                        </div>
                        <hr>
                        <div class="stats-entry">
                            <span class="stats-entry-title"><i class="fas fa-coins"></i> Coins</span>
                            <span class="stats-entry-value"><?php echo $clan->getCoins() ?></span>
                        </div>
                    </div>
                </div>
                <?php if(MLGRUSH){
                    $mlgrushstats = $clan->getMLGRushStats();
                    ?>
                    <div class="stats-card col-4">
                        <div class="stats-header">
                            <h1 class="stats-title">MLG-Rush</h1>
                            <img class="stats-img" src="img/mlgrush.png">
                        </div>
                        <div class="stats-entries">
                            <div class="stats-entry">
                                <span class="stats-entry-title"><i class="fas fa-trophy"></i> Wins</span>
                                <span class="stats-entry-value"><?php echo $mlgrushstats->wins ?></span>
                            </div>
                            <hr>
                            <div class="stats-entry">
                                <span class="stats-entry-title"><i class="fas fa-skull-crossbones"></i> Kills</span>
                                <span class="stats-entry-value"><?php echo $mlgrushstats->kills ?></span>
                            </div>
                            <hr>
                            <div class="stats-entry">
                                <span class="stats-entry-title"><i class="fas fa-skull"></i> Tode</span>
                                <span class="stats-entry-value"><?php echo $mlgrushstats->deaths ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if (BUILDFFA) {
                    $buildffastats = $clan->getBuildFFAStats();
                    ?>
                    <div class="stats-card col-4">
                        <div class="stats-header">
                            <h1 class="stats-title">BuildFFA</h1>
                            <img class="stats-img" src="img/buildffa.png">
                        </div>
                        <div class="stats-entries">
                            <div class="stats-entry">
                                <span class="stats-entry-title"><i class="fas fa-skull-crossbones"></i> Kills</span>
                                <span class="stats-entry-value"><?php echo $buildffastats->kills ?></span>
                            </div>
                            <hr>
                            <div class="stats-entry">
                                <span class="stats-entry-title"><i class="fas fa-skull"></i> Tode</span>
                                <span class="stats-entry-value"><?php echo $buildffastats->deaths ?></span>
                            </div>
                            <hr>
                            <div class="stats-entry">
                                <span class="stats-entry-title"><i class="fas fa-stop"></i> Blöcke</span>
                                <span class="stats-entry-value"><?php echo $buildffastats->blocks ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if (SKYWARS) {
                    $swstats = $clan->getSkywarsStats();
                    ?>
                    <div class="stats-card col-4">
                    <div class="stats-header">
                        <h1 class="stats-title">SkyWars</h1>
                        <img class="stats-img" src="img/skywars.png">
                    </div>
                    <div class="stats-entries">
                        <div class="stats-entry">
                            <span class="stats-entry-title"><i class="fas fa-trophy"></i> Wins</span>
                            <span class="stats-entry-value"><?php echo $swstats->wins ?></span>
                        </div>
                        <hr>
                        <div class="stats-entry">
                            <span class="stats-entry-title"><i class="fas fa-skull-crossbones"></i> Kills</span>
                            <span class="stats-entry-value"><?php echo $swstats->kills ?></span>
                        </div>
                        <hr>
                        <div class="stats-entry">
                            <span class="stats-entry-title"><i class="fas fa-skull"></i> Tode</span>
                            <span class="stats-entry-value"><?php echo $swstats->deaths ?></span>
                        </div>
                    </div>
                    </div>
                    <?php
                }
                if(BEDWARS){
                    $bwstats = $clan->getBedWarsStats();
                    ?><div class="stats-card col-4">
                    <div class="stats-header">
                        <h1 class="stats-title">BedWars</h1>
                        <img class="stats-img" src="img/bedwars.jpg">
                    </div>
                    <div class="stats-entries">
                        <div class="stats-entry">
                            <span class="stats-entry-title"><i class="fas fa-trophy"></i> Wins</span>
                            <span class="stats-entry-value"><?php echo $bwstats->wins ?></span>
                        </div>
                        <hr>
                        <div class="stats-entry">
                            <span class="stats-entry-title"><i class="fas fa-skull-crossbones"></i> Kills</span>
                            <span class="stats-entry-value"><?php echo $bwstats->kills ?></span>
                        </div>
                        <hr>
                        <div class="stats-entry">
                            <span class="stats-entry-title"><i class="fas fa-skull"></i> Tode</span>
                            <span class="stats-entry-value"><?php echo $bwstats->deaths ?></span>
                        </div>
                    </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

    <?php

}