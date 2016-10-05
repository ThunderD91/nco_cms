<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$include_path = '../' . $include_path;
}

$content_type = $pagefunction = $layout = $desc = $content = "";

$page_id="";
if(isset($_GET['page-id']))
	$page_id=$_GET['page-id'];
?>

<div class="page-title">
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['page-content-edit']['icon'] . ' ' . $view_files['page-content-edit']['title']
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
		<form method="post" data-page="page-content-edit">
			<?php
			$id=false;
			if(isset($_GET['id']) && !empty($_GET['id'])){
				$id=intval($_GET['id']);
				$getPageContent=$DB->find('page_content',array('cond'=>"fk_page_id=$page_id AND page_content_id=$id"));
				if(count($getPageContent)>0){
					$pagefunction=$getPageContent[0]['fk_page_function_id'];
					$layout=$getPageContent[0]['fk_page_layout_id'];
					$desc=$getPageContent[0]['page_content_description'];
					$content=$getPageContent[0]['page_content'];
					$content_type=$getPageContent[0]['page_content_type'];
				}else{
					alert('warning',sprintf(NO_ITEM_FOUND, PAGE_CONTENT) . ' <a href="index.php?page=page-content&page-id='.$page_id.'" data-page="page-content" data-params="page-id='.$page_id.'">' . RETURN_TO_OVERVIEW . '</a>');
				}
			}else{
				alert('warning',sprintf(NO_ITEM_SELECTED, PAGE_CONTENT) . ' <a href="index.php?page=page-content&page-id='.$page_id.'" data-page="page-content" data-params="page-id='.$page_id.'">' . RETURN_TO_OVERVIEW . '</a>');
			}
			if(isset($_POST['save_item'])){
				$result=$dataHandle->editPageContent($_POST,$page_id);
				$pagefunction=$result['data']['page_function'];
				$layout=$result['data']['layout'];
				$desc=$result['data']['description'];
				$content=$result['data']['content'];
				$content_type=$result['data']['content_type'];
				if($result['success'])
					$Event->createEvent('update','af side indholdet <a href="index.php?page=page-content-edit&page-id='.$page_id.'&id='.$id.'" data-page="page-content-edit" data-params="page-id='.$page_id.'&id='.$id.'">'.$desc.'</a>',100,$user['user_id']);
			}
			include $include_path . 'form-page-content.php';
			?>
			<input type="hidden" id="contentid" name="contentid" value="<?php echo $id;?>">
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
