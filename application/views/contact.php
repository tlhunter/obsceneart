<h3>Contact ObsceneArt</h3>
<?php if ($message) { ?>
<div class="success">
	<?=$message?>
</div>
<?php } ?>
<p>Use this form to send an email to ObsceneArt administrators.</p>
<p>If you are reporting a quote which violates either copyright or our terms of service, make sure you paste the link or quote number into your message.</p>
<div class="warning">All fields are required.</div>
<form action="about/contact_execute" method="post">
	<table class="input-table">
		<tr>
			<td class="label-column">Name</td>
			<td><input type="text" name="name" /></td>
		</tr>
		<tr>
			<td class="label-column">Email</td>
			<td><input type="text" name="email" /></td>
		</tr>
		<tr>
			<td class="label-column">Subject</td>
			<td>
				<select name="subject">
					<option value="">Please Select...</option>
					<option>Fan Mail</option>
					<option>Hate Mail</option>
					<option>Quote Violation</option>
					<option>Inquiry</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="label-column" valign="top">Message</td>
			<td><textarea name="message" rows="8" cols="50"></textarea></td>
		</tr>
		<tr>
			<td class="label-column"></td>
			<td><input type="submit" value="Send Email" class="button" /></td>
		</tr>
	</table>
</form>