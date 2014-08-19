<h3><a href="users/<?=$user['username']?>"><?=rank_symbol($user['rank'])?><?=$user['username']?></a><div class="right"><a href="users/<?=$user['username']?>/newest" class="active">Newest Quotes</a> | <a href="users/<?=$user['username']?>/popular">Popular Quotes</a> | <a href="users/<?=$user['username']?>/random">Random Quotes</a></div></h3>
<div class="pagination"><?=$pagination?></div>
<?php
foreach($quotes AS $quote) {
	display_quote($quote, $logged_in, $current_user_id, $current_user_rank);
}
?>
<div class="pagination"><?=$pagination?></div>