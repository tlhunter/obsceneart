<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends App_Controller {

	function __construct() {
		parent::__construct();
		if (!$this->page_data['logged_in']) {
			redirect('home');
		}
		$this->page_data['title'] .= ' : Settings';
		$this->load->model('user_model');
	}

	function index() {
		$this->page_data['success'] = $this->session->flashdata('success');
		$this->page_data['submit_error'] = $this->session->flashdata('submit_error');
		$this->page_data['user'] = $this->user_model->select_single($this->tank_auth->get_user_id());
		$this->page_data['contents'] = $this->load->view('settings', $this->page_data, TRUE);
		$this->load->view('template', $this->page_data);
	}

	function save() {
		$data['aim'] = htmlentities($this->input->post('aim'));
		$data['yahoo'] = htmlentities($this->input->post('yahoo'));
		$data['website'] = htmlentities($this->input->post('website'));
		$result = $this->user_model->update_profile($this->tank_auth->get_user_id(), $data);
		if ($result) {
			$this->session->set_flashdata('success', TRUE);
		} else {
			$this->session->set_flashdata('submit_error', TRUE);
		}
		redirect('settings');
	}

	function delete($confirm = '') {
		if (!$confirm) {
			$this->page_data['contents'] = $this->load->view('delete-confirm', $this->page_data, TRUE);
			$this->load->view('template', $this->page_data);
		} else if ($confirm == 'nice') {

		} else if ($confirm == 'naughty') {

		}
	}

}
