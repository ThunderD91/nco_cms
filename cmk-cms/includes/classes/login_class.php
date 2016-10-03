<?php
class User extends DB{
    private $user;
    private $acl;

    public $timeout = 1800;
    /* change for new projects */
    private $user_key="96b25b05-eb95-4ddb-bc27-d28e84045c1f";
    private $acl_key ="91b8c397-61ad-48e2-b5e5-6de75f80c06c";

    function __construct(){
        parent::__construct();
        if(isset($_SESSION[$this->user_key])) {
            $this -> get_user_from_session();
        }else{
            $this -> set_user_session();
        }
    }
    function getUser(){
        return $this->user;
    }
    function getUserId(){
        return $this->user['user_id'];
    }
    function getAcl(){
        return $this->acl;
    }
    function login($email="",$pw=""){
        $email=$this->esc($email);
        $pw=$this->esc($pw);
        $user = $this->find('users',array('cond'=>"user_email='$email' AND role_access_level>=10",'join'=>array(array('type'=>'inner','table'=>'roles','cond'=>'fk_role_id=role_id'))));
        $success=false;
        $s=false;
        if(count($user) > 0){
            if(password_verify($pw, $user[0]['user_password'])){
                if($user[0]['user_status']==0){
                    $s=true;
                    alert('danger', ACCOUNT_NOT_ACTIVE);
                }else {
                    session_regenerate_id();
                    unset($user[0]['user_password']);
                    $user[0]['fingerPrint']=fingerPrint();
                    $this->user = $user[0];
                    //$this->userACL();
                    $this->set_user_session();
                    $success = true;
                }
            }
        }
        if(!$success && !$s){
            alert('danger', FAILED_LOGIN);
        }
        return $success;
    }
    function createUser($post){
        $result=array(
            'data'=>$post,
            'success'=>false
        );
        if($this->validate($post,'add')){
            $email=$this->esc($post['email']);
            $pw1=$this->esc($post['password']);
            $name=$this->esc($post['name']);
            $role=$this->esc($post['role']);
            $hash=password_hash($pw1,PASSWORD_DEFAULT);
            $insert=$this->execute("INSERT INTO users (user_name,user_email,user_password,fk_role_id) VALUES ('$name','$email','$hash',$role);",true);

            if(count($this->find('users',array('cond'=>"user_email='$email'"))) > 0){
                $result['data']['inserted_id']=$insert;
                $result['success']=true;
                alert('success',ITEM_CREATED . ' <a href="index.php?page=users" data-page="users">'. RETURN_TO_OVERVIEW .'</a>' );
            }
        }
        return $result;
    }
    function editUser($post){
        $result=array(
            'data'=>$post,
            'success'=>false
        );
        if($this->validate($post,'edit')){
            $id=$this->esc($post['userid']);
            $email=$this->esc($post['email']);
            $pw1=$this->esc($post['password']);
            $name=$this->esc($post['name']);
            $role=$this->esc($post['role']);
            $hash=!empty($pw1) ? password_hash($pw1,PASSWORD_DEFAULT) : false;
            $this->execute("UPDATE users SET user_name='$name',user_email='$email',fk_role_id=$role".($hash ? ",user_password='".$hash."'" : "")." WHERE user_id=$id;");
            if(!$this->last_err) {
                $result['success'] = true;
                alert('success', ITEM_UPDATED . ' <a href="index.php?page=users" data-page="users">' . RETURN_TO_OVERVIEW . '</a>');
            }else{
                alert('warning', sprintf(UPDATE_FAILED, USER) . ' <a href="index.php?page=users" data-page="users">' . RETURN_TO_OVERVIEW . '</a>');
            }
        }
        return $result;
    }
    private function validate($post,$type){
        $valid=false;
        $id="";
        if($type=="edit") $id=$this->esc($post['userid']);
        if(empty($post['name']) || empty($post['email']) || empty($post['role']) ||
            ($type=="add" && (empty($post['password']) || empty($post['confirm_password'])))){
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else if($id == 1 && $post['role']!=1){
            alert('warning',sprintf(CANT_TOUCH_SUPER_ROLE, SUPER_ADMINISTRATOR). ' <a href="index.php?page=users" data-page="users">' . RETURN_TO_OVERVIEW . '</a>');
        }else if($post['role']==1){
            alert('warning',sprintf(CANT_ASSIGN_SUPER_ROLE, SUPER_ADMINISTRATOR). ' <a href="index.php?page=users" data-page="users">' . RETURN_TO_OVERVIEW . '</a>');
        }else{

            $email=$this->esc($post['email']);
            $cond = "user_email='$email'" . ($type=="edit" ? " AND user_id!=$id" : "");
            $this->find('users',array('cond'=>$cond));
            if($this->row_totals>0){
                alert('warning',EMAIL_NOT_AVAILABLE);
            }else{
                if($post['password'] != $post['confirm_password']){
                    alert('warning',PASSWORD_MISMATCH);
                }else{
                    return true;
                }
            }
        }
        return $valid;
    }
    function logout(){
        unset($_SESSION[$this->user_key]);
        //unset($_SESSION[$this->acl_key]);
        $this->user = array();
        //$this->acl = array();
        session_regenerate_id();
    }
    function setActivity(){
        session_regenerate_id();
        $this->user['last_activity']=time();
        $this->set_user_session();
    }
    function getUserOrders($id){
        return $this->_getUserOrders($id);
    }
    function getUserOrderItems($oid,$user_id){
        return $this->_getUserOrderItems($oid,$user_id);
    }
    private function userACL(){
        $query="SELECT access_name,access_code FROM access
			INNER JOIN
				access_roles ON access_id=fk_access_id
		   	AND
				fk_role_id=" . $this->user['fk_role_id']
            ." ORDER BY access_id";
        $acl = $this->findQuery($query);

        foreach($acl as $a){
            $this->acl[$a['access_name']] = $a['access_code'];
        }
    }
    private function set_user_session() {
        $_SESSION[$this->user_key] = $this->user;
        //$_SESSION[$this->acl_key] = $this->acl;
    }
    private function get_user_from_session() {
        $this->user = $_SESSION[$this->user_key];
       // $this->acl = $_SESSION[$this->acl_key];
    }

}
?>