<h3>Search</h3>
<div class="cluster">
	<form action="search" method="get" onsubmit="return getToURI(this)">
		<table class="input-table">
			<tr>
				<td><input type="search" name="q" placeholder="Search" /></td>
				<td><input type="submit" value="Search" class="button" /></td>
			</tr>
		</table>
	</form>
</div>
<h3>Browse by Tags</h3>
<div id="tag-list">
<?php
foreach($tags AS $tag) {
	echo "\t<a class=\"tag-view\" href=\"", base_url(), "browse/{$tag['slug']}\">{$tag['name']} ({$tag['count']})</a>";
}
?>
	<div class="clear"></div>
</div>