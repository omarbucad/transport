<?php

class Trailer_model extends CI_Model {

    public function InsertTrailer(){
    	$arr = array(
            "short_trailer_number" => htmlentities($this->input->post('short_trailer_number', TRUE)),
    		"trailer_number" => htmlentities($this->input->post('trailer_number', TRUE)),
            "trailer_make" => htmlentities($this->input->post('trailer_make', TRUE)),
            "trailer_type" => htmlentities($this->input->post('trailer_type', TRUE)),
            "trailer_axle" => htmlentities($this->input->post('trailer_axle', TRUE)),
    		"description" => ($this->input->post('description', TRUE)),
    		"status" => 1 ,
            "store_id" => $this->input->post("store_id", TRUE),
            "company_id" => $this->session->userdata('company_id'),
    		"created" => time()
    		);

       	$this->db->insert('trailer_information', $arr);
            
        return $this->db->insert_id();
    }

    public function removeTrailer($id){
    	$this->db->where("id" , $id)->delete("trailer_information");
    }

    public function getTrailerList(){
        $this->db->where('company_id' , $this->session->userdata('company_id'));

        if($this->input->get("submit")){

            if($this->input->get("short_trailer_number")){
                $this->db->where("short_trailer_number" , $this->input->get("short_trailer_number"));
            }

            if($this->input->get("trailer_number")){
                $this->db->where("trailer_number" , $this->input->get("trailer_number"));
            }

            if($this->input->get("trailer_make")){
                $this->db->where("trailer_make" , $this->input->get("trailer_make"));
            }

            if($this->input->get("trailer_type")){
                $this->db->where("trailer_type" , $this->input->get("trailer_type"));
            }

            if($this->input->get("trailer_axle")){
                $this->db->where("trailer_axle" , $this->input->get("trailer_axle"));
            }

            if($this->input->get("status")){
                $status_code = 0;

                if($this->input->get("status") == "active"){
                    $status_code = 1;
                }

                $this->db->where("status" , $status_code);

            }

        }

    	$result = $this->db->order_by('trailer_number' , 'asc')->get('trailer_information')->result();

    	foreach($result as $key => $row){
    		$result[$key]->status = account_status($row->status);
    	}

    	return $result;
    }

    public function UpdateTrailer(){
        $arr = array(
            "short_trailer_number" => htmlentities($this->input->post('short_trailer_number', TRUE)) ,
            "trailer_number" => htmlentities($this->input->post('trailer_number', TRUE)) ,
            "description" => htmlentities($this->input->post('description', TRUE)) ,
            "trailer_make" => htmlentities($this->input->post('trailer_make', TRUE)) ,
            "trailer_type" => htmlentities($this->input->post('trailer_type', TRUE)) ,
            "trailer_axle" => htmlentities($this->input->post('trailer_axle', TRUE)) ,
            "status" =>  $this->input->post('status', TRUE)  ,
            "updated" => time()
            );

        $this->db->where('id' , $this->input->post('id', TRUE));

        $this->db->update('trailer_information' , $arr);

        return $this->input->post('id', TRUE);
    }

    public function getTrailerById($id){
        $this->db->where('id' , $id);

        return $this->db->where('company_id' , $this->session->userdata('company_id'))->get('trailer_information')->row();
    }

    public function getTrailerNumber($id){
        $this->db->select('ti.trailer_number , ti.description , ti.id');
        $this->db->join('accounts a ' , 'a.company_id = ti.company_id');
        $result = $this->db->where('a.id' , $id)->where('ti.status' , 1)->order_by('trailer_number' , 'asc')->get('trailer_information ti')->result_array();

        return $result;
    }
    public function getStore(){
        return $this->db->select("store_name , store_id")->where("company_id" , $this->session->userdata('company_id'))->where("status" , 1)->get("store")->result();
    }


}