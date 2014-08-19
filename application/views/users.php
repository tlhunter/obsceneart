<h3>Registered Users</h3>
<div id="user_list">
<?php
foreach($users AS $user) {
	echo "<a href=\"users/{$user['username']}\">", rank_symbol($user['rank']), "{$user['username']}</a>,\n";
}
?>
</div>