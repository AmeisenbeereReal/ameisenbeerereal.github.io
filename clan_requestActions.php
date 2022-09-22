<?php
include_once 'inc/sqlinc.php';
if (!isset($_GET['clan'])) {
 echo "INVALID";
 return;
}
$clan = Clan::fromName($_GET['clan']);
?>

<!-- Modal EDIT -->
<div class="modal fade" id="editReqeust" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Einladung von <?php echo $clan->realname." [".$clan->tag."]" ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <br>
                <div class="d-flex justify-content-center">
                    <button id="view" class="btn btn-primary"><i class="far fa-eye"></i> Clan betrachten</button>
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
        document.location.href = "clan.php?clan=<?php echo $clan->name ?>";
    });
    $('#deny').click(function (event){
        document.location.href = "action_clanRequest.php?action=deny&clan=<?php echo $clan->id ?>";
    });
    $('#accept').click(function (event){
        document.location.href = "action_clanRequest.php?action=accept&clan=<?php echo $clan->id ?>";
    });
</script>