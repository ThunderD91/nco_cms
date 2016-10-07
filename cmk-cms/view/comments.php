<?php
if ( !isset($view_files) )
{
	require '../config.php';
	$view_file='comments';
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

$icon_created		= $icon_content = $icon_user = '';

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
		$sorting="comment_time ".$order;
		break;
	case 'content':
		$icon_title		= $icon;
		$sorting="comment_content ".$order;
		break;
	case 'user-name':
		$icon_link		= $icon;
		$sorting="user_name ".$order;
		break;;
}

$post_id="";
if(isset($_GET['post-id'])) {
	$post_id = $_GET['post-id'];
	if(isset($_GET['id']) && isset($_GET['delete'])){
		$pgid=$DB->esc($post_id);
		$delid=$DB->esc($_GET['id']);
		$resultDel = $DB->find('post_comments',array('cond'=>"fk_post_id=$pgid AND comment_id=$delid",
				'fields'=>'role_access_level',
				'join'=>array(
					array('type'=>'INNER','table'=>'users','cond'=>'fk_user_id=user_id'),
					array('type'=>'INNER','table'=>'roles','cond'=>'fk_role_id=role_id')
				)
			)
		);
		if ($DB->row_totals > 0 && ($resultDel[0]['role_access_level']<$user['role_access_level']  || $user['role_access_level']==1000)) {
			$query = "DELETE FROM post_comments WHERE fk_post_id=$pgid AND comment_id=$delid";
			$DB->execute($query);
			if ($DB->last_err)
				query_error($DB->last_err, $query, __LINE__, __FILE__);
			else {
				$Event->createEvent('delete', 'af ' . COMMENT, 10, $user['user_id']);
			}
		}
	}
}

?>

<div class="page-title">
	<a class="<?php echo $buttons['create'] ?> pull-right" href="index.php?page=comment-create&post-id=<?php echo $post_id;?>" data-page="comment-create" data-params="post-id=<?php echo $post_id;?>"><?php echo $icons['create'] . CREATE_ITEM ?></a>
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files[$view_file]['icon'] . ' Eksempel på indlæg 1'
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
					<input type="hidden" name="post-id" value="<?php echo $post_id;?>">
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
					<input type="hidden" name="post-id" value="<?php echo $post_id;?>">
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
						<a href="index.php?page=<?php echo $view_file;?>&post-id=<?php echo $post_id;?>&sort-by=created&order=<?php echo $new_order;?>" data-page="<?php echo $view_file;?>" data-params="post-id=<?php echo $post_id;?>&sort-by=created&order=asc" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_created . CREATED ?></a>
					</th>
					<th>
						<a href="index.php?page=<?php echo $view_file;?>&post-id=<?php echo $post_id;?>&sort-by=content&order=<?php echo $new_order;?>" data-page="<?php echo $view_file;?>" data-params="post-id=<?php echo $post_id;?>&sort-by=content&order=asc" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_content . CONTENT ?></a>
					</th>
					<th>
						<a href="index.php?page=<?php echo $view_file;?>&post-id=<?php echo $post_id;?>&sort-by=user-name&order=<?php echo $new_order;?>" data-page="<?php echo $view_file;?>" data-params="post-id=<?php echo $post_id;?>&sort-by=user-name&order=asc" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_user . USER ?></a>
					</th>
					<th class="icon"></th>
					<th class="icon"></th>
				</tr>
				</thead>

				<tbody>
				<?php
				$offset=$page_length*($page_no-1);
				$sql_options=array(
					'fields'=>'comment_id,comment_content,user_name,DATE_FORMAT(comment_time,"'.$timeStamp.'") AS created,role_access_level',
					'order'=> $sorting,
					'join'=>array(
						array('type'=>'INNER','table'=>'users','cond'=>'fk_user_id=user_id'),
						array('type'=>'INNER','table'=>'roles','cond'=>'fk_role_id=role_id')
					),
					'limit'=> $page_length . " OFFSET " . $offset
				);
				$ar=[];
				$ar[] ="fk_post_id=$post_id";
				if($search) $ar[] = "(user_name like '%$search%' OR comment_content like '%$search%')";
				$sql_options['cond']=join(' AND ',$ar);

				$result=$DB->find('post_comments',$sql_options);

				$items_total = $DB->row_totals;
				$items_current_total = $DB->row_totals_second;
				foreach($result as $v){ ?>
				<tr>
					<td><?php echo $v['created']; ?></td>
					<td><?php echo $v['comment_content']; ?></td>

					<td><?php echo $v['user_name']; ?></td>

					<!-- REDIGER LINK -->
					<td class="icon">
						<?php if($v['role_access_level']<$user['role_access_level']){ ?>
						<a class="<?php echo $buttons['edit'] ?>" href="index.php?page=comment-edit&post-id=<?php echo $post_id; ?>&id=<?php echo $v['comment_id']; ?>" data-page="comment-edit" data-params="post-id=<?php echo $post_id; ?>&id=<?php echo $v['comment_id']; ?>" title="<?php echo EDIT_ITEM ?>"><?php echo $icons['edit'] ?></a>
						<?php }?>
					</td>

					<!-- SLET LINK -->
					<td class="icon">
						<?php if($v['role_access_level']<$user['role_access_level']){ ?>
						<a class="<?php echo $buttons['delete'] ?>" data-toggle="confirmation" href="index.php?page=<?php echo $view_file;?>&post-id=<?php echo $post_id; ?>&id=<?php echo $v['comment_id']; ?>&delete" data-page="comments" data-params="post-id=<?php echo $post_id; ?>&id=<?php echo $v['comment_id']; ?>&delete" title="<?php echo DELETE_ITEM ?>"><?php echo $icons['delete'] ?></a>
						<?php }?>
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
				<?php pagination($page_no,$items_total,$page_length,$view_file,"post-id=".$post_id)?>
			</div>
		</div>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
