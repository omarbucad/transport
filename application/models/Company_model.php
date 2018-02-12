<?php

class Company_model extends CI_Model {

	public function getStore(){
		$this->db->where("company_id" , $this->session->userdata('company_id'));
		$result = $this->db->order_by('store_name' , 'asc')->get('store')->result();

		foreach($result as $key => $row){
			$result[$key]->created = convert_timezone($row->created);
			$result[$key]->status = account_status($row->status);
		}

		return $result;
	}

	public function addStore(){
		$arr = array(
			"company_id" => $this->session->userdata('company_id') ,
			"store_name" => htmlentities($this->input->post("company_name", TRUE)),
			"description" => htmlentities(nl2br($this->input->post("description", TRUE))),
			"status" => 1 ,
			"created" => time()
			);
		$this->db->insert("store" , $arr);
		
		return $this->db->insert_id();
	}
	public function removeStore($id){
		return $this->db->where("store_id" , $id)->delete("store");
	}
	public function getStoreById($id){
		$this->db->where('store_id' , $id);
		$this->db->where('company_id' , $this->session->userdata('company_id'));
		return $this->db->get('store')->row();
	}
	public function updateStore(){
		$arr = array(
			"store_name" => htmlentities($this->input->post("company_name", TRUE)),
			"description" => htmlentities(nl2br($this->input->post("description", TRUE))),
			"status" => $this->input->post("status", TRUE) 
			);
		$this->db->where('store_id' , $this->input->post('store_id', TRUE))->update('store' , $arr);

		return $this->input->post('store_id', TRUE);
	}
	public function getSelectStore(){
		$this->db->select('store_id , store_name');
		$this->db->where('company_id' , $this->session->userdata('company_id'));
		$this->db->order_by('store_name' , 'asc');
		return $this->db->get('store', TRUE)->result();
	}
}