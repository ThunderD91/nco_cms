<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$view_file='posts';
}
if(!isset($_SESSION[$view_file.'conf']))
	$_SESSION[$view_file.'conf']=[];
if(isset($_GET['page-length']) && $_GET['page-length'] >= min($show_pages_length) && $_GET['page-length'] <= max($show_pages_length)){
	$_SESSION[$view_file.'conf']['page_length']=$DB->esc($_GET['page-length']);
	unset($_SESSION[$view_file.'conf']['page_no']);
}
if(isset($_GET['page-no'])) $page_no =$_SESSION[$view_file.'conf']['page_no']=$_GET['page-no'];
if(isset($_GET['sort-by'])) $_SESSION[$view_file.'conf']['sort_by']=$_GET['sort-by'];
if(isset($_GET['order'])) $_SESSION[$view_file.'conf']['order']=$DB->esc($_GET['order']);

if(isset($_GET['search']) && !empty($_GET['search'])){
	$_SESSION[$view_file.'conf']['search']=$DB->esc($_GET['search']);
	unset($_SESSION[$view_file.'conf']['page_no']);
}
if((isset($_GET['search']) && empty($_GET['search']) || (isset($_GET['clear']) && $_GET['clear']=="search"))) unset($_SESSION[$view_file.'conf']['search']);

// Defaults
$page_length = isset($_SESSION[$view_file.'conf']['page_length'])	? intval($_SESSION[$view_file.'conf']['page_length'])	: DEFAULT_PAGE_LENGTH;
$page_no 	 = isset($_SESSION[$view_file.'conf']['page_no'])		? $_SESSION[$view_file.'conf']['page_no']	: 1;
$sort_by	 = isset($_SESSION[$view_file.'conf']['sort_by'])		? $_SESSION[$view_file.'conf']['sort_by']	: 'created';
$order 		 = isset($_SESSION[$view_file.'conf']['order'])		? $_SESSION[$view_file.'conf']['order']	: 'desc';
$search 	 = isset($_SESSION[$view_file.'conf']['search'])		? $_SESSION[$view_file.'conf']['search']	: '';

$icon_created		= $icon_title = $icon_link = $icon_user = $icon_status = '';

if ($order  == 'desc')
{
	$new_order	= 'asc';
	$icon		= $icons['sort-desc'];
}
else
{
	$new_order	= 'desc';
	$icon		= $icons['sort-asc'];
}

switch($sort_by)
{
	case 'created':
		$icon_created	= $icon;
		$sorting="post_time ".$order;
		break;
	case 'title':
		$icon_title		= $icon;
		$sorting="post_title ".$order;
		break;
	case 'url':
		$icon_link		= $icon;
		$sorting="post_url_key ".$order;
		break;
	case 'user-name':
		$icon_user	= $icon;
		$sorting="user_name ".$order;
		break;
	case 'status':
		$icon_status	= $icon;
		$sorting="post_status ".$order;
		break;
}

if(isset($_GET['delete']) && isset($_GET['id']) && !empty($_GET['id'])){
	$delete_id=$_GET['id'];
	if($delete_id!=1) {
		if($delete_id != $user['user_id']) {
			$resultDel = $DB->find('users', array('cond' => "user_id=$delete_id", 'fields' => 'user_name,role_access_level',
				'join'=>array(
					array('type'=>'INNER','table'=>'roles','cond'=>'fk_role_id=role_id')
				)
			));

			if ($DB->row_totals > 0 && ($resultDel[0]['role_access_level']<$user['role_access_level']  || $user['role_access_level']==1000)) {
				$query = "DELETE FROM users WHERE user_id=$delete_id";
				$DB->execute($query);
				if ($DB->last_err)
					query_error($this->conn->error, $query, __LINE__, __FILE__);
				else {
					$Event->createEvent('delete', 'af brugeren <a href="index.php?page=user-edit&id=' . $delete_id . '" data-page="user-edit" data-params="id=' . $delete_id . '">' . $resultDel[0]['user_name'] . '</a>', 100, $user['user_id']);
				}
			}
		}else{
			alert('warning',CANT_DELETE_SELF);
		}
	}else
		alert('warning',sprintf(CANT_DELETE_SUPER, SUPER_ADMINISTRATOR));
}
?>

