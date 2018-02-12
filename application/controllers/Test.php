<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
	}

	public function index(){

		// $this->db->select("jp.company_id , jp.customer_id , j.jobs_id , j.job_number, j.job_po_number");
		// $this->db->join("jobs j" , "j.jobs_id = i.job_id");
		// $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id");
		// $list = $this->db->get("invoice i")->result();

		// foreach($list as $row){
		// 	$this->db->where("job_id" , $row->jobs_id)->update("invoice" , ["job_number" => $row->job_number , "jpo_number" => $row->job_po_number ]);
		// }


		$check = $this->db->select("merge , invoice_id")->where("merge_id" , 0)->get("invoice")->result();
		
		$x = array();

		foreach($check as $row){

			if($row->merge == "Y"){

				$this->db->select("invoice_id");
				$result = $this->db->where("merge_id" , $row->invoice_id)->get("invoice")->result();

				$m = array();

				foreach($result as $id){

					$m[] = $id->invoice_id;
					
				}


				$r = array();

	    		$r['result'] = $this->getJobByIdForInvoice($m , true);
	    		$r['invoice_id'] = $row->invoice_id;
	    		$r['invoice'] = $this->getJobByIdForInvoice($row->invoice_id , true);
	    		$r['filename'] = "transport_app_invoice_MERGE_".$row->invoice_id.'_'.time().'.pdf';

				$x["MERGE"][] = $r;
			}else{

				$a = $this->generate_invoices_r($row->invoice_id);
				
				if(isset($a['result'])){
					$x["MERGE"][] = $a;
				}else{
					$x["NOT_MERGE"][] = $a;
				}
				
			}

		}

		$res = $this->pdf->generate_all($x);

		foreach($res['NOT_MERGE'] as $id){
			$this->db->where("invoice_id" , $id['invoice_id'])->update("invoice" , ['generated_pdf_paid' => $id['path'] , 'generated_pdf' => $id['path']]);
		}

		foreach($res['MERGE'] as $id){
			$this->db->where("invoice_id" , $id['invoice_id'])->update("invoice" , ['generated_pdf_paid' => $id['path'] , 'generated_pdf' => $id['path']]);
		}

		print_r_die($res);
	
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

    		$y[] = $id;

    		$r = array();

    		$r['result'] = $this->getJobByIdForInvoice($x , true);
    		$r['invoice_id'] = $id;
    		$r['invoice'] = $this->getJobByIdForInvoice($y , true);
    		$r['filename'] = "transport_app_invoice_MERGE_".$id.'_'.time().'.pdf';

    		$x = $r;
    		

    	}else{
    		

    		$job_id = $this->db->select("job_id , confirmed_by , invoice_id")->where("invoice_id" , $id)->get("invoice")->row();
    		$jobs = $this->getJobByIdForInvoice( $id , true);
    		$a = ($job_id->confirmed_by) ? true : false;


    		//$x = generatePdfToLocal($jobs[0] , $job_id->invoice_id , $job_id->job_id);
    		$x['data'] = $jobs[0];
    		$x['filename'] = "transport_app_invoice_".$job_id->invoice_id .'_'.$job_id->job_id.'_'.time().'.pdf';
    		
    	}

    	return $x;
    }


    public function getJobByIdForInvoice($job_id , $invoice = false){
        $this->db->select("i.paid_by , i.invoice_date , i.invoice_number , i.invoice_id , i.job_id , i.paid_date , i.confirmed_by , i.price , i.demurrage , i.total_price , i.vat , i.to_outsource , i.customer_id , i.job_number as jn , i.notes as inotes  , i.jpo_number");
        $this->db->select("j.job_name , j.telephone , j.address , j.job_notes as JPnotes");
        $this->db->select("c.company_name , c.registration_number , c.vat_number , c.account_no , c.billing_address");
        $this->db->select("CONCAT(a.name , ' ' , a.surname ) AS user_name , a.email , a.address as user_address");
        $this->db->select("j.type_of_truck , j.loading_time , j.delivery_time , j.signature, j.parent_id , j.jobs_id , j.signature , j.signature_name , j.with_outsource , j.job_number , j.job_po_number");
        $this->db->select("o.billing_address as outsource_billing_address , o.vat_number as outsource_vat_number");
        $this->db->join("jobs j" , "i.job_id = j.jobs_id" , 'LEFT');
        $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id" , "LEFT" );
        
        $this->db->join("accounts a" , "a.id = jp.account_id" , "LEFT" );
        
        $this->db->join("customer c " , 'c.customer_id = i.customer_id' );
        $this->db->join("outsource o" , "o.outsource_id = j.outsource_company_name" , "LEFT");

        if($invoice){
            $this->db->where_in("i.invoice_id" , $job_id);
        }else{
            $this->db->where("j.jobs_id" , $job_id);
        }
        $result = $this->db->order_by('j.delivery_time' , "ASC")->get("invoice i")->result();

        if($result){
            foreach($result as $key => $row){
                $result[$key]->created = convert_timezone(time() , false , false , false , "" , "d/m/Y");
                $result[$key]->price = round($row->price , 2);
                $result[$key]->total_price = round($row->total_price , 2);
                $result[$key]->demurrage = round($row->demurrage , 2);
                $result[$key]->vat = round($row->vat , 2);
                $result[$key]->total_price = round($row->total_price , 2);
                $result[$key]->loading_time = convert_timezone($row->loading_time);
                $result[$key]->delivery_time = convert_timezone($row->delivery_time , false , false , false , "" , "d/m/Y");
                $result[$key]->invoice_date = convert_timezone($row->invoice_date , false , false , false , "" , "d/m/Y");
                $result[$key]->paid_date = convert_timezone($row->paid_date);
                $result[$key]->paid_by = paid_status($row->paid_by , true , ($row->confirmed_by) ? "COMPLETE" : FALSE);
                $result[$key]->billing_address = nl2br($row->billing_address);


                if($result[$key]->signature){

                  $image = full_path_image($result[$key]->signature , 'signature');
                  $result[$key]->signature = $image['path'];

                    if(!file_exists($image['absolute_path'])){
                        $result[$key]->signature = $this->config->base_url('/public/images/image-not-found.png');
                    }

                }else{
                    $result[$key]->signature = $this->config->base_url('/public/images/image-not-found.png');
                }
            }
        }

        return $result;
    }


    public function a(){
    	$invoice_id = 857;

    	$this->db->select("invoice_id");
    	$result = $this->db->where("merge_id" , $invoice_id)->get("invoice")->result();

    	$m = array();

    	foreach($result as $id){

    		$m[] = $id->invoice_id;

    	}


    	$r = array();

    	$r['result'] = $this->getJobByIdForInvoice($m , true);
    	

    	$tmp = $r;
    	$tmp2 = array();
    	$y = array();
    	$x = 0;

    	foreach($r['result'] as $row){

    		if($x <= 6){
    			$tmp2[] = $row->invoice_id;
    			$x++;
    		}else{
    			$tmp2[] = $row->invoice_id;
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



    	print_r_die($y);
    }
}
