<?php

class Mechanic_model extends CI_Model {

	public function get_checklist(){

		$this->db->select(" rs.* , r.* , rs.status as r_status");
		$this->db->join("mechanic_report_status rs"  , "rs.report_status_id = r.report_status");
		$result = $this->db->where("r.status" , "COMPLETE")->order_by("r.created" , "DESC")->get("mechanic_report r")->result();

		foreach($result as $key => $row){
			$result[$key]->created = convert_timezone($row->created , true);
			$result[$key]->r_status = report_type($row->r_status);
		}

		return $result;
	}

	public function getReportById($report_id){
		$this->db->select(" rs.* , r.* , rs.status as r_status");
		$this->db->join("mechanic_report_status rs"  , "rs.report_status_id = r.report_status");
		$this->db->where("r.report_id", $report_id);
		$result = $this->db->where("r.status" , "COMPLETE")->order_by("r.created" , "DESC")->get("mechanic_report r")->row();

		$result->created = convert_timezone($result->created , true);
		$result->r_status = report_type($result->r_status);

		$this->db->select("m.* , a.id, a.name");
		$this->db->join("accounts a", "a.id = m.account_id");
		$this->db->where("m.report_id" , $result->report_id);
		$result->mechanic_status = $this->db->get("mechanic_report_status m")->result();

		foreach ($result->mechanic_status as $key => $value) {
			$result->mechanic_status[$key]->status = report_type($value->status);
			$result->mechanic_status[$key]->created = convert_timezone($value->created , true);
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
			"account_id" => $this->session->userdata("user")->user_id,
			"report_id" => $report_id,
			"created" => time()
		]);

		$last = $this->db->insert_id();

		$this->db->where("report_id" , $report_id);
		
		$update = $this->db->update("mechanic_report" , [
			"report_status" => $last
		]);

		return $update;
    }
	
}