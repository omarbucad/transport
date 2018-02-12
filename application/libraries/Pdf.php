<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Load the DOMPDF libary
require(APPPATH.'third_party/pdf/vendor/autoload.php');

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class Pdf {

	private $html2pdf = array();

	function __construct( ) {
		$this->html2pdf = new HTML2PDF('P','A4','en' , true , 'UTF-8' , $marges = array(10, 10, 10, 10));
	}

	public function create_invoice($data = array() , $filename){
		$CI =& get_instance();

		try{
			$year = date("Y");
	        $month = date("m");
	        $folder = FCPATH."/public/upload/pdf/".$year."/".$month;

	        if (!file_exists($folder)) {
	            mkdir($folder, 0777, true);
	            create_index_html($folder);
	        }

			//$filename = 'transport_app_invoice_'.time().'.pdf';
			$path = $folder.'/'.$filename;
			$this->html2pdf->writeHTML($CI->load->view("common/invoice" , $data , TRUE));
			$this->html2pdf->Output($path , 'F');

			return "/public/upload/pdf/".$year."/".$month.'/'.$filename;

		}catch (Html2PdfException $e) {
			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();
		}

	}

	public function create_invoice_merge($data = array() , $filename){
		$CI =& get_instance();

		try{
			$year = date("Y");
	        $month = date("m");
	        $folder = FCPATH."/public/upload/pdf/".$year."/".$month;

	        if (!file_exists($folder)) {
	            mkdir($folder, 0777, true);
	            create_index_html($folder);
	        }
	        $tmp = $data;
	        $tmp2 = array();
	        $y = array();
	        $x = 0;

	        foreach($data['result'] as $row){

	        	if($x < 6){
	        		$tmp2[] = $row;
	        		$x++;
	        	}else{
	        		$tmp2[] = $row;
	        		$tmp['result'] = $tmp2;
	        		$y[] = $tmp;
	        		$tmp2 = array();
	        		
	        		$x = 0;
	        	}
	
	        }

	        if($tmp2){
	        	$tmp['result'] = $tmp2;
	        	$y[] = $tmp;
	        }

	        if(!$y){
	        	$y[] = $tmp;
	        }

			//$filename = 'transport_app_invoice_'.time().'.pdf';
			$path = $folder.'/'.$filename;
			$this->html2pdf->writeHTML($CI->load->view("common/invoice_merge" , ["y" => $y] , TRUE));
			$this->html2pdf->Output($path , 'F');

			return "/public/upload/pdf/".$year."/".$month.'/'.$filename;

		}catch (Html2PdfException $e) {
			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();
		}
	}


	public function generate_all($data){
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 900);

		$CI =& get_instance();
		$my = array();

		$year = date("Y");
		$month = date("m");
		$folder = FCPATH."/public/upload/pdf/".$year."/".$month;

		if (!file_exists($folder)) {
			mkdir($folder, 0777, true);
			create_index_html($folder);
		}

		try{
			foreach($data['NOT_MERGE'] as $k => $d){
				$path = $folder.'/'.$d['filename'];
				$html2pdf = new HTML2PDF('P','A4','en' , true , 'UTF-8' , $marges = array(10, 10, 10, 10));

				$html2pdf->writeHTML($CI->load->view("common/invoice" , $d['data'] , TRUE));
				$html2pdf->Output($path , 'F');
				$my["NOT_MERGE"][] = array(
					"invoice_id" => $d['data']->invoice_id ,
					"path" => "/public/upload/pdf/".$year."/".$month.'/'.$d['filename']
				);
			}


			foreach($data['MERGE'] as $k => $d){
				$tmp = $d;
		        $tmp2 = array();
		        $y = array();
		        $x = 0;

		        foreach($d['result'] as $row){

		        	if($x < 7){
		        		$tmp2[] = $row;
		        		$x++;
		        	}else{
		        		$tmp['result'] = $tmp2;
		        		$y[] = $tmp;
		        		$tmp2 = array();
		        		$tmp2[] = $row;
		        		$x = 0;
		        	}
		
		        }

		        if($tmp2){
		        	$tmp['result'] = $tmp2;
		        	$y[] = $tmp;
		        }

		        if(!$y){
		        	$y[] = $tmp;
		        }

		        $path = $folder.'/'.$d['filename'];
		        $html2pdf = new HTML2PDF('P','A4','en' , true , 'UTF-8' , $marges = array(10, 10, 10, 10));
				$html2pdf->writeHTML($CI->load->view("common/invoice_merge" , ["y" => $y] , TRUE));
				$html2pdf->Output($path , 'F');

				$my["MERGE"][] = array(
					"invoice_id" => $d['invoice_id'] ,
					"path" => "/public/upload/pdf/".$year."/".$month.'/'.$d['filename']
				);
			}

			return $my;

		}catch(Html2PdfException $e){
			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();
		}
		

	}

}