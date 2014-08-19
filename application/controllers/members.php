<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Members extends App_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		$this->load->library('pagination');
		$this->load->model('quote_model');
	}

	function index() {
		$this->page_data['title'] .= " : Registered Users";
		$this->page_data['users'] = $this->user_model->select_all();
		$this->page_data['contents'] = $this->load->view('users', $this->page_data, TRUE);
		$this->load->view('template', $this->page_data);
	}

	function view($username = '', $method = 'view', $first_record = 0) { #popular, latest, random, view
		$this->page_data['user'] = $this->user_model->select_single_by_username($username);
		$this->page_data['title'] .= " : User $username";
		if (!$this->page_data['user']) {
			$this->output->set_status_header('404');
			$this->page_data['contents'] = $this->load->view('missing', $this->page_data, TRUE);
		} else if ($method == 'view') {
			$this->page_data['user'] = $this->user_model->select_single_by_username($username);
			$this->page_data['contents'] = $this->load->view('user-view', $this->page_data, TRUE);
		} else if ($method == 'random') {
			$this->enableSyntax('*'); // TODO: Get list of all languages used on this particular page view

			$this->load->model('quote_model');
			$this->page_data['quotes'] = $this->quote_model->select_by_user_random($this->page_data['user']['id']);
			$this->page_data['contents'] = $this->load->view('quotes/user/random', $this->page_data, TRUE);
		} else if ($method == 'popular') {
			$this->enableSyntax('*'); // TODO: Get list of all languages used on this particular page view

			$config['base_url'] = base_url() . 'users/' . $username . '/popular';
			$config['total_rows'] = $this->quote_model->count_by_user($this->page_data['user']['id']);
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$this->page_data['pagination'] = $this->pagination->create_links();

			$this->page_data['quotes'] = $this->quote_model->select_by_user_popular($this->page_data['user']['id'], $first_record);
			$this->page_data['contents'] = $this->load->view('quotes/user/popular', $this->page_data, TRUE);
		} else if ($method == 'newest') {
			$this->enableSyntax('*'); // TODO: Get list of all languages used on this particular page view

			$config['base_url'] = base_url() . 'users/' . $username . '/newest';
			$config['total_rows'] = $this->quote_model->count_by_user($this->page_data['user']['id']);
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$this->page_data['pagination'] = $this->pagination->create_links();

			$this->page_data['quotes'] = $this->quote_model->select_by_user_newest($this->page_data['user']['id'], $first_record);
			$this->page_data['contents'] = $this->load->view('quotes/user/newest', $this->page_data, TRUE);
		}
		$this->load->view('template', $this->page_data);
	}

}
