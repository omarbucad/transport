<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
		
		$this->output->set_header('Access-Control-Allow-Origin: *');

		$this->load->model('login_model', 'login');
		$this->load->model('vehicle_model', 'vehicle');
		$this->load->model('trailer_model', 'trailer');
		$this->load->model('report_model', 'report');
		$this->load->model('account_model' , 'account');

	}

	public function login(){
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');

		if ($this->form_validation->run() == FALSE){
			echo json_encode(['success' => false , 'message' => 'Please input all fields']);
		}
		else
		{
			if($account = $this->login->getAccount()){

				$this->login->updateLastLogin($account['id']);
				
				echo json_encode(['success' => true , 'data' => $account , "message" => "Logging in ".$account["name"] ]);

			}else{
				echo json_encode(['success' => false , 'message' => 'Invalid Username / Password']);
			}
		}
	}

	public function login_face(){
		$account = $this->login->login_face();

		if($account["status"]){
			echo json_encode(['success' => true , 'data' => $account["data"] , "message" => "Logging in ".$account["data"]["name"]]);
		}else{
			echo json_encode(['success' => false , 'message' => $account["message"]]);
		}
	}


	public function generateId(){

		$last_id = $this->report->generateId();
		
		if($last_id){
			echo json_encode(['id' => $last_id , "success" => true]);
		}else{
			echo json_encode(["success" => false]);
		}
		
	}

	public function insertChecklist(){
		$this->report->insertChecklist();
	}

	public function insertReport(){
		$this->report->insertReport();
	}
	public function insertDefectReportWithImage(){
		//echo $this->report->do_upload($this->input->post('report_id'));
		$last_id = $this->report->uploadImage();
		echo json_encode(["id" => $last_id]);
	}

	public function endReport(){
		$this->report->endReport();
	}

	public function removeReport(){
		$this->report->removeReport();
	}

	public function getProfile(){
		$data = $this->login->getAccount($this->input->post("id", TRUE));
		echo json_encode($data);
	}

	public function updateProfile(){
		
		if($this->input->post()){

			if($this->input->post("email", TRUE) != $this->input->post("OldEmail", TRUE)){
				$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[accounts.email]');
			}else{
				$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
			}

			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(["success" => false , "message" => "This Email Address is not Available , Please try a new one"]);
			}
			else
			{
				$id = $this->account->updateAccount(true);

				if($id == $this->input->post("id", TRUE)){
					echo json_encode(["success" => true]);
				}else{
					echo json_encode(["success" => false , "message" => "Something wrong on the server , Please Try Again Later"]);
				}
			}
		}
	}

	public function updatePassword(){
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('confirm', 'Password Confirmation', 'trim|required|matches[password]');

		if ($this->form_validation->run() == FALSE)
		{
			echo json_encode(["success" => false , "message" => "Please Try Again Later"]);
		}
		else
		{
			$this->account->changepassword($this->input->post('id', TRUE));
			echo json_encode(["success" => true]);
		}
	}

	public function getMyReport(){
		echo json_encode($this->report->getMyReport(false , 'j.delivery_time'));
	}

	public function getMyReportById(){
		$id = $this->input->post('id', TRUE);

		echo json_encode($this->report->getMyReportById($id));
	}

	public function getReportById(){
		$id = $this->input->post('id', TRUE);

		echo json_encode($this->report->getReportById($id , FALSE));
	}

	public function removeImage(){
		if($this->input->post("id", TRUE)){
			$id = $this->input->post("id", TRUE);
			$this->report->removeImage($id);
		}
	}

	public function getDriverJobs(){
		if($driver_id = $this->input->post("id", TRUE)){
			echo json_encode($this->report->getDriverJobs($driver_id));
		}
	}

	public function getSingleJobs(){
		if($this->input->post("id", TRUE)){

			$report = $this->db->select("id")->where("job_id" , $this->input->post("id", TRUE) )->where("report_change" , 0)->get("report")->row();

			$report_id = ($report) ? $report->id : 0;
			
			$jobs = $this->report->getSingleJobs($this->input->post("id", TRUE));

			// if(!$report){
			// 	if($response = $this->report->checkReportCount($jobs->vehicle_number , $jobs->driver_id)){
			// 		$report_id = $this->report->CloneReport($response , $this->input->post("id"));
			// 	}
			// }

			$arr = array(
				"jobs" => $jobs,
				"report" => ($report_id) ? $this->report->getReportById($report_id , FALSE) : new stdClass() ,
				);
			
			echo json_encode($arr);
		}
	}

	public function uploadSignature(){
		if($this->input->post()){
			echo $this->report->uploadSignature();
		}
	}

	public function insertJobDate(){
		if($response = $this->report->insertJobDate()){
			echo json_encode(array("status" => true , "message" => "Success" , "response" => $response));
		}else{
			echo json_encode(array("status" => false , "message" => "Error in the Server Please Try Again Later"));
		}
	}

	public function test(){

		$report = $this->db->select("id")->where("job_id" , 3 )->where("report_change" , 0)->get("report")->row();

		$report_id = ($report) ? $report->id : 0;

		$jobs = $this->report->getSingleJobs(3);

		if(!$report){
			if($response = $this->report->checkReportCount($jobs->vehicle_number , $jobs->driver_id)){
				$report_id = $this->report->CloneReport($response , 3);
			}
		}

		$arr = array(
			"jobs" => $jobs,
			"report" => ($report_id) ? $this->report->getReportById($report_id , FALSE) : new stdClass() 
			);

		echo json_encode($arr);
	}
}
