<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$include_path = '../' . $include_path;
}

$title=$url=$robots=$desc="";
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
			<div class="title"><?php echo EDIT_ITEM ?></div>
		</div>
	</div>

	<div class="card-body">
		<form method="post" data-page="page-edit">

			<?php
			$showForm=true;
			$id=false;
			if(isset($_GET['id']) && !empty($_GET['id'])){
				$id=intval($_GET['id']);
				$getPage=$DB->find('pages',array('cond'=>"page_id=$id"));
				if(count($getPage)>0){
					if($user['role_access_level']!=1000 && $getPage[0]['page_protected']){
						$showForm=false;
					}else {
						$title=$getPage[0]['page_title'];
						$url=$getPage[0]['page_url_key'];
						$robots=$getPage[0]['page_meta_robots'];
						$desc=$getPage[0]['page_meta_description'];
					}
				}else{
					alert('warning',sprintf(NO_ITEM_FOUND, PAGE) . ' <a href="index.php?page=pages" data-page="pages">' . RETURN_TO_OVERVIEW . '</a>');
				}
			}else{
				alert('warning',sprintf(NO_ITEM_SELECTED, PAGE) . ' <a href="index.php?page=pages" data-page="pages">' . RETURN_TO_OVERVIEW . '</a>');
			}
			if(isset($_POST['save_item'])){
				$result=$dataHandle->editPage($_POST);

				$title=$result['data']['title'];
				$url=$result['data']['url_key'];
				$robots=$result['data']['meta_robots'];
				$desc=$result['data']['meta_description'];
				if($result['success'])
					$Event->createEvent('update','af siden <a href="index.php?page=page-edit&id='.$id.'" data-page="page-edit" data-params="id='.$id.'">'.$title.'</a>',100,$user['user_id']);
			}
			if($showForm)
				include $include_path . 'form-page.php';
			else
				alert('warning',MISSING_AUTH . ' <a href="index.php?page=pages" data-page="pages">' . RETURN_TO_OVERVIEW . '</a>');

			?>
			<input type="hidden" id="pageid" name="pageid" value="<?php echo $id;?>">
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
