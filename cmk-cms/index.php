<?php
ob_start();

require 'config.php';
if(isset($_GET['action']) && $_GET['action']=="logud") {
    $userClass->logout();
    header("location: login.php");
    exit;
}


// If view is set in URL params, save value to variable $view_file
if ( isset($_GET['page']) )
{
	$view_file = $_GET['page'];
}
// If view is not set in URL params, save index to variable $view_file
else
{
	$view_file = 'index';
}

// Save path to files to include, with filename from value in $view_file
$view_path	= 'view/' . $view_file . '.php';

// Use title from $files Array, defined in include/config.php, with the key that matches the filename from $view_file
$view_title	= $view_files[$view_file]['title'] . ' - CMK Admin';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $view_title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/font-awesome-4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/animate.css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-switch-3.3.2/css/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/checkbox3/checkbox3.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/select2-4.0.3/css/select2.min.css">
    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/themes/flat-blue.css">

	<!-- CKEditor -->
	<script src="../assets/ckeditor-4.5.1/ckeditor.js"></script>
	<script>CKEDITOR.dtd.$removeEmpty['span'] = false;</script> <!-- Sikrer at tomme spans ikke fjernes i editor, da de bruges til font awesome ikoner -->
</head>

<body class="flat-blue">
    <div class="app-container expanded">
        <div class="row content-container">
            <nav class="navbar navbar-default navbar-fixed-top navbar-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-expand-toggle">
                            <i class="fa fa-bars icon"></i>
                        </button>

						<ol class="breadcrumb navbar-breadcrumb" id="breadcrumb">
							<?php include 'includes/breadcrumb.php' ?>
                        </ol>

                        <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                            <i class="fa fa-th icon"></i>
                        </button>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                            <i class="fa fa-times icon"></i>
                        </button>
                        <li class="dropdown profile">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $user['user_name'];?> <span class="caret"></span></a>
                            <ul class="dropdown-menu animated fadeInDown">
                                <li>
                                    <div class="profile-info">
                                        <?php
                                            $suser=$DB->find('users',array('cond'=>'user_id='.$user['user_id']));
                                            if(count($suser) == 0){
                                                header('location: index.php?action=logud');
                                            }
                                        ?>
                                        <h4 class="username"><?php echo $suser[0]['user_name'];?></h4>
                                        <p><?php echo $suser[0]['user_email'];?></p>
                                        <div class="btn-group margin-bottom-2x" role="group">
											<a class="<?php echo $buttons['default'] ?>" href="index.php?page=user-edit"><?php echo $icons['edit'] . ' ' . EDIT_USER ?></a>
											<a class="<?php echo $buttons['default'] ?>" href="index.php?action=logud"><?php echo $icons['sign-out'] . ' ' . SIGN_OUT ?></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <?php include 'includes/side_menu.php' ?>

            <!-- Main Content -->
            <div class="container-fluid">
                <div class="side-body" id="main-content" data-content="<?php echo $view_file ?>">
					<?php
					if ( file_exists($view_path) )
					{
						include $view_path;
					}
					else
					{
						header('Location: index.php?page=error&status=404');
						exit;
					}
					?>
                </div>

				<div class="loader-container text-center">
					<div><i class="fa fa-spinner fa-pulse fa-3x"></i></div>
					<div><?php echo LOADING ?>...</div>
				</div>
            </div>
        </div>
        <footer class="app-footer">
            <div class="wrapper">
                <span class="pull-right">Flat Admin v2.1.2 <a href="#"><i class="fa fa-long-arrow-up"></i></a></span> 2016 - Roskilde Tekniske Skole.
            </div>
        </footer>
		<!-- Javascript Libs -->
		<script type="text/javascript" src="../assets/jquery-2.2.4/jquery.min.js"></script>
		<script type="text/javascript" src="../assets/bootstrap-3.3.7/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../assets/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
		<script type="text/javascript" src="../assets/bootstrap-switch-3.3.2/js/bootstrap-switch.min.js"></script>
		<script type="text/javascript" src="../assets/jquery-match-height-0.7.0/jquery.matchHeight-min.js"></script>
		<script type="text/javascript" src="../assets/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script><!-- jQuery UI Sortable -->
		<script type="text/javascript" src="../assets/select2-4.0.3/js/select2.full.min.js"></script>
		<!-- Javascript -->
		<script type="text/javascript" src="js/app.js"></script>
		<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
</body>

</html>
<?php
ob_end_flush();
