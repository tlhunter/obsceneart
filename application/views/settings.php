<h3>Social Settings</h3>
<?php if ($success) { ?><div class="success">Your social settings have been updated.</div><?php } ?>
<?php if ($submit_error) { ?><div class="error">There was an error updating your social settings.</div><?php } ?>
<form action="settings/save" method="post">
	<table class="input-table">
		<tr>
			<td class="label-column">AIM</td>
			<td><input type="text" name="aim" value="<?=$user['aim']?>" /></td>
		</tr>
		<tr>
			<td class="label-column">Yahoo</td>
			<td><input type="text" name="yahoo" value="<?=$user['yahoo']?>" /></td>
		</tr>
		<tr>
			<td class="label-column">Website</td>
			<td><input type="text" name="website" value="<?=$user['website']?>" /></td>
		</tr>
		<tr>
			<td class="label-column"></td>
			<td><input type="submit" value="Change Settings" class="button" /></td>
		</tr>
	</table>
</form>
<p>These items are publicly visible. If you do not want people to know them, leave them blank.</p>