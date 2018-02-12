<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	private $data = array();
	private $first_store_id;
	private $store_id;

	function __construct( ) {
		parent::__construct();
		$this->data = Share::get_share();

		if(!$this->session->userdata("id")){
			redirect('/login', 'refresh');
		}

		$this->load->model('invoice_model', 'invoice');

		$this->store_list = $this->dashboard->getStoreList();
		$this->first_store_id = $this->dashboard->getFirstStore($this->store_list);
		$this->data['store_list'] = $this->store_list;
		
	}

	public function index($store_id = false){
		if($this->session->userdata("account_type") != 'SUPER ADMIN'){
			redirect('/app/accounts', 'refresh');
		}

		$this->data['page'] = "page/dashboard";
		$this->data['show_dashboard'] = true;

		if(!$store_id){
			if(!$this->store_list){
				$this->data['show_dashboard'] = false;
			}else{
				redirect('/app/dashboard/index/'.$this->first_store_id, 'refresh');
			}
		}else if(!$this->dashboard->checkSelectedStore($this->store_list , $store_id)){
			redirect('/app/dashboard/index/'.$this->first_store_id, 'refresh');
		}else{

			$this->data['store_selected_id'] = ($store_id) ? $store_id : $this->first_store_id;
			$this->data['driver_license_notif'] = $this->dashboard->getDriverLicenseExpiry();
			$this->data['activeTruck'] = $this->dashboard->getActiveCount($store_id);
			$this->data['defectTruck'] = $this->dashboard->getDefectCount($store_id);
	        $this->data['incompleteTruck'] = $this->dashboard->getIncompleteCount($store_id);
			$this->data['activeTruckList'] = $this->dashboard->getActiveList($store_id);
			$this->data['defectTruckList'] = $this->dashboard->getDefectList($store_id);
	        $this->data['incompleteTruckList'] = $this->dashboard->getIncompleteList($store_id);
	        $this->data['demurrageReport'] = $this->dashboard->demurrageReport($store_id);
	        $this->data['boxResult'] = $this->invoice->getSalesForBox($this->session->userdata("company_id") , false , $store_id);

	        /*JOBS*/


	        $id = false;
			$job_name = false;

			$this->data['config']["base_url"] = base_url('/app/dashboard/index/'.$this->first_store_id.'/') ;
			$this->data['config']["total_rows"] = $this->customer->getJobsListV2(false , false , false , true);

			$this->pagination->initialize($this->data['config']);

			$this->data["links"] = $this->pagination->create_links();

			if($this->session->userdata("account_type") == CUSTOMER){
				$this->data['include_page'] = "page/customer/jobs/jobs_customer";
				$this->data['result'] = $this->customer->getJobsListV2(true , $id , $job_name);
				$this->data['result_count'] = $this->customer->getJobsListV2(true , $id , $job_name , true);


			}else{
				$this->data['include_page'] = "page/customer/jobs/jobs_admin";
				$this->data['result'] = $this->customer->getJobsListV2(false , $id , $job_name);
				$this->data['select'] = $this->customer->getSelectData();
				//$this->data['result_count'] = $this->customer->getJobsList(false , $id , $job_name , true);


				$this->data['cancel_count'] = $this->customer->countCancelled();
			}

			/*JOBS*/
		}

		
        
		$this->load->view('master' , $this->data );
	}

	public function readNotification(){
		if($this->input->post("id", TRUE)){
			$this->db->where("notification_id" , $this->input->post("id", TRUE))->update("notification" , ["has_open" => 1]);
		}
	}

	public function readNotificationAll(){
		$this->db->where("to_id" , $this->session->userdata("id"))->update("notification" , ["has_open" => 1]);
	}
	public function test(){
		$this->db->select("CONCAT(a.name , ' ' , a.surname) as driver_name");
		$this->db->select("r.start_date , r.start_mileage , r.end_date , r.end_mileage , r.report_type , r.report_id");
		$this->db->select("r.vehicle_registration_number as vehicle_number , r.trailer_number , r.created");
		$this->db->select("j.jobs_id , j.job_number , j.job_notes , j.signature_name , j.signature");
		$this->db->select("jp.job_name , jp.load_site , jp.address as destination");
		$this->db->join("accounts a" , "a.id = r.user_id" , "LEFT");
		$this->db->join("report_jobs rj" , "rj.report_id = r.id" , "LEFT");
		$this->db->join("jobs j" , "j.jobs_id = rj.job_id" , "LEFT");
		$this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id" , "LEFT");
		$result = $this->db->get("report r")->result();  	

		foreach($result as $key => $row){
			$result[$key]->created = convert_timezone($row->created);
			$result[$key]->start_date = convert_timezone($row->start_date , true);
			$result[$key]->end_date = convert_timezone($row->end_date , true);
			$result[$key]->safety_check = ($result[$key]->report_type == 1) ? "NO" : "YES";
			$result[$key]->report_type = ($result[$key]->report_type == 1) ? "INCOMPLETE" : $result[$key]->report_type;
			$result[$key]->distance_travel = (floatval($row->end_mileage) - floatval($row->start_mileage));

			$result[$key]->begin_driving = "0";
			$result[$key]->complete_job_time = "0";

			$a = $this->jobDate($result[$key]->jobs_id , "A");

			$result[$key]->load_site_arrived_time = $a->arrived_time;
			$result[$key]->load_site_pickup_time = $a->pick_up_time;
			$result[$key]->load_site_mileage = $a->start_mileage;
			$result[$key]->load_site_latitude = $a->latitude;
			$result[$key]->load_site_longitude = $a->longitude;

			$b = $this->jobDate($result[$key]->jobs_id , "B");
			
			$result[$key]->destination_site_arrived_time = $a->arrived_time;
			$result[$key]->destination_site_unloading_time = $a->pick_up_time;
			$result[$key]->destination_site_mileage = $a->start_mileage;
			$result[$key]->destination_site_latitude = $a->latitude;
			$result[$key]->destination_site_longitude = $a->longitude;

			if($row->signature){
               $result[$key]->signature = full_path_image($row->signature , "signature");
               $result[$key]->signature = $result[$key]->signature['path'];

            }


		}

		//print_r_die($result);
  		$t = array();
		foreach($result as $row){
			$t[] = array(
				"Driver Name" => $row->driver_name ,
				"Start Date" => $row->start_date ,
				"Start Mileage" => $row->start_mileage ,
				
				"Distance Travel" => $row->distance_travel ,
				"Safety Check" => $row->safety_check ,
				"Vehicle Number" => $row->vehicle_number ,
				"Trailer Number" => $row->trailer_number ,
				"Report Type" => $row->report_type ,
				"Job ID" => $row->jobs_id ,
				"Job Name" => $row->job_name ,
				"Job Number" => $row->job_number ,
				"Load site" => $row->load_site ,
				"Destination" => $row->destination ,
				"Begin Driving" => 0 ,

				"Load Site Arrival" => $row->load_site_arrived_time,
				"Load Site Pick Up" => $row->load_site_pickup_time,
				"Load Site Latitude" => $row->load_site_latitude,
				"Load Site Longitude" => $row->load_site_longitude,
				"Load Site Mileage" => $row->load_site_mileage,

				"Destination Site Arrival" => $row->destination_site_arrived_time,
				"Destination Site Unload" => $row->destination_site_unloading_time,
				"Destination Site Latitude" => $row->destination_site_latitude,
				"Destination Site Longitude" => $row->destination_site_longitude,
				"Destination Site Mileage" => $row->destination_site_mileage,

				"Customer Name" => $row->signature_name ,
				"Signature" => $row->signature,

				"End Date" => $row->end_date ,
				"End Mileage" => $row->end_mileage ,
				"Job Completed" => $row->complete_job_time

				);
		}


		download_send_headers('report_' . date("Y-m-d") . ".csv");
		echo array2csv($t);
		die();
	}

	private function jobDate($jobs_id , $type){
        $A = $this->db->select("arrived_time , pick_up_time , start_mileage , latitude , longitude")->where("jobs_id" , $jobs_id)->where("point_destination" , $type)->get("jobs_date")->row();

        if($A){
            $A->arrived_time = ($A->arrived_time) ? convert_timezone($A->arrived_time , true , true , false , "d M Y D h:i:s A") : "NA";
            $A->pick_up_time = ($A->pick_up_time) ? convert_timezone($A->pick_up_time , true , true , false , "d M Y D h:i:s A") : "NA";
            $A->latitude = ($A->latitude) ? $A->latitude : "NA";
            $A->longitude = ($A->longitude) ? $A->longitude : "NA";
        }else{
            $A = new stdClass();
            $A->arrived_time = "NA";
            $A->pick_up_time = "NA";
            $A->start_mileage = "NA";
            $A->longitude = "NA";
            $A->latitude = "NA";
        }

        return $A;
    }
}
