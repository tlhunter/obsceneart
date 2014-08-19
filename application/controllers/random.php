<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Random extends App_Controller {

	function __construct() {
		parent::__construct();
		$this->page_data['title'] .= ' : Random Quotes';
		$this->load->model('quote_model');
	}

	function index() {
		$this->enableSyntax('*'); // TODO: Get list of all languages used on this particular page view

		$this->page_data['quotes'] = $this->quote_model->select_random();
		$this->page_data['contents'] = $this->load->view('quotes/random', $this->page_data, TRUE);

		$this->load->view('template', $this->page_data);
	}
}
