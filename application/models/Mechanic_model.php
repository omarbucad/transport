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
}