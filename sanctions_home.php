<?php
$player = new Player();
$player->name = $_SESSION['username'];
$player->uuid = $_SESSION['uuid'];

if(isset($_GET['accept'])){
    sendSocketSudoCommand($player->uuid, $player->uuid, "reports ".$_GET['accept']);
}

if(isset($_GET['delete'])){
    $stmt = getMySQL()->prepare("DELETE FROM prime_bungee_punishments WHERE id = :id");
    $stmt->bindParam(":id", $_GET['delete']);
    $stmt->execute();
}
?>
<div class="container">
    <div class="row">
        <div class="col mycol">
            <div class="window-child">
                <h1><i class="fas fa-exclamation-triangle"></i> Reports</h1>
                <hr>
                <table class="transparent-table">
                    <thead>
                    <tr>
                        <th scope="col">Spieler</th>
                        <th>Grund</th>
                        <th>Reportet von</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $btnclass = "";
                    if($player->getServer() == null){
                        $btnclass = "disabled";
                    }
                    $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_reports");
                    $stmt->execute();
                    if($stmt->rowCount() == 0){
                        ?>
                        <tr>
                            <th scope="col">Keine Offenen Reports</th>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    while ($row = $stmt->fetch()){
                        $target = Player::fromUUID($row['player']);
                        $reporter = Player::fromUUID($row['reporter']);
                        $reason = $row['reason'];
                        ?>

                        <tr>
                            <th scope="col"><a class="normal" href="usermanegement.php?uuid=<?php echo $target->uuid ?>"><img class="avatar-s" src="<?php echo $target->getAvatarUrl() ?>"> <?php echo $target->name ?></a></th>
                            <td> <?php echo $reason ?></td>
                            <td><a class="normal" href="usermanegement.php?uuid=<?php echo $reporter->uuid ?>"><img class="avatar-s" src="<?php echo $reporter->getAvatarUrl() ?>"> <?php echo $reporter->name ?></a></td>
                            <td><a class="btn btn-outline-warning <?php echo $btnclass ?>" href="usermanegement.php?uuid=<?php echo $target->uuid?>&accept=<?php echo $row['id'] ?>">Bearbeiten</a> </td>
                        </tr>
                    <?php

                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col mycol">
            <div class="window-child">
                <h1><i class="fas fa-unlock-alt"></i> Entbannungsanträge</h1>
                <hr>
                <table class="transparent-table">
                    <thead>
                    <tr>
                        <th scope="col">Spieler</th>
                        <th scope="col">Typ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $btnclass = "";
                    if($player->getServer() == null){
                        $btnclass = "disabled";
                    }
                    $stmt = getMySQL()->prepare("SELECT * FROM core_web_unban");
                    $stmt->execute();
                    if($stmt->rowCount() == 0){
                        ?>
                        <tr>
                            <th scope="col">Keine offenen Entbannungsanträge</th>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    while ($row = $stmt->fetch()){
                        $target = Player::fromUUID($row['player']);
                        $type = $row['type'];
                        ?>

                        <tr>
                            <th scope="col"><a class="normal" href="usermanegement.php?uuid=<?php echo $target->uuid ?>"><img class="avatar-s" src="<?php echo $target->getAvatarUrl() ?>"> <?php echo $target->name ?></a></th>
                            <td> <?php echo $type ?></td>
                            <td><a class="btn btn-outline-warning" href="usermanegement.php?uuid=<?php echo $target->uuid?>">Bearbeiten</a> </td>
                        </tr>
                    <?php

                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col mycol">
            <div class="window-child">
                <h1><i class="fas fa-exclamation-circle"></i> Letzte Aktionen</h1>
                <hr>

                <table class="transparent-table">
                    <thead>
                    <tr>
                        <th scope="col">Typ</th>
                        <th>Grund</th>
                        <th>Länge</th>
                        <th>Teammitglied</th>
                        <th>Zeit</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_actions ORDER BY timestamp DESC LIMIT 5");
                    $stmt->execute();
                    if ($stmt->rowCount() >= 1) {
                        while ($row = $stmt->fetch()) {
                            $team = $row['issuer'];
                            $teamplayer = Player::fromUUID($team);
                            if ($teamplayer != null) $team = $teamplayer->name;
                            $time = $row['timestamp'];
                            $time = getDateUnix($time);
                            echo '
                                            <tr>
                                                <th scope="row">' . $row['action'] . '</th>
                                                <td>' . $row['value'] . '</td>
                                                <td>' . $row['valuetime'] . '</td>
                                                <td>' . $team . '</td>
                                                <td>' . $time . '</td>
                                            </tr>
                                            ';

                        }

                    }


                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col mycol">
            <div class="window-child">
                <div class="row">
                    <div class="col-8">
                        <h1><i class="fas fa-list-ol"></i> Bangründe</h1>
                    </div>
                    <div class="col-4" style="position: relative">
                        <button style="position: absolute; right: 5%;" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addBan">Hinzufügen</button>
                    </div>
                </div>
                <hr>
                <table class="transparent-table" id="bans">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th>Grund</th>
                        <th>Länge</th>
                        <th>Länge (2. Ban)</th>
                        <th>Permission</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $btnclass = "";
                    if($player->getServer() == null){
                        $btnclass = "disabled";
                    }
                    $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_punishments WHERE type='BAN' ORDER BY sortid");
                    $stmt->execute();
                    if($stmt->rowCount() == 0){
                        ?>
                        <tr>
                            <th scope="col">Keine Bangründe vorhanden</th>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    while ($row = $stmt->fetch()){
                        if($row['lenght'] == -1){
                            $lenght = "Permanent";
                        }else{
                            $lenght = remainingToString($row['lenght']);
                        }
                        if($row['secondlenght'] == -1){
                            $lenght2 = "Permanent";
                        }elseif($row['secondlenght'] == null) {
                            $lenght2 = remainingToString($row['lenght']);
                        }else{
                            $lenght2 = remainingToString($row['secondlenght']);
                        }
                        $permission = $row['permission'];
                        if($permission == null){
                            $permission = "-";
                        }
                        ?>

                        <tr>
                            <th scope="col"><?php echo $row['identifier'] ?></th>
                            <td><?php echo $row['reason'] ?></td>
                            <td><?php echo $lenght ?></td>
                            <td><?php echo $lenght2 ?></td>
                            <td><?php echo $permission ?></td>
                            <td><button onclick="editBan('<?php echo $row['id']?>')" class="btn btn-outline-warning">🖊️</button> <a class="btn btn-outline-danger" href="?delete=<?php echo $row['id'] ?>">-</a></td>
                        </tr>
                        <?php

                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col mycol">
            <div class="window-child">
                <div class="row">
                    <div class="col-8">
                        <h1><i class="fas fa-list-ol"></i> Mutegründe</h1>
                    </div>
                    <div class="col-4" style="position: relative">
                        <button style="position: absolute; right: 5%;" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addMute">Hinzufügen</button>
                    </div>
                </div>
                <hr>
                <table class="transparent-table" id="mutes">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th>Grund</th>
                        <th>Länge</th>
                        <th>Länge (2. Mute)</th>
                        <th>Permission</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $btnclass = "";
                    if($player->getServer() == null){
                        $btnclass = "disabled";
                    }
                    $stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_punishments WHERE type='MUTE'");
                    $stmt->execute();
                    if($stmt->rowCount() == 0){
                        ?>
                        <tr class="draggable">
                            <th scope="col">Keine Bangründe vorhanden</th>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    while ($row = $stmt->fetch()){
                        if($row['lenght'] == -1){
                            $lenght = "Permanent";
                        }else{
                            $lenght = remainingToString($row['lenght']);
                        }
                        if($row['secondlenght'] == -1){
                            $lenght2 = "Permanent";
                        }elseif($row['secondlenght'] == null) {
                            $lenght2 = remainingToString($row['lenght']);
                        }else{
                            $lenght2 = remainingToString($row['secondlenght']);
                        }
                        $permission = $row['permission'];
                        if($permission == null){
                            $permission = "-";
                        }
                        ?>

                        <tr>
                            <th scope="col"><?php echo $row['identifier'] ?></th>
                            <td><?php echo $row['reason'] ?></td>
                            <td><?php echo $lenght ?></td>
                            <td><?php echo $lenght2 ?></td>
                            <td><?php echo $permission ?></td>
                            <td><button onclick="editBan('<?php echo $row['id']?>')" class="btn btn-outline-warning">🖊️</button> <a class="btn btn-outline-danger" href="?delete=<?php echo $row['id'] ?>">-</a></td>
                        </tr>
                        <?php

                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="popup"></div>


<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script>
    function editBan(id){
        $('#popup').html($.ajax({
            type: "GET",
            url: "sanctions_home_editreason.php?id=" + id,
            async: false
        }).responseText);
    }
</script>
<!-- Modal UNBAN -->
<div class="modal fade" id="addBan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ban hinzufügen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="post" action="action_addBanReason.php">
                <div class="modal-body">
                    <br>
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" required name="ban-ident"
                                       id="ban-reason" placeholder="Abkürzung">
                                <label for="ban-reason" style="color: black">Abkürzung</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" required name="ban-reason"
                                       id="ban-reason" placeholder="Grund">
                                <label for="ban-reason" style="color: black">Grund</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    1. Ban
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="dur-val" name="dur-val" placeholder="1">
                                <label for="dur-val" style="color: black">Dauer</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" id="dur-type" name="dur-type"
                                        aria-label="Floating label select example">
                                    <option value="minuten">Minuten</option>
                                    <option value="stunden">Stunden</option>
                                    <option value="tage">Tage</option>
                                    <option value="permanent">Permanent</option>
                                </select>
                                <label for="dur-type" style="color: black">Einheit</label>
                            </div>
                        </div>
                    </div>
                    2. Ban
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="dur-val-2" name="dur-val-2" placeholder="1">
                                <label for="dur-val-2" style="color: black">Dauer</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" id="dur-type-2" name="dur-type-2"
                                        aria-label="Floating label select example">
                                    <option value="minuten">Minuten</option>
                                    <option value="stunden">Stunden</option>
                                    <option value="tage">Tage</option>
                                    <option value="permanent">Permanent</option>
                                </select>
                                <label for="dur-type" style="color: black">Einheit</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="ban-permission"
                                   id="ban-reason" placeholder="Permission">
                            <br>
                            <label for="ban-reason" style="color: black">Permission</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="number" class="form-control" name="sortid"
                                   id="sortid" placeholder="Permission" value="0">
                            <br>
                            <label for="sortid" style="color: black">Sortierungs-ID</label>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit-ban" class="btn btn-success">Bestätigen</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal MUTE -->
<div class="modal fade" id="addMute" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mute hinzufügen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="post" action="action_addMuteReason.php">
                <div class="modal-body">
                    <br>
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" required name="ban-ident"
                                       id="ban-reason" placeholder="Abkürzung">
                                <label for="ban-reason" style="color: black">Abkürzung</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" required name="ban-reason"
                                       id="ban-reason" placeholder="Grund">
                                <label for="ban-reason" style="color: black">Grund</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    1. Mute
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="dur-val" name="dur-val" placeholder="1">
                                <label for="dur-val" style="color: black">Dauer</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" id="dur-type" name="dur-type"
                                        aria-label="Floating label select example">
                                    <option value="minuten">Minuten</option>
                                    <option value="stunden">Stunden</option>
                                    <option value="tage">Tage</option>
                                    <option value="permanent">Permanent</option>
                                </select>
                                <label for="dur-type" style="color: black">Einheit</label>
                            </div>
                        </div>
                    </div>
                    2. Mute
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="dur-val-2" name="dur-val-2" placeholder="1">
                                <label for="dur-val-2" style="color: black">Dauer</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" id="dur-type-2" name="dur-type-2"
                                        aria-label="Floating label select example">
                                    <option value="minuten">Minuten</option>
                                    <option value="stunden">Stunden</option>
                                    <option value="tage">Tage</option>
                                    <option value="permanent">Permanent</option>
                                </select>
                                <label for="dur-type" style="color: black">Einheit</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="ban-permission"
                                   id="ban-reason" placeholder="Permission">
                            <br>
                            <label for="ban-reason" style="color: black">Permission</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="number" class="form-control" name="sortid"
                                   id="sortid" placeholder="Permission" value="0">
                            <br>
                            <label for="sortid" style="color: black">Sortierungs-ID</label>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit-ban" class="btn btn-success">Bestätigen</button>
                </div>
            </form>
        </div>
    </div>
</div>