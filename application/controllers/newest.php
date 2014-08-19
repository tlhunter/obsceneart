<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newest extends App_Controller {

	function __construct() {
		parent::__construct();
		$this->page_data['title'] .= ' : Newest Quotes';
		$this->load->model('quote_model');
		$this->load->library('pagination');
	}

	function index() {
		redirect('newest/page');
	}

	function page($first_record = 0) {
		$first_record += 0;

		$this->enableSyntax('*'); // TODO: Get list of all languages used on this particular page view

		$config['base_url'] = base_url() . 'newest/page';
		$config['total_rows'] = $this->quote_model->count();

		$this->pagination->initialize($config);
		$this->page_data['pagination'] = $this->pagination->create_links();

		$this->page_data['quotes'] = $this->quote_model->select_newest($first_record);
		$this->page_data['contents'] = $this->load->view('quotes/newest', $this->page_data, TRUE);

		$this->load->view('template', $this->page_data);
	}
}
