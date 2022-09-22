<?php
include_once 'inc/PermissionGroup.php';
$default = PermissionGroup::fromName("default");
$bg = translateMcColor($default->color);
$color = translateToReadableColorOfMc($default->color);

?>
<div class="container">
    <div class="row">
        <div class="col-sm mycol">
            <div class="window-child">
                <div class="row">
                    <div class="col-8">
                        <h1><i class="fas fa-layer-group"></i> <span class="rankBadge rankBadge-xl" style="color: <?php echo $color ?>;background-color: <?php echo $bg ?>">Gruppen</span></h1>
                    </div>
                    <div class="col-4" style="position: relative">
                        <button style="position: absolute; right: 5%;" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#createGroup">Gruppe erstellen</button>
                    </div>
                </div>
                <hr>
                <table class="transparent-table">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th>Anzeigename</th>
                        <th>Gewicht</th>
                        <th>Betrachten</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = getMySQL()->prepare("SELECT * FROM prime_perms_groups ORDER BY weight");
                        $stmt->execute();
                        while ($row = $stmt->fetch()){
                            $color = translateToReadableColorOfMc($row['color']);
                            $bg = translateMcColor($row['color']);
                            ?>

                            <tr>
                                <th scope="col"><?php echo $row['name'] ?></th>
                                <td><span class="rankBadge rankBadge-l" style="color: <?php echo $color ?>;background-color: <?php echo $bg ?>"><?php echo $row['display_name'] ?></span></td>
                                <td><?php echo $row['weight'] ?></td>
                                <td><a href="?group=<?php echo $row['id'] ?>" class="btn btn-outline-warning"><i class="fas fa-pen"></i></a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm mycol">
            <div class="window-child">
                <h1><i class="fas fa-search"></i> Gebt mir Ideen, was hier hin kann</h1>
                <hr>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createGroup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <form method="post" action="action_createGroup.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Gruppe erstellen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <br>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                            <label for="name" style="color: black">Name</label>
                        </div>
                    </div><br>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="displayname" name="displayname" placeholder="Anzeigename">
                            <label for="displayname" style="color: black">Anzeigename</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit-create" class="btn btn-success">Hinzufügen</button>
                </div>
            </form>
        </div>
    </div>
</div>
