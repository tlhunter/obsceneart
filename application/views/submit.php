<h3>Submit Text</h3>
<?php if ($new_id) { ?><div class="success">Your text has been added! <a href="quotes/<?=$new_id?>">You can view it here</a>.</div><?php } ?>
<?php if ($submit_error) { ?><div class="error">There was an error adding your text. Perhaps it was too short or you have JavaScript disabled?</div><?php } ?>
<?php if (!$logged_in) { ?><div class="warning">You are not currently logged in! This quote will be added anonymously and you will not be able to delete it later.</div><?php } ?>
<form action="submit/save" method="post">
	<table class="input-table">
		<tr><td class="label-column" valign="top">Title:</td><td><input type="text" name="title" size="50" maxlength="32" value="<?=date('Y-m-d')?> Untitled Text" /></td></tr>
		<tr><td class="label-column" valign="top">Text:</td><td><textarea name="quote" rows="15" cols="60"></textarea></td></tr>
		<tr><td class="label-column" valign="top">Language:</td><td><select id="language-selector" name="language">
			<?php foreach($languages AS $language) { ?>
				<option value="<?=$language['id']?>"><?=$language['name']?></option>
			<?php } ?>
		</select></td></tr>
		<tr><td class="label-column" valign="top">Strip Timestamps:</td><td><input id="timestamp-check" type="checkbox" name="remove_timestamps" checked="checked" /> <small>Select this and we will try our best to remove timestamps from your chats.</small></td></tr>
		<tr><td class="label-column" valign="top">Tags:</td><td><input type="text" name="tags" size="80" id="tags" /><br /><small>Start typing a tag, then use the arrow keys or mouse to highlight, and press enter or click to select.</small></td></tr>
		<tr><td class="label-column" valign="top">Private:</td><td><input type="checkbox" name="private" /> <small>Can't be discovered except through the URL</small></td></tr>
		<tr><td></td><td><input type="submit" value="Add Text" class="button" /></td></tr>
	</table>
	<input type="hidden" name="code" value="33" id="code-input" />
	<script type="text/javascript">
	var $timestamp = $('#timestamp-check');
		$('#code-input').val('44');
		$('#language-selector').change(function() {
			var index = $(this).val();
			if (index != 1) {
				$timestamp.attr('disabled', 'disabled').removeAttr('checked');
			} else {
				$timestamp.removeAttr('disabled').attr('checked', 'checked');
			}
		});
	</script>
</form>