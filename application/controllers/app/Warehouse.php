<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
        
        $this->data = Share::get_share();
 		$this->load->model("warehouse_model" , "warehouse");
	}

	public function index()
	{
		$this->data['page'] = "page/warehouse/view";
		$this->data['result'] = $this->warehouse->getJobList();
		$this->load->view('master' , $this->data );
	}

	public function updateStatus(){
		if($this->input->post()){
			$this->db->insert("warehouse_status" , ["jobs_id" => $this->input->post("id", TRUE) , "status" => $this->input->post("type", TRUE) , "created" => time()]);
			$warehouse_status_id = $this->db->insert_id();
			$this->db->where("jobs_id" , $this->input->post("id", TRUE))->update("jobs" , ['warehouse_status_id' => $warehouse_status_id]);
			echo warehouse_status($this->input->post("type", TRUE));
		}
	}


	public function test(){
		// $this->db->select("j.jobs_id ,  CONCAT(jp.address , ',',jp.zip_Code) as address ");
		// $this->db->join("jobs j" , "j.parent_id = jp.job_parent_id");
  //       $this->db->join("warehouse_status ws" , "ws.warehouse_status_id = j.warehouse_status_id" , "LEFT");
  //       $this->db->where_in("jp.customer_id" , ["4" , "6"]);
  //       $this->db->where("j.warehouse_status_id" , NULL);
  //       //$this->db->where("ws.status" , "LOADED");

  //       $result = $this->db->where("j.driver_id !=" , 0)->where("j.with_outsource" , 0)->order_by("j.loading_time" , "DESC")->get("jobs_parent jp")->result();

  //       foreach($result as $key => $row){
           
  //           $result[$key]->btn_type = (strpos(strtolower($row->address), 'needingworth') !== false) ? "destination" : "load_site";

  //           if($result[$key]->btn_type == "destination"){
  //               $this->db->insert("warehouse_status" , ["jobs_id" => $row->jobs_id , "status" => "TO BE UNLOADED" , "created" => time()]);
  //           }else{
  //              $this->db->insert("warehouse_status" , ["jobs_id" => $row->jobs_id , "status" => "TO BE LOADED" , "created" => time()]);
  //           }


  //           $warehouse_status_id = $this->db->insert_id();
		// 	$this->db->where("jobs_id" , $row->jobs_id)->update("jobs" , ['warehouse_status_id' => $warehouse_status_id]);
           
  //       }

	}

}
