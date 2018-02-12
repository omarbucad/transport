<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();

		$this->data = Share::get_share();

		$this->load->model("Invoice_model" , "invoice");

		if(!$this->session->userdata("id")){
			redirect('/login', 'refresh');
		}

	}

	public function index(){
		redirect('/app/customer/jobs', 'refresh');
	}

	public function accounts(){
		$this->data['page'] = "page/customer/accounts/view";
		$this->data['result'] = $this->customer->getAccount();
		$this->load->view('master' , $this->data );
	}

	public function add(){

		$this->form_validation->set_error_delimiters('<div class="text-danger bg-danger">', '</div>');
	
		$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[accounts.username]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('confirm', 'Password Confirmation', 'trim|required|matches[password]');

		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[accounts.email]');

		$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required');
		$this->form_validation->set_rules('registration_number', 'Registration Name', 'trim|required');
		$this->form_validation->set_rules('vat_number', 'VAT Name', 'trim|required');
		$this->form_validation->set_rules('billing_address', 'Billing Address', 'trim|required');
		
		if ($this->form_validation->run() == FALSE){
			$this->data['page'] = "page/customer/accounts/add";
		}else{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'insert';
			$this->data['last_id'] = $this->customer->insertAccount();
			$this->data['page'] = "page/customer/accounts/success";
		}

		$this->load->view('master' , $this->data);
	}

	public function update($customer_id){


		$this->form_validation->set_error_delimiters('<div class="bg-danger text-danger">', '</div>');

		$this->form_validation->set_rules('company_name', 'Company Name', 'required');
		$this->form_validation->set_rules('registration_number', 'Registration Number', 'required');
		$this->form_validation->set_rules('vat_number', 'VAT Number', 'required');
		$this->form_validation->set_rules('billing_address', 'Billing Address', 'required');


		if ($this->form_validation->run() == FALSE){
			$this->data['page'] = "page/customer/accounts/update";
			$this->data['result'] = $this->customer->getCustomerById($customer_id);
		}else{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'update';
			$this->data['last_id'] = $this->customer->updateCustomer();
			$this->data['page'] = "page/customer/accounts/success";
		}
		
		$this->load->view('master' , $this->data);
	}

	public function jobs($id = false , $job_name = false){

		if($this->session->userdata("account_type") == CUSTOMER){
			$this->data['config']["base_url"] = base_url("app/customer/jobs/") ;
			$this->data['config']["total_rows"] = $this->customer->getJobsListV2(true , false , false , true);

			$this->pagination->initialize($this->data['config']);

			$this->data["links"] = $this->pagination->create_links();

			$this->data['page'] = "page/customer/jobs/jobs_customer";
			$this->data['result'] = $this->customer->getJobsListV2(true , $id , $job_name);
			$this->data['select'] = $this->customer->getSelectData();


			//$this->data['result_count'] = $this->customer->getJobsList(true , $id , $job_name , true);

		}else if($this->session->userdata("account_type") == OUTSOURCE){
			$this->data['page'] = "page/customer/jobs/jobs_outsource";
			$this->data['result'] = $this->customer->getJobsListV2(false , $id , $job_name , false , false , true);
			//$this->data['result_count'] = $this->customer->getJobsList(false , $id , $job_name , true , false , true);
		}else{
			$this->data['config']["base_url"] = base_url("app/customer/jobs/") ;
			$this->data['config']["total_rows"] = $this->customer->getJobsListV2(false , false , false , true);

			$this->pagination->initialize($this->data['config']);

			$this->data["links"] = $this->pagination->create_links();



			$this->data['page'] = "page/customer/jobs/jobs_admin";
			$this->data['result'] = $this->customer->getJobsListV2(false , $id , $job_name);
			$this->data['select'] = $this->customer->getSelectData();

			//$this->data['result_count'] = $this->customer->getJobsList(false , $id , $job_name , true);

			$this->data['cancel_count'] = $this->customer->countCancelled();
		}

		$this->load->view('master' , $this->data);
	}

	public function loadJobById($job_id){
		$data = $this->customer->getSingleJobParse($job_id);
		$this->data['row'] = $data[0];
		$this->data['select'] = $this->customer->getSelectData();

		if($this->session->userdata("account_type") == SUPER_ADMIN){
			$this->load->view("page/customer/ajax/admin_content" , $this->data );
		}else{
			$this->load->view("page/customer/ajax/customer_content" , $this->data );
		}
	
	}

	public function createJob(){

		$this->form_validation->set_rules('job_name', 'Job Name', 'trim|required');
		$this->form_validation->set_rules('number_of_truck', 'Number of Truck', 'trim|required');
		$this->form_validation->set_rules('address', 'Destination', 'trim|required');
		$this->form_validation->set_rules('zip_code', 'Zip Code', 'trim|required');
		// $this->form_validation->set_rules('load_site', 'Load Site', 'trim|required');
		$this->form_validation->set_rules('type_of_truck', 'Type Of Truck', 'trim|required');
		$this->form_validation->set_rules('loading[date][0]', 'loading Date', 'trim|required');
		//$this->form_validation->set_rules('loading[time][0]', 'loading Time', 'trim|required');
		$this->form_validation->set_rules('delivery[date][0]', 'Delivery Date', 'trim|required');
		//$this->form_validation->set_rules('delivery[time][0]', 'Delivery Time', 'trim|required');


		if($this->session->userdata("special_account_type")){
			$this->form_validation->set_rules('division', 'Division', 'trim|required');
			$this->form_validation->set_rules('build_dismantle', 'Build/Dismantle/JTJ', 'trim|required');

			if($this->input->post("build_dismantle", TRUE) == "OTHER"){
				$this->form_validation->set_rules('build_dismantle_other', 'Build/Dismantle/JTJ', 'trim|required');
			}

		}

		if ($this->form_validation->run() == FALSE){
			$errors = validation_errors();
			$errors = str_replace("<p>", "" , $errors);
			$errors = str_replace("</p>", "" , $errors);
			echo json_encode(array("status" => false , "message" => $errors));
		}else{
			if($id = $this->customer->addJobNames()){
				if($parent_id = $this->customer->createJob($id)){

					if($this->session->userdata("account_type") == SUPER_ADMIN){
                        $html = $this->load->view("page/customer/ajax/ajax_admin" , array("result" => $this->customer->getJobsList(false , $parent_id) , "select" => $this->customer->getSelectData()) , TRUE);
                    }else{
                        $html = $this->load->view("page/customer/ajax/ajax_customer" , array("result" => $this->customer->getJobsList(true , $parent_id)) , TRUE);
                    }

					echo json_encode(array("status" => true , "message" => "Success" , "id" => $id , "html" => $html));
				}else{
					echo json_encode(array("status" => false , "message" => "Something Wrong in the Server , Please Try Again Later"));
				}
			}else{
				echo json_encode(array("status" => false , "message" => "Something Wrong in the Server , Please Try Again Later"));
			}
		}
	}

	public function add_artictype(){
    	if($this->input->post()){
    		$this->db->insert("vehicle_artic_type" , ["code" => htmlentities($this->input->post("code", TRUE))]);

    		$arr = array(
    			"id" => $this->db->insert_id(),
    			"code" => $this->input->post("code", TRUE)
    			);

    		echo json_encode($arr);
    	}
    }


    public function updateJob(){
    	$response = $this->customer->updateStatus();

    	if(isset($response['_type'])){
    		$this->generatePdf($response['_job_id'] , true , false , true);
    	}

    	if($response['status'] == true){
    		if($this->input->post("with_outsource", TRUE) == 1){
    			//$this->sendEmailToOutsource($this->input->post("outsource_company_name", TRUE) , $this->input->post("jobs_id", TRUE));
    		}
    	}

    	$job_id = $this->input->post("jobs_id", TRUE);
    	$result = $this->customer->getJobsList(false , false , false , false , $job_id);
    	$select = $this->customer->getSelectData();
    	$html = $this->load->view("page/customer/ajax/ajax_admin" , [ "result" => $result , "select" => $select] , TRUE);

    	$response['html'] = $html;

    	echo json_encode($response);
    }

    public function autocomplete(){
    	echo json_encode($this->customer->autocomplete());
    }

    public function invoices(){
    	$this->data['request_parameters'] = ($this->input->get()) ? basename($_SERVER['REQUEST_URI']).'&generateReport=true' : basename($_SERVER['REQUEST_URI']).'?generateReport=true';

    	if($this->session->userdata("account_type") == CUSTOMER){
    		$this->data['boxResult'] = $this->invoice->getSalesForBox($this->session->userdata("company_id") , true , $this->session->userdata("store_id"));
    		$this->data['page'] = "page/customer/invoice/view_customer";
    	}else{
    		$this->data['page'] = "page/customer/invoice/view_admin";
    		$this->data['boxResult'] = $this->invoice->getSalesForBox($this->session->userdata("company_id") , false , $this->session->userdata("store_id"));
    	}

    	$this->data['result'] = $this->invoice->getInvoiceList();

    	if($this->input->get("generateReport", TRUE)){
    		$this->invoiceReportfixedArray($this->data);
    	}

    	$this->data['select'] = $this->customer->getInvoiceSearchData();
    	$this->load->view('master' , $this->data);
    }

    public function merge_invoice(){
    	$id = $this->input->get("id");

    	$demurrage = 0;
    	$price = 0;
    	$vat = 0;
    	$total_price = 0;

    	$this->db->select_sum("total_price");
    	$this->db->select_sum("vat");
    	$this->db->select_sum("demurrage");
    	$this->db->select_sum("price");
    	$result = $this->db->where_in("invoice_id" , $id)->get("invoice")->row();


    	//GENERATE INVOICE
    	$invoice = $this->db->where("invoice_id" , $id[0])->get("invoice")->row();
    	$j = $this->db->where("jobs_id" , $invoice->job_id)->get("jobs")->row();

    	$i = array(
    		"job_id" => $invoice->job_id,
    		"paid_by" => $invoice->paid_by ,
    		"invoice_date" => time() ,
    		"status" => $invoice->status ,
    		"paid_date" => $invoice->paid_date ,
    		"cheque_number" => $invoice->cheque_number ,
    		"price" => $result->price ,
    		"vat" => $result->vat ,
    		"demurrage" => $result->demurrage ,
    		"total_price" => $result->total_price ,
    		"i_type" => $invoice->i_type ,
    		"notes" => $invoice->notes ,
    		"company_id" => $invoice->company_id ,
    		"customer_id" => $invoice->customer_id ,
    		"merge" => "Y" ,
    		"jmerge_name" => $j->job_name
    	);

    	//create new data on the invoice table
    	$this->db->insert("invoice" , $i);
    	$invoice_id = $this->db->insert_id();

    	// //generate the pdf using the invoice id that created
    	$path = $this->generateMergeInvoice2($this->customer->getJobByIdForInvoice($id , true) , $invoice_id);

    	$this->db->where_in("invoice_id" , $id)->update("invoice" , ["merge_id" => $invoice_id]);


    	$this->db->insert("invoice_id_generator" , ["invoice_id" => $invoice_id]);
    	$lid = $this->db->insert_id();

    	$i["merge"] = "Y";
    	$i["invoice_number"] = "BW".str_pad($lid, 5, '0', STR_PAD_LEFT);
    	$i["generated_pdf"] = $path['path'];
    	$i["generated_pdf_paid"] = $path['path'];

    	$this->db->where("invoice_id" , $invoice_id)->update("invoice" , $i);
    	$this->invoice->add_invoice_history($invoice_id);
    }

    public function pay_invoices(){

    	$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
	
		$this->form_validation->set_rules('paid_by', 'Paid By', 'trim|required');
		$this->form_validation->set_rules('paid_date', 'Paid Date', 'trim|required');
		$this->form_validation->set_rules('notes', 'Notes', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['request_parameters'] = basename($_SERVER['REQUEST_URI']);
	    	$this->data['invoice'] = $this->invoice->getInvoiceList($this->input->get());
	    	$this->data['page'] = "page/customer/invoice/pay";
	    	$this->load->view('master' , $this->data);
		}
		else
		{
			//use invoice updateInvoice
			if($job_id = $this->invoice->updateInvoice($this->customer->getJobByIdForInvoice($this->input->get("id") , true))){

				$id = $this->input->get("id");

				if(is_array($id) AND count($id) == 1){
					$this->generate_invoices();
				}

				foreach($job_id['job_id'] as $id){
					
					$this->notification->add_notification($id , "pay_invoice");
				}

				redirect('/app/customer/invoices', 'refresh');
			}
		}
    }

    public function download_invoices(){
    	$id = ($this->input->get("id")) ? $this->input->get("id") : array();
    	
    	$a = array();

    	foreach($id as $i){
    		$invoice = $this->db->select("generated_pdf , generated_pdf_paid")->where("invoice_id" , $i)->get("invoice")->row();

    		if($invoice->generated_pdf_paid != NULL){
    			$pdf = ltrim($invoice->generated_pdf_paid , ".");
    			$pdf = ltrim($pdf , "/");
    			$path = FCPATH.$pdf;
    		}else{
    			$pdf = ltrim($invoice->generated_pdf , ".");
    			$pdf = ltrim($pdf , "/");
    			$path = FCPATH.$pdf;
    		}

    		$a[] = $path;
    	}

    	if(count($id) > 1){

    		$zipname = time()."_invoice_pdf.zip";

    		$year = date("Y");
	        $month = date("m");
	        $folder = "./public/upload/zip/".$year."/".$month.'/';

	        if (!file_exists($folder)) {
	            mkdir($folder, 0777, true);
	            create_index_html($folder);
	        }

    		$zipname = $folder.$zipname;

	    	$zip = new ZipArchive;
			$zip->open($zipname, ZipArchive::CREATE);
			
			foreach ($a as $file) {
				$file_name = explode("/", $file);
			  	$zip->addFile($file , end($file_name));
			}

			$zip->close();

			header('Content-Type: application/zip');
			header('Content-disposition: attachment; filename='.$zipname);
			header('Content-Length: ' . filesize($zipname));

			readfile($zipname);
    		die(); 
    	}else{

    		$file = $a[0];

    		header("Content-Description: File Transfer"); 
			header("Content-Type: application/octet-stream"); 
			header("Content-Disposition: attachment; filename='" . basename($file) . "'"); 

			readfile ($file);
			die(); 
    	}
    }

    public function confirm_invoices(){

    	if($this->input->post("id", TRUE)){
    		echo json_encode($this->invoice->getInvoiceById($this->input->post("id", TRUE)));
    	}

    	if($this->input->post("invoice_id")){
    		if($job_id = $this->invoice->confirmedInvoice($this->input->post("invoice_id", TRUE))){
				
    			$arr = array(
    				"status" => '<span class="label label-success">COMPLETE</span>',
    				"confirmed_by" => '<span class="label bg-blue-grey">'.$this->session->userdata("name").'</span>'
    				);
				
    			echo json_encode($arr);

    			//$this->generatePdf($job_id , true , true , true);

    			$this->notification->add_notification($job_id , "confirm_invoice");
    		}
			   
    	}
    }

    public function getInvoiceHistory(){

    	if($this->input->post()){
    		$id = $this->input->post("id", TRUE);
    		$this->data['result'] = $this->invoice->get_invoice_history($id);
    		$this->data['type'] = "INVOICE_HISTORY";
    		echo $this->load->view("page/customer/invoice/view_history" , $this->data , true);
    		die();
    	}
    }

    public function getInvoiceListById(){
    	if($this->input->post()){
    		$this->data['result'] = $this->invoice->getInvoiceList(array() , $this->input->post("id", TRUE));
    		$this->data['type'] = "INVOICE_LIST";
	    	echo $this->load->view("page/customer/invoice/view_history" , $this->data , true);
	    	die();
    	}
    }

    public function transaction_logs(){
    	$this->data['result'] = $this->invoice->getTransactionLogs();
    	$this->data['page'] = "page/customer/transaction/view";
    	$this->load->view('master' , $this->data);
    }

    public function getUpdateForm(){

    	$id = $this->input->post("job_id", TRUE);
    	$type = $this->input->post("type", TRUE);


    	$this->data['select'] = $this->customer->getSelectData();
    	$this->data['jobs_id'] = $id;
    	$this->data['result'] = $this->customer->getJobById($id , false);

    	switch ($type) {
    		case 'first':
    			$this->data['type'] = SUPER_ADMIN;
    			echo $this->load->view('page/customer/modal_form/first' , $this->data , true);
    			break;
    		case 'new_customer':
    			$this->data['type'] = CUSTOMER;
    			echo $this->load->view('page/customer/modal_form/new' , $this->data , true);
    			break;
    		case 'new':
    			$this->data['type'] = SUPER_ADMIN;
    			echo $this->load->view('page/customer/modal_form/new' , $this->data , true);
    			break;
    		case 'to_be_allocated':
    			echo $this->load->view('page/customer/modal_form/to_be_allocated' , $this->data , true);
    			break;
    		default:
    			# code...
    			break;
    	}
    	
    }

    public function updateFormJob(){
    	if($this->input->post()){
    		if($result = $this->customer->updateFormJob()){

    			$job_id = $this->input->post("jobs_id", TRUE);
		    	$result = $this->customer->getJobsList(false , false , false , false , $job_id);
		    	$select = $this->customer->getSelectData();
		    	$html = $this->load->view("page/customer/ajax/ajax_admin" , [ "result" => $result , "select" => $select] , TRUE);

    			echo json_encode(["status" => true , "message" => "Job #" . $this->input->post("jobs_id", TRUE) .' has been updated.' , "result" => $result , "job_id" => $this->input->post("jobs_id", TRUE) , "html" => $html ]);
    		}else{
    			echo json_encode(["status" => false , "message" => "Please Try Again"]);
    		}
    	}
    }


    public function backToNew(){

    	if($this->input->post()){

    		$status = $this->db->where("jobs_id" , $this->input->post("jobs_id", TRUE))->get("jobs_status")->result();
	    	$status_id = 0;

	    	foreach($status as $key => $row){
	    		if($row->status_type == "new"){
	    			$status_id = $row->jobs_status_id;
	    		}else{
	    			$this->db->where("jobs_status_id" , $row->jobs_status_id)->delete("jobs_status");
	    		}
	    	}

	    	$this->db->where("jobs_id" , $this->input->post("jobs_id", TRUE))->update("jobs" , [
	    		"price" => 0 ,
	    		"total_price" => 0 ,
	    		"with_outsource" => 0 ,
	    		"outsource_price" => 0,
	    		"outsource_company_name" => "" ,
	    		"status_id" => $status_id ,
	    		"vat" => 0
	    		]);


	    	$job_id = $this->input->post("jobs_id", TRUE);
	    	$result = $this->customer->getJobsListv2(false , false , false , false , $job_id);
	    	$select = $this->customer->getSelectData();
	    	$html = $this->load->view("page/customer/ajax/ajax_admin" , [ "result" => $result , "select" => $select] , TRUE);

	    	$response['html'] = $html;

	    	echo json_encode($response);
    	}
    }

    public function turnoffblinker(){
    	if($this->input->post()){
    		$this->db->where("jobs_id" , $this->input->post("job_id", TRUE))->update("jobs" , ["status_update" => 1]);

    		$job_id = $this->input->post("job_id", TRUE);
	    	$result = $this->customer->getJobsList(false , false , false , false , $job_id);
	    	$select = $this->customer->getSelectData();
	    	$html = $this->load->view("page/customer/ajax/ajax_admin" , [ "result" => $result , "select" => $select] , TRUE);

	    	$response['html'] = $html;

	    	echo json_encode($response);
    	}
    }

    public function set_instructions(){
    	if($this->input->post()){
    		$this->db->insert("driver_instructions" , [
    			"driver_id" => $this->input->post("driver_id", TRUE),
    			"message" => $this->input->post("comment", TRUE),
    			"created" => time()
    			]);

    		send_notification($this->input->post("driver_id", TRUE) , "send_instructions" , true , ["message" => $this->input->post("comment", TRUE)]);
    	}
    }

    public function generate_invoices(){
    	$id = $this->input->get("id");
    	$id = (is_array($id)) ? $id[0] : $id;	

    	if($id){
    		$check = $this->db->select("merge , invoice_id")->where("invoice_id" , $id)->get("invoice")->row();

    		if($check->merge == "Y"){

    			$this->db->select("invoice_id");
    			$result = $this->db->where("merge_id" , $check->invoice_id)->get("invoice")->result();
    			$x = array();

    			foreach($result as $id){
    				$x[] = $id->invoice_id;
    			}

    			$path = $this->generateMergeInvoice2($this->customer->getJobByIdForInvoice($x , true) , $check->invoice_id);

    			$this->db->where("invoice_id" , $check->invoice_id)->update("invoice" , ["generated_pdf" => $path['path'] , "generated_pdf_paid" => $path['path']]);

    			echo base_url().$path['path'];
    		}else{
    			$this->generate_invoices_r($id);
    		}
    	}

    }

    private function generate_invoices_r($id , $old = true){
    	$this->db->select("iti.invoice_transaction_id");
    	$this->db->join("invoice_transaction_invoices iti" , "iti.invoice_transaction_id = i.invoice_transaction_id");
    	$invoice_transaction_id = $this->db->where("iti.invoice_id" , $id)->get("invoice_transaction i")->row();

    	$invoice_id = $this->db->where("invoice_transaction_id" , @$invoice_transaction_id->invoice_transaction_id)->get("invoice_transaction_invoices")->result();

    	if(count($invoice_id) > 1){


    		$x = array();
    		foreach($invoice_id as $i){
    			$x[] = $i->invoice_id;
    		}

    		$path = $this->generateMergeInvoice2($this->customer->getJobByIdForInvoice($x , true) , $id);

    		foreach($invoice_id as $row){
    			$this->db->where("invoice_id" , $row->invoice_id)->update("invoice" , ["generated_pdf_paid" => $path['path']]);
    		}

    		if($old){
    			echo base_url().$path['path'];
    		}else{
    			return base_url().$path['path'];
    		}

    	}else{

    		$job_id = $this->db->select("job_id , confirmed_by , invoice_id")->where("invoice_id" , $id)->get("invoice")->row();

    		$jobs = $this->customer->getJobByIdForInvoice( $id , true);

    		$a = ($job_id->confirmed_by) ? true : false;
    		$x = generatePdfToLocal($jobs[0] , $job_id->invoice_id , $job_id->job_id);

    		$this->invoice->saveInvoicePDF( $x['path'], $job_id->invoice_id , $a );

    		if($old){
    			echo base_url().$x['path'];
    		}else{
    			return base_url().$x['path'];
    		}
    	}
    }
    public function updateJStatus(){
    	if($this->input->post("id")){
    		$this->db->where("jobs_id" , $this->input->post("id"))->update("jobs" , ["j_status" => $this->input->post("status")]);
    	}
    }

    public function add_invoice(){

    	$this->form_validation->set_error_delimiters('<div class="text-danger bg-danger">', '</div>');
	
		$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required');
		$this->form_validation->set_rules('paid_by', 'Paid Status', 'trim|required');
		$this->form_validation->set_rules('invoice_date', 'Invoice Date', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('demurrage', 'Demurrage', 'trim|required');
		$this->form_validation->set_rules('vat', 'VAT', 'trim|required');
		$this->form_validation->set_rules('total_price', 'Total Price', 'trim|required');

		
		if ($this->form_validation->run() == FALSE){
			$this->data['page'] = "page/customer/invoice/add";
		}else{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'insert';
			$this->data['last_id'] = $this->invoice->add_invoice();
			$this->data['page'] = "page/customer/invoice/success";

			$this->generate_invoices_r($this->data['last_id'] , true);
		}

    	$this->load->view("master" , $this->data);
    }

    public function update_invoices(){
    	$invoice_id = $this->input->get("id");

    	$this->form_validation->set_error_delimiters('<div class="text-danger bg-danger">', '</div>');
	
		$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required');
		$this->form_validation->set_rules('paid_by', 'Paid Status', 'trim|required');
		$this->form_validation->set_rules('invoice_date', 'Invoice Date', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('demurrage', 'Demurrage', 'trim|required');
		$this->form_validation->set_rules('vat', 'VAT', 'trim|required');
		$this->form_validation->set_rules('total_price', 'Total Price', 'trim|required');

		
		if ($this->form_validation->run() == FALSE){
			$this->data['result'] = $this->invoice->getInvoiceByIdRaw($invoice_id);
			$this->data['page'] = "page/customer/invoice/update";
		}else{
			$this->data['data'] = $this->input->post();
			$this->data['success_type'] = 'update';
			$this->data['last_id'] = $this->invoice->update_invoice();
			$this->data['page'] = "page/customer/invoice/success";

			//$this->generate_invoices_r($invoice_id , true);
		}

    	$this->load->view("master" , $this->data);
    }

    /*PRIVATE*/

    private function sendEmailToOutsource($outsource_id , $job_id = 0){
    	$this->data['job_data'] = $this->customer->getJobByIdWithName($job_id);
    	$this->data['outsource_data'] = $this->outsource->getOutsourceByIdWithEmail($outsource_id);

    	$this->load->library('email');

    	foreach($this->data['outsource_data']['outsource_data'] as $outsource){
    		$this->email->from( DEFAULT_EMAIL , 'Trackerteer Web Developer');
			//$this->email->to($jobs->email); 

	    	// $this->email->to('mhar@trackerteer.com'); 
	    	// $this->email->set_mailtype("html");

	    	// $this->email->subject('New Transport Job : '.$this->data['job_data']['job_name'].' #'.$this->data['job_data']['job_number']);
	    	// $this->email->message($this->load->view("email/outsource" , $this->data , true));	


	    	// $this->email->send();

	    	// $this->email->clear();
    	}
    }

    private function convert_toBase64($src){
        $type = pathinfo($src, PATHINFO_EXTENSION);
        $data = file_get_contents($src);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }

    private function generatePdf($job_id , $local = true , $paid = false , $send = true){
    	if($jobs = $this->customer->getJobByIdForInvoice($job_id)){
    		foreach($jobs as $key => $row){
    			if($key == 0){
    					if($path = generatePdfToLocal($row , $row->parent_id , $row->jobs_id)){
    					
	    				$this->invoice->saveInvoicePDF( $path['path'] , $row->invoice_id , $paid );

	    				if($send){

		    	// 			$this->load->library('email');

							// $this->email->from( DEFAULT_EMAIL , 'Trackerteer Web Developer');
							// //$this->email->to($jobs->email); 
							
							// $this->email->to('mhar@trackerteer.com'); 
							// $this->email->set_mailtype("html");
							
							// $this->email->subject('Tranport Job Invoice #'.$jobs->invoice_id);
							// $this->email->message($this->load->view("email/pdf" , array() , true));	

							// $this->email->attach($path['absolute_path']);

							// $this->email->send();

							// $this->email->clear();
	    				}

	    			}
    			}

    		}
    	}
    }

    private function invoiceReportfixedArray($data){
    	download_send_headers('invoice_report_' . date("Y-m-d") . ".csv");

    	$arr = array();

    	foreach($data['result'] as $key => $row){
    		$arr[] = array(
    			"Invoice # " => $row->invoice_id ,
    			"Job ID" => $row->job_id ,
    			"Job Name" => $row->job_name ,
    			"Telephone" => $row->telephone ,
    			"Type of Truck" => $row->type_of_truck ,
    			"Loading Time" => $row->loading_time ,
    			"Delivery Time" => $row->time_of_arrival ,
    			"Time of Arrival" => $row->loading_time ,
    			"Unloading Time" => $row->delivered_fulldate ,
    			"Price" => $row->price_raw ,
    			"Demurrage" => $row->demurrage_raw ,
    			"VAT" => $row->vat_raw,
    			"Total Price" => $row->total_price_raw ,
    			"Invoice Date" => $row->invoice_date ,
    			"Paid Date" => $row->paid_date,
    			"Paid By" => $row->paid_status_raw ,
    			"Status" => $row->status_raw ,
    			"Customer" => $row->company_name ,
    			"Registration Number" => $row->registration_number ,
    			"VAT Number" => $row->vat_number ,
    			"Billing Address" => $row->billing_address
    			);
    	}


		echo array2csv($arr);
		die();
    }

    private function generateMergeInvoice2($data , $invoice_id = 0){
    	
    	if($data){
    		$x[] = $invoice_id;

            $a['result'] = $data;
            $a['invoice_id'] = $invoice_id;
            $a['invoice'] = $this->customer->getJobByIdForInvoice($x , true);

            if($path = generatePdfToLocal($a , "MERGE_" , $invoice_id , true)){
                return $path;
            }
        }
    }


    private function generateMergeInvoice($jobs , $transaction_id = 0){
    	
    	if($jobs){
            $data['result'] = $jobs;
            $data['transaction_id'] = $transaction_id;
            $data['price'] = 0;
            $data['vat'] = 0;
            $data['total_price'] = 0;

            foreach($jobs as $row){
                $data['price'] += $row->price;
                $data['vat'] += $row->vat ;
                $data['total_price'] += $row->total_price;
            }
            if($path = generatePdfToLocal($data , $jobs[0]->parent_id , $jobs[0]->jobs_id , true)){
                return $path;
            }
        }
    }


   
   	public function remove_merge(){
   		$this->data['page'] = "page/customer/invoice/remove_invoice";


   		if($this->invoice->getInvoiceListMerge($this->input->get("id"))){
   			$this->data['result'] = $this->invoice->getInvoiceListMerge($this->input->get("id"));
   		}else{
   			redirect("app/customer/invoices" , 'refresh');
   		}
   		

   		$this->load->view('master' , $this->data);
   	}

   	public function remove_merge_id(){
   		$y = $this->db->select("merge_id , demurrage , vat , total_price , price")->where("invoice_id", $this->input->get("id"))->get("invoice")->row();

   		$c = $this->db->where("merge_id" , $y->merge_id)->get("invoice")->num_rows();

   		if($c == 1){
   			$this->db->where("invoice_id" , $y->merge_id)->delete("invoice");
   		}else{
   			$merge_data = $this->db->select("demurrage , vat , total_price , price")->where("invoice_id" , $y->merge_id)->get("invoice")->row();

   			$merge_data->demurrage = ($merge_data->demurrage - $y->demurrage);
   			$merge_data->vat = ($merge_data->vat - $y->vat);
   			$merge_data->total_price = ($merge_data->total_price - $y->total_price);
   			$merge_data->price = ($merge_data->price - $y->price);
   			
   			$this->db->where("invoice_id" , $y->merge_id)->update("invoice" , $merge_data);

   			$this->db->select("invoice_id");
   			$result = $this->db->where("merge_id" , $y->merge_id)->get("invoice")->result();
   			$x = array();

   			foreach($result as $id){
   				$x[] = $id->invoice_id;
   			}

   			$path = $this->generateMergeInvoice2($this->customer->getJobByIdForInvoice($x , true) , $y->merge_id);

   			$this->db->where("invoice_id" , $y->merge_id)->update("invoice" , ["generated_pdf" => $path['path'] , "generated_pdf_paid" => $path['path']]);
   		}

   		$this->db->where("invoice_id" , $this->input->get("id"))->update("invoice" , ["merge_id" => 0]);

   		$this->invoice->add_invoice_history($y->merge_id);
   	}

   	public function add_merge_id(){
   		$y = $this->db->select("merge , demurrage , total_price , price , vat")->where("invoice_id" , $this->input->post("id"))->get("invoice")->row();

   		if($y){
   			$this->db->where("invoice_id" , $this->input->post("id"))->update("invoice" , ["merge_id" => $this->input->post("merge_id")]);

   			$merge_id = $this->input->post("merge_id");

   			$merge_data = $this->db->select("demurrage , vat , total_price , price")->where("invoice_id" , $merge_id)->get("invoice")->row();

   			$merge_data->demurrage = ($merge_data->demurrage + $y->demurrage);
   			$merge_data->vat = ($merge_data->vat + $y->vat);
   			$merge_data->total_price = ($merge_data->total_price + $y->total_price);
   			$merge_data->price = ($merge_data->price + $y->price);
   			
   			$this->db->where("invoice_id" , $merge_id)->update("invoice" , $merge_data);

   			$this->db->select("invoice_id");
   			$result = $this->db->where("merge_id" , $merge_id)->get("invoice")->result();
   			$x = array();

   			foreach($result as $id){
   				$x[] = $id->invoice_id;
   			}

   			$path = $this->generateMergeInvoice2($this->customer->getJobByIdForInvoice($x , true) , $merge_id);

   			$this->db->where("invoice_id" , $merge_id)->update("invoice" , ["generated_pdf" => $path['path'] , "generated_pdf_paid" => $path['path']]);

   			$this->invoice->add_invoice_history($merge_id);

   			echo json_encode(["status" => true , "message" => "Successfully added a invoice"]);
   		}else{
   			echo json_encode(["status" => false , "message" => "something went wrong"]);
   		}
   	}
}
