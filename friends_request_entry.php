<?php
include_once 'inc/sqlinc.php';
if (!isset($_GET['requester'])) {
    echo "INVALID";
    return;
}
$requester = Player::fromName($_GET['requester']);
if($requester == null){
    echo "INVALID: ".$_GET['requester'];
    return;
}
?>

<!-- Modal EDIT -->
<div class="modal fade" id="editReqeust" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Freundschaftsanfrage von <?php echo $requester->name ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <br>
                <div class="d-flex justify-content-center">
                    <button id="view" class="btn btn-primary"><i class="far fa-eye"></i> Pofil ansehen</button>
                </div><br>
                <div class="d-flex justify-content-center">
                    <div class="row">
                        <div class="col">
                            <button id="deny" class="btn btn-danger"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="col">
                            <button id="accept" class="btn btn-success"><i class="fas fa-check"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var myModal = new bootstrap.Modal(document.getElementById('editReqeust'))
    myModal.toggle();

    $('#view').click(function (event){
        var uuid = "<?php echo $requester->uuid ?>";
        document.location.href = "stats.php?uuid=" + uuid;
    });
    $('#deny').click(function (event){
        var uuid = "<?php echo $requester->uuid ?>";
        document.location.href = "action_friendAction.php?type=REQUEST&action=REMOVE&target=" + uuid;
    });
    $('#accept').click(function (event){
        var uuid = "<?php echo $requester->uuid ?>";
        document.location.href = "action_friendAction.php?type=REQUEST&action=ACCEPT&target=" + uuid;
    });
</script>