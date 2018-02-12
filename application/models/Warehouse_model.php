<?php

class Warehouse_model extends CI_Model {

    public function getJobList(){
        $this->db->select("j.jobs_id , j.job_number , j.loading_time , j.delivery_time");
        $this->db->select("j.job_name , CONCAT(j.address , ',',j.zip_Code) as address , CONCAT(j.load_site , ',' , j.zip_code_load_site) as load_site ");
        $this->db->select("c.company_name");
        $this->db->select("CONCAT(a.name , ' ' , a.surname) as driver_name");
        $this->db->select("vi.vehicle_number , ti.trailer_number");

        $this->db->select("ws.status as warehouse_status");

        $this->db->join("jobs j" , "j.parent_id = jp.job_parent_id");
        $this->db->join("customer c" , "c.customer_id = jp.customer_id");
        $this->db->join("accounts a" , "a.id = j.driver_id");
        $this->db->join("vehicle_information vi" , "vi.id = j.vehicle_id" , "LEFT");
        $this->db->join("trailer_information ti" , "ti.id = j.trailer_id" , "LEFT");
        $this->db->join("warehouse_status ws" , "ws.warehouse_status_id = j.warehouse_status_id");

        if($this->session->userdata("warehouse_account_id") == 0){
            $this->db->where_in("jp.customer_id" , ["4" , "6"]);
        }else{
            $this->db->where("jp.customer_id" , $this->session->userdata("warehouse_account_id"));
        }
        
        $this->db->where("jp.company_id" , $this->session->userdata("company_id"));

        if($this->input->get("submit")){
            if($this->input->get("job_name")){
                $this->db->like("j.job_name" , $this->input->get("job_name"));
            }

             if($this->input->get("driver_name")){
                $this->db->like("name" , $this->input->get("driver_name"));
                $this->db->or_like("surname" , $this->input->get("driver_name"));
            } 

            if($this->input->get("vehicle_number")){
                $this->db->like("vehicle_number" , $this->input->get("vehicle_number"));
            }

            if($this->input->get("trailer_number")){
                $this->db->like("trailer_number" , $this->input->get("trailer_number"));
            }

            if($this->input->get("loading_time_from") AND $this->input->get("loading_time_to")){
                $from = strtotime($this->input->get("loading_time_from").' midnight');
                $to = strtotime($this->input->get("loading_time_to").' 23:59');

                $this->db->where("j.loading_time >=" , $from);
                $this->db->where("j.loading_time <=" , $to);
            }

            if($this->input->get("delivery_time_from") AND $this->input->get("delivery_time_to")){
                $from = strtotime($this->input->get("delivery_time_from").' midnight');
                $to = strtotime($this->input->get("delivery_time_to").' 23:59');

                $this->db->where("j.delivery_time >=" , $from);
                $this->db->where("j.delivery_time <=" , $to);
            }

        }

        if($this->input->get("status") == "complete"){
            $this->db->where_in("ws.status" , ["UNLOADED" , "PICKED UP"]);
        }else{
            $this->db->where_not_in("ws.status" , ["UNLOADED" , "PICKED UP"]);
        }

        $result = $this->db->where("j.driver_id !=" , 0)->where("j.with_outsource" , 0)->order_by("j.loading_time" , "DESC")->get("jobs_parent jp")->result();

        foreach($result as $key => $row){
            $result[$key]->loading_time = convert_timezone($row->loading_time , true , false , false , "d M Y D h:i:s A");
            $result[$key]->delivery_time = convert_timezone($row->delivery_time , true , false , false , "d M Y D h:i:s A");
            $result[$key]->vehicle_number = ifNA($result[$key]->vehicle_number);
            $result[$key]->trailer_number = ifNA($result[$key]->trailer_number);
            $result[$key]->address = nl2br(ifNA($result[$key]->address));
            $result[$key]->load_site = nl2br(ifNA($result[$key]->load_site));
            $result[$key]->btn_type = (strpos(strtolower($row->address), 'needingworth') !== false) ? "destination" : "load_site";

            if($result[$key]->btn_type == "destination"){
                if($row->warehouse_status == ""){
                    $result[$key]->warehouse_status_raw = "TO BE UNLOADED";
                    $result[$key]->warehouse_status = warehouse_status("TO BE UNLOADED");
                }else{
                    $result[$key]->warehouse_status_raw = $row->warehouse_status;
                    $result[$key]->warehouse_status = warehouse_status($row->warehouse_status);
                } 
            }else{
                if($row->warehouse_status == ""){
                    $result[$key]->warehouse_status_raw = "TO BE LOADED";
                    $result[$key]->warehouse_status = warehouse_status("TO BE LOADED");
                }else{
                    $result[$key]->warehouse_status_raw = $row->warehouse_status;
                    $result[$key]->warehouse_status = warehouse_status($row->warehouse_status);
                } 
            }

            // $result[$key]->warehouse_status_raw = $row->warehouse_status;
            // $result[$key]->warehouse_status = warehouse_status($row->warehouse_status);
        }

        return $result;
    }



}