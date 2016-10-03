<?php
if ( !isset($view_files) )
{
	require '../config.php';
}

if(!isset($_SESSION['userconf']))
	$_SESSION['userconf']=[];
if(isset($_GET['page-length']) && $_GET['page-length'] >= min($show_pages_length) && $_GET['page-length'] <= max($show_pages_length)){
	$_SESSION['userconf']['page_length']=$DB->esc($_GET['page-length']);
	unset($_SESSION['userconf']['page_no']);
}
if(isset($_GET['page-no'])) $page_no =$_SESSION['userconf']['page_no']=$_GET['page-no'];
if(isset($_GET['sort-by'])) $_SESSION['userconf']['sort_by']=$_GET['sort-by'];
if(isset($_GET['order'])) $_SESSION['userconf']['order']=$DB->esc($_GET['order']);

if(isset($_GET['toggle']) && isset($_GET['id'])){
	$cid=$DB->esc($_GET['id']);
	if($cid != 1) {
		$tog = $_GET['toggle'] ? 1 : 0;
		$DB->execute("UPDATE users SET user_status=$tog WHERE user_id=$cid");
	}
}

if(isset($_GET['search']) && !empty($_GET['search'])){
	$_SESSION['userconf']['search']=$DB->esc($_GET['search']);
	unset($_SESSION['userconf']['page_no']);
}
if((isset($_GET['search']) && empty($_GET['search']) || (isset($_GET['clear']) && $_GET['clear']=="search"))) unset($_SESSION['userconf']['search']);

// Defaults
$page_length = isset($_SESSION['userconf']['page_length'])	? intval($_SESSION['userconf']['page_length'])	: DEFAULT_PAGE_LENGTH;
$page_no 	 = isset($_SESSION['userconf']['page_no'])		? $_SESSION['userconf']['page_no']	: 1;
$sort_by	 = isset($_SESSION['userconf']['sort_by'])		? $_SESSION['userconf']['sort_by']	: 'created';
$order 		 = isset($_SESSION['userconf']['order'])		? $_SESSION['userconf']['order']	: 'desc';
$search 	 = isset($_SESSION['userconf']['search'])		? $_SESSION['userconf']['search']	: '';

$icon_created		= $icon_name = $icon_email = $icon_role = $icon_status = '';

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
		$sorting="user_created ".$order;
		break;
	case 'name':
		$icon_name		= $icon;
		$sorting="user_name ".$order;
		break;
	case 'email':
		$icon_email		= $icon;
		$sorting="user_email ".$order;
		break;
	case 'role':
		$icon_role		= $icon;
		$sorting="role_name ".$order;
		break;
	case 'status':
		$icon_status	= $icon;
		$sorting="user_status ".$order;
		break;
}

if(isset($_GET['delete']) && isset($_GET['id']) && !empty($_GET['id'])){
	$delete_id=$_GET['id'];
	if($delete_id!=1) {
		$resultDel=$DB->findQuery("select user_name from users where user_id=$delete_id");
		$query = "DELETE FROM users WHERE user_id=$delete_id";
		$DB->execute($query);
		if ($DB->last_err)
			query_error($this->conn->error, $query, __LINE__, __FILE__);
		else {
			$Event->createEvent('delete', 'af brugeren <a href="index.php?page=user-edit&id=' . $delete_id . '" data-page="user-edit" data-params="id=' . $delete_id . '">' . $resultDel[0]['user_name'] . '</a>', 100, $user['user_id']);
		}
	}else
		alert('warning',sprintf(CANT_DELETE_SUPER, SUPER_ADMINISTRATOR));
}
?>

