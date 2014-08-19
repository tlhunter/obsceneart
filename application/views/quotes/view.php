<?php
display_quote($quote, $logged_in, $current_user_id, $current_user_rank);
if ($tags) {
?>
<div class="cluster">
	<h3>Tags</h3>
	<div id="tag-list">
<?php
foreach($tags AS $tag) {
	echo "\t\t<a class=\"tag-show\" href=\"", base_url(), "browse/{$tag['slug']}\">{$tag['name']}</a>\n";
}
?>
		<div class="clear"></div>
	</div>
</div>
<?php
}
?>
<div class="cluster">
	<h3>Social Media</h3>
	<div><a href="javascript:;" onclick="return fbs_click()" class="fb_share_link">Share This Item on Facebook</a></div>
</div>