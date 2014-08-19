<h3>Random <div class="right"><a href="random">Refresh</a></div></h3>
<p>To get a new batch, mash on the Cmd+R or F5 keys until your fingers bleed.</p>
<?php
foreach($quotes AS $quote) {
	display_quote($quote, $logged_in, $current_user_id, $current_user_rank);
}
?>