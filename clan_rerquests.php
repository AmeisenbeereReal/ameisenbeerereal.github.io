<?php
include_once 'inc/sqlinc.php';

$stmt = getMySQL()->prepare("SELECT * FROM prime_clan_requests WHERE uuid = :uuid");
$stmt->bindParam(":uuid", $_GET['uuid']);
$stmt->execute();
if($stmt->rowCount() >= 1){
    ?>

    <div class="container">
        <div class="window-child">
            <h1><i class="fas fa-question"></i> Clan Einladungen</h1>
            <hr>
            <div class="clan-reqeusts row">
                <?php
                while ($row = $stmt->fetch()){
                    $clan = Clan::fromId($row['clan']);
                    ?>
                    <div class="col-sm-3">
                        <div class="clan-entry">
                            <span class="clan-name"><?php echo $clan->realname ?></span>
                            <span class="clan-tag">[<?php echo $clan->tag ?>]</span>
                        </div>
                        <br>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

<div id="popup"></div>

<script>
    $('.clan-entry').click(function (event){
        let elem = $(this);
        let clanName = $('.clan-name', elem).html();
        $('#popup').html($.ajax({
            type: "GET",
            url: "clan_requestActions.php?clan=" + clanName,
            async: false
        }).responseText);
    });
</script>

<?php
}