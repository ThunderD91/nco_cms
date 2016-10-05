<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$include_path = '../' . $include_path;
}

$title=$url=$robots=$desc="";

$id=false;
?>

<div class="page-title">
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['page-create']['icon'] . ' ' . $view_files['page-create']['title']
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
		<form method="post" data-page="page-create">
			<?php
			if(isset($_POST['save_item'])){
				$result=$dataHandle->createPage($_POST);
				if(!$result['success']){
					$title=$result['data']['title'];
					$url=$result['data']['url_key'];
					$robots=$result['data']['meta_robots'];
					$desc=$result['data']['meta_description'];
				}else{
					$Event->createEvent('create','af siden <a href="index.php?page=page-edit&id='.$result['data']['inserted_id'].'" data-page="page-edit" data-params="id='.$result['data']['inserted_id'].'">'.$result['data']['title'].'</a>',100,$user['user_id']);
				}
			}

			include $include_path . 'form-page.php' ?>
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
