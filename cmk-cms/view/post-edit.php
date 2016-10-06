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
			<div class="title"><?php echo EDIT_ITEM ?></div>
		</div>
	</div>

	<div class="card-body">
		<form method="post" data-page="post-edit">
			<?php
			$id=false;
			if(isset($_GET['id']) && !empty($_GET['id'])){
				$id=intval($_GET['id']);
				$getPost=$DB->find('posts',array('cond'=>"post_id=$id"));
				if(count($getPost)>0){
					$title=$getPost[0]['post_title'];
					$url_key=$getPost[0]['post_url_key'];
					$content=$getPost[0]['post_content'];
					$meta_description=$getPost[0]['post_meta_description'];

				}else{
					alert('warning',sprintf(NO_ITEM_FOUND, PAGE) . ' <a href="index.php?page=posts" data-page="posts">' . RETURN_TO_OVERVIEW . '</a>');
				}
			}else{
				alert('warning',sprintf(NO_ITEM_SELECTED, PAGE) . ' <a href="index.php?page=posts" data-page="posts">' . RETURN_TO_OVERVIEW . '</a>');
			}
			if(isset($_POST['save_item'])){
				$result=$dataHandle->editPost($_POST,$user['user_id']);

				$title=$result['data']['title'];
				$url_key=$result['data']['url_key'];
				$content=$result['data']['content'];
				$meta_description=$result['data']['meta_description'];
				if($result['success'])
					$Event->createEvent('update','af post <a href="index.php?page=post-edit&id='.$id.'" data-page="post-edit" data-params="id='.$id.'">'.$title.'</a>',10,$user['user_id']);
			}
			include $include_path . 'form-post.php';
			?>

			<input type="hidden" id="postid" name="postid" value="<?php echo $id;?>">
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
