<?php
	if (isset($_POST['type']) && isset($_POST['section']) && isset($_POST['data'])) {
        include 'classes/db_conf.php';
        $db=new DB();
	    $id=$db->esc($_POST['section']);
        $type=$db->esc($_POST['type']);
        $data=$_POST['data'];
        $skip=false;
        switch($type){
            case 'page-content':
                $table="page_content";
                $order="page_content_order";
                $field="page_content_id";
                $section_field="fk_page_id";
                $skip=true;
                break;
        }
        if($skip) {
            $count=1;
            foreach($data as $v){
                $sid=$v['id'];
                $db->execute("UPDATE $table SET $order=$count WHERE $field=$sid AND $section_field=$id;");
                if($db->last_err) {
                    break;
                }
                $count++;
            }
            echo '1';
            exit;
        }
	}
    echo '0';
    exit;
?>