<?php
function show_developer_info()
{
	?>
	<br>
	<pre class="prettyprint">GET <?php print_r($_GET) ?></pre>
	<pre class="prettyprint">POST <?php print_r($_POST) ?></pre>
	<pre class="prettyprint">FILES <?php print_r($_FILES) ?></pre>
	<pre class="prettyprint">SESSION <?php print_r($_SESSION) ?></pre>
	<pre class="prettyprint">COOKIE <?php print_r($_COOKIE) ?></pre>
	<?php
}
function updateOrdering($id_type,$page,$type,$order,$fk_page_id,$page_id){
	global $DB;
	$result=$DB->find($page,array('cond'=>"$type>$order AND $fk_page_id=$page_id"));
	if(count($result)>0){
		foreach($result as $v){
			$DB->execute("UPDATE $page SET $type=".($v[$type]-1)." WHERE $id_type=".$v[$id_type]);
		}
	}
}
function fingerPrint(){
	return hash('sha256',$_SERVER['HTTP_USER_AGENT'] . 'aUb-eÆaÅ!dr0%9@ø');
}
function pageAccess($view,$page="index",$skip=false){
	global $user;
	global $view_files;
	global $root;
	if(($view && $view_files[$view]['acl'] > $user['role_access_level']) || $skip){
		?>
		<script type="text/javascript">
			location.href="<?php echo $root."cmk-cms/".$page;?>.php";
		</script>
		<?php
	}
}
function isAdminUser(){
	global $loggedIn;
	global $user;
	global $userClass;

	if(!$loggedIn || $user['role_access_level']<10){
		pageAccess(false,'login',true);
		exit;
	}
	if($user['fingerPrint']!=fingerPrint()){
		$userClass->logout();
		pageAccess(false,'login',true);
		exit;
	}
	if(!DEVELOPER_STATUS) {
		if (isset($user['last_activity']) && $user['last_activity'] + $userClass->timeout < time()) {
			$userClass->logout();
			pageAccess(false,'login',true);
			exit;
		}
		$userClass->setActivity();
	}
}
function connect_error($error_no,$error,$line_number,$file_name){
	if(DEVELOPER_STATUS){
		die("<p>Forbindelsesfejl ($error_no): $error</p><p>Linje: $line_number</p><p>Fil: $file_name</p>");
	}else{
		die('<p>'.CONNECT_ERROR.'</p>');
	}
}
function query_error($error,$query,$line_number,$file_name){
	global $DB;
	if(DEVELOPER_STATUS){
		$msg = "<strong>$error</strong><br><strong>Linje: $line_number</strong><br><strong>Fil: $file_name</strong><br><pre>$query</pre>";
		alert('danger',$msg);
		$DB->_close();
	}else{
		alert('danger',SQL_ERROR);
		$DB->_close();
	}
}
function prettyprint($data){
	?>
		<pre class="prettyprint lang-php"><code><?php print_r($data);?></code></pre>
	<?php
}
function alert($type,$msg){
	?>
	<div class="alert alert-<?php echo $type;?> alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<?php echo $msg; ?>
	</div>
<?php }
/*
 * @param $page_no, actuelle side
 * @param $items_total, totalle antal elementer
 * @param $page_length, længde af hver side
 * @param $overall_range, range fra og til på actuelle side, standard er 2
 */
function pagination($page_no,$items_total,$page_length,$page,$overall_range=2){
		global $icons;
		echo '<ul class="pagination">';
		$pages_total=ceil($items_total/$page_length);
		if($pages_total>1){
			$floor=floor($pages_total);
			if($page_no==1 && $floor>0){
				echo '<li class="disabled"><span>'.$icons['previous'].'</span></li>';
			}else if($pages_total>0){
				echo '<li><a href="index.php?page='.$page.'&page-no='.intval($page_no-1).'" data-page="'.$page.'" data-params="page-no='.intval($page_no-1).'">'.$icons['previous'].'</a></li>';
			}
			$range=$overall_range;
			$rest=0;
			/* start, vores start index i vores for løkke
			 * hvis current page minus range er større end nul blir start indexed sat
			 * inde i løkken bliver range minuset, og rest pluset indtil den gyldige værdi 1 er fundet
			 */
			$start=($page_no - $range > 0 ? $page_no - $range : false);
			while(!$start){
				$range--;
				$rest++;
				$start=($page_no - $range > 0 ? $page_no - $range : false);
			}
			$range=$overall_range;
			$rest2=0;
			/* end, skal vi ind og se på vores total pages
			 * ligesom vi brugte minus før skal vi nu bruge plus, og huske vores rest!
			 * så hvis current page plus range plus rest er mindre eller lig med total pages bliver end værdi sat
			 * hvis den er false går den igen ind i løkken som før indtil en gyldig værdi bliver fundet
			 */
			$end=($page_no + $range + $rest <= $pages_total ? $page_no + $range + $rest: false);
			while(!$end){
				$range--;
				$rest2++;
				$end=($page_no + $range + $rest <= $pages_total ? $page_no + $range + $rest : false);
			}
			$start-=$rest2;
			$start=$start>0 ? $start : 1;

			if($start != 1) {
				echo '<li '.($page_no==1 ? 'class="active"' : "").'><a href="index.php?page='.$page.'&page-no=1" data-page="'.$page.'" data-params="page-no=1">1</a></li>';
				if($page_no - $overall_range >2)
					echo '<li class="disabled"><span>&hellip;</span></li>';
			}

			for($i=$start;$i<=$end;$i++){
				echo '<li '.($page_no==$i ? 'class="active"' : "").'><a href="index.php?page='.$page.'&page-no='.$i.'" data-page="'.$page.'" data-params="page-no='.$i.'">'.$i.'</a></li>';
			}

			if($end != $pages_total) {
				if($page_no + $overall_range <$pages_total-1)
					echo '<li class="disabled"><span>&hellip;</span></li>';
				echo '<li '.($page_no==$pages_total ? 'class="active"' : "").'><a href="index.php?page='.$page.'&page-no='.$pages_total.'" data-page="'.$page.'" data-params="page-no='.$pages_total.'">'.$pages_total.'</a></li>';
			}
			if($page_no==$pages_total){
				echo '<li class="disabled"><span>'.$icons['next'].'</span></li>';
			}else if($floor>0){
				echo '<li><a href="index.php?page='.$page.'&page-no='.intval($page_no+1).'" data-page="'.$page.'" data-params="page-no='.intval($page_no+1).'">'.$icons['next'].'</a></li>';
			}
		}
		echo '</ul>';
}
?>