<?php
	if ( !isset($view_files) )
	{
		require '../config.php';
		$include_path = '../' . $include_path;
	}

	$name = $email = $id = $password_required ='';
	$role = 4;

?>

<div class="page-title">
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['user-edit']['icon'] . ' ' . $view_files['user-edit']['title']
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
		<form method="post" data-page="user-edit">
			<!--<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php //echo ITEM_UPDATED ?> <a href="index.php?page=users" data-page="users"><?php //echo RETURN_TO_OVERVIEW ?></a>
			</div>-->


			<?php
			$showForm=true;
			if(!isset($_GET['id']))
				$_GET['id']=$user['user_id'];
			if(isset($_GET['id']) && !empty($_GET['id'])){
				$id=intval($_GET['id']);
				$getUser=$DB->find('users',array('cond'=>"user_id=$id",
					'join'=>array(
						array('type'=>'INNER','table'=>'roles','cond'=>'fk_role_id=role_id')
					)
				));
				if(count($getUser)>0){
					if($user['role_access_level']<=$getUser[0]['role_access_level'] && $getUser[0]['user_id'] != $user['user_id'] && $user['role_access_level']!=1000){
						$showForm=false;
					}else {
						$name = $getUser[0]['user_name'];
						$email = $getUser[0]['user_email'];
						$role = $getUser[0]['fk_role_id'];
					}
				}else{
					alert('warning',sprintf(NO_ITEM_FOUND, USER) . ' <a href="index.php?page=users" data-page="users">' . RETURN_TO_OVERVIEW . '</a>');
				}
			}else{
				alert('warning',sprintf(NO_ITEM_SELECTED, USER) . ' <a href="index.php?page=users" data-page="users">' . RETURN_TO_OVERVIEW . '</a>');
			}
			if(isset($_POST['save_item'])){
				$result=$userClass->editUser($_POST);
				$name=$result['data']['name'];
				$email=$result['data']['email'];
				$role=$result['data']['role'];
				if($result['success'])
					$Event->createEvent('update','af brugeren <a href="index.php?page=user-edit&id='.$id.'" data-page="user-edit" data-params="id='.$id.'">'.$name.'</a>',100,$user['user_id']);
			}
			if($showForm)
				include $include_path . 'form-user.php';
			else
				alert('warning',MISSING_AUTH . ' <a href="index.php?page=users" data-page="users">' . RETURN_TO_OVERVIEW . '</a>');
			?>
				<!--<script>
					location.href=<?php //echo $root;?>"index.php?page=users";
				</script>-->

			<input type="hidden" id="userid" name="userid" value="<?php echo $id;?>">
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
