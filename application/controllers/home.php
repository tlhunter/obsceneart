<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends App_Controller {

	function __construct() {
		parent::__construct();
		$this->page_data['title'] .= ' : Home';
		$this->load->model('quote_model');
	}

	function index() {
		// Homepage quotes must be rated by 2 people, be at least a 6 average, and resets every 60 minutes.
		$this->page_data['quote'] = $this->quote_model->select_homepage(2, 6, HOME_QUOTE_CACHE);

		if ($this->page_data['quote']['language_alias']) {
			$this->enableSyntax($this->page_data['quote']['language_alias']);
		}

		$this->page_data['contents'] = $this->load->view('home', $this->page_data, TRUE);
		$this->load->view('template', $this->page_data);
	}
}
