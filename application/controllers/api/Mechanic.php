<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mechanic extends CI_Controller {

	function __construct( ) {
		parent::__construct();
		$this->output->set_header('Access-Control-Allow-Origin: *');


		$this->load->model('login_model', 'login');
		$this->load->model('report_model', 'report');
		$this->load->model('vehicle_model', 'vehicle');
		$this->load->model('trailer_model', 'trailer');
	}

	public function get_report(){


		if($this->input->post()){
			$this->db->select("mr.* , mt1.*");
			$this->db->select("mt2.b1 as p_b1 , mt2.b2 as p_b2 , mt2.b3 as p_b3 , mt2.b4 as p_b4 , mt2.mb1 as p_mb1 , mt2.mb2 as p_mb2 , mt2.mb3 as p_mb3 , mt2.mb4 as p_mb4 , mt2.mb5 as p_mb5 , mt2.mb6 as p_mb6 , mt2.mb7 as p_mb7 , mt2.mb8 as p_mb8 , mt2.spare_tyre as p_spare_tyre");
			$this->db->join("mechanic_tyres mt1" , "mt1.tyres_id = mr.thread_depth");
			$this->db->join("mechanic_tyres mt2" , "mt2.tyres_id = mr.tyre_pressure");
			$result = $this->db->where("mr.status" , "INCOMPLETE")->where("mr.mechanic_id" , $this->input->post("id"))->get("mechanic_report mr")->row();

			if($result){
				$all_checklist = $this->db->select("checklist_index , checklist_timer , value")->where('mechanic_report_id' , $result->report_id)->order_by("checklist_index" , "asc")->get('mechanic_report_checklist')->result_array();

				    foreach($all_checklist as $k => $r){
				        $all_checklist[$k]['checklist_timer'] = date('M d Y h:i:s A' , $r['checklist_timer']);
				     }

				    $result->all_checklist = $all_checklist;

				echo json_encode(["status" => true , "data" => $result]);
			}else{
				echo json_encode(["status" => false]);
			}

		}

	}

	public function submit_checklist_report(){

		if($this->input->post()){
			$check = $this->db->where("mr.status" , "INCOMPLETE")->where("mr.mechanic_id" , $this->input->post("mechanic_id"))->get("mechanic_report mr")->row();

			if($check){

				$this->db->where("report_id" , $check->report_id)->update("mechanic_report" ,  [
					"mechanic_id"		=> $this->input->post("mechanic_id"),
					"operator"			=> $this->input->post("operator"),
					"mileage_in"		=> $this->input->post("mileage_in"),
					//"mileage_out"		=> $this->input->post("mileage_out"),
					"registration_no"	=> $this->input->post("registration_no"),
					"fleet_no"			=> $this->input->post("fleet_no"),
					"make_type"			=> $this->input->post("make_type")
				]);

				echo json_encode([
					"id" => $check->report_id , 
					"depth" => $check->thread_depth,
					"pressure" => $check->tyre_pressure
				]);

			}else{
				$this->db->insert("mechanic_report" , [
					"mechanic_id"		=> $this->input->post("mechanic_id"),
					"operator"			=> $this->input->post("operator"),
					"mileage_in"		=> $this->input->post("mileage_in"),
					//"mileage_out"		=> $this->input->post("mileage_out"),
					"registration_no"	=> $this->input->post("registration_no"),
					"fleet_no"			=> $this->input->post("fleet_no"),
					"make_type"			=> $this->input->post("make_type"),
					"created"			=> time()
				]);

				$report_id = $this->db->insert_id();
				
				$arr = array();

				for ($x = 0; $x < 61; $x++) {
				   $arr[] = [
						"mechanic_report_id" => $report_id ,
						"checklist_index" 	 => 0 ,
						"checklist_timer"    => 0 ,
						"value"			     => "NA"
					];
				} 

				$this->db->insert_batch("mechanic_report_checklist" , $arr);

				$this->db->insert("mechanic_tyres" , [
					"report_id"		=> $report_id
				]);

				$depth = $this->db->insert_id();

				$this->db->insert("mechanic_tyres" , [
					"report_id"		=> $report_id
				]);
				
				$pressure = $this->db->insert_id();

				$this->db->where("report_id" , $report_id)->update("mechanic_report" , [
					"thread_depth" => $depth ,
					"tyre_pressure" => $pressure
				]);

				echo json_encode([
					"id" => $report_id , 
					"depth" => $depth,
					"pressure" => $pressure
				]);
			}

			
		}
	}

	public function mechanic_checklist_save(){

		$report_id = $this->input->post('report_id');

		
		$this->remove_checklist($report_id);

		$checklist = $this->input->post('checklist');
        $timer = $this->input->post("checklisttimer");

        foreach($checklist as $key => $row){

	        $this->db->insert('mechanic_report_checklist' , array(
	            "mechanic_report_id" => $report_id ,
	            "checklist_index" => $key ,
	            "checklist_timer" => c_strtotime($timer[$key]),
	            "value" => $row
	        ));

        }

        $c = $this->db->where("report_id" , $this->input->post("report_id"))->get("mechanic_report_status")->row();

        if(!$c){

        	if($this->input->post("report_type") == "DEFECT"){

	            $this->db->insert("mechanic_report_status" , [
	            	"status" => 1 ,
		            "account_id" => $this->input->post('mechanic_id'),
		            "report_id" => $this->input->post('report_id'),
		            "created" => time()
	            ]);

	        }else{
	        	
	        	$this->db->insert("mechanic_report_status" , [
	            	"status" => 0 ,
		            "account_id" => $this->input->post('mechanic_id'),
		            "report_id" => $this->input->post('report_id'),
		            "created" => time()
	            ]);
	        }

	        $status_id = $this->db->insert_id();

        }else{

        	$status_id = $c->report_status_id;

        }
        

        $this->db->where("report_id" , $report_id)->update("mechanic_report" , [
			"report_type" => $this->input->post("report_type"),
			"report_status" => $status_id ,
			"road_test_note" => $this->input->post("road_test_note"),
			"other_notes"	=> $this->input->post("other_note"),
			"mileage_out"	=> $this->input->post("mileage_out")
		]);

		echo json_encode(["report_status_id" => $status_id]);
	}

	public function complete_checklist(){
		if($this->input->post()){

			$this->db->where("report_id" , $this->input->post("report_id"))->update("mechanic_report" , [
				"status" => "COMPLETE"
			]);

			$this->db->where("report_status_id" , $this->input->post("status_id"))->update("mechanic_report_status" , [
				"comment" => $this->input->post("comment")
			]);
		}
	}

	private function remove_checklist($report_id){
		$this->db->where("mechanic_report_id" , $report_id)->delete("mechanic_report_checklist");
	}

	public function u_tyres(){
		if($this->input->post()){

			$this->update_tyres($this->input->post("depth_id") , $this->input->post("depth"));
			$this->update_tyres($this->input->post("pressure_id") , $this->input->post("pressure"));

			echo json_encode(["status" => 1]);
		}
	}

	private function update_tyres($id , $data){
		$this->db->where("tyres_id" , $id)->update("mechanic_tyres" , [
			"b1" => $data['b1'],
			"b2" => $data['b2'],
			"b3" => $data['b3'],
			"b4" => $data['b4'],
			"mb1" => $data['mb1'],
			"mb2" => $data['mb2'],
			"mb3" => $data['mb3'],
			"mb4" => $data['mb4'],
			"mb5" => $data['mb5'],
			"mb6" => $data['mb6'],
			"mb7" => $data['mb7'],
			"mb8" => $data['mb8'] ,
			"spare_tyre" => ($data['spare_tyre']) ? $data['spare_tyre'] : "NO"
		]);
	}

	public function insertDefectReportWithImage(){
		$last_id = $this->uploadImage();
		echo json_encode(["id" => $last_id]);
	}

	public function insertEmergencyWithImage(){
		$last_id = $this->uploadImage(false);
		echo json_encode(["id" => $last_id]);
	}

	public function submit_emergency(){
		if($this->input->post()){

			$this->db->insert("emergency_report" , [
				"driver_id"		=> $this->input->post("driver_id"),
				"longitude"     => $this->input->post("longitude"),
				"latitude"		=> $this->input->post("latitude"),
				"comment"		=> $this->input->post("comment"),
				"status"		=> "DEFECT" ,
				"created"		=> time()
			]);

			$last_id = $this->db->insert_id();

			$this->db->where("emergency_id IS NULL")->where("driver_id" , $this->input->post("driver_id"))->update("emergency_report_images" , [
				"emergency_id"		=> $last_id
			]);
		}
	}

	private function uploadImage($mechanic = true){
      $id = $this->input->post('report_id');
      $images = $this->input->post("images");

      if($this->input->post()){
        $name = $id.'_'.time().'.JPG';
        $thumb_name = $id.'_'.time().'_thumb.JPG';
        $year = date("Y");
        $month = date("m");
        
        if($mechanic){
        	$folder = "./public/upload/mechanic/".$year."/".$month;
        }else{
        	$folder = "./public/upload/emergency/".$year."/".$month;
        }

        if (!file_exists($folder)) {
          mkdir($folder, 0777, true);
          create_index_html($folder);
        }

        $path = $folder.'/'.$name;

        $decodedImage = base64_decode("$images");

        file_put_contents($path , $decodedImage);

        $thumb_config['image_library']  = 'gd2';
        $thumb_config['source_image']   = $path;
        $thumb_config['create_thumb']   = TRUE;
        $thumb_config['maintain_ratio'] = TRUE;
            //$thumb_config['width']          = 500;
        $thumb_config['height']         = 500;

        $this->load->library('image_lib', $thumb_config);
        $this->image_lib->initialize($thumb_config); 
        $this->image_lib->resize();

        $thumb_name = $year."/".$month.'/'.$thumb_name;
        $image_name = $year."/".$month.'/'.$name;

        if($mechanic){
        	$this->db->insert('mechanic_report_images' , ["images" => $image_name , "image_thumb" => $thumb_name , 'report_id' => $id]);
        }else{
        	$this->db->insert('emergency_report_images' , ["images" => $image_name , "image_thumb" => $thumb_name , "driver_id" => $this->input->post("driver_id")]);
        }

        
        return $this->db->insert_id();
      }
    }

    public function get_checklist(){
    	$this->db->select("rs.* ,r.* ,  mt1.* ");
    	$this->db->select("mt2.b1 as p_b1 , mt2.b2 as p_b2 , mt2.b3 as p_b3 , mt2.b4 as p_b4 , mt2.mb1 as p_mb1 , mt2.mb2 as p_mb2 , mt2.mb3 as p_mb3 , mt2.mb4 as p_mb4 , mt2.mb5 as p_mb5 , mt2.mb6 as p_mb6 , mt2.mb7 as p_mb7 , mt2.mb8 as p_mb8 , mt2.spare_tyre as p_spare_tyre");

        $this->db->join("mechanic_report_status rs" , "rs.report_status_id = r.report_status" , "LEFT");
        $this->db->join("mechanic_tyres mt1" , "mt1.tyres_id = r.thread_depth");
        $this->db->join("mechanic_tyres mt2" , "mt2.tyres_id = r.tyre_pressure");
        $this->db->where("mechanic_id" , $this->input->post("id"))->order_by("r.created" , "DESC")->where("r.status" , "COMPLETE");
        $result = $this->db->limit(30)->get("mechanic_report r")->result();

        foreach($result as $key => $row){
          	$result[$key]->created = convert_timezone($row->created , true);
          	$checklist = $this->db->where("mechanic_report_id" , $row->report_id)->order_by("checklist_index" , "ASC")->get("mechanic_report_checklist")->result();

           	foreach ($checklist as $k => $r) {
           		$result[$key]->checklist[] = array(
                          "checklist_index" => checklist_mechanic($r->checklist_index),
                          "checklist_timer" => convert_timezone($r->checklist_timer , true),
                          "value"           => $r->value
                      );
           	}
        }

        echo json_encode($result);
    }

}