<div class="page-title">
	<a class="<?php echo $buttons['create'] ?> pull-right" href="index.php?page=user-create" data-page="user-create"><?php echo $icons['create'] . CREATE_ITEM ?></a>
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['users']['icon'] . ' ' . $view_files['users']['title']
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
				<form class="form-inline" data-page="users">
					<input type="hidden" name="page" value="users">
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
				<form data-page="users">
					<input type="hidden" name="page" value="users">
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
						<a href="index.php?page=users&sort-by=created&order=<?php echo $new_order ?>" data-page="users" data-params="sort-by=created&order=<?php echo $new_order ?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_created . CREATED ?></a>
					</th>
					<th>
						<a href="index.php?page=users&sort-by=name&order=<?php echo $new_order ?>" data-page="users" data-params="sort-by=name&order=<?php echo $new_order ?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_name . NAME ?></a>
					</th>
					<th>
						<a href="index.php?page=users&sort-by=email&order=<?php echo $new_order ?>" data-page="users" data-params="sort-by=email&order=<?php echo $new_order ?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_email . EMAIL ?></a>
					</th>
					<th>
						<a href="index.php?page=users&sort-by=role&order=<?php echo $new_order ?>" data-page="users" data-params="sort-by=role&order=<?php echo $new_order ?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_role . ROLE ?></a>
					</th>
					<th class="toggle">
						<a href="index.php?page=users&sort-by=status&order=<?php echo $new_order ?>" data-page="users" data-params="sort-by=status&order=<?php echo $new_order ?>" title="<?php echo SORT_BY_THIS_COLUMN ?>"><?php echo $icon_status . STATUS ?></a>
					</th>
					<th class="icon"></th>
					<th class="icon"></th>
				</tr>
				</thead>

				<tbody>
				<?php
					$offset=$page_length*($page_no-1);
					$sql_options=array(
						'fields'=>'user_id,user_status,DATE_FORMAT(user_created,"%a, %e. %b %Y kl. %H:%i") AS created,user_name,user_email,role_name',
						'order'=> $sorting,
						'limit'=> $page_length . " OFFSET " . $offset,
						'join'=>array(
							array('type'=>'INNER','table'=>'roles','cond'=>'fk_role_id=role_id'))
					);
					if($search || $user['user_id']!=1) {
						$ar=[];
						if($search) $ar[] = "(user_name like '%$search%' OR user_email like '%$search%')";
						if($user['user_id']!=1) $ar[] = "user_id!=1";
						$sql_options['cond']=join(' AND ',$ar);
					}

					$result=$DB->find('users',$sql_options);

					$items_total = $DB->row_totals;
					$items_current_total = $DB->row_totals_second;
					foreach($result as $v){
				?>
				<tr>
					<td><?php echo $v['created']; ?></td>
					<td><?php echo $v['user_name']; ?></td>
					<td><?php echo $v['user_email']; ?></td>
					<td><?php echo constant($v['role_name']); ?></td>

					<!-- TOGGLE TIL AKTIVER/DEAKTIVER ELEMENT -->
					<td class="toggle">
						<input type="checkbox" class="toggle-checkbox" id="<?php echo $v['user_id']; ?>" data-type="users" <?php echo $v['user_status'] ? "checked" : ""; ?>>
					</td>

					<!-- REDIGER LINK -->
					<td class="icon">
						<a class="<?php echo $buttons['edit'] ?>" href="index.php?page=user-edit&id=<?php echo $v['user_id']; ?>" data-page="user-edit" data-params="id=<?php echo $v['user_id']; ?>" data-type="users" title="<?php echo EDIT_ITEM ?>"><?php echo $icons['edit'] ?></a>
					</td>

					<!-- SLET LINK -->
					<td class="icon">
						<a class="<?php echo $buttons['delete'] ?>" <?php if($v['user_id']!=1){?>data-toggle="confirmation" href="index.php?page=users&id=<?php echo $v['user_id']; ?>&delete" data-page="users" data-params="id=<?php echo $v['user_id']; ?>&delete" title="<?php echo DELETE_ITEM ?>" <?php } echo $v['user_id']== 1 ? "disabled" : "";?>><?php echo $icons['delete'] ?></a>
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
				<?php pagination($page_no,$items_total,$page_length,'users')?>
				<!--<ul class="pagination">-->
					<?php
						/*$pages_total=30;//ceil($items_total/$page_length);
						if($pages_total>1){
							$floor=floor($pages_total);
							if($page_no==1 && $floor>0){
								echo '<li class="disabled"><span>'.$icons['previous'].'</span></li>';
							}else if($pages_total>0){
								echo '<li><a href="index.php?page=users&page-no='.intval($page_no-1).'" data-page="users" data-params="page-no='.intval($page_no-1).'">'.$icons['previous'].'</a></li>';
							}

							// overall_range, antal pages fra selected page så 2*2+1=5, hvis overall_range var 3, ville total pages være 3*2+1=7
							$overall_range=3;
							// current page no
							$__current_page=$page_no;
							// total pages
							$__total_pages=$pages_total;
							// range, bruges til selve beregningen af rest
							$range=$overall_range;
							// rest, resterende værdi der skal pluses til end
							$rest=0;
							/* start, vores start index i vores for løkke
							 * hvis current page minus range er større end nul blir start indexed sat
							 * inde i løkken bliver range minuset, og rest pluset indtil den gyldige værdi 1 er fundet
							 *
							$start=($__current_page - $range > 0 ? $page_no - $range : false);
							while(!$start){
								$range--;
								$rest++;
								$start=($__current_page - $range > 0 ? $__current_page - $range : false);
							}
							// samme som før, range og rest2
							$range=$overall_range;
							$rest2=0;
							/* end, skal vi ind og se på vores total pages
							 * ligesom vi brugte minus før skal vi nu bruge plus, og huske vores rest!
							 * så hvis current page plus range plus rest er mindre eller lig med total pages bliver end værdi sat
							 * hvis den er false går den igen ind i løkken som før indtil en gyldig værdi bliver fundet
							 *
							$end=($__current_page + $range + $rest <= $__total_pages ? $__current_page + $range + $rest: false);
							while(!$end){
								$range--;
								$rest2++;
								$end=($__current_page + $range + $rest <= $__total_pages ? $__current_page + $range + $rest : false);
							}
							// start bliver minuset med rest2 og hvis start er større end nul bliver start sat, ellers blir den sat til 1
							$start-=$rest2;
							$start=$start>0 ? $start : 1;

							// beregning af vores start page, hvis start ikke er lig med 1
							if($start != 1) {
								echo '<li '.($__current_page==1 ? 'class="active"' : "").'><a href="index.php?page=users&page-no=1" data-page="users" data-params="page-no=1">1</a></li>';
								// beregning af '...'
								if($__current_page - $overall_range >2)
									echo '<li class="disabled"><span>&hellip;</span></li>';
							}

							// for løkke, standard
							for($i=$start;$i<=$end;$i++){
								echo '<li '.($__current_page==$i ? 'class="active"' : "").'><a href="index.php?page=users&page-no='.$i.'" data-page="users" data-params="page-no='.$i.'">'.$i.'</a></li>';
							}

							// beregning af vores end page, hvis end ikke er lig med total pages
							if($end != $__total_pages) {
								// beregning af '...'
								if($__current_page + $overall_range <$__total_pages-1)
									echo '<li class="disabled"><span>&hellip;</span></li>';
								echo '<li '.($__current_page==$__total_pages ? 'class="active"' : "").'><a href="index.php?page=users&page-no='.$__total_pages.'" data-page="users" data-params="page-no='.$__total_pages.'">'.$__total_pages.'</a></li>';
							}
							if($__current_page==$__total_pages){
								echo '<li class="disabled"><span>'.$icons['next'].'</span></li>';
							}else if($floor>0){
								echo '<li><a href="index.php?page=users&page-no='.intval($__current_page+1).'" data-page="users" data-params="page-no='.intval($__current_page+1).'">'.$icons['next'].'</a></li>';
							}
						}*/
					?>
				<!--</ul>-->
			</div>
		</div>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
