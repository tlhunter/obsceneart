<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] =			'home';
$route['404_override'] =				'missing';

$route['signup'] =						"auth/register";
$route['login'] =						"auth/login";
$route['logout'] =						"auth/logout";
$route['users'] =						"members";
$route['settings/change_email'] =		"auth/change_email";
$route['settings/change_password'] =	"auth/change_password";
$route['users/(:any)'] =				"members/view/$1";
$route['users/(:any)/(:any)'] =			"members/view/$1/$2";
$route['quotes/(:num)'] =				"browse/view/$1";
$route['delete/(:num)'] =				"browse/delete/$1";
$route['quotes/(:num)/vote/(:num)'] =	"browse/vote/$1/$2";
$route['browse/(:any)'] =				"browse/category/$1";
$route['search/(:any)'] =				"search/query/$1";
