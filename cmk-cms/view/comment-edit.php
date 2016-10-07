<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$include_path = '../' . $include_path;
}
$content="";
$post_id="";
if(isset($_GET['post-id']))
	$post_id=$_GET['post-id'];
?>
?>

<div class="page-title">
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['comment-edit']['icon'] . ' ' . $view_files['comment-edit']['title']
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
		<form method="post" data-page="comment-edit">
			<?php
			$showForm=true;
			$id=false;
			if(isset($_GET['id']) && !empty($_GET['id'])){
				$id=intval($_GET['id']);
				$getComment=$DB->find('post_comments',array('cond'=>"fk_post_id=$post_id AND comment_id=$id",
						'fields'=>'user_id,role_access_level',
						'join'=>array(
							array('type'=>'INNER','table'=>'users','cond'=>'fk_user_id=user_id'),
							array('type'=>'INNER','table'=>'roles','cond'=>'fk_role_id=role_id')
						)
					)
				);
				if(count($getComment)>0){
					if($user['role_access_level']<$getComment[0]['role_access_level'] && $getComment[0]['user_id'] != $user['user_id'] && $user['role_access_level']!=1000){
						$showForm=false;
					}else {
						$content = $getComment[0]['comment_content'];
					}
				}else{
					alert('warning',sprintf(NO_ITEM_FOUND, COMMENT) . ' <a href="index.php?page=comments&post-id='.$poste_id.'" data-page="comments" data-params="post-id='.$post_id.'">' . RETURN_TO_OVERVIEW . '</a>');
				}
			}else{
				alert('warning',sprintf(NO_ITEM_SELECTED, COMMENT) . ' <a href="index.php?page=comments&post-id='.$post_id.'" data-page="comments" data-params="post-id='.$post_id.'">' . RETURN_TO_OVERVIEW . '</a>');
			}
			if(isset($_POST['save_item'])){
				$result=$dataHandle->editPostComment($_POST,$post_id);
				$content=$result['data']['content'];
				if($result['success'])
					$Event->createEvent('update','af <a href="index.php?page=comment-edit&post-id='.$post_id.'&id='.$id.'" data-page="comment-edit" data-params="post-id='.$post_id.'&id='.$id.'">'.COMMENT.'</a>',10,$user['user_id']);
			}
			if($showForm)
				include $include_path . 'form-comment.php';
			else
				alert('warning',MISSING_AUTH . ' <a href="index.php?page=comments&post-id='.$post_id.'" data-page="comments" data-params="post-id='.$post_id.'">' . RETURN_TO_OVERVIEW . '</a>');?>
			<input type="hidden" id="commentid" name="commentid" value="<?php echo $id;?>">
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
