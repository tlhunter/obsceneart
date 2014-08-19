<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends App_Controller {

	function __construct() {
		parent::__construct();
		$this->page_data['title'] .= ' : Search Quotes';
		$this->load->model('quote_model');
	}

	function index() {
		redirect('browse');
	}

	function query($query) {
		$query = urldecode($query);
		$this->enableSyntax('*'); // TODO: Get list of all languages used on this particular page view

		$this->page_data['query'] = htmlentities($query);
		if ($query) {
			$this->page_data['quotes'] = $this->quote_model->select_by_query($query, 0);
			$this->page_data['contents'] = $this->load->view('quotes/search', $this->page_data, TRUE);
		} else {
			$this->page_data['contents'] = '<div class="warning">Did not specify a search term</div>';
		}
		$this->load->view('template', $this->page_data);
	}
}
