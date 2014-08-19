<?php
/**
 * Use this file to configure your Quotes applications.
 * Put the hostnames that you want to configure independently in this switch statement.
 * You can move define statements below the case statements if you'd like to configure them separately.
 */
switch($_SERVER['SERVER_NAME']) {
	// Your local "development" server
	case 'localhost':
	case 'obsceneart.local':
		define('MYSQL_HOSTNAME', "localhost");
		define('MYSQL_USERNAME', "obsceneart");
		define('MYSQL_PASSWORD', "");
		define('MYSQL_DATABASE', "obsceneart");
		define('HOME_QUOTE_CACHE', 1);
		break;

	// Your live server
	case 'obsceneart.net':
	case 'www.obsceneart.net':
		define('MYSQL_HOSTNAME', "localhost");
		define('MYSQL_USERNAME', "obsceneart");
		define('MYSQL_PASSWORD', "");
		define('MYSQL_DATABASE', "obsceneart");
		define('HOME_QUOTE_CACHE', 60);
		break;

	// The hostname visiting this site isn't configured
	default:
		die("INVALID HOST");
}
define('QUOTES_PER_PAGE',	20);

define('TITLE_TEXT', "ObsceneArt");
define('HEADER_TEXT', "Obscene<span>Art</span>");
define('TAGLINE', "Funny Conversations, Code Samples, Everything!");
define('FOOTER_TEXT', "ObsceneArt Copyright &copy; 2007 - " . date('Y') . " <a href='http://thomashunter.name' target='_blank'>Thomas Hunter</a>. User-Submitted content belongs to original authors.");
define('ANALYTICS_CODE', 'UA-1577519-4');

// Set an array of tags to show on the top of the Browse page. Make sure they exist in the `tags` table first.
function get_favorite_tags() {
	return array(
		'instant-messages' => 'Instant Messages',
		'email' => 'Email',
		'translations' => 'Translations',
		'google-voice-transcripts' => 'Google Voice Transcripts',
		'forum-posts' => 'Forum Posts',
		'status-updates' => 'Status Updates',
		'mobile-texts' => 'Mobile Texts',
		'religion' => 'Religion',
		'dating-sites' => 'Dating Sites'
	);
}

// You probably don't want to change anything below this line
define('OA_UNKNOWN',		0);
define('OA_NORMAL',			1);
define('OA_MODERATOR',		2);
define('OA_ADMIN',			3);
