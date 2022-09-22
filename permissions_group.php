<?php
include_once 'inc/PermissionGroup.php';
$group = PermissionGroup::fromId($_GET['group']);

if(isset($_GET['deletePermission'])){
    $stmt = getMySQL()->prepare("DELETE FROM prime_perms_grouppermission WHERE `group`=:group AND id=:id");
    $stmt->bindParam(":group", $group->id);
    $stmt->bindParam(":id", $_GET['deletePermission']);
    $stmt->execute();
}

$color = translateToReadableColorOfMc($group->color);
$bg = translateMcColor($group->color);
$inherit = $group->getInheritGroup();
$inheritcolor = translateToReadableColorOfMc($inherit->color);
$inheritbg = translateMcColor($inherit->color);
?>
<div class="container">
    <div class="row">
        <div class="col mycol">
            <div class="window-child">
                <div class="row">
                    <div class="col-8">
                        <h1><i class="fas fa-list"></i> <span class="rankBadge rankBadge-xl" style="color: <?php echo $color ?>;background-color: <?php echo $bg ?>"><?php echo $group->displayname ?></span></h1>
                    </div>
                    <div class="col-4" style="position: relative">
                        <button style="position: absolute; right: 5%;" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#editGroup">Bearbeiten</button>
                    </div>
                </div>
                <hr>
                <b>ID: </b><?php echo $group->name ?> <br>
                <b>Anzeigename: </b><?php echo $group->displayname ?> <br>
                <b>Erbt von: </b><a href="permissions.php?group=<?php echo $inherit->id ?>" class="rankBadge rankBadge-l" style="color: <?php echo $inheritcolor ?>;background-color: <?php echo $inheritbg ?>"><?php echo $inherit->displayname ?></a><br>
                <div style="margin: 5px 0 5px 0"><b>Prefix: </b> <span class="colored mcColor"><?php echo $group->prefix ?></span><br></div>
                <div style="margin: 5px 0 5px 0"><b>Suffix: </b> <span class="colored mcColor"><?php echo $group->suffix ?></span><br></div>
                <b>Farbe: </b><span class="rankBadge rankBadge-l" style="color: <?php echo $color ?>;background-color: <?php echo $bg ?>"><?php echo str_replace("§","&", $group->color) ?></span><br>
                <b>Sortierungs Gewicht: </b><?php echo $group->weight ?> <br>
            </div>
        </div>
        <div class="col mycol">
            <div class="window-child">
                <div class="row">
                    <div class="col-8">
                        <h1><i class="fas fa-users-cog"></i> Permissions</h1>
                    </div>
                    <div class="col-4" style="position: relative">
                        <button style="position: absolute; right: 5%;" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addPermission">Hinzufügen</button>
                    </div>
                </div>
                <hr>
                <table class="transparent-table">
                    <thead>
                    <tr>
                        <th scope="col">Permission</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $stmt = getMySQL()->prepare("SELECT * FROM prime_perms_grouppermission WHERE `group` = :group");
                    $stmt->bindParam(":group", $group->id);
                    $stmt->execute();
                    while ($row = $stmt->fetch()){
                        ?>
                        <tr>
                            <th scope="col"><?php echo $row['permission'] ?></th>
                            <td><a class="btn btn-outline-danger" href="permissions.php?group=<?php echo $group->id.'&deletePermission='.$row['id'] ?>">-</a></td>
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

<div class="modal fade" id="addPermission" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <form method="post" action="action_addGroupPermission.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Permission Hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input name="group" hidden type="hidden" value="<?php echo $group->id ?>">
                <div class="modal-body">
                    Du kannst mehrere Permissions mit einem Komma (,) trennen!
                    <br>
                    <br>
                    <div class="col-md">
                        <div class="form-floating">
                            <textarea style="height: 100px;" type="text" class="form-control" id="permission" placeholder="Permission" name="permission"></textarea>
                            <label for="permission" style="color: black">Permission</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit-addperm" class="btn btn-success">Hinzufügen</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editGroup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <form method="post" action="action_editGroup.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $group->name ?> bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input name="group" hidden type="hidden" value="<?php echo $group->id ?>">
                <div class="modal-body">
                    <br>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="displayname" name="displayname" value="<?php echo $group->displayname ?>">
                            <label for="displayname" style="color: black">Anzeigename</label>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="prefix" name="prefix" value="<?php echo str_replace('§', '&', $group->prefix) ?>">
                                <label for="prefix" style="color: black">Prefix</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="suffix" name="suffix" value="<?php echo str_replace('§', '&', $group->suffix) ?>">
                                <label for="suffix" style="color: black">Suffix</label>
                            </div>
                        </div>
                    </div> <br>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="color" name="color" value="<?php echo str_replace('§', '&', $group->color) ?>">
                                <label for="color" style="color: black">Farbe</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="weight" name="weight" value="<?php echo $group->weight ?>">
                                <label for="weight" style="color: black">Sortierungs Gewicht</label>
                            </div>
                        </div>
                    </div><br>

                    <div class="form-floating">
                        <select class="form-select" id="inherit" name="inherit" aria-label="Floating label select example">
                            <?php
                            $stmt = getMySQL()->prepare("SELECT * FROM prime_perms_groups order by weight");
                            $stmt->execute();
                            while ($row = $stmt->fetch()) {
                                if($row['id'] == $inherit->id){
                                    echo '<option selected value="' . $row['id'] . '">' . $row['display_name'] . '</option>';
                                }else{
                                    echo '<option value="' . $row['id'] . '">' . $row['display_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <label for="inherit" style="color: black">Erbt von</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit-addperm" class="btn btn-success">Hinzufügen</button>
                </div>
            </form>
        </div>
    </div>
</div>
