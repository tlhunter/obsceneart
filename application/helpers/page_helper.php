<?php
function nav_main($logged_in, $page) {
	$output = '';
	if (!$page) { $page = 'home'; }
	if ($page == 'search') { $page = 'browse'; }
	$left = array(
		'home' => 'Home',
		'newest' => 'Newest',
		'popular' => 'Popular',
		'random' => 'Random',
		'browse' => 'Browse',
		'submit' => 'Submit',
		'users' => 'Users',
		'about' => 'About'
	);
	if ($logged_in) {
		$right = array(
			'settings' => 'Settings',
			'logout' => 'Logout'
		);
	} else {
		$right = array(
			'signup' => 'Sign Up'
		);
	}
	$output .= "\t\t\t\t<ul class=\"left\">\n";
	foreach($left AS $url => $name) {
		$active = '';
		if ($page == $url) {
			$active = ' class="active"';
		}
		$output .= "\t\t\t\t\t<li$active><a href=\"$url\">$name</a></li>\n";
	}
	$output .= "\t\t\t\t</ul>\n";
	$output .= "\t\t\t\t<ul class=\"right\">\n";
	foreach($right AS $url => $name) {
		$active = '';
		if ($page == $url) {
			$active = ' class="active"';
		}
		$output .= "\t\t\t\t\t<li$active><a href=\"$url\">$name</a></li>\n";
	}
	$output .= "\t\t\t\t</ul>\n";
	return $output;
}

function nav_sub($logged_in, $page, $subpage) {
	$output = '<div id="sub-empty"></div>';
	if (!$page) { $page = 'home'; }
	$prefix = $page . '/';
	switch($page) {
		case 'browse':
			$nav = get_favorite_tags();
			break;
		case 'settings':
			$nav = array(
				'' => 'Social Settings',
				'change_password' => 'Change Password',
				'change_email' => 'Change Email',
				'delete' => 'Delete Account'
			);
			break;
		case 'about':
			$nav = array(
				'' => 'About ObsceneArt',
				'contact' => 'Contact Us'
			);
			break;
		default:
			return $output;
			break;
	}
	$output = "\t\t\t<nav id=\"sub\">\n";
	$output .= "\t\t\t\t<ul class=\"left\">\n";
	foreach($nav AS $url => $name) {
		$active = '';
		if ($subpage == $url) {
			$active = ' class="active"';
		}

		$output .= "\t\t\t\t\t<li$active><a href=\"$prefix$url\">$name</a></li>\n";
	}
	$output .= "\t\t\t\t</ul>\n";
	$output .= "\t\t\t</nav>\n";
	return $output;
}

function remove_timestamps($string) { # I need to learn backreferences...
	$string = preg_replace("/(^|\n)\((.*)\)\ /", "", $string);
	$string = preg_replace("/(^|\n)\[(.*)\]\ /", "", $string);
	return $string;
}

function line2break($string) {
	$string = str_replace("\r", "", $string);		#remove windows feed character
	return str_replace("\n", "<br />\n", $string);	#remove and replace standard \n newline
}

function rank_symbol($rank_code) {
	switch ($rank_code) {
		case OA_UNKNOWN:
			return '?';
		case OA_NORMAL:
			return '~';
		case OA_MODERATOR:
			return '=';
		case OA_ADMIN:
			return '!';
	}
}

function display_quote($quote, $logged_in = FALSE, $user_id = 0, $rank = 0) {
	if (isset($quote['language_alias']) && $quote['language_alias'] != 'text') {
		$language_alias = ' class="brush: ' . $quote['language_alias'] . ';"';
		$language_name = $quote['language'];
	} else {
		$language_alias = '';
		$language_name = 'Text';
	}
	if ($quote['id'] <= 580) {
		// Time to pay for my sins...
		$quote['quote'] = preg_replace('#<br\s*/?>\n#i', "\n", $quote['quote']);
		$quote['quote'] = preg_replace('#<br\s*/?>#i', "\n", $quote['quote']);
		$quote['quote'] = html_entity_decode($quote['quote']);
	}
	$title = '#' . $quote['id'];
	if ($quote['title']) {
		$title = $quote['title'];
	}
?>
<div class="single-quote<?php if ($quote['private']) echo " private"; ?>">
<h3>
	<a href="quotes/<?=$quote['id']?>"><?=$title?></a> [<?=$language_name?>] by <?=isset($quote['username']) ? "<a href=\"users/{$quote['username']}\">" . rank_symbol($quote['rank']) . "{$quote['username']}</a>" : 'Anonymous'; ?>
	<div class="right">Added on <?=date('M d, Y', strtotime($quote['added']))?></div>
</h3>
<div class="quote_text">
	<pre<?=$language_alias?>><?=htmlentities($quote['quote'])?></pre>
</div>
<?php
	if ($logged_in || (isset($quote['average_score']) && isset($quote['count_ratings']))) {
?>
<div class="quote_meta">
<?php if ($logged_in) {
	echo "Rate: ";
	for($i = 1; $i <= 10; $i++) {
		$c = '';
		if (isset($quote['current_user_score']) && $quote['current_user_score'] == $i) {
			$c = 'selected';
		}
		echo "<a href=\"#\" class=\"$c score-link\" data-score=\"$i\" data-quote=\"{$quote['id']}\">$i</a> ";

	}
}

if ( $logged_in && ( $user_id == $quote['user_id'] || $rank >= OA_MODERATOR ) ) {
	echo "| <a href='delete/{$quote['id']}' class='delete_link' onclick='return confirmQuoteDelete()'>Delete</a>";
}

if (isset($quote['average_score']) && isset($quote['count_ratings'])) { ?>
	<div class="right">Rated <span class="score"><?=sprintf("%01.1f", $quote['average_score'])?>/10</span> by <?=$quote['count_ratings']?> Users</div>
<?php } ?>
</div>
<?php
	}
?>
</div><!-- /.single-quote -->
<?php
}