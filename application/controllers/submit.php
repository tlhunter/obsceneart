<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Submit extends App_Controller {

	function __construct() {
		parent::__construct();
		$this->page_data['title'] .= ' : Submit a Quote';
		$this->load->model('quote_model');
	}

	function index() {
		$this->page_data['new_id'] = $this->session->flashdata('new_id');
		$this->page_data['submit_error'] = $this->session->flashdata('submit_error');


		$this->load->model('language_model');
		$this->page_data['languages'] = $this->language_model->select_all();

		$this->page_data['contents'] = $this->load->view('submit', $this->page_data, TRUE);

		$this->load->view('template', $this->page_data);
	}

	function save() {
		if ($this->input->post('code') != '44') {
			redirect('');
		}

		$data['quote'] = $this->input->post('quote');
		$data['title'] = substr($this->input->post('title'), 0, 32);

		if ($this->tank_auth->is_logged_in()) {
			$data['user_id'] = $this->tank_auth->get_user_id();
		}

		$data['ip_address'] = $this->input->ip_address();
		$data['language_id'] = (int) $this->input->post('language');
		$data['private'] = !!$this->input->post('private');

		if (!$data['language_id'] || $data['language_id'] == 1) {
			$data['language_id'] = null;
		}

		$quote_id = $this->quote_model->insert($data, $this->input->post('remove_timestamps'));

		if ($quote_id) {
			$tags = $this->input->post('tags');
			if ($tags) {
				$tag_array = explode(',', $tags);
				foreach($tag_array AS $tag) {
					$this->quote_model->tag_a_quote($quote_id, trim($tag));
				}
			}
			$this->session->set_flashdata('new_id', $quote_id);
		} else {
			$this->session->set_flashdata('submit_error', TRUE);
		}
		redirect('submit');
	}

	function autocomplete($raw_phrase = '') {
		if (!$raw_phrase) {
			$raw_phrase = $this->input->post('term');
		}
		$phrases = explode(',', $raw_phrase);
		$phrase = trim(array_pop($phrases));
		$matching_tags = $this->quote_model->get_tags_like($phrase);
		echo json_encode($matching_tags);
	}
}
