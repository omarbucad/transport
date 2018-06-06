<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs extends CI_Controller {

	function __construct( ) {
		parent::__construct();
		$this->output->set_header('Access-Control-Allow-Origin: *');

		// $a = $this->input->request_headers();

		// if($a["SERVER_TOKEN"] != SERVER_TOKEN){
		// 	die(header('HTTP/1.1 401 Unauthorized', true, 401));
		// }
		
		$this->load->model('vehicle_model', 'vehicle');
		$this->load->model('trailer_model', 'trailer');
		$this->load->model('report_model', 'report');
		$this->load->model('account_model' , 'account');

	}

	public function beginDrivingTime(){
		if($this->input->post("id")){
			$this->db->where("jobs_id" , $this->input->post("id"))->where("begin_driving_time" , 0)->update("jobs" , ["begin_driving_time" => time()]);
		}
	}

	public function getDriverJobs(){
		if($driver_id = $this->input->post("id")){
			echo json_encode($this->report->getDriverJobs($driver_id));
		}
	}

	public function insertJobDate(){
		if($response = $this->report->insertJobDate()){
			echo json_encode(array("status" => true , "message" => "Success" , "response" => $response));
		}else{
			echo json_encode(array("status" => false , "message" => "Error in the Server Please Try Again Later"));
		}
	}

	public function uploadSignature(){
		if($this->input->post()){
			echo $this->report->uploadSignature();
		}
	}

	public function jobAcceptjob(){
		if($this->input->post()){
			$this->db->where("jobs_id" , $this->input->post("job_id"))->update("jobs" , ["driver_status" => $this->input->post("status")]);
			$this->db->insert("jobs_driver_accepted" , array(
				"jobs_id" => $this->input->post("job_id") ,
				"driver_id" => $this->input->post("driver_id") ,
				"status" => $this->input->post("status")
				));
			echo json_encode(["success" => 1 , "message" => "Success"]);
		}
	}
	public function test(){
		echo print_r_die($this->report->getDriverJobs(13));
	}
}
