<h3><?=$tag['name']?></h3>
<div class="pagination"><?=$pagination?></div>
<?php
foreach($quotes AS $quote) {
	display_quote($quote, $logged_in, $current_user_id, $current_user_rank);
}
?>
<div class="pagination"><?=$pagination?></div>