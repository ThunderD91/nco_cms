<?php
    ob_start();
    $no_variable=true;
    require 'config.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login - CMN Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->
	<link rel="stylesheet" type="text/css" href="../assets/bootstrap-3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/font-awesome-4.6.3/css/font-awesome.min.css">
    <!-- CSS App -->
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/themes/flat-blue.css">
</head>

<body class="flat-blue login-page">
    <div class="container">
        <div class="login-box">
            <div>
                <div class="login-form row">
                    <div class="col-sm-12 text-center login-header">
                        <i class="login-logo fa fa-connectdevelop fa-5x"></i>
                        <h4 class="login-title">CMN Admin</h4>
                    </div>
                    <div class="col-sm-12">
                        <div class="login-body">
                            <?php
                                $email = "";
                                if(isset($_POST['login'])){
                                    $email=$_POST['email'];
                                    $login=$userClass->login($_POST['email'],$_POST['password']);
                                    if($login) {
                                        $uid=$userClass->getUserId();
                                        $Event->createEvent('info','Loggede in',10,$uid);
                                        header('location: index.php');
                                        exit;
                                    }
                                }
                            ?>
                            <div class="progress hidden" id="login-progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    <?php echo SIGN_IN ?>
                                </div>
                            </div>
                            <form method="post">
                                <div class="control">
                                    <input type="email" class="form-control" name="email" placeholder="<?php echo EMAIL ?>" value="<?php echo $email;?>" required>
                                </div>
                                <div class="control">
                                    <input type="password" class="form-control" name="password" placeholder="<?php echo PASSWORD ?>" required>
                                </div>
                                <div class="login-button text-center">
                                    <button type="submit" name="login" class="btn btn-primary"><?php echo $icons['sign-in'] . SIGN_IN ?></button>
                                </div>
                            </form>
                        </div>
                        <div class="login-footer">
                            <span class="text-right"><a href="#" class="color-white"></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="../assets/jquery-2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="../assets/bootstrap-3.3.7/js/bootstrap.min.js"></script>
</body>

</html>
<?php
ob_end_flush();