<div class="page-title">
	<a class="<?php echo $buttons['create'] ?> pull-right" href="index.php?page=post-create" data-page="post-create"><?php echo $icons['create'] . CREATE_ITEM ?></a>
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files[$view_file]['icon'] . ' ' . $view_files[$view_file]['title']
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
		<div class="row">
			<div class="col-md-4">
				<form class="form-inline" data-page="<?php echo $view_file;?>">
					<input type="hidden" name="page" value="<?php echo $view_file;?>">
					<label class="font-weight-300">
						Vis
						<select class="form-control input-sm" name="page-length" data-change="submit-form">
							<?php foreach($show_pages_length as $k=>$v){?>
								<option value="<?php echo $k?>" <?php echo $page_length == $k ? "selected" : "" ?>><?php echo $v?></option>
							<?php }?>
						</select>
						elementer
					</label>
				</form>
			</div>
			<div class="col-md-5 col-md-offset-3 text-right">
				<form data-page="<?php echo $view_file;?>">
					<input type="hidden" name="page" value="<?php echo $view_file;?>">
					<div class="input-group input-group-sm">
						<input type="search" name="search" id="search" class="form-control" placeholder="<?php echo PLACEHOLDER_SEARCH ?>" value="<?php if($search) echo  $search;?>">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit"><?php echo $icons['search'] ?></button>
						</span>
						<span class="input-group-btn"></span>
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit" name="clear" id="clear" value="search">Clear</button>
						</span>
					</div>
				</form>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-hover table-striped">
				<thead>
				<tr>
					<th>
						<a href="index.php?page=<?php echo $view_file;?>&sort-by=created&order=<?php echo $new_order;?>" data-page="<?php echo $view_file;?>" data-params="sort-by=created&order=asc" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_created .  CREATED ?></a>
					</th>
					<th>
						<a href="index.php?page=<?php echo $view_file;?>&sort-by=title&order=<?php echo $new_order;?>" data-page="<?php echo $view_file;?>" data-params="sort-by=title&order=asc" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_title .  TITLE ?></a>
					</th>
					<th>
						<a href="index.php?page=<?php echo $view_file;?>&sort-by=url&order=<?php echo $new_order;?>" data-page="<?php echo $view_file;?>" data-params="sort-by=address&order=asc" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_link .  URL ?></a>
					</th>
					<th>
						<a href="index.php?page=<?php echo $view_file;?>&sort-by=user-name&order=<?php echo $new_order;?>" data-page="<?php echo $view_file;?>" data-params="sort-by=user-name&order=asc" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_user .  USER ?></a>
					</th>
					<th class="icon"></th>
					<th class="toggle">
						<a href="index.php?page=<?php echo $view_file;?>&sort-by=status&order=<?php echo $new_order;?>" data-page="<?php echo $view_file;?>" data-params="sort-by=status&order=asc" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_status .  STATUS ?></a>
					</th>
					<th class="icon"></th>
					<th class="icon"></th>
				</tr>
				</thead>

				<tbody>
				<?php
				$offset=$page_length*($page_no-1);
				$sql_options=array(
					'fields'=>'post_id,post_status,DATE_FORMAT(post_time,"'.$timeStamp.'") AS created,user_name,post_title,post_url_key',
					'order'=> $sorting,
					'limit'=> $page_length . " OFFSET " . $offset,
					'join'=>array(
						array('type'=>'INNER','table'=>'users','cond'=>'fk_user_id=user_id'))
				);
				if($search) $sql_options['cond'] = "(user_name like '%$search%' OR post_url_key like '%$search%' OR post_title like '%$search%')";

				$result=$DB->find('posts',$sql_options);

				$items_total = $DB->row_totals;
				$items_current_total = $DB->row_totals_second;
				foreach($result as $v){
				?>
				<tr>
					<td><?php echo $v['created']; ?></td>
					<td><?php echo $v['post_title']; ?></td>
					<td><a href="../<?php echo $v['post_url_key']; ?>" target="_blank">/<?php echo $v['post_url_key']; ?></a></td>

					<td><?php echo $v['user_name']; ?></td>

					<!-- LINK TIL KOMMENTARER -->
					<td class="icon">
						<a href="index.php?page=comments&post-id=<?php echo $v['post_id']; ?>" title="<?php echo $view_files['comments']['title'] ?>" data-page="comments" data-params="post-id=<?php echo $v['post_id']; ?>"><?php echo $view_files['comments']['icon'] ?></a>
					</td>

					<!-- TOGGLE TIL AKTIVER/DEAKTIVER ELEMENT -->
					<td class="toggle">
						<input type="checkbox" class="toggle-checkbox" name="my-checkbox" id="<?php echo $v['post_id']; ?>" data-type="<?php echo $view_file; ?>" <?php echo $v['post_status'] ? "checked" : ""; ?>>
					</td>

					<!-- REDIGER LINK -->
					<td class="icon">
						<a class="<?php echo $buttons['edit'] ?>" href="index.php?page=post-edit&id=<?php echo $v['post_id']; ?>" data-page="post-edit" data-params="id=<?php echo $v['post_id']; ?>" title="<?php echo EDIT_ITEM ?>"><?php echo $icons['edit'] ?></a>
					</td>

					<!-- SLET LINK -->
					<td class="icon">
						<a class="<?php echo $buttons['delete'] ?>" data-toggle="confirmation" href="index.php?page=<?php echo $view_file;?>&id=<?php echo $v['post_id']; ?>&delete" data-page="<?php echo $view_file;?>" data-params="id=<?php echo $v['post_id']; ?>&delete" title="<?php echo DELETE_ITEM ?>"><?php echo $icons['delete'] ?></a>
					</td>
				</tr>
				<?php }?>
				</tbody>
			</table>
		</div><!-- /.table-responsive -->

		<div class="row">
			<div class="col-md-3">
				<?php echo sprintf(SHOWING_ITEMS_AMOUNT, ($items_current_total == 0 ? 0 : $offset+1) , $offset+$items_current_total, $items_total) ?>
			</div>
			<div class="col-md-9 text-right">
				<?php pagination($page_no,$items_total,$page_length,$view_file)?>
			</div>
		</div>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
