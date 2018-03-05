<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
		$this->data = Share::get_share();

		$this->load->model('account_model', 'account');
		$this->load->model('report_model', 'report');
		$this->load->model('company_model', 'company');

		if(!$this->session->userdata("id")){
			redirect('/login', 'refresh');
		}
	}

	public function index()
	{
		$this->noCustomer();

		$this->data['page'] = "page/accounts/view";
		$this->data['result'] = $this->account->getAccount();
		$this->load->view('master' , $this->data );
	}

	public function add(){
		
		$this->noCustomer();

		$this->data['page'] = "page/accounts/add";
		$this->form_validation->set_error_delimiters('<div class="bg-danger text-danger">', '</div>');
	
		$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[accounts.username]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('confirm', 'Password Confirmation', 'trim|required|matches[password]');

		if($this->input->post('account_type', TRUE) != 'SUPER ADMIN'){
			$this->form_validation->set_rules('store_id[]', 'Company', 'trim|required');
		}
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[accounts.email]');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->data['store_list'] = $this->company->getSelectStore();
			$this->data['page'] = "page/accounts/add";
		}
		else
		{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'insert';
			$this->data['last_id'] = $this->account->insertAccount();
			$this->data['page'] = "page/accounts/success";
		}

		$this->load->view('master' , $this->data);
	}

	public function removeAccount(){
		if($this->input->post()){
			if($this->account->removeAccount($this->input->post("id", TRUE))){
				echo json_encode(["status" => 1]);
			}else{
				echo json_encode(["status" => 0]);
			}
		}else{
			redirect('/accounts', 'refresh');
		}
	}

	public function updateAccount($id){
		
		$this->noCustomer();

		$this->form_validation->set_error_delimiters('<div class="bg-danger text-danger">', '</div>');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		
		if($this->input->post('oldEmail', TRUE) == $this->input->post('email', TRUE)){
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
		}else{
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[accounts.email]');
		}

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['page'] = "page/accounts/update";
			$this->data['result'] = $this->account->getAccountById($id , true);
		}
		else
		{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'update';
			$this->data['last_id'] = $this->account->updateAccount();
			$this->data['page'] = "page/accounts/success";
		}

		$this->load->view('master' , $this->data);
	}

	public function profile($id){
		$profile = $this->account->getAccountById($id);

		if(!$profile){
			$this->load->view('errors/html/error_404');
		}else{
			$this->data['page'] = "page/accounts/profile";
			$this->data['profile'] = $profile;
			$this->data['report_count'] = $this->report->getCountReport($this->session->userdata('id'));
			$this->load->view('master' , $this->data);
		}
	}

	public function changepassword(){

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('confirm', 'Password Confirmation', 'trim|required|matches[password]');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['page'] = "page/accounts/changepassword";
			$this->data['change_status'] = false;
		}
		else
		{

			$this->account->changepassword();
			$this->data['page'] = "page/accounts/changepassword";
			$this->data['change_status'] = true;
		}

		$this->load->view('master' , $this->data);
	}

	public function change_password(){
		if($this->input->post()){
			$this->account->changepassword($this->input->post("id", TRUE));
		}
	}

	private function noCustomer(){
		if($this->session->userdata("account_type") == CUSTOMER OR $this->session->userdata("account_type") == OUTSOURCE OR $this->session->userdata("account_type") == WAREHOUSE OR $this->session->userdata("account_type") == MECHANIC){
			redirect('/app/accounts/profile/'.$this->session->userdata("id"), 'refresh');
		}
	}
}
