<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
		$this->data = Share::get_share();

		if(!$this->session->userdata("id")){
			redirect('/login', 'refresh');
		}

		$this->load->model('vehicle_model', 'vehicle');
		$this->load->model('trailer_model', 'trailer');
		$this->load->model('report_model', 'report');
		$this->load->model('account_model', 'account');

		$this->data['trailer_number_list'] = $this->trailer->getTrailerNumber($this->session->userdata('id'));
		$this->data['vehicle_list'] = $this->vehicle->getVehicleNumber($this->session->userdata('id'));
		$this->data['driver_list'] = $this->account->getAccountList(true);

		$this->data['totalfixed'] = $this->report->getall_fixed();
		$this->data['totalundermaintenance'] = $this->report->getall_undermaintenance();
	}

	public function index(){
		redirect('/reports/defect', 'refresh');
	}

	public function defect(){
		$this->data['page'] = "page/report/defect";
		$this->data['result'] = $this->report->getDefectReportList();
		$this->data['get_form'] = basename($_SERVER['REQUEST_URI']);
		
		$this->load->view('master' , $this->data );
	}

	public function daily(){
		//print_r_die($this->report->getDailyReportList());
		$this->data['page'] = "page/report/daily";
		$this->data['result'] = $this->report->getDailyReportList();
		$this->data['get_form'] = basename($_SERVER['REQUEST_URI']);
	
		$this->load->view('master' , $this->data );
	}

	public function getReport($id){

		//$this->data['page'] = "page/report/view";
		$data['result'] = $this->report->getReportById($id);
		$this->data['html'] = $this->load->view('page/report/view' , $data , true );
		$this->data["next"] = $this->report->getChecklistReportList("NEXT" , $id);
		$this->data["prev"] = $this->report->getChecklistReportList("PREV" , $id);
		
		echo json_encode($this->data);
	}

	public function vehicles($id = false){
		$id =  urldecode($id);

		if($this->input->post()){
			$id = $this->input->post("id", TRUE);
			$this->data['result'] = $this->report->getVehicleReport($id);
			echo $this->load->view("page/report/ajax" , $this->data , TRUE);
			die();
		}
		$this->data['result'] = $this->report->getVehicleReport($id);
		$this->data['result_count'] = $this->report->getCountReportByVehicle($id);
		$this->data['page'] = "page/report/vehicles";
		$this->load->view('master' , $this->data );
	}

	public function getReportById($id , $from_job = false){
		if($from_job){

			$report_id = $this->db->where("job_id" , $id)->get("report_jobs")->row();

			if($report_id){
				echo json_encode($this->report->getReportById($report_id->report_id));
			}

		}else{

			echo json_encode($this->report->getReportById($id));
		}
	}

	public function update(){
		if($this->input->post()){
			if($this->report->updateReport()){
				
				if($this->input->post("from", TRUE) == "jobs"){

					$result = $this->customer->getJobsList(false , false , false , false , $this->input->post("job_id", TRUE));
			    	$select = $this->customer->getSelectData();
			    	$html = $this->load->view("page/customer/ajax/ajax_admin" , [ "result" => $result , "select" => $select] , TRUE);

			    	$arr['html'] = $html;

				}else{

					$arr = array(
						"status" => report_type($this->input->post("status", TRUE)),
						"report_id" => $this->input->post("id", TRUE),
						"fixed_by" => $this->session->userdata("name").' '.$this->session->userdata("surname")
						);
					
				}

				echo json_encode($arr);
			}
		}
	}

	public function checklist(){
		$this->data['page'] = "page/report/checklist";
		$this->data['result'] = $this->report->getChecklistReportList();
		$this->data['get_form'] = basename($_SERVER['REQUEST_URI']);
		$this->load->view('master' , $this->data );
	}

	public function printReport($id){

		if($ids = $this->input->get("id")){
			foreach($ids as $row){

				$this->data['result'] = $this->report->getReportById($row);
		
				echo $this->load->view("page/report/print" , $this->data , true);
			
			}
			die();
		}else{
			$this->data['result'] = $this->report->getReportById($id);
		
			echo $this->load->view("page/report/print" , $this->data , true);
			die();
		}

		
		//print_r_die($checklist);
	}

	public function active_driver(){
		$this->data['page'] = "page/report/active_driver";
		$this->data['result'] = $this->report->get_active_driver();
		$this->data['get_form'] = basename($_SERVER['REQUEST_URI']);
	
		$this->load->view('master' , $this->data );
	}
}
