<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$include_path = '../' . $include_path;
}
$content = "";

$post_id="";
if(isset($_GET['post-id']))
	$post_id=$_GET['post-id'];
?>

<div class="page-title">
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['comment-create']['icon'] . ' ' . $view_files['comment-create']['title']
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
		<form method="post" data-page="comment-create">
			<?php
			if(isset($_POST['save_item'])){
				$result=$dataHandle->createPostComment($_POST,$post_id,$user['user_id']);
				if(!$result['success']){
					$content=$result['data']['content'];
				}else{
					$Event->createEvent('create','af <a href="index.php?page=comment-edit&post-id='.$post_id.'&id='.$result['data']['inserted_id'].'" data-page="comment-edit" data-params="post-id='.$post_id.'&id='.$result['data']['inserted_id'].'">'.COMMENT.'</a>',10,$user['user_id']);
				}
			}
			include $include_path . 'form-comment.php' ?>
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
