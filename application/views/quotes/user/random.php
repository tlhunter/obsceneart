<h3><a href="users/<?=$user['username']?>"><?=rank_symbol($user['rank'])?><?=$user['username']?></a><div class="right"><a href="users/<?=$user['username']?>/newest">Newest Quotes</a> | <a href="users/<?=$user['username']?>/popular">Popular Quotes</a> | <a href="users/<?=$user['username']?>/random" class="active">Random Quotes</a></div></h3>
<p>Press the F5 key, or click the random link to view more random quotes by this user.</p>
<?php
foreach($quotes AS $quote) {
	display_quote($quote, $logged_in, $current_user_id, $current_user_rank);
}
?>