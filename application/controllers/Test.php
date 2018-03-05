<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();

        $this->load->model('dashboard_model', 'dashboard');
	}

	public function index(){
       print_r_die($this->dashboard->getAdvisory());
	}


}
