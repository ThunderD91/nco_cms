<div class="form-group">
	<label for="content"><?php echo CONTENT ?>:</label>
	<textarea name="content" id="content" autofocus required><?php echo $content; ?></textarea>
	<script>
		CKEDITOR.replace('content', {
			toolbar: 'Basic'
		})
	</script>
</div>

<button type="submit" class="<?php echo $buttons['save'] ?>" name="save_item">
	<?php echo $icons['save'] . ' ' .SAVE_CHANGES ?>
</button>