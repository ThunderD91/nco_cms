<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$include_path = '../' . $include_path;
}
$name = $page = $post = "";
$link_type=1;
$menu_id="";
if(isset($_GET['menu-id'])) {
	$menu_id = $_GET['menu-id'];
}
?>

<div class="page-title">
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['menu-link-edit']['icon'] . ' ' . $view_files['menu-link-edit']['title']
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
		<form method="post" data-page="menu-link-edit">
			<?php
			$id=false;
			if(isset($_GET['id']) && !empty($_GET['id'])){
				$id=intval($_GET['id']);
				$getMenuLink=$DB->find('menu_links',array('cond'=>"menu_link_id=$id AND fk_menu_id=$menu_id"));
				if(count($getMenuLink)>0){
					$name=$getMenuLink[0]['menu_link_name'];
					$link_type=$getMenuLink[0]['menu_link_type'];
					$page=$getMenuLink[0]['fk_page_id'];
					$post=$getMenuLink[0]['fk_post_id'];
				}else{
					alert('warning',sprintf(NO_ITEM_FOUND, MENU_LINKS) . ' <a href="index.php?page=menu-links&menu-id='.$menu_id.'" data-page="menu-links" data-params="menu-id='.$menu_id.'">' . RETURN_TO_OVERVIEW . '</a>');
				}
			}else{
				alert('warning',sprintf(NO_ITEM_SELECTED, MENU_LINKS) . ' <a href="index.php?page=menu-links&menu-id='.$menu_id.'" data-page="menu-links" data-params="menu-id='.$menu_id.'">' . RETURN_TO_OVERVIEW . '</a>');
			}
			if(isset($_POST['save_item'])){
				$result=$dataHandle->editMenuLink($_POST,$menu_id);

				$name=$result['data']['name'];
				$link_type=$result['data']['link_type'];
				$page=$result['data']['page'];
				$post=$result['data']['post'];
				if($result['success'])
					$Event->createEvent('update','af side menu linket <a href="index.php?page=menu-links-edit&menu-id='.$menu_id.'&id='.$id.'" data-page="menu-links-edit" data-params="menu-id='.$menu_id.'&id='.$id.'">'.$name.'</a>',100,$user['user_id']);
			}
			include $include_path . 'form-menu-link.php' ?>
			<input type="hidden" id="menulinkid" name="menulinkid" value="<?php echo $id;?>">
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
