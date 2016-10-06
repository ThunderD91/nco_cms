<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$include_path = '../' . $include_path;
}

$title=$url_key=$content=$meta_description="";
?>

<div class="page-title">
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['post-create']['icon'] . ' ' . $view_files['post-create']['title']
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
		<form method="post" data-page="post-create">

			<?php
			if(isset($_POST['save_item'])){
				$result=$dataHandle->createPost($_POST,$user['user_id']);
				if(!$result['success']){
					$title=$result['data']['title'];
					$url_key=$result['data']['url_key'];
					$content=$result['data']['content'];
					$meta_description=$result['data']['meta_description'];
				}else{
					$Event->createEvent('create','af post <a href="index.php?page=post-edit&id='.$result['data']['inserted_id'].'" data-page="post-edit" data-params="id='.$result['data']['inserted_id'].'">'.$result['data']['title'].'</a>',10,$user['user_id']);
				}
			}
			include $include_path . 'form-post.php' ?>
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
