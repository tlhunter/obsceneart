<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class App_Controller extends CI_Controller {
	protected $page_data;

	protected $languages = array(
		'text' 				=> 'shBrushPlain.js',
		'applescript' 		=> 'shBrushAppleScript.js',
		'actionscript3' 	=> 'shBrushAS3.js',
		'shell' 			=> 'shBrushBash.js',
		'coldfusion' 		=> 'shBrushColdFusion.js',
		'c-sharp' 			=> 'shBrushCSharp.js',
		'cpp' 				=> 'shBrushCpp.js',
		'css' 				=> 'shBrushCss.js',
		'delphi' 			=> 'shBrushDelphi.js',
		'diff' 				=> 'shBrushDiff.js',
		'erlang' 			=> 'shBrushErlang.js',
		'groovy' 			=> 'shBrushGroovy.js',
		'javascript' 		=> 'shBrushJScript.js',
		'java' 				=> 'shBrushJava.js',
		'javafx' 			=> 'shBrushJavaFX.js',
		'perl' 				=> 'shBrushPerl.js',
		'php' 				=> 'shBrushPhp.js',
		'powershell' 		=> 'shBrushPowerShell.js',
		'python' 			=> 'shBrushPython.js',
		'ruby' 				=> 'shBrushRuby.js',
		'sass' 				=> 'shBrushSass.js',
		'scala' 			=> 'shBrushScala.js',
		'sql' 				=> 'shBrushSql.js',
		'vb' 				=> 'shBrushVb.js',
		'xml' 				=> 'shBrushXml.js'
	);

	function __construct() {
		$this->deny = array(
			"79.142.79.15",
			"111.235.200.187",
			"196.45.142.5",
			);
		if (in_array ($_SERVER['REMOTE_ADDR'], $this->deny)) {
			header("location: http://www.google.com/");
			exit();
		}
		parent::__construct();
		$this->page_data['logged_in'] = $this->tank_auth->is_logged_in();

		if ($this->page_data['logged_in']) {
			$this->page_data['current_username'] = $this->tank_auth->get_username();
			$this->page_data['current_user_id'] = $this->tank_auth->get_user_id();
			$this->page_data['current_user_rank'] = $this->session->userdata('rank');
		} else {
			$this->page_data['current_username'] = NULL;
			$this->page_data['current_user_id'] = FALSE;
			$this->page_data['current_user_rank'] = 0;
		}

		$this->page_data['sidebar_quotes'] = $this->quote_model->get_recent_titles(20);

		$this->page_data['syntax'] = '';

		$this->page_data['nav_main'] = nav_main($this->page_data['logged_in'], $this->uri->segment(1));
		$this->page_data['nav_sub'] = nav_sub($this->page_data['logged_in'], $this->uri->segment(1), $this->uri->segment(2));

		$this->page_data['analytics'] = $this->load->view('analytics', null, TRUE);
		$this->page_data['sidebar'] = $this->load->view('sidebar', $this->page_data, TRUE);

		$this->page_data['title'] = TITLE_TEXT;
	}

	/**
	 * Provide an array of the languages needed to be loaded, or a single string for one language, or a * for all languages
	 * @param $syntax array|string
	 */
	function enableSyntax($syntax = '*') {
		if (!$syntax) return;
		$syntax_view_data = array();

		if ($syntax === '*') {
			$syntax_view_data['languages'] = $this->languages;
		} else if (is_array($syntax)) {
			foreach($syntax AS $syn) {
				$syntax_view_data['languages'][$syn] = $this->languages[$syn];
			}
		} else if (is_string($syntax)) {
			$syntax_view_data['languages'][$syntax] = $this->languages[$syntax];
		}

		$this->page_data['syntax'] = $this->load->view('syntax', $syntax_view_data, TRUE);
	}
}