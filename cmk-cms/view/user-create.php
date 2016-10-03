<?php
	if ( !isset($view_files) )
	{
		require '../config.php';
		$include_path = '../' . $include_path;
	}
	$name = $email = '';
	$role = 4;
	$id=-1;
	$password_required="required";
?>

<div class="page-title">
	<span class="title">
		<?php
		// Get icon and title from Array $files, defined in config.php
		echo $view_files['user-create']['icon'] . ' ' . $view_files['user-create']['title']
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
		<form method="post" data-page="user-create">
			<?php
			if(isset($_POST['save_item'])){
				$result=$userClass->createUser($_POST);
				if(!$result['success']){
					$name=$result['data']['name'];
					$email=$result['data']['email'];
					$role=$result['data']['role'];
				}else{
					$Event->createEvent('create','af brugeren <a href="index.php?page=user-edit&id='.$result['data']['inserted_id'].'" data-page="user-edit" data-params="id='.$result['data']['inserted_id'].'">'.$result['data']['name'].'</a>',100,$user['user_id']);
				}
			}

			//alert('success',ITEM_CREATED . '<a href="index.php?page=users" data-page="users">' . RETURN_TO_OVERVIEW . '</a>');
			include $include_path . 'form-user.php' ?>
		</form>
	</div>
</div>

<?php
if (DEVELOPER_STATUS) { show_developer_info(); }
