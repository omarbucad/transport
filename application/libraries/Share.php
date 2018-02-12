<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Share {

	public static function get_share(){
		$CI =& get_instance();

	    $CI->load->model('dashboard_model', 'dashboard');  
	    $CI->load->model('outsource_model', 'outsource');  
	    $CI->load->model('customer_model', 'customer'); 

		$data['title'] = "Transport Website";

		if($CI->session->userdata("id")){

			$CI->dashboard->updateStillActiveAccount();

			$data['jobs_name'] = $CI->customer->getJobNames();
			$data['notification'] = $CI->notification->get_notification();
			$data['show_online'] = $CI->dashboard->getStillActiveAccount();
			$data['customerList'] = $CI->customer->getAllCustomer();

			$data['outsource_list'] = $CI->outsource->getListForSelect();
			$data['type_of_truck_modal'] = $CI->customer->typeOfTruck();
			$data['type_of_truck'] = $data['type_of_truck_modal'];
			$data['type_of_artic'] = $CI->dashboard->getArticType();
			$data['type_of_division'] = $CI->dashboard->getDevisionType();

			$config["per_page"] =  ($CI->input->get("limit", TRUE)) ? $CI->input->get("limit", TRUE) : 50;
			$config['reuse_query_string'] = true;
			$config["page_query_string"] = true;
			$config['full_tag_open'] = "<ul class='pagination'>";
			$config['full_tag_close'] ="</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tagl_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";
			$config['num_links'] = 3;
			$config['prev_link'] = "Previous";
			$config['next_link'] = "Next";

			$data['config'] = $config;
		}

		return $data;
	}
}