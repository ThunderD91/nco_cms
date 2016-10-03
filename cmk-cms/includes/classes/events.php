<?php
class Events extends DB{

    function __construct(){
        parent::__construct();
    }
    function createEvent($type,$desc,$acl,$userid){
        $desc=$this->esc($desc);
        $acl=intval($acl);
        $userid=intval($userid);

        switch($type){
            case 'create':
                $event_type_id=1;
                break;
            case 'update':
                $event_type_id=2;
                break;
            case 'delete':
                $event_type_id=3;
                break;
            default:
                $event_type_id=4;
        }
        $query="INSERT INTO events (event_description,event_access_level_required,fk_user_id,fk_event_type_id) VALUES ('$desc',$acl,$userid,$event_type_id);";
        $this->execute($query);
        if($this->last_err){
            query_error($this->conn->error,$query,__LINE__,__FILE__);
        }
    }
}
?>