<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Missing extends App_Controller {

	function __construct() {
		parent::__construct();
		$this->page_data['logged_in'] = $this->tank_auth->is_logged_in();
		$this->page_data['nav_main'] = nav_main($this->page_data['logged_in'], $this->uri->segment(1));
		$this->page_data['nav_sub'] = nav_sub($this->page_data['logged_in'], $this->uri->segment(1), $this->uri->segment(2));
		$this->page_data['title'] = 'ObsceneArt : Submit a Quote';
	}

	function index() {
		$this->output->set_status_header('404');
		$this->page_data['contents'] = $this->load->view('missing', $this->page_data, TRUE);
		$this->load->view('template', $this->page_data);
	}

	function save() {

	}
}
