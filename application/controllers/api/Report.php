<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	function __construct( ) {
		parent::__construct();
		$this->output->set_header('Access-Control-Allow-Origin: *');

		// $a = $this->input->request_headers();

		// if($a["SERVER_TOKEN"] != SERVER_TOKEN){
		// 	die(header('HTTP/1.1 401 Unauthorized', true, 401));
		// }

		$this->load->model('login_model', 'login');
		$this->load->model('report_model', 'report');
		$this->load->model('vehicle_model', 'vehicle');
		$this->load->model('trailer_model', 'trailer');
	}
	public function generateId(){

		$last_id = $this->report->first();
		
		if($last_id){
			echo json_encode(['id' => $last_id , "success" => true]);
		}else{
			echo json_encode(["success" => false]);
		}
		
	}
	public function insertChecklist(){
		$this->report->second();
	}
	public function insertReport(){
		$this->report->third();
	}

	public function insertDefectReportWithImage(){
		$last_id = $this->report->uploadImage();
		echo json_encode(["id" => $last_id]);
	}

	public function removeImage(){
		if($this->input->post("id")){
			$id = $this->input->post("id");
			$this->report->removeImage($id);
		}
	}

	public function getReport(){
		echo json_encode($this->report->getMyReport(false , 'j.delivery_time'));
	}

	public function fixedVehicle(){

		$driver_id = $this->input->post("driver_id");
		$report_id = $this->input->post("report_id");
		$comment = $this->input->post("comment");
		$job_id = $this->input->post("job_id");

		if($this->input->post()){
			$arr = array(
		        "report_id" => $report_id,
		        "status" => 3,
		        "account_id" => $driver_id,
		        "comment" => $comment,
		        "created" => time()
		        );


      		$this->db->insert('report_status' , $arr);

      		$last_id = $this->db->insert_id();

      		$this->db->where("id" , $report_id)->update('report' , ["status_id" => $last_id]);

			//$this->db->where("jobs_id" , $job_id)->update("jobs" , ["begin_driving_time" => time() ]);
		}
	}

	
	public function getChecklist(){
		echo json_encode($this->report->getChecklist());
	}
}
