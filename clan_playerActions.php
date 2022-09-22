<?php
include_once 'inc/sqlinc.php';
if (!isset($_GET['player'])) {
 echo "INVALID";
 return;
}

$auth = json_decode($_COOKIE['auth']);
$player = Player::fromUUID($auth->uuid);

$currPlayer = Player::fromName($_GET['player']);

$classPromote = "";
$classDemote = "";
$classKick = "";

if($currPlayer->getClanRank() >= 2){
    $classPromote = "disabled";
}
if($currPlayer->getClanRank() == 0){
    $classDemote = "disabled";
}
if($currPlayer->getClanRank() == 3){
    $classPromote = "disabled";
    $classDemote = "disabled";
    $classKick = "disabled";
}

if($currPlayer->getClanRank() +1 >= $player->getClanRank()){
    $classPromote = "disabled";
}

if($player->uuid == $currPlayer->uuid){
    $classPromote = "disabled";
    $classDemote = "disabled";
    $classKick = "disabled";
}
if($player->getClanRank() <= $currPlayer->getClanRank()){
    $classPromote = "disabled";
    $classDemote = "disabled";
    $classKick = "disabled";
}

?>

<!-- Modal EDIT -->
<div class="modal fade" id="editPlayer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Spieler verwalten</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <br>
                <div class="col-md">
                    <div class="row">
                        <div class="col-sm d-flex justify-content-center">
                            <img src="<?php echo "https://cravatar.eu/helmavatar/".$currPlayer->uuid."/128" ?>" style="border-radius: 1rem">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm d-flex justify-content-center">
                            <h1><?php echo $currPlayer->name ?></h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm d-flex justify-content-center">
                            <button id="promote" class="btn btn-success <?php echo $classPromote ?>"><i class="fas fa-arrow-up"></i> Befördern</button>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-sm d-flex justify-content-center">
                            <button id="demote" class="btn btn-warning <?php echo $classDemote ?>"><i class="fas fa-arrow-down"></i> Degradieren</button>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-sm d-flex justify-content-center">
                            <button id="kick" class="btn btn-danger <?php echo $classKick ?>"><i class="fas fa-times"></i> Vom Clan kicken</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var myModal = new bootstrap.Modal(document.getElementById('editPlayer'))
    myModal.toggle();

    $('#promote').click(function (event){
        document.location.href = "action_clanEditUser.php?target=<?php echo $currPlayer->uuid ?>&action=promote";
    });
    $('#demote').click(function (event){
        document.location.href = "action_clanEditUser.php?target=<?php echo $currPlayer->uuid ?>&action=demote";
    });
    $('#kick').click(function (event){
        document.location.href = "action_clanEditUser.php?target=<?php echo $currPlayer->uuid ?>&action=kick";
    });
</script>