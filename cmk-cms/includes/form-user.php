<!--<div class="alert alert-warning alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<?php //echo REQUIRED_FIELDS_EMPTY ?>
</div>

<div class="alert alert-warning alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<?php //echo EMAIL_NOT_AVAILABLE ?>
</div>

<div class="alert alert-warning alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<?php //echo PASSWORD_MISMATCH ?>
</div>-->

<div class="form-group">
	<label for="name"><?php echo NAME ?>:</label>
	<input type="text" name="name" id="name" class="form-control" required maxlength="100" autofocus value="<?php echo $name;?>">
</div>

<div class="form-group">
	<label for="email"><?php echo EMAIL ?>:</label>
	<input type="email" name="email" id="email" class="form-control" required maxlength="100" value="<?php echo $email;?>">
</div>

<div class="form-group">
	<label for="password"><?php echo PASSWORD ?>:</label>
	<input type="password" name="password" id="password" class="form-control" minlength="4" value="" <?php echo $password_required;?>>
</div>

<div class="form-group">
	<label for="confirm_password"><?php echo CONFIRM_PASSWORD ?>:</label>
	<input type="password" name="confirm_password" id="confirm_password" class="form-control" minlength="4" value="" <?php echo $password_required;?>>
</div>
<?php
	$options=array('order'=>'role_id desc');
	$ar=[];
	if($user['role_access_level']!=1000)$ar[]="role_access_level<".$user['role_access_level'];
	if($id!==1 && $user['role_id']!=1) $ar[]='role_id!=1';
	if(count($ar) > 0)$options['cond']=join(' AND ',$ar);
	if($id==$user['user_id']) $options['cond']="role_id=".$user['role_id'];
	$roles = $DB->find('roles',$options);

?>
<div class="form-group">
	<label for="role"><?php echo ROLE ?>:</label>
	<select class="form-control" name="role" id="role" required>
		<?php
			foreach($roles as $v){
				//echo '<option value="'.$v['role_id'].'" '.$role==$v['role_id'] ? "selected" : "".'><'.'</option>';
			 	echo '<option value="'.$v['role_id'].'"'.($role==$v['role_id'] ? " selected" : "").'>'. constant($v['role_name']) .'</option>';
			}
		?>
	</select>
</div>

<button type="submit" class="<?php echo $buttons['save'] ?>" name="save_item">
	<?php echo $icons['save'] . ' ' .SAVE_CHANGES ?>
</button>