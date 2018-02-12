<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	private $data = array();

	function __construct( ) {

		parent::__construct();
		$this->data = Share::get_share();
		$this->load->model('login_model', 'login');

	}

	public function index()
	{
		$this->data['loginError'] = "";

		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('login' , $this->data );
		}
		else
		{

			if($account = $this->login->getAccount()){

				if($account['image_thumb']){
					$image = full_path_image($account['image_thumb'] , 'accounts');
					$account['image_thumb'] = $image['path'];

					if(!file_exists($image['absolute_path'])){
						$account['image_thumb'] = $this->config->base_url('/public/images/image-not-found.png');
					}

				}else{
					$account['image_thumb'] = $this->config->base_url('/public/images/image-not-found.png');
				}

				$this->session->set_userdata($account);

				if($account['account_type'] == SUPER_ADMIN){
					redirect('/app/dashboard', 'refresh');
				}else if($account['account_type'] == ADMIN){
					redirect('/app/accounts', 'refresh');
				}else if($account['account_type'] == MECHANIC){
					redirect('/app/reports/defect', 'refresh');
				}else if($account['account_type'] == CUSTOMER){
					redirect('/app/customer/jobs', 'refresh');
				}else if($account['account_type'] == OUTSOURCE){
					redirect('/app/customer/jobs', 'refresh');
				}else if($account['account_type'] == WAREHOUSE){
					redirect('/app/warehouse', 'refresh');
				}else{
					$this->session->sess_destroy();
					$this->data['loginError'] = "ERROR";
					$this->load->view('login' , $this->data );
				}

			}else{
				$this->data['loginError'] = "ERROR";
				$this->load->view('login' , $this->data );
			}
		}

	}

	public function logout(){
		$this->login->updateLastLogin($this->session->userdata('id'));

		$this->session->sess_destroy();

		$this->data['loginError'] = "SUCCESS";
		$this->load->view('login' , $this->data );
	}

	public function forgotPassword($link = false){
		$this->load->library('email');

		if($link){
			if($data = $this->login->resetPassword($link)){

				$this->email->from('no-reply@trackerteer.com', 'Transport App Team');
				$this->email->to($data->email);
				$this->email->set_mailtype("html");
				$this->email->subject('Forgotten password Reset');
				$this->email->message($this->load->view('email/resetpassword', $data , true));

				$this->email->send();

				redirect('/login?status=forgottenpasswordsuccess', 'refresh');

			}else{
				echo 'Sorry This link has been expired ';
			}
		}else{
			$this->data['message'] = "";

			if($this->input->post()){
				
				if($data = $this->login->getAccountByEmail($this->input->post("email", TRUE))){

					$token = $this->login->generateTokenForgottenPassword($data);

					$link = get_instance()->config->site_url("login/forgotPassword/".$token);

					$data->link = $link;

					$this->email->from('no-reply@trackerteer.com', 'Transport App Team');
					$this->email->to($this->input->post("email", TRUE));
					$this->email->set_mailtype("html");
					$this->email->subject('Forgotten password Reset');
					$this->email->message($this->load->view('email/forgotten', $data , true));

					$this->email->send();

					redirect('/login?status=forgottenpasswordsend', 'refresh');

				}else{

					$this->data['message'] = "Email Address Not Exist";
				}

				
			}

			$this->load->view('forgotpassword' , $this->data );
		}
	}


	public function register(){

		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

		$this->form_validation->set_rules('company', 'Company Name', 'trim|required|is_unique[company.company_name]');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[accounts.username]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('confirm', 'Password Confirmation', 'trim|required|matches[password]');

		$this->form_validation->set_rules('terms', 'Terms', 'required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[accounts.email]');

		if ($this->form_validation->run() != FALSE)
		{
			$this->login->registerCompany();
			redirect('/login?status=success', 'refresh');
		}

		$this->load->view("register" , $this->data);
	}

}
