<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification {
	
	public $CI;
	public $session;

	function __construct( ) {
		$this->CI =& get_instance();	
		$this->session = $this->CI->session->userdata();

		if($this->CI->input->post("from_device")){
			$this->session = $this->getAccount($this->CI->input->post("account_id"));
		}
	}

	public function get_notification($viewAll = false){
		$my_id = $this->session['id'];
		
		$this->CI->db->select("notification_id , icon , message , link , created , color , has_open")->where("to_id" , $my_id)->order_by("created" , "DESC");

		if($viewAll){
			$notification = $this->CI->db->get("notification")->result();
		}else{
			$notification = $this->CI->db->limit(10)->get("notification")->result();
		}

		foreach($notification as $key => $row){
			$notification[$key]->created = fromNow($row->created);
 		}

 		$unread = $this->CI->db->where(array("to_id" => $my_id , "has_open" => 0))->get("notification")->num_rows();

 		return array("notification" => $notification , "unread" => $unread);
	}

	public function add_notification($id , $type){

		$group_id = $this->session["company_id"];
		$from_id = $this->session["id"];

		if($this->session["account_type"] == CUSTOMER){

			$this->CI->db->select("id , CONCAT(name , ' ' , surname) as name");
			$this->CI->db->where_in("account_type" , array(SUPER_ADMIN , ADMIN));
			$this->CI->db->where("company_id" , $this->session["company_id"]);
			$to_id = $this->CI->db->get("accounts")->result();
			
		}else{

			$this->CI->db->select("company_name as name , jp.account_id as id");
			$this->CI->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id");
			$this->CI->db->join("customer c" , "c.customer_id = jp.customer_id");
			$this->CI->db->where("jobs_id" , $id);

			$to_id = $this->CI->db->get("jobs j")->result();
		}

		$data = array(
			"from_id" => $from_id ,
			"to_id" => $to_id ,
			"job_id" => $id
			);

		switch ($type) {
			case "cancel":
				$this->cancel_job($data);
				break;
			case "partially_complete":
				$this->partially_finished_job($data);
				break;
			case "finished":
				$this->finished_job($data);
				break;
			case "allocated":
				$this->allocated_job($data);
				break;
			case "to_be_allocated":
				$this->to_be_allocated_job($data);
				break;
			case 'pay_invoice':
				$this->pay_invoice($data);
				break;
			case 'confirm_invoice':
				$this->confirm_invoice($data);
				break;
			case 'new_job':
				if($this->session["account_type"] == CUSTOMER){
					$this->new_job($data);
				}
				break;
			default:
				# code...
				break;
		}

	}

	private function cancel_job($data){
		
		foreach($data["to_id"] as $id){
			$this->CI->db->insert("notification" , array(
				"from_id" => $data["from_id"] ,
				"to_id" => $id->id ,
				"icon" => "highlight_off" ,
				"message" => $this->message("CANCEL" , ["job_id" => $data["job_id"]]),
				"link" => "app/customer/jobs?job_number=".$data["job_id"]."&submit=submit",
				"color" => "bg-red",
				"created" => time() ,
				"has_open" => 0
				));
		}
	
	}

	private function to_be_allocated_job($data){

		foreach($data["to_id"] as $id){
			$this->CI->db->insert("notification" , array(
				"from_id" => $data["from_id"] ,
				"to_id" => $id->id ,
				"icon" => "bookmark" ,
				"message" => $this->message("TO_BE_ALLOCATED" , ["job_id" => $data["job_id"]]),
				"link" => "app/customer/jobs?job_number=".$data["job_id"]."&submit=submit",
				"color" => "bg-orange",
				"created" => time() ,
				"has_open" => 0
				));
		}
	}

	private function allocated_job($data){
		foreach($data["to_id"] as $id){
			$this->CI->db->insert("notification" , array(
				"from_id" => $data["from_id"] ,
				"to_id" => $id->id ,
				"icon" => "book" ,
				"message" => $this->message("ALLOCATED" , ["job_id" => $data["job_id"]]),
				"link" => "app/customer/jobs?job_number=".$data["job_id"]."&submit=submit",
				"color" => "bg-light-green",
				"created" => time() ,
				"has_open" => 0
				));
		}
	}

	private function partially_finished_job($data){
		foreach($data["to_id"] as $id){
			$this->CI->db->insert("notification" , array(
				"from_id" => $data["from_id"] ,
				"to_id" => $id->id ,
				"icon" => "bookmark_border" ,
				"message" => $this->message("PARTIALLY" , ["job_id" => $data["job_id"]]),
				"link" => "app/customer/jobs?job_number=".$data["job_id"]."&submit=submit",
				"color" => "bg-blue",
				"created" => time() ,
				"has_open" => 0
				));
		}
	}

	private function finished_job($data){
		foreach($data["to_id"] as $id){
			$this->CI->db->insert("notification" , array(
				"from_id" => $data["from_id"] ,
				"to_id" => $id->id ,
				"icon" => "check_circle" ,
				"message" => $this->message("FINISHED" , ["job_id" => $data["job_id"]]),
				"link" => "app/customer/jobs?job_number=".$data["job_id"]."&submit=submit",
				"color" => "bg-green",
				"created" => time() ,
				"has_open" => 0
				));
		}
	}

	private function pay_invoice($data){
		foreach($data["to_id"] as $id){
			$this->CI->db->insert("notification" , array(
				"from_id" => $data["from_id"] ,
				"to_id" => $id->id ,
				"icon" => "check_circle" ,
				"message" => $this->message("PAY_INVOICE" , ["job_id" => $data["job_id"]]),
				"link" => "",
				"color" => "bg-blue",
				"created" => time() ,
				"has_open" => 0
				));
		}
	}

	private function confirm_invoice($data){
		foreach($data["to_id"] as $id){
			$this->CI->db->insert("notification" , array(
				"from_id" => $data["from_id"] ,
				"to_id" => $id->id ,
				"icon" => "check_circle" ,
				"message" => $this->message("CONFIRM_INVOICE" , ["job_id" => $data["job_id"]]),
				"link" => "",
				"color" => "bg-orange",
				"created" => time() ,
				"has_open" => 0
				));
		}
	}

	private function new_job($data){
		$parent_id = $data['job_id'];

		$this->CI->db->select("company_name");
		$this->CI->db->join("customer c" , "c.customer_id = jp.customer_id");
		$customer = $this->CI->db->where("job_parent_id" , $parent_id)->get("jobs_parent jp")->row();

		foreach($data["to_id"] as $id){
			$this->CI->db->insert("notification" , array(
				"from_id" => $data["from_id"] ,
				"to_id" => $id->id ,
				"icon" => "bookmark" ,
				"message" => $this->message("NEW_JOB" , $customer->company_name),
				"link" => "app/customer/jobs/".$parent_id,
				"color" => "bg-blue",
				"created" => time() ,
				"has_open" => 0
				));
		}
	}

	private function message($type , $data){
		switch ($type) {
			case 'CANCEL':
				return "Job #".$data["job_id"]." has been cancelled.";
				break;
			case 'TO_BE_ALLOCATED':
				return "Job #".$data["job_id"]." has been booked.";
				break;
			case 'ALLOCATED':
				return "Job #".$data["job_id"]." has been allocated.";
				break;
			case 'PARTIALLY':
				return "Job #".$data["job_id"]." completed";
				break;
			case 'FINISHED':
				return "Job #".$data["job_id"]." invoice has been sent.";
				break;
			case 'PAY_INVOICE':
				return "Invoice #".$data["job_id"]." has been paid.";
				break;
			case 'CONFIRM_INVOICE':
				return "Invoice #".$data["job_id"]." has been confirmed.";
				break;
			case 'NEW_JOB':
				return $data." created a new job.";
				break;
			default:
				# code...
				break;
		}
	}

	private function getAccount($id = false){
        

        $this->CI->db->select('c.company_name , a.* , us.store_id');
        $this->CI->db->where("a.id" , $id);    
        

        $this->CI->db->where("status" , 1);
        $this->CI->db->join("company c" , "c.id = a.company_id");
        $this->CI->db->join("users_store us" , "us.account_id = a.id" , "LEFT");

        $result = $this->CI->db->get('accounts a')->row_array();

        if($result){
            $image = full_path_image($result['image'] , 'accounts');
            $result['image'] = $image['path'];

            if(!file_exists($image['absolute_path'])){
                $result['image'] = $this->CI->config->base_url('/public/images/image-not-found.png');
            }

            if($result["account_type"] == SUPER_ADMIN){
                $store = $this->CI->db->select("store_id")->where("company_id" , $result["company_id"])->get("store")->row_array();
                $result["store_id"] = $store["store_id"];
            }
        }

        return $result;
    }

}