<?php
	$ar=[];
	$ar[]='page_url_key=""';
	if($id) $ar[]="page_id!=$id";
	$requireUrl=$DB->find('pages',array('cond'=>join(' AND ',$ar)));
	$req=count($requireUrl) > 0 ? 'required' : '';
?>
<div class="form-group">
	<label for="title"><?php echo TITLE ?>:</label>
	<input type="text" name="title" id="title" class="form-control" required maxlength="55" autofocus value="<?php echo $title;?>">
</div>

<div class="form-group">
	<label for="url_key"><?php echo URL_KEY ?>:</label>
	<input type="text" name="url_key" id="url_key" class="form-control" <?php echo $req;?> maxlength="50" pattern="([a-z0-9-.])+" title="<?php echo INVALID_URL_KEY ?>" value="<?php echo $url;?>">
</div>

<?php

	$options=[
		'noindex, follow'=>META_ROBOTS_NOT_THIS,
		'noindex, nofollow'=>META_ROBOTS_NONE,
		'index, follow'=>META_ROBOTS_ALL,
		'index, nofollow'=>META_ROBOTS_ONLY_THIS
	];

?>

<div class="form-group">
	<label for="meta_robots"><?php echo META_ROBOTS ?>:</label>
	<select class="form-control" name="meta_robots" id="meta_robots" required>
		<?php foreach($options as $k=>$v){
			echo '<option value="'.$k.'" '.($robots == $k ? "selected" : "").'>'.$v.'</option>';
		}?>
	</select>
</div>

<div class="form-group">
	<label for="meta_description"><?php echo META_DESCRIPTION ?>: <small class="text-muted">(<?php echo OPTIONAL ?>)</small></label>
	<textarea name="meta_description" id="meta_description" class="form-control" maxlength="155"><?php echo $desc;?></textarea>
</div>

<button type="submit" class="<?php echo $buttons['save'] ?>" name="save_item">
	<?php echo $icons['save'] . ' ' .SAVE_CHANGES ?>
</button>