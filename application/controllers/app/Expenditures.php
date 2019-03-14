<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenditures extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
		$this->data = Share::get_share();

		$this->load->model('expenditure_model', 'expenditure');

		if(!$this->session->userdata("id")){
			redirect('/login', 'refresh');
		}
	}

	public function index(){
        $this->data['request_parameters'] = ($this->input->get()) ? basename($_SERVER['REQUEST_URI']).'&generateExcel=true' : basename($_SERVER['REQUEST_URI']).'?generateExcel=true';

		$this->data['page'] = "page/expenditure/view";
        $this->data["categoryList"] = $this->expenditure->categoryList();
        $this->data["typeList"] = $this->expenditure->typeList();
		$this->data['expenditure_site_url'] = "expenditures";
		$this->data['result'] = $this->expenditure->expenditureList();
		$this->load->view('master' , $this->data );

	}
	
	public function add(){
        $this->data["categoryList"] = $this->expenditure->categoryList();
        $this->data["typeList"] = $this->expenditure->typeList();

		$this->form_validation->set_error_delimiters('<div class="bg-danger text-danger">', '</div>');
		
		$this->form_validation->set_rules('invoice_date', 'Invoice Date', 'trim|required');

		if ($this->form_validation->run() == FALSE){
			$this->data['page'] = "page/expenditure/add";
            
		}
		else{
            
            $this->data['success_type'] = 'insert';
            $this->data['result'] = $this->expenditure->add();
            $this->data['page'] = "page/expenditure/success";
		}
        $this->load->view('master' , $this->data );

	}

    public function edit($exp_id){
        $this->data["categoryList"] = $this->expenditure->categoryList();
        $this->data["typeList"] = $this->expenditure->typeList();

        $this->form_validation->set_error_delimiters('<div class="bg-danger text-danger">', '</div>');
        
        $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'trim|required');
        $this->form_validation->set_rules('vat', 'Vat', 'trim|required');
        $this->form_validation->set_rules('subtotal', 'Subtotal', 'trim|required');

        if ($this->form_validation->run() == FALSE){

            $this->data['data'] = $this->expenditure->get_expenditure($exp_id);
            $this->data['page'] = "page/expenditure/edit";
        }
        else{
            
            $this->data['success_type'] = 'update';
            $this->data['result'] = $this->expenditure->edit($exp_id);
            $this->data['page'] = "page/expenditure/success";
        }
        $this->load->view('master' , $this->data );

    }

    public function delete($exp_id){
        if($exp_id){
            $deleted = $this->expenditure->delete($exp_id);

            if($deleted){
                echo json_encode(["status" => true, "message"=>"Successfully deleted"]);    
            }else{
                echo json_encode(["status" => false, "message"=>"Something went wrong. Try Again"]);
            }
            
        }

    }

	public function removeVehicle(){
		if($this->input->post()){
			$this->vehicle->removeVehicle($this->input->post("id", TRUE));
		}else{
			redirect('/vehicle', 'refresh');
		}
	}

	public function updateVehicle($id){
		$this->form_validation->set_error_delimiters('<div class="bg-danger text-danger">', '</div>');

		$this->form_validation->set_rules('truck_make', 'Truck Make', 'required');
		$this->form_validation->set_rules('truck_type', 'Truck Type', 'required');

		
		if($this->input->post('old_vehicle_number', TRUE) != $this->input->post('vehicle_number', TRUE)){
			$this->form_validation->set_rules('vehicle_number', 'Trailer Number', 'trim|required|numeric|is_unique[vehicle_information.vehicle_number]');

		}
		$this->form_validation->set_rules('status', 'Status', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['page'] = "page/vehicles/update";
			$this->data['result'] = $this->vehicle->getVehicleById($id);
		}
		else
		{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'update';
			$this->data['last_id'] = $this->vehicle->UpdateVehicle();
			$this->data['page'] = "page/vehicles/success";
		}

		$this->load->view('master' , $this->data );
	}
    
    public function driver(){
        $this->data['page'] = "page/vehicles/driver";
        $this->data["result"] = $this->vehicle->getDriver();
        $this->load->view('master' , $this->data );
    }
    
    public function getSelectVehicleAndTrailer(){
        echo json_encode($this->vehicle->getSelectVehicleAndTrailer());
    }
    
    public function setDefaultTruck(){
        $this->vehicle->setDefaultTruck();
    }

    public function removeFileDriver(){
        if($this->input->post("id")){
            $this->db->where("driver_image_id" , $this->input->post("id"))->delete("driver_images");
        }
    }

    public function add_artic_type(){
    	if($this->input->post()){
    		$this->db->insert("vehicle_artic_type" , ["code" => htmlentities($this->input->post("code" , TRUE)) , "company_id" => $this->session->userdata("company_id")] );

    		$arr = array(
    			"id" => $this->db->insert_id(),
    			"code" => $this->input->post("code" , TRUE)
    			);

    		echo json_encode($arr);
    	}
    }

    public function removeArticType(){
    	$this->db->where("vehicle_artic_type_id" , $this->input->post("id", TRUE))->delete("vehicle_artic_type");
    }
    public function updateArticType(){
    	$this->db->where("vehicle_artic_type_id" , $this->input->post("id", TRUE))->update("vehicle_artic_type" , ["code" => htmlentities($this->input->post("code", TRUE))]);
    }

    public function artic_type(){
    	$this->data['page'] = "page/vehicles/artic_type";
    	$this->data['result'] = $this->db->where('company_id',$this->session->userdata('company_id'))->order_by("code" , "ASC")->get("vehicle_artic_type")->result();
    	$this->load->view('master' , $this->data );	
    }


    public function add_vehicle_type(){
    	if($this->input->post()){
    		$this->db->insert("vehicle_type_of_truck" , ["code" => htmlentities($this->input->post("code", TRUE)) , "company_id" => $this->session->userdata("company_id")]);

    		$arr = array(
    			"id" => $this->db->insert_id(),
    			"code" => $this->input->post("code", TRUE)
    			);

    		echo json_encode($arr);
    	}
    }

    public function removeVehicleType(){
    	$this->db->where("vehicle_type_id" , $this->input->post("id"))->delete("vehicle_type_of_truck");
    }
    public function updateVehicleType(){
    	$this->db->where("vehicle_type_id" , $this->input->post("id", TRUE))->update("vehicle_type_of_truck" , ["code" => $this->input->post("code")]);
    }

    public function truck_type(){
    	$this->data['page'] = "page/vehicles/truck_type";
    	$this->data['result'] = $this->db->order_by("code" , "ASC")->get("vehicle_type_of_truck")->result();
    	$this->load->view('master' , $this->data );	
    }

    
    public function add_division_type(){
    	if($this->input->post()){
    		$this->db->insert("vehicle_division_type" , ["code" => htmlentities($this->input->post("code", TRUE)) , "company_id" => $this->session->userdata("company_id")] );

    		$arr = array(
    			"id" => $this->db->insert_id(),
    			"code" => $this->input->post("code", TRUE)
    			);

    		echo json_encode($arr);
    	}
    }


    public function removeDivisionType(){
    	$this->db->where("division_type_id" , $this->input->post("id"))->delete("vehicle_division_type");
    }
    public function updateDivisionType(){
    	$this->db->where("division_type_id" , $this->input->post("id", TRUE))->update("vehicle_division_type" , ["code" => htmlentities($this->input->post("code"))]);
    }

    public function division_type(){
    	$this->data['page'] = "page/vehicles/division";
    	$this->data['result'] = $this->db->where('company_id',$this->session->userdata('company_id'))->order_by("code" , "ASC")->get("vehicle_division_type")->result();
    	$this->load->view('master' , $this->data );	
    }


    public function add_build(){
    	if($this->input->post()){
    		$this->db->insert("vehicle_build_type" , ["code" => htmlentities($this->input->post("code", TRUE)) , "company_id" => $this->session->userdata("company_id")]);

    		$arr = array(
    			"id" => $this->db->insert_id(),
    			"code" => $this->input->post("code", TRUE)
    			);

    		echo json_encode($arr);
    	}
    }

    public function removeBuildType(){
    	$this->db->where("build_type_id" , $this->input->post("id", TRUE))->delete("vehicle_build_type");
    }
    public function updateBuildType(){
    	$this->db->where("build_type_id" , $this->input->post("id", TRUE))->update("vehicle_build_type" , ["code" => htmlentities($this->input->post("code", TRUE))]);
    }

    public function build_type(){
    	$this->data['page'] = "page/vehicles/build";
    	$this->data['result'] = $this->db->where('company_id',$this->session->userdata('company_id'))->order_by("code" , "ASC")->get("vehicle_build_type")->result();
    	$this->load->view('master' , $this->data );	
    }


}