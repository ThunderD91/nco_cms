<?php
    if(@session_start() == false){
        session_destroy();
        session_start();
    }
    include 'db_conf.php';
    include 'login_class.php';
    include '../functions.php';
    $user = new User();
    if(isset($_POST['login'])){
        $login=$user->login($_POST['email'],$_POST['password']);
        if($login['success'])
            header('location: ../../index.php');
        exit;
    }else if(isset($_POST['create'])){
        $create=$user->createUser($_POST);
        if($_POST['returner']!="")
            $create['redirect']=$_POST['returner'];
        echo json_encode($create);
        exit;
    }else if(isset($_POST['edit'])){
        $edit=$user->editUser($_POST);
        $edit['redirect']="profil";
        echo json_encode($edit);
        exit;
    }
?>