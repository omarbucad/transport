<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
		$this->data = Share::get_share();

		if($this->session->userdata("account_type") != 'SUPER ADMIN'){
			redirect('/app/accounts', 'refresh');
		}

		$this->load->model('company_model', 'company');
	}

	public function index(){
		$this->data['page'] = "page/company/view";
		$this->data['result'] = $this->company->getStore();
        
		$this->load->view('master' , $this->data );
	}


	public function add(){
		
		$this->form_validation->set_error_delimiters('<div class="text-danger bg-danger">', '</div>');
		
		$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['page'] = "page/company/add";
		}
		else
		{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'insert';
			$this->data['last_id'] = $this->company->addStore();
			$this->data['page'] = "page/company/success";
		}
		
        
		$this->load->view('master' , $this->data );
	}

	public function remove(){
		if($this->input->post()){
			if($this->company->removeStore($this->input->post("id", TRUE))){
				echo json_encode(["status" => true]);
			}else{
				echo json_encode(["status" => false]);
			}
		}else{
			redirect('/company', 'refresh');
		}
	}

	public function update($id){
		

		$this->form_validation->set_error_delimiters('<div class="bg-danger text-danger">', '</div>');
		
		$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['page'] = "page/company/update";
			$this->data['result'] = $this->company->getStoreById($id);
		}
		else
		{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'update';
			$this->data['last_id'] = $this->company->updateStore();
			$this->data['page'] = "page/company/success";
		}
		
		$this->load->view('master' , $this->data );
	}
}
