<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$include_path = '../' . $include_path;
}

$pagefunction = $layout = $desc = $content = "";

$content_type=1;

$page_id="";
if(isset($_GET['page-id']))
	$page_id=$_GET['page-id'];
?>

<div class="page-title">
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['page-content-create']['icon'] . ' ' . $view_files['page-content-create']['title']
		?>
	</span>
</div>

<div class="card">
	<div class="card-header">
		<div class="card-title">
			<div class="title"><?php echo CREATE_ITEM ?></div>
		</div>
	</div>

	<div class="card-body">
		<form method="post" data-page="page-content-create">

			<?php
			if(isset($_POST['save_item'])){
				$result=$dataHandle->createPageContent($_POST,$page_id);
				if(!$result['success']){
					$pagefunction=$result['data']['page_function'];
					$layout=$result['data']['layout'];
					$desc=$result['data']['description'];
					$content=$result['data']['content'];
					$content_type=$result['data']['content_type'];
				}else{
					$Event->createEvent('create','af side indholdet <a href="index.php?page=page-content-edit&page-id='.$page_id.'&id='.$result['data']['inserted_id'].'" data-page="page-content-edit" data-params="page-id='.$page_id.'&id='.$result['data']['inserted_id'].'">'.$result['data']['description'].'</a>',100,$user['user_id']);
				}
			}

			include $include_path . 'form-page-content.php' ?>
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
