<?php
if ( !isset($view_files) )
{
	require '../config.php';
}
$menu_link_types=[
	'1'=>PAGE,
	'2'=>BLOG_POSTS
];

$menu_id="";
if(isset($_GET['menu-id'])) {
	$menu_id = $_GET['menu-id'];
	if(isset($_GET['id']) && isset($_GET['delete'])){
		$pgid=$DB->esc($menu_id);
		$delid=$DB->esc($_GET['id']);
		$resultDel = $DB->find('menu_links',array('cond'=>"menu_link_id=$delid"));
		$query = "DELETE FROM menu_links WHERE menu_link_id=$delid";
		$DB->execute($query);
		if ($DB->last_err)
			query_error($this->conn->error, $query, __LINE__, __FILE__);
		else {
			$Event->createEvent('delete', 'af menu linket ' . $resultDel[0]['menu_link_name'], 100, $user['user_id']);
		}
	}
}
?>

<div class="page-title">
	<a class="<?php echo $buttons['create'] ?> pull-right" href="index.php?page=menu-link-create&menu-id=1" data-page="menu-link-create" data-params="menu-id=1"><?php echo $icons['create'] . CREATE_ITEM ?></a>
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['menu-links']['icon'] . ' Main';
		?>
	</span>
</div>

<div class="card">
	<div class="card-header">
		<div class="card-title">
			<div class="title"><?php echo OVERVIEW_TABLE_HEADER ?></div>
		</div>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover table-striped">
				<thead>

				<tr>
					<th class="icon"><?php echo $icons['sort-asc'] ?></th>
					<th class="icon"></th>
					<th><?php echo TYPE ?></th>
					<th><?php echo NAME ?></th>
					<th><?php echo LINKS ?></th>
					<th class="icon"></th>
					<th class="icon"></th>
				</tr>
				</thead>
				<tbody id="sortable" data-type="menu-links" data-section="<?php echo $menu_id?>">
				<?php
					$sql_options=array(
						'fields'=>'menu_link_id,menu_link_type,menu_link_order,menu_link_name,page_url_key',
						'order'=> "menu_link_order asc",
						'join'=>array(
							array('type'=>'LEFT','table'=>'pages','cond'=>'fk_page_id=page_id'),
							array('type'=>'LEFT','table'=>'posts','cond'=>'fk_post_id=post_id')
						),
						'cond'=>"fk_menu_id=$menu_id",
						'group'=>'menu_link_id'
					);

					$result=$DB->find('menu_links',$sql_options);

					foreach($result as $v){ ?>
				<tr class="sortable-item" id="<?php echo $v['menu_link_id']; ?>">
					<td class="icon">1</td>
					<td class="icon"><?php echo $icons['sort'] ?></td>
					<td><?php echo $menu_link_types[$v['menu_link_type']]; ?></td>
					<td><?php echo $v['menu_link_name']; ?></td>
					<td><a href="../<?php echo $v['page_url_key']; ?>" target="_blank">/</a></td>

					<!-- REDIGER LINK -->
					<td class="icon">
						<a class="<?php echo $buttons['edit'] ?>" href="index.php?page=menu-link-edit&menu-id=<?php echo $menu_id; ?>&id=<?php echo $v['menu_link_id']; ?>" data-page="menu-link-edit" data-params="menu-id=<?php echo $menu_id; ?>&id=<?php echo $v['menu_link_id']; ?>" title="<?php echo EDIT_ITEM ?>"><?php echo $icons['edit'] ?></a>
					</td>

					<!-- SLET LINK -->
					<td class="icon">
						<a class="<?php echo $buttons['delete'] ?>"  data-toggle="confirmation" href="index.php?page=menu-links&menu-id=<?php echo $menu_id; ?>&id=<?php echo $v['menu_link_id']; ?>&delete" data-page="menu-links" data-params="menu-id=<?php echo $menu_id; ?>&id=<?php echo $v['menu_link_id']; ?>&delete" title="<?php echo DELETE_ITEM ?>"><?php echo $icons['delete'] ?></a>
					</td>
				</tr>
				<?php }?>
				<!--<tr class="sortable-item" id="2">
					<td class="icon">2</td>
					<td class="icon"><?php /*echo $icons['sort'] ?></td>
					<td><?php echo PAGE ?></td>
					<td>Blog</td>
					<td><a href="../blog" target="_blank">/blog</a></td>

					<td class="icon">
						<a class="<?php echo $buttons['edit'] ?>" href="index.php?page=menu-link-edit&menu-id=1&id=2" data-page="menu-link-edit" data-params="menu-id=1&id=2" title="<?php echo EDIT_ITEM ?>"><?php echo $icons['edit'] ?></a>
					</td>

					<td class="icon">
						<a class="<?php echo $buttons['delete'] ?>" data-toggle="confirmation" href="index.php?page=menu-links&menu-id=1&id=2&delete" data-page="menu-links" data-params="menu-id=1&id=2&delete" title="<?php echo DELETE_ITEM ?>"><?php echo $icons['delete'] ?></a>
					</td>
				</tr>

				<tr class="sortable-item" id="3">
					<td class="icon">3</td>
					<td class="icon"><?php echo $icons['sort'] ?></td>
					<td><?php echo BLOG_POSTS ?></td>
					<td>Indlæg 1</td>
					<td><a href="../blog/post/eksempel-paa-indlaeg-1" target="_blank">/blog/post/eksempel-paa-indlaeg-1</a></td>

					<td class="icon">
						<a class="<?php echo $buttons['edit'] ?>" href="index.php?page=menu-link-edit&menu-id=1&id=3" data-page="menu-link-edit" data-params="menu-id=1&id=3" title="<?php echo EDIT_ITEM ?>"><?php echo $icons['edit'] ?></a>
					</td>

					<td class="icon">
						<a class="<?php echo $buttons['delete'] ?>" data-toggle="confirmation" href="index.php?page=menu-links&menu-id=1&id=3&delete" data-page="menu-links" data-params="menu-id=1&id=3&delete" title="<?php echo DELETE_ITEM ?>"><?php echo $icons['delete'] ?></a>
					</td>
				</tr>

				<tr class="sortable-item" id="4">
					<td class="icon">2</td>
					<td class="icon"><?php echo $icons['sort'] ?></td>
					<td><?php echo PAGE ?></td>
					<td>Kontakt</td>
					<td><a href="../kontakt" target="_blank">/kontakt</a></td>

					<td class="icon">
						<a class="<?php echo $buttons['edit'] ?>" href="index.php?page=menu-link-edit&menu-id=1&id=4" data-page="menu-link-edit" data-params="menu-id=1&id=4" title="<?php echo EDIT_ITEM ?>"><?php echo $icons['edit'] ?></a>
					</td>

					<td class="icon">
						<a class="<?php echo $buttons['delete'] ?>" data-toggle="confirmation" href="index.php?page=menu-links&menu-id=1&id=4&delete" data-page="menu-links" data-params="menu-id=1&id=4&delete" title="<?php echo DELETE_ITEM ?>"><?php echo $icons['delete'] */?></a>
					</td>
				</tr>-->
				</tbody>
			</table>
		</div><!-- /.table-responsive -->
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
