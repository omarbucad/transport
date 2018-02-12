<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends CI_Controller {

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


	public function login(){
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');

		if ($this->form_validation->run() == FALSE){
			echo json_encode(['success' => false , 'message' => 'Please input all fields']);
		}else{
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

	public function get_last_checklist(){
		$id = $this->input->post("id");

		if($result = $this->report->getLastChecklist($id)){
			echo json_encode($result);
		}else{
			echo json_encode(array("status" => false , "message" => "no results..." , "default" => $this->report->defaultVehicle($id)));
		}
	}

	private function getVehicleNumber(){
		return $this->vehicle->getVehicleNumber($this->input->post('id'));
	}

	private function getTrailerNumber(){
		return $this->trailer->getTrailerNumber($this->input->post('id'));
	}

	public function getSelectdata(){
		
		// $a[] = array(
		// 	"vehicle_number" => "- Select Vehicle Number - " ,
		// 	"description" => "" ,
		// 	"id" => ""
		// 	);

		// $a = $a + $this->getVehicleNumber();


		// $b[] = array(
		// 	"trailer_number" => "- Select Trailer Number - " ,
		// 	"description" => "" ,
		// 	"id" => ""
		// 	);

		// $b = $b + $this->getTrailerNumber();

		

		echo json_encode([
			"vehicle_data" => $this->getVehicleNumber(),
			"trailer_data" => $this->getTrailerNumber(),
			]);
	}

	public function logout(){
		$driver_id = $this->input->post("user_id");
		$end_mileage = $this->input->post("end_mileage");
		$device_id = $this->input->post("device_id");
		$login_id = $this->input->post("login_id");

		$this->db->where("device_id" , $device_id)->where("account_id" , $driver_id)->delete("device_info");
		$this->db->where("login_id" , $login_id)->update("timetracker_login" , array("time_out" => time() , "end_mileage" => $end_mileage));

		echo "Logging out";
	}

	public function initToken(){
		if($this->input->post()){

			$token = $this->input->post("token");
			$id = $this->input->post("id");
			$device_id = $this->input->post("device_id");

			$c = $this->db->where("account_id" , $id)->where("device_id" , $device_id)->get("device_info")->row();

			if($c){
				$this->db->where("account_id" , $c->account_id)->update("device_info" , ["device_token" => $token]);
			}else{
				$this->db->insert("device_info" , ["account_id" => $id , "device_token" => $token , "device_id" => $device_id]);
			}

		}
	}

	public function getInstructions(){
		if($this->input->post()){
			$arr = $this->db->where("driver_id" , $this->input->post("driver_id"))->where("created >=" , strtotime("-12 hours"))->limit(10)->order_by("created" , "DESC")->get("driver_instructions")->result();
			echo json_encode($arr);
		}
	}
}
