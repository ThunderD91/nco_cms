<?php
	if ( !isset($view_files) )
	{
		require '../config.php';
		$view_file='pages';
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
	$sort_by	 = isset($_SESSION[$view_file.'conf']['sort_by'])		? $_SESSION[$view_file.'conf']['sort_by']	: 'title';
	$order 		 = isset($_SESSION[$view_file.'conf']['order'])		? $_SESSION[$view_file.'conf']['order']	: 'asc';
	$search 	 = isset($_SESSION[$view_file.'conf']['search'])		? $_SESSION[$view_file.'conf']['search']	: '';

	$icon_title		= $icon_url = $icon_locked = $icon_status = '';

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
		case 'title':
			$icon_title	= $icon;
			$sorting="page_title ".$order;
			break;
		case 'url':
			$icon_url		= $icon;
			$sorting="page_url_key ".$order;
			break;
		case 'locked':
			$icon_locked		= $icon;
			$sorting="page_protected ".$order;
			break;
		case 'status':
			$icon_status	= $icon;
			$sorting="page_status ".$order;
			break;
	}

	if(isset($_GET['delete']) && isset($_GET['id']) && !empty($_GET['id'])){
		$delete_id=$_GET['id'];
		$resultDel = $DB->find('pages',array('cond'=>"page_id=$delete_id"));
		if ($DB->row_totals > 0) {
			if($resultDel[0]['page_protected']){
				alert('warning',PROTECTED_PAGE);
			}else {
				$query = "DELETE FROM pages WHERE page_id=$delete_id";
				$DB->execute($query);
				if ($DB->last_err)
					query_error($this->conn->error, $query, __LINE__, __FILE__);
				else {
					$Event->createEvent('delete', 'af side ' . $resultDel[0]['page_title'], 100, $user['user_id']);
				}
			}
		}
	}
?>

<div class="page-title">
	<a class="<?php echo $buttons['create'] ?> pull-right" href="index.php?page=page-create" data-page="page-create"><?php echo $icons['create'] . CREATE_ITEM ?></a>
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
				<form class="form-inline" data-page="<?php echo $view_file; ?>">
					<input type="hidden" name="page" value="<?php echo $view_file; ?>">
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
				<form data-page="<?php echo $view_file; ?>">
					<input type="hidden" name="page" value="<?php echo $view_file; ?>">
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
						<a href="index.php?page=<?php echo $view_file; ?>&sort-by=title&order=<?php echo $new_order;?>" data-page="<?php echo $view_file; ?>" data-params="sort-by=title&order=<?php echo $new_order;?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_title . TITLE ?></a>
					</th>
					<th>
						<a href="index.php?page=<?php echo $view_file; ?>&sort-by=url&order=<?php echo $new_order;?>" data-page="<?php echo $view_file; ?>" data-params="sort-by=url&order=<?php echo $new_order;?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_url . URL ?></a>
					</th>
					<th class="icon"></th>
					<?php if($user['role_access_level']==1000){?>
						<th class="toggle">
							<a href="index.php?page=<?php echo $view_file; ?>&sort-by=locked&order=<?php echo $new_order;?>" data-page="<?php echo $view_file; ?>" data-params="sort-by=locked&order=<?php echo $new_order;?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_locked . LOCKED ?></a>
						</th>
					<?php }?>
					<th class="toggle">
						<a href="index.php?page=<?php echo $view_file; ?>&sort-by=status&order=<?php echo $new_order;?>" data-page="<?php echo $view_file; ?>" data-params="sort-by=status&order=<?php echo $new_order;?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_status . STATUS ?></a>
					</th>
					<th class="icon"></th>
					<th class="icon"></th>
				</tr>
				</thead>

				<tbody>
				<?php
					$offset=$page_length*($page_no-1);
					$sql_options=array(
						'order'=> $sorting,
						'limit'=> $page_length . " OFFSET " . $offset
					);
					$ar=[];
					if($search) $ar[] = "(page_title like '%$search%' OR page_url_key like '%$search%')";
					if(count($ar) > 0) $sql_options['cond']=join(' AND ',$ar);

					$result=$DB->find('pages',$sql_options);

					$items_total = $DB->row_totals;
					$items_current_total = $DB->row_totals_second;
					foreach($result as $v){
				?>
				<tr>
					<td><?php echo $v['page_title'];?></td>
					<td><a href="../<?php echo $v['page_url_key'];?>" target="_blank">/<?php echo $v['page_url_key'];?></a></td>

					<!-- LINK TIL SIDEINDHOLD -->
					<td class="icon">
						<a href="index.php?page=page-content&page-id=<?php echo $v['page_id'];?>" title="<?php echo $view_files['page-content']['title'] ?>" data-page="page-content" data-params="page-id=<?php echo $v['page_id'];?>"><?php echo $view_files['page-content']['icon'] ?></a>
					</td>

					<!-- TOGGLE TIL BESKYT/BESKYT IKKE ELEMENT -->
					<?php if($user['role_access_level']==1000){?>
					<td class="toggle">
						<input type="checkbox" class="toggle-checkbox" id="<?php echo $v['page_id']; ?>" data-type="page-protected" <?php echo $v['page_protected'] ? "checked" : ""; ?>>
					</td>
					<?php }?>

					<!-- TOGGLE TIL AKTIVER/DEAKTIVER ELEMENT -->

					<td class="toggle">
						<?php if(!$v['page_protected'] || $user['role_access_level']==1000){?>
						<input type="checkbox" class="toggle-checkbox" id="<?php echo $v['page_id']; ?>" data-type="page-status" <?php echo $v['page_status'] ? "checked" : ""; ?>>
						<?php }?>
					</td>


					<!-- REDIGER LINK -->

					<td class="icon">
						<a class="<?php echo $buttons['edit'] ?>" href="index.php?page=page-edit&id=<?php echo $v['page_id'];?>" data-page="page-edit" data-params="id=<?php echo $v['page_id'];?>" title="<?php echo EDIT_ITEM ?>"><?php echo $icons['edit'] ?></a>
					</td>


					<!-- SLET LINK -->
					<?php if(!$v['page_protected'] || $user['role_access_level']==1000){?>
					<td class="icon">
						<a class="<?php echo $buttons['delete'] ?>" data-toggle="confirmation" href="index.php?page=<?php echo $view_file; ?>&id=<?php echo $v['page_id'];?>&delete" data-page="<?php echo $view_file; ?>" data-params="id=<?php echo $v['page_id'];?>&delete" title="<?php echo DELETE_ITEM ?>"><?php echo $icons['delete'] ?></a>
					</td>
					<?php }?>
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
