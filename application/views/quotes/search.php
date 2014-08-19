<h3>Search Results</h3>
<p>There are the results for your search for <strong><?=htmlentities($query)?></strong>.</p>
<?php
foreach($quotes AS $quote) {
	display_quote($quote, $logged_in, $current_user_id, $current_user_rank);
}
?>