<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outsource extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
		$this->data = Share::get_share();
		$this->load->model("Outsource_model" , "outsource");
	}

	public function index(){
		$this->data['page'] = "page/outsource/view";
		$this->data['result'] = $this->outsource->getAccount();
		$this->load->view('master' , $this->data );
	}

	public function add(){
		$this->form_validation->set_error_delimiters('<div class="text-danger bg-danger">', '</div>');
		$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required');

		if ($this->form_validation->run() == FALSE){
			$this->data['page'] = "page/outsource/add";
		}else{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'insert';
			$this->data['last_id'] = $this->outsource->add();
			$this->data['page'] = "page/outsource/success";
		}
		$this->load->view('master' , $this->data );
	}

	public function update($outsource_id){
		$this->form_validation->set_error_delimiters('<div class="bg-danger text-danger">', '</div>');

		$this->form_validation->set_rules('company_name', 'Company Name', 'required');
		$this->form_validation->set_rules('registration_number', 'Registration Number', 'required');
		$this->form_validation->set_rules('vat_number', 'VAT Number', 'required');
		$this->form_validation->set_rules('billing_address', 'Billing Address', 'required');


		if ($this->form_validation->run() == FALSE){
			$this->data['page'] = "page/outsource/update";
			$this->data['result'] = $this->outsource->getOutsourceById($outsource_id);
		}else{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'update';
			$this->data['last_id'] = $this->outsource->update();
			$this->data['page'] = "page/outsource/success";
		}
		
		$this->load->view('master' , $this->data);
	}
}
