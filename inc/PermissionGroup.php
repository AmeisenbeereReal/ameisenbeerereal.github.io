<?php


class PermissionGroup
{
    public $id;
    public $name;
    public $inherit;
    public $displayname;
    public $prefix;
    public $suffix;
    public $color;
    public $weight;


    public static function fromName($name){
        require_once 'sqlinc.php';
        $name = strtolower($name);
        $stmt = getMySQL()->prepare("SELECT * FROM prime_perms_groups WHERE name = :name");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $group = new PermissionGroup();
            $group->id = $row['id'];
            $group->name = $row['name'];
            $group->inherit = $row['inherit'];
            $group->displayname = $row['display_name'];
            $group->prefix = $row['prefix'];
            $group->suffix = $row['suffix'];
            $group->color = $row['color'];
            $group->weight = $row['weight'];
            return $group;
        }
        return null;
    }
    public static function fromId($id){
        require_once 'sqlinc.php';
        $stmt = getMySQL()->prepare("SELECT * FROM prime_perms_groups WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $group = new PermissionGroup();
            $group->id = $row['id'];
            $group->name = $row['name'];
            $group->inherit = $row['inherit'];
            $group->displayname = $row['display_name'];
            $group->prefix = $row['prefix'];
            $group->suffix = $row['suffix'];
            $group->color = $row['color'];
            $group->weight = $row['weight'];
            return $group;
        }
        return null;
    }

    public function getInheritGroup(){
        if($this->inherit == null || $this->inherit == 0){
            return PermissionGroup::fromName("default");
        }else{
            $group = PermissionGroup::fromId($this->inherit);
            if($group == null){
                return PermissionGroup::fromName("default");
            }else{
                return $group;
            }
        }
    }

}