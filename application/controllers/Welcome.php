<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
	}

	public function index()
	{
		redirect('/login', 'refresh');
	}
    public function not_found()
	{
		$this->load->view('errors/html/error_404');
	}
	public function browser_not_supported(){
		$this->load->view("errors/html/error_browser_not_supported");
	}
	public function maintenance(){
		$this->load->view("errors/html/maintenance");
	}
}
