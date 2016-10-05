<?php

$page_content_types=[
	'1'=>EDITOR,
	'2'=>PAGE_FUNCTION
];
$page_layouts=$DB->find('page_layouts');
$page_functions=$DB->find('page_functions');
?>

<div class="form-group">
	<label for="content_type"><?php echo TYPE ?>:</label>
	<select class="form-control" name="content_type" id="content_type">
		<?php foreach($page_content_types as $k=>$v){
			echo '<option value="'.$k.'" '.($content_type==$k ? "selected" : "").'>'.$v.'</option>';
		}?>
	</select>
</div>

<div id="1" <?php echo $content_type == "1" ? "" : 'style="display: none"'?>>
	<div class="form-group">
		<label for="description"><?php echo DESCRIPTION ?>:</label>
		<input type="text" name="description" id="description" class="form-control" required maxlength="255" value="<?php echo $desc;?>">
	</div>

	<div class="form-group">
		<label for="content"><?php echo CONTENT ?>:</label>
		<textarea name="content" id="content"><?php echo $content;?></textarea>
		<script>
			CKEDITOR.replace('content', {
				toolbar: 'Full'
			})
		</script>
	</div>
</div>

<div class="form-group" id="2" <?php echo $content_type == "2" ? "" : 'style="display: none"'?>>
	<label for="page_function"><?php echo PAGE_FUNCTION ?>:</label>
	<select class="form-control" name="page_function" id="page_function">
		<?php
			foreach($page_functions as $v){
				echo '<option value="'.$v['page_function_id'].'" '.($pagefunction==$v['page_function_id'] ? "selected" : "").'>'.$v['page_function_description'].'</option>';
			}
		?>
	</select>
</div>

<div class="form-group">
	<label for="layout"><?php echo LAYOUT ?>:</label>
	<select class="form-control" name="layout" id="layout">
		<?php
			foreach($page_layouts as $v){
				echo '<option value="'.$v['page_layout_id'].'" '.($layout==$v['page_layout_id'] ? "selected" : "").'>'.COLUMN.' '.$v['page_layout_description'].'</option>';
			}
		?>
	</select>
</div>

<button type="submit" class="<?php echo $buttons['save'] ?>" name="save_item">
	<?php echo $icons['save'] . ' ' .SAVE_CHANGES ?>
</button>