<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'third_party/pdf/vendor/autoload.php');

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class Report extends CI_Controller {

	function __construct( ) {
		parent::__construct();
		$this->output->set_header('Access-Control-Allow-Origin: *');

		// $a = $this->input->request_headers();

		// if($a["SERVER_TOKEN"] != SERVER_TOKEN){
		// 	die(header('HTTP/1.1 401 Unauthorized', true, 401));
		// }

		$this->load->model('login_model', 'login');
		$this->load->model('report_model', 'report');
		$this->load->model('vehicle_model', 'vehicle');
		$this->load->model('trailer_model', 'trailer');
	}

	public function generateId(){

		$last_id = $this->report->first();
		
		if($last_id){
			echo json_encode(['id' => $last_id , "success" => true]);
		}else{
			echo json_encode(["success" => false]);
		}
		
	}
	
	public function insertChecklist(){
		$this->report->second();
	}
	
	public function insertReport(){
		$this->report->third();
	}

	public function insertDefectReportWithImage(){
		$last_id = $this->report->uploadImage();
		echo json_encode(["id" => $last_id]);
	}

	public function removeImage(){
		if($this->input->post("id")){
			$id = $this->input->post("id");
			$this->report->removeImage($id);
		}
	}

	public function getReport(){
		echo json_encode($this->report->getMyReport(false , 'j.delivery_time'));
	}

	public function fixedVehicle(){

		$driver_id = $this->input->post("driver_id");
		$report_id = $this->input->post("report_id");
		$comment = $this->input->post("comment");
		$job_id = $this->input->post("job_id");

		if($this->input->post()){
			$arr = array(
		        "report_id" => $report_id,
		        "status" => 3,
		        "account_id" => $driver_id,
		        "comment" => $comment,
		        "created" => time()
		        );


      		$this->db->insert('report_status' , $arr);

      		$last_id = $this->db->insert_id();

      		$this->db->where("id" , $report_id)->update('report' , ["status_id" => $last_id]);

			//$this->db->where("jobs_id" , $job_id)->update("jobs" , ["begin_driving_time" => time() ]);
		}
	}

	
	public function getChecklist(){
		$report = $this->report->getChecklist();

		foreach($report as $key => $r){

			$html2pdf = new HTML2PDF('P','A4','en' , true , 'UTF-8' , $marges = array(10, 10, 10, 10));

			try{
				$year = date("Y");
		        $month = date("m");
		        $folder = FCPATH."/public/upload/driver_pdf/";

		        if (!file_exists($folder)) {
		            mkdir($folder, 0777, true);
		            create_index_html($folder);
		        }
		        
		        $filename = $r->id.'_'.str_replace(" ", "_", $r->checklist_type).'_'.str_replace(" ", "_", $r->start_date).'.pdf';

				if( !file_exists($folder.$filename)) {

		        	$path = $folder.'/'.$filename;
					$html2pdf->writeHTML($this->load->view("common/checklist" , $r , TRUE));
					$html2pdf->Output($path , 'F');
		        }

			

				$report[$key]->pdf = site_url("/public/upload/driver_pdf/".$filename);
				$report[$key]->pdf_name = $filename;

			}catch (Html2PdfException $e) {
				$formatter = new ExceptionFormatter($e);
				echo $formatter->getHtmlMessage();
			}	
		}

		echo json_encode($report);
	}
}
