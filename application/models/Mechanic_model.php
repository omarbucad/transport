<?php

class Mechanic_model extends CI_Model {

	public function get_checklist(){

		$this->db->select(" rs.* , r.* , rs.status as r_status, rs.created as updated_status");
		$this->db->join("mechanic_report_status rs"  , "rs.report_status_id = r.report_status");

		if(!$this->input->get('all')){

			if($report_id = $this->input->get('report_id')){
				$this->db->where("r.report_id",$report_id);
			}

			if($fleet_no = $this->input->get('fleet_no')){
				$this->db->where("r.fleet_no",$fleet_no);
			}

			if($registration_number = $this->input->get('registration_number')){
				$this->db->where("r.registration_no",$registration_number);
			}

			if($report_type = $this->input->get('report_type')){
				$this->db->where("r.report_type",$report_type);
			}

			if($this->input->get('date_from') AND $this->input->get('date_to')){
		          $from = strtotime($this->input->get('date_from').' 00:00 ');
		          $to = strtotime($this->input->get('date_to').' 23:59 ');

		          $this->db->where('r.created >=' , $from);
		          $this->db->where('r.created <=' , $to);
		    }

		    if($this->input->get('status') == "fixed"){
		    	$this->db->where('rs.status', 3);
		    }

		    if($this->input->get('status') == "under_maintenance"){
		    	$this->db->where('rs.status', 2);
		    }

		    if($this->input->get('status') == "open"){
		    	$this->db->where('rs.status', 1);
		    }
		}
		

	    $result = $this->db->where("r.status" , "COMPLETE")->order_by("r.created" , "DESC")->get("mechanic_report r")->result();


		foreach($result as $key => $row){
			$result[$key]->created = convert_timezone($row->created , true);
			$result[$key]->r_status = report_type($row->r_status);
			$result[$key]->updated_status = convert_timezone($row->updated_status,true);
		}



		return $result;
	}

	public function getReportById($report_id, $print = false){
		$this->db->select(" rs.* , r.* , rs.status as r_status");
		$this->db->join("mechanic_report_status rs"  , "rs.report_status_id = r.report_status");
		$this->db->where("r.report_id", $report_id);
		$result = $this->db->where("r.status" , "COMPLETE")->order_by("r.created" , "DESC")->get("mechanic_report r")->row();

		$result->created = convert_timezone($result->created , true);
		$result->r_status = report_type($result->r_status);

		if($print){
			$this->db->select("m.* , a.id, a.name");
			$this->db->join("accounts a", "a.id = m.account_id");
			$this->db->where("m.report_id" , $result->report_id);
			$result->mechanic_status = $this->db->get("mechanic_report_status m")->result();

			foreach ($result->mechanic_status as $key => $value) {
				$result->mechanic_status[$key]->status = report_type($value->status);
				$result->mechanic_status[$key]->created = convert_timezone($value->created , true);
			}
		}
		else{
			$this->db->select("m.* , a.id, a.name");
			$this->db->join("accounts a", "a.id = m.account_id");
			$this->db->where("m.report_id" , $result->report_id);
			$this->db->limit(1)->order_by("m.created","DESC");
			$result->mechanic_status = $this->db->get("mechanic_report_status m")->result();

			foreach ($result->mechanic_status as $key => $value) {
				$result->mechanic_status[$key]->status = report_type($value->status);
				$result->mechanic_status[$key]->created = convert_timezone($value->created , true);
			}
		}
		
		

		$result->tyre_pressure = $this->db->where("tyres_id", $result->tyre_pressure)->get("mechanic_tyres mt")->row();
		$result->thread_depth = $this->db->where("tyres_id", $result->thread_depth)->get("mechanic_tyres mt")->row();

		if($result->report_type == "DEFECT"){
			$result->report_images = $this->db->where("report_id",$result->report_id)->get("mechanic_report_images")->result();
			foreach ($result->report_images as $key => $value) {
				$result->report_images[$key]->images = site_url('public/upload/mechanic/'.$value->images);
			}
		}

		return $result;
	}

	public function getChecklistMechanicList($report_id, $defects = false){
		$this->db->where("mechanic_report_id", $report_id);

		if($defects){
			$this->db->where("value","DEFECT");
		}

		$result = $this->db->get("mechanic_report_checklist")->result();

		foreach ($result as $key => $value) {
			$result[$key]->checklist_index = checklist_mechanic($value->checklist_index);
			$result[$key]->checklist_timer = convert_timezone($value->checklist_timer, true);
		}

		return $result;  		
    }

