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
		echo $view_files['menu-link-create']['icon'] . ' ' . $view_files['menu-link-create']['title']
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
		<form method="post" data-page="menu-link-create">

			<?php
			if(isset($_POST['save_item'])){
				$result=$dataHandle->createMenuLink($_POST,$menu_id);
				if(!$result['success']){
					$name=$result['data']['name'];
					$link_type=$result['data']['link_type'];
					$page=$result['data']['page'];
					$post=$result['data']['post'];
				}else{
					$Event->createEvent('create','af menu linket <a href="index.php?page=menu-link-edit&menu-id='.$menu_id.'&id='.$result['data']['inserted_id'].'" data-page="menu-link-edit" data-params="menu-id='.$menu_id.'&id='.$result['data']['inserted_id'].'">'.$result['data']['name'].'</a>',100,$user['user_id']);
				}
			}
			include $include_path . 'form-menu-link.php' ?>
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
