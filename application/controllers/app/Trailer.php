<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trailer extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
		$this->data = Share::get_share();

		$this->load->model('trailer_model', 'trailer');

		if(!$this->session->userdata("id")){
			redirect('/login', 'refresh');
		}
	}

	public function index()
	{

		$this->data['page'] = "page/trailer/view";
		$this->data['result'] = $this->trailer->getTrailerList();
		$this->load->view('master' , $this->data );
	}

	public function add()
	{
        $this->data["store_list"] = $this->trailer->getStore();
        
		$this->form_validation->set_error_delimiters('<div class="text-danger bg-danger">', '</div>');
		
		$this->form_validation->set_rules('trailer_number', 'Trailer Number', 'trim|required');
		$this->form_validation->set_rules('trailer_make', 'Trailer Make', 'trim|required');
		$this->form_validation->set_rules('trailer_type', 'Trailer Type', 'trim|required');
		$this->form_validation->set_rules('trailer_axle', 'Trailer Axle', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['page'] = "page/trailer/add";
		}
		else
		{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'insert';
			$this->data['last_id'] = $this->trailer->InsertTrailer();
			$this->data['page'] = "page/trailer/success";
		}

		$this->load->view('master' , $this->data );
	}
	public function removeTrailer(){
		if($this->input->post()){
			$this->trailer->removeTrailer($this->input->post("id", TRUE));
		}else{
			redirect('/trailer', 'refresh');
		}
	}

	public function updateTrailer($id){
		$this->form_validation->set_error_delimiters('<div class="bg-danger text-danger">', '</div>');
		
		if($this->input->post('old_trailer_number', TRUE) != $this->input->post('trailer_number', TRUE)){
			$this->form_validation->set_rules('trailer_number', 'Trailer Number', 'required');
		}
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		$this->form_validation->set_rules('trailer_make', 'Trailer Make', 'trim|required');
		$this->form_validation->set_rules('trailer_type', 'Trailer Type', 'trim|required');
		$this->form_validation->set_rules('trailer_axle', 'Trailer Axle', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['page'] = "page/trailer/update";
			$this->data['result'] = $this->trailer->getTrailerById($id);
		}
		else
		{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'update';
			$this->data['last_id'] = $this->trailer->UpdateTrailer();
			$this->data['page'] = "page/trailer/success";
		}

		$this->load->view('master' , $this->data );
	}
}
