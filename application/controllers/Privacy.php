<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Privacy extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
	}

	public function index()
	{
	
		$this->load->view('privacy');
	}

}
