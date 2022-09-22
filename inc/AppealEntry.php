<?php


class AppealEntry
{
    public $id;
    public $uuid;
    public $type;
    public $reason;
    public $length;
    public $message;

    public static function create($uuid, $type, $reason, $lenght, $message){
        include_once 'sqlinc.php';
        $message = strip_tags($message, '');
        $stmt = getMySQL()->prepare("INSERT INTO core_web_unban VALUES (id, :uuid, :type, :reason, :lenght, :message)");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->bindParam(":reason", $reason);
        $stmt->bindParam(":lenght", $lenght);
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":type", $type);
        $stmt->execute();
    }

    public static function fromPlayer($uuid){
        include_once 'sqlinc.php';
        $stmt = getMySQL()->prepare("SELECT * FROM core_web_unban WHERE player = :uuid");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch();
            $appeal = new AppealEntry();
            $appeal->id = $row['id'];
            $appeal->uuid = $row['player'];
            $appeal->type = $row['type'];
            $appeal->reason = $row['reason'];
            $appeal->length = $row['lenght'];
            $appeal->message = $row['message'];
            return $appeal;
        }
        return null;
    }

    public function delete(){
        include_once 'sqlinc.php';
        $stmt = getMySQL()->prepare("DELETE FROM core_web_unban WHERE id = ?");
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
    }
}