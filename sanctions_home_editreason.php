<?php
include_once 'inc/sqlinc.php';
if (!isset($_GET['id'])) {
    echo "INVALID";
    return;
}

$stmt = getMySQL()->prepare("SELECT * FROM prime_bungee_punishments WHERE id = :ident");
$stmt->bindParam(":ident", $_GET['id']);
$stmt->execute();
if ($stmt->rowCount() <= 0) {
    echo 'INVALID id';
    return;
}
$row = $stmt->fetch();

$len1str = remainingToString($row['lenght']);
$len2str = remainingToString($row['secondlenght']);

$len1int = 0;
$len2int = 0;

if (strpos($len1str, "Permanent") !== false) {
    $len1int = -1;
} else {
    $len1int = explode(" ", $len1str)[0];
}
if (strpos($len2str, "Permanent") !== false) {
    $len2int = -1;
} else {
    $len2int = explode(" ", $len2str)[0];
}

?>

<div class="modal fade" id="editpunish" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bearbeite
                    Bestrafung: <?php echo $row['identifier'] . ' [' . $row['type'] . ']' ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="post" action="action_editPunishment.php">
                <input name="id" value="<?php echo $row['id'] ?>" hidden>
                <div class="modal-body">
                    <br>
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" required name="ban-ident"
                                       id="ban-reason" placeholder="Abkürzung" value="<?php echo $row['identifier'] ?>">
                                <label for="ban-reason" style="color: black">Abkürzung</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" required name="ban-reason"
                                       id="ban-reason" placeholder="Grund" value="<?php echo $row['reason'] ?>">
                                <label for="ban-reason" style="color: black">Grund</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    1. Ban
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="dur-val" name="dur-val"
                                       value="<?php echo $len1int; ?>">
                                <label for="dur-val" style="color: black">Dauer</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" id="dur-type" name="dur-type"
                                        aria-label="Floating label select example">
                                    <option <?php if (strpos($len1str, "Minute") !== false) {
                                        echo "selected";
                                    } ?> value="minuten">Minuten
                                    </option>
                                    <option <?php if (strpos($len1str, "Stunde") !== false) {
                                        echo "selected";
                                    } ?> value="stunden">Stunden
                                    </option>
                                    <option <?php if (strpos($len1str, "Tag") !== false) {
                                        echo "selected";
                                    } ?> value="tage">Tage
                                    </option>
                                    <option <?php if (strpos($len1str, "Permanent") !== false) {
                                        echo "selected";
                                    } ?> value="permanent">Permanent
                                    </option>
                                </select>
                                <label for="dur-type" style="color: black">Einheit</label>
                            </div>
                        </div>
                    </div>
                    2. Ban
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="dur-val-2" name="dur-val-2"
                                       value="<?php echo $len2int; ?>">
                                <label for="dur-val-2" style="color: black">Dauer</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" id="dur-type-2" name="dur-type-2"
                                        aria-label="Floating label select example">
                                    <option <?php if (strpos($len2str, "Minute") !== false) {
                                        echo "selected";
                                    } ?> value="minuten">Minuten
                                    </option>
                                    <option <?php if (strpos($len2str, "Stunde") !== false) {
                                        echo "selected";
                                    } ?> value="stunden">Stunden
                                    </option>
                                    <option <?php if (strpos($len2str, "Tag") !== false) {
                                        echo "selected";
                                    } ?> value="tage">Tage
                                    </option>
                                    <option <?php if (strpos($len2str, "Permanent") !== false) {
                                        echo "selected";
                                    } ?> value="permanent">Permanent
                                    </option>
                                </select>
                                <label for="dur-type" style="color: black">Einheit</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="ban-permission"
                                   id="ban-reason" value="<?php echo $row['permission'] ?>">
                            <br>
                            <label for="ban-reason" style="color: black">Permission</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="number" class="form-control" name="sortid"
                                   id="sortid" value="<?php echo $row['sortid'] ?>">
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

<script>
    var myModal = new bootstrap.Modal(document.getElementById('editpunish'))
    myModal.toggle();
</script>