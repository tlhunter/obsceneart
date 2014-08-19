<div class="cluster">
	<h3>Search</h3>
	<form action="search" method="get" onsubmit="return getToURI(this)">
		<table class="input-table">
			<tr>
				<td><input type="search" name="q" placeholder="Search" /></td>
				<td><input type="submit" value="Search" class="button" /></td>
			</tr>
		</table>
	</form>
</div>

<?php if (!$logged_in) { ?>
<div class="cluster">
	<h3>Login</h3>
	<form action="login" method="post">
		<table class="input-table">
			<tr><td class="label-column">Username:</td><td><input autofocus name="login" /></td></tr>
			<tr><td class="label-column">Password:</td><td><input type="password" name="password" /></td></tr>
			<tr><td class="label-column">Remember:</td><td><input type="checkbox" name="remember" value="1" checked="checked" /></td></tr>
			<tr><td></td><td><input type="submit" value="Login" class="button" /> | <a href="auth/forgot_password">Forgot</a> | <a href="signup">Sign Up</a></td></tr>
		</table>
	</form>
</div>

<?php } ?>
<div class="cluster">
	<h3>Recent Quotes</h3>
	<ul id="recent-quotes">
		<?php foreach($sidebar_quotes AS $quote) {
			if (!empty($quote['title'])) {
				$title = $quote['title'];
			} else {
				$title = "#{$quote['id']}";
			}
			if (!empty($quote['language'])) {
				$language = $quote['language'];
			} else {
				$language = 'Text';
			}
		?>
		<li><div><a href="<?=base_url()?>quotes/<?=$quote['id']?>"><?=$title?></a> [<?=$language?>]</div><div><small><?=date('M d, Y', strtotime($quote['added']))?></small></li>
	<?php } ?>
	</ul>
</div>
