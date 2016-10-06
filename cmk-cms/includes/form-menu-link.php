<?php
$menu_link_types=[
	'1'=>PAGE,
	'2'=>BLOG_POSTS
];
$pages=$DB->find('pages');
$posts=$DB->find('posts');
?>

<div class="form-group">
	<label for="name"><?php echo NAME ?>:</label>
	<input type="text" name="name" id="name" class="form-control" required maxlength="55" autofocus value="<?php echo $name;?>">
</div>

<div class="form-group">
	<label for="content_type"><?php echo TYPE ?>:</label>
	<select class="form-control" name="link_type" id="link_type">
		<?php foreach($menu_link_types as $k=>$v){
			echo '<option value="'.$k.'" '.($link_type==$k ? "selected" : "").'>'.$v.'</option>';
		}?>
	</select>
</div>

<div class="form-group">
	<label for="page"><?php echo PAGE ?>:</label>
	<select class="form-control" name="page" id="page" required>
		<option value=""><?php echo SELECT_AN_OPTION ?></option>
		<?php foreach($pages as $v){
			echo '<option value="'.$v['page_id'].'" '.($page==$v['page_id'] ? "selected" : "").'>'.$v['page_title'].'</option>';
		}?>
	</select>
</div>

<div class="form-group" id="2" <?php echo $link_type == "2" ? "" : 'style="display: none"'?>>
	<label for="post"><?php echo BLOG_POSTS ?>:</label>
	<select class="form-control" name="post" id="post" <?php echo $link_type == "2" ? "required" : ''?>>
		<option value=""><?php echo SELECT_AN_OPTION ?></option>
		<?php foreach($posts as $v){
			echo '<option value="'.$v['post_id'].'" '.($post==$v['post_id'] ? "selected" : "").'>'.$v['post_title'].'</option>';
		}?>
	</select>
</div>

<button type="submit" class="<?php echo $buttons['save'] ?>" name="save_item">
	<?php echo $icons['save'] . ' ' .SAVE_CHANGES ?>
</button>