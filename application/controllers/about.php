<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends App_Controller {

	function __construct() {
		parent::__construct();
		$this->page_data['title'] .= ' : About';
	}

	function index() {
		$this->page_data['contents'] = $this->load->view('about', $this->page_data, TRUE);
		$this->load->view('template', $this->page_data);
	}

	function contact() {
		$this->page_data['message'] = $this->session->flashdata('message');
		$this->page_data['contents'] = $this->load->view('contact', $this->page_data, TRUE);
		$this->load->view('template', $this->page_data);
	}

	function contact_execute() {
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$subject = $this->input->post('subject');
		$message = $this->input->post('message');

		if (!$name || !$email || !$subject || !$message) {
			die("ERROR SENDING EMAIL.\n");
		}

		$this->load->library('email');
		$this->email->from($email, $name);
		$this->email->to('tlhunter+obsceneart@gmail.com');
		$this->email->subject($subject);
		$this->email->message($message);

		if ($this->email->send()) {
			$this->session->set_flashdata('message', 'Your email has been sent.');
			redirect('about/contact');
		} else {
			die( "ERROR SENDING EMAIL.\n");
		}
	}
}
