<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$view_file='events';
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
$sort_by	 = isset($_SESSION[$view_file.'conf']['sort_by'])		? $_SESSION[$view_file.'conf']['sort_by']	: 'timestamp';
$order 		 = isset($_SESSION[$view_file.'conf']['order'])		? $_SESSION[$view_file.'conf']['order']	: 'desc';
$search 	 = isset($_SESSION[$view_file.'conf']['search'])		? $_SESSION[$view_file.'conf']['search']	: '';

$icon_time		= $icon_type = $icon_desc = $icon_name = $icon_role = '';

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
	case 'timestamp':
		$icon_time	= $icon;
		$sorting="event_time ".$order;
		break;
	case 'type':
		$icon_type		= $icon;
		$sorting="event_type_name ".$order;
		break;
	case 'description':
		$icon_desc		= $icon;
		$sorting="event_description ".$order;
		break;
	case 'user-name':
		$icon_name		= $icon;
		$sorting="user_name ".$order;
		break;
	case 'role-name':
		$icon_role	= $icon;
		$sorting="role_name ".$order;
		break;
}
?>

<div class="page-title">
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
			<div class="title"><?php echo LOGBOOK_DESCRIPTION ?></div>
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
						<a href="index.php?page=<?php echo $view_file; ?>&sort-by=timestamp&order=<?php echo $new_order;?>" data-page="<?php echo $view_file; ?>" data-params="sort-by=timestamp&order=<?php echo $new_order;?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_time . DATE_AND_TIME ?></a>
					</th>
					<th>
						<a href="index.php?page=<?php echo $view_file; ?>&sort-by=type&order=<?php echo $new_order;?>" data-page="<?php echo $view_file; ?>" data-params="sort-by=type&order=<?php echo $new_order;?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_type . TYPE ?></a>
					</th>
					<th>
						<a href="index.php?page=<?php echo $view_file; ?>&sort-by=description&order=<?php echo $new_order;?>" data-page="<?php echo $view_file; ?>" data-params="sort-by=description&order=<?php echo $new_order;?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_desc . DESCRIPTION ?></a>
					</th>
					<th>
						<a href="index.php?page=<?php echo $view_file; ?>&sort-by=user-name&order=<?php echo $new_order;?>" data-page="<?php echo $view_file; ?>" data-params="sort-by=user-name&order=<?php echo $new_order;?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_name . USER ?></a>
					</th>
					<th>
						<a href="index.php?page=<?php echo $view_file; ?>&sort-by=role-name&order=<?php echo $new_order;?>" data-page="<?php echo $view_file; ?>" data-params="sort-by=role-name&order=<?php echo $new_order;?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_role . ROLE ?></a>
					</th>
				</tr>
				</thead>

				<tbody>
				<?php
					$offset=$page_length*($page_no-1);
					$sql_options=array(
						'fields'=>'DATE_FORMAT(event_time,"'.$timeStamp.'") AS event_created,event_description,event_type_name,event_type_class,role_name,user_name',
						'order'=> $sorting,
						'limit'=> $page_length . " OFFSET " . $offset,
						'join'=>array(
							array('type'=>'INNER','table'=>'event_types','cond'=>'fk_event_type_id=event_type_id'),
							array('type'=>'INNER','table'=>'users','cond'=>'fk_user_id=user_id'),
							array('type'=>'INNER','table'=>'roles','cond'=>'fk_role_id=role_id')
						)
					);
					$ar=[];
					$ar[] ="event_access_level_required<=".$user['role_access_level']." AND role_access_level<=".$user['role_access_level'];
					if($search) $ar[] = "(event_description like '%$search%' OR user_name like '%$search%')";
					$sql_options['cond']=join(' AND ',$ar);

					$events=$DB->find('events',$sql_options);

					$events_total = $DB->row_totals;
					$events_current_total = $DB->row_totals_second;
					foreach($events as $v){
						?>
						<tr>
							<td><?php echo $v['event_created'] ?></td>
							<td><span class="label label-<?php echo $v['event_type_class'] ?>"><?php echo constant($v['event_type_name']) ?></span></td>
							<td><?php echo $v['event_description'] ?></td>
							<td><?php echo $v['user_name'] ?></td>
							<td><?php echo constant($v['role_name']) ?></td>
						</tr>
						<?php
					}
				?>

				</tbody>
			</table>
		</div><!-- /.table-responsive -->

		<div class="row">
			<div class="col-md-3">
				<?php echo sprintf(SHOWING_ITEMS_AMOUNT, ($events_current_total == 0 ? 0 : $offset+1) , $offset+$events_current_total, $events_total) ?>
			</div>
			<div class="col-md-9 text-right">
				<?php pagination($page_no,$events_total,$page_length,$view_file)?>
			</div>
		</div>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
