<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mechanic extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
		$this->data = Share::get_share();

		$this->load->model('vehicle_model' , 'vehicle');
		$this->load->model('trailer_model' , 'trailer');
		$this->load->model('report_model'  , 'report');
		$this->load->model('account_model' , 'account');
		$this->load->model('mechanic_model', 'mechanic');

		$this->data['trailer_number_list'] = $this->trailer->getTrailerNumber($this->session->userdata('id'));
		$this->data['vehicle_list'] = $this->vehicle->getVehicleNumber($this->session->userdata('id'));
		$this->data['driver_list'] = $this->account->getAccountList(true);
	}

	public function index(){
		$this->data['page'] = "page/mechanic/checklist";
		$this->data['result'] = $this->mechanic->get_checklist();
		$this->data['get_form'] = basename($_SERVER['REQUEST_URI']);
		
		$this->load->view('master' , $this->data );
		
	}

}
