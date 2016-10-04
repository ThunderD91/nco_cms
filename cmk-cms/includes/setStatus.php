<?php
	if (isset($_POST['id']) && isset($_POST['table']) && isset($_POST['state']) &&
        !empty($_POST['id']) && !empty($_POST['table']) && !empty($_POST['state'])) {
        session_start();
        include 'classes/db_conf.php';
        include 'classes/login_class.php';
        $db=new DB();
        $uClass=new User();
        $user=$uClass->getUser();
	    $id=$db->esc($_POST['id']);
        $table=$db->esc($_POST['table']);
        $state=$db->esc($_POST['state']);
        $status=0;
        switch($table){
            case 'users':
                $status="user_status";
                $field="user_id";
                break;
        }
        if(($status && $id!=1 && $id!=$user['user_id']) || $user['role_access_level']==1000) {
            $db->execute("UPDATE $table SET $status=$state WHERE $field=$id;");
            if($db->last_err)
                echo '0';
            else
                echo '1';
            exit;
        }
	}
    echo '0';
    exit;
?>