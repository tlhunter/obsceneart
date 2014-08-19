<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?=$title?></title>
		<link type="text/css" rel="stylesheet" href="<?=base_url();?>styles/reset.css" />
		<link type="text/css" rel="stylesheet" href="<?=base_url();?>styles/style.css" />
		<link type="text/css" rel="stylesheet" href="<?=base_url();?>styles/eggplant/jquery-ui-1.8.10.custom.css" />
		<script type="text/javascript" src="<?=base_url();?>scripts/jquery.js"></script>
		<script type="text/javascript" src="<?=base_url();?>scripts/jquery-ui-1.8.10.custom.min.js"></script>
		<script type="text/javascript" src="<?=base_url();?>scripts/obsceneart.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="<?=base_url();?>favicon.ico" type="image/png">
		<!--[if lte IE 8]>
		<script src="<?=base_url();?>scripts/html5.js" type="text/javascript"></script>
		<![endif]-->
		<base href="<?=base_url();?>" />
		<meta name="title" content="<?=addslashes($title)?>" />
		<link rel="image_src" type="image/png" href="<?=base_url();?>images/logo.png" />
<?=$syntax?>
	</head>
	<body>
		<div id="page">
			<header>
<?php if ($logged_in) { ?>
				<div id="top-username">Logged in as: <a href="<?=base_url();?>users/<?=$current_username?>"><?=$current_username?></a></div>
<?php } ?>
				<h1><a href="<?=base_url();?>"><?=HEADER_TEXT?></a></h1>
				<h2><?=TAGLINE?></h2>
			</header>
			<nav id="main">
<?=$nav_main?>
			</nav>
			<div id="content">
<?=$nav_sub?>
				<article>
<?=$contents?>
				</article>
				<aside>
<?=$sidebar?>
				</aside>
				<div class="clear"></div>
			</div>
			<footer><?=FOOTER_TEXT?></footer>
		</div>
<?=$analytics?>
	</body>
</html>