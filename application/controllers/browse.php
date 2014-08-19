<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Browse extends App_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('quote_model');
	}

	function index() {
		$this->page_data['title'] .= ' : Browse Tags';
		$this->page_data['tags'] = $this->quote_model->get_all_tags();
		$this->page_data['contents'] = $this->load->view('browse', $this->page_data, TRUE);
		$this->load->view('template', $this->page_data);
	}

	function category($tag_slug, $first_record = 0) {
		$this->enableSyntax('*'); // TODO: Get list of all languages used on this particular page view

		$tag = $this->quote_model->get_tag_by_slug($tag_slug);
		if ($tag) {
			$this->page_data['tag'] = $tag;
			$this->page_data['title'] .= ' : ' . $tag['name'];

			$first_record += 0;

			$config['base_url'] = base_url() . 'browse/' . $tag['slug'];
			$config['total_rows'] = $this->quote_model->count_by_tag($tag['id']);

			$this->pagination->initialize($config);
			$this->page_data['pagination'] = $this->pagination->create_links();

			$this->page_data['quotes'] = $this->quote_model->select_by_tag($tag['id'], $first_record);
			$this->page_data['contents'] = $this->load->view('quotes/tags', $this->page_data, TRUE);
		} else {
			$this->output->set_status_header('404');
			$this->page_data['contents'] = $this->load->view('missing', $this->page_data, TRUE);
		}

		$this->load->view('template', $this->page_data);
	}

	function view($quote_id = 0) {
		$quote_id += 0;
		$this->page_data['quote'] = $this->quote_model->select_single($quote_id);
		if ($this->page_data['quote']) {
			$this->enableSyntax($this->page_data['quote']['language_alias']);
			$this->page_data['title'] .= " : " . (!empty($this->page_data['quote']['title']) ? $this->page_data['quote']['title'] : "Quote #$quote_id");
			$this->page_data['tags'] = $this->quote_model->get_tags_by_quote($quote_id);
			$this->page_data['contents'] = $this->load->view('quotes/view', $this->page_data, TRUE);
		} else {
			$this->output->set_status_header('404');
			$this->page_data['contents'] = $this->load->view('missing', $this->page_data, TRUE);
		}
		$this->load->view('template', $this->page_data);
	}

	function vote($quote_id = 0, $score = 0) {
		$quote_id = (int) $quote_id;
		$score = (int) $score;
		if ($score < 1 || $score > 10) {
			die("Score must be between 1 and 10.");
		} else if (!$this->tank_auth->is_logged_in()) {
			die("Must be logged in to rate.");
		} else {
			$this->quote_model->rate($quote_id, $score, $this->page_data['current_user_id']);
		}
		# user REPLACE MySQL command
	}

	function delete($quote_id = 0) {
		if ($quote_id && $this->page_data['logged_in']) {
			$quote = $this->quote_model->select_single((int) $quote_id);
			if ($quote) {
				if ($quote['user_id'] == $this->page_data['current_user_id'] || $this->page_data['current_user_rank'] >= OA_MODERATOR) {
					if ($this->quote_model->delete((int) $quote_id)) {
						redirect($_SERVER['HTTP_REFERER']);
					} else {
						echo "ERROR DELETING QUOTE!";
					}
				}
			} else {
				echo "THIS QUOTE DOESN'T EXIST!";
			}
		} else {
			echo "NO QUOTE TO DELETE!";
		}
	}

}