    public function updateChecklistStatus($report_id){

		$insert = $this->db->insert("mechanic_report_status", [
			"status" => $this->input->post("status"),
			"comment" => $this->input->post("comment"),
			"account_id" => $this->session->userdata("id"),
			"report_id" => $report_id,
			"created" => time()
		]);

		$last = $this->db->insert_id();

		$this->db->where("report_id" , $report_id);
		
		$update = $this->db->update("mechanic_report" , [
			"report_status" => $last
		]);

		if($update){
			$new_status['status'] = report_type($this->input->post("status"));
			$new_status['report_id'] = $report_id;

			return $new_status;
		}
		else{
			return false;
		}

    }

    public function getAllNeedsServicing(){
    	$this->db->select(" rs.* , r.* , rs.status as r_status");
		$this->db->where("r.status" , "COMPLETE")->join("mechanic_report_status rs"  , "rs.report_status_id = r.report_status");
		$result = $this->db->get("mechanic_report r")->result();

		$needsServicing = array();
		foreach($result as $key => $row){
			$datecreated = convert_timezone($result[$key]->created, true);
			$today = date("M d Y");

			$sixthmonth = date("M d Y", strtotime($datecreated ." +6 Month"));
			$weekbefore = date("M d Y", strtotime($sixthmonth ."-1 week"));

			$timeleft = (strtotime($sixthmonth) - strtotime($today));
			$left = "<small class='help-block'>". round((($timeleft/24)/60)/60) . " day(s) left</small>";

			if(strtotime($today) >= strtotime($weekbefore) && strtotime($today) <= strtotime($sixthmonth)){
				$result[$key]->servicing_date = $sixthmonth . $left;
				array_push($needsServicing, $row);
			}
		}
		return $needsServicing;
    }

    public function getEmergencyReports(){
    	

    	if($this->input->get("status") == "fixed"){
    		$this->db->where("e.fix_date !=", 0);
    	}

    	if($this->input->get("status") == "defect"){
    		$this->db->where("e.fix_date", 0);
    	}

    	if($emergency_id = $this->input->get('emergency_id')){
			$this->db->where("e.emergency_id",$emergency_id);
		}

		if($driver_id = $this->input->get('driver_id')){
			$this->db->where("e.driver_id",$driver_id);
		}


    	if($this->input->get('date_from') AND $this->input->get('date_to')){
	        $from = strtotime($this->input->get('date_from').' 00:00 ');
	        $to = strtotime($this->input->get('date_to').' 23:59 ');

	        $this->db->where('e.created >=' , $from);
	        $this->db->where('e.created <=' , $to);
	    }


    	$this->db->select("e.* , a.name, a.surname");
    	$this->db->join("accounts a","a.id = e.driver_id");
    	$emergency = $this->db->get("emergency_report e")->result();
    	//print_r_die()
    	foreach ($emergency as $key => $value) {
    		if($emergency[$key]->fix_date){
    			$emergency[$key]->fix_date = convert_timezone($value->fix_date, true);
    		}
        	$emergency[$key]->created = convert_timezone($value->created, true);
    	}

      	return $emergency;
    }

    public function getEmergencyReportById($id){
    	$this->db->select(" a.name, a.surname, e.*");
    	$this->db->join("accounts a","a.id = e.driver_id");
    	$this->db->where("e.emergency_id", $id);
    	$emergency = $this->db->get("emergency_report e")->row();

    	$emergency->images = $this->db->where("emergency_id", $id)->get("emergency_report_images")->result();

    	//print_r_die($emergency);
   		$emergency->created = convert_timezone($emergency->created, true);    	

    	return $emergency;
    }

    public function getEmergencyReportToday(){
    	$h = strtotime(date("M d Y"));

    	$this->db->select("e.* , a.name, a.surname");
    	$this->db->where("e.created",$h);
    	$this->db->where("e.fix_date", 0);
    	$this->db->join("accounts a","a.id = e.driver_id");
    	$emergency = $this->db->get("emergency_report e")->result();

    	foreach ($emergency as $key => $value) {
    		$emergency[$key]->created = convert_timezone($value->created, true);
    	}

     	return $emergency;
    }

    public function updateEmergencyReport($id){
    	$this->db->where("emergency_id", $id);
    	$emergency = $this->db->update("emergency_report",[
    		"fix_date" => time()
    	]);

    	return $emergency;
    }
	
}