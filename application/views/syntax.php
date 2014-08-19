		<script type="text/javascript" src="scripts/syntax/shCore.js"></script>
		<?php foreach($languages AS $alias => $filename) { ?>
		<script type="text/javascript" src="scripts/syntax/<?=$filename?>"></script>
		<?php }?>
		<link type="text/css" rel="stylesheet" href="styles/syntax/shCoreDefault.css"/>
		<script type="text/javascript">
			SyntaxHighlighter.defaults['toolbar'] = false;
			SyntaxHighlighter.defaults['gutter'] = false;
			SyntaxHighlighter.defaults['auto-links'] = false;
			SyntaxHighlighter.all();
		</script>