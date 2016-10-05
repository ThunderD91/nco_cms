<?php
if ( !isset($view_files) )
{
	require '../config.php';
}

$page_content_types=[
	'1'=>EDITOR,
	'2'=>PAGE_FUNCTION
];

$page_id="";
if(isset($_GET['page-id'])) {
	$page_id = $_GET['page-id'];
	if(isset($_GET['id']) && isset($_GET['delete'])){
		$pgid=$DB->esc($page_id);
		$delid=$DB->esc($_GET['id']);
		$resultDel = $DB->find('page_content',array('cond'=>"fk_page_id=$pgid AND page_content_id=$delid"));
		$query = "DELETE FROM page_content WHERE fk_page_id=$pgid AND page_content_id=$delid";
		$DB->execute($query);
		if ($DB->last_err)
			query_error($this->conn->error, $query, __LINE__, __FILE__);
		else {
			$Event->createEvent('delete', 'af side indholdet ' . $resultDel[0]['page_content_description'], 100, $user['user_id']);
		}
	}
}

?>

<div class="page-title">
	<a class="<?php echo $buttons['create'] ?> pull-right" href="index.php?page=page-content-create&page-id=<?php echo $page_id;?>" data-page="page-content-create" data-params="page-id=<?php echo $page_id;?>"><?php echo $icons['create'] . CREATE_ITEM ?></a>
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['page-content']['icon'] . ' Blog';
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
					<th><?php echo DESCRIPTION ?></th>
					<th><?php echo LAYOUT ?></th>
					<th class="icon"></th>
					<th class="icon"></th>
				</tr>
				</thead>

				<tbody id="sortable" data-type="page-content" data-section="<?php echo $page_id?>">
				<?php
					$sql_options=array(
						'fields'=>'page_content_id,page_content_order,page_content_description,page_content_type,page_layout_description,page_function_description',
						'order'=> "page_content_order asc",
						'join'=>array(
							array('type'=>'INNER','table'=>'page_layouts','cond'=>'fk_page_layout_id=page_layout_id'),
							array('type'=>'LEFT','table'=>'page_functions','cond'=>'fk_page_layout_id=page_layout_id')
						),
						'cond'=>"fk_page_id=$page_id",
						'group'=>'page_content_id'
					);

					$result=$DB->find('page_content',$sql_options);

					$items_total = $DB->row_totals;
					$items_current_total = $DB->row_totals_second;
					foreach($result as $v){ ?>
						<tr class="sortable-item" id="<?php echo $v['page_content_id']?>">
							<td class="icon"><?php echo $v['page_content_order'];?></td>
							<td class="icon"><?php echo $icons['sort'] ?></td>
							<td><?php echo $view_files[($v['page_content_type'] == '1' ? 'page-content' : 'page-functions')]['icon'] . ' ' .$page_content_types[$v['page_content_type']] ?></td>
							<td><?php echo $v['page_content_type'] == '1' ? $v['page_content_description'] : $v['page_function_description'];?></td>
							<td><?php echo COLUMN ?>: <?php echo $v['page_layout_description'];?></td>

							<!-- REDIGER LINK -->
							<td class="icon">
								<a class="<?php echo $buttons['edit'] ?>" href="index.php?page=page-content-edit&page-id=<?php echo $page_id?>&id=<?php echo $v['page_content_id']?>" data-page="page-content-edit" data-params="page-id=<?php echo $page_id?>&id=<?php echo $v['page_content_id']?>" title="<?php echo EDIT_ITEM ?>"><?php echo $icons['edit'] ?></a>
							</td>

							<!-- SLET LINK -->
							<td class="icon">
								<a class="<?php echo $buttons['delete'] ?>"  data-toggle="confirmation" href="index.php?page=page-content&page-id=<?php echo $page_id?>&id=<?php echo $v['page_content_id']?>&delete" data-page="page-content" data-params="page-id=<?php echo $page_id?>&id=<?php echo $v['page_content_id']?>&delete" title="<?php echo DELETE_ITEM ?>"><?php echo $icons['delete'] ?></a>
							</td>
						</tr>
					<?php }?>
				</tbody>
			</table>
		</div><!-- /.table-responsive -->
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
