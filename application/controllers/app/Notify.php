<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notify extends CI_Controller {

	private $data = array();

	function __construct( ) {
		parent::__construct();
        
        $this->data = Share::get_share();
 
	}

	public function index()
	{
		$this->data['page'] = "page/notification/view";
		$this->data['notification'] = $this->notification->get_notification(TRUE);
		$this->load->view('master' , $this->data );
	}

}
