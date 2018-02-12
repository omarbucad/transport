<?php

class Customer_model extends CI_Model {

    public function getAllCustomer(){
        $this->db->select("c.company_name , ca.customer_id");
        $this->db->join("customer c" , "c.account_id = a.id");
        $this->db->join("customer_accounts ca" , "ca.customer_id = c.customer_id");
        return $this->db->where("company_id" , $this->session->userdata("company_id"))->group_by("c.company_name")->order_by("company_name" , "ASC")->get("accounts a")->result();
    }

    public function getAccount(){
        //$this->db->join("customer_accounts ca" , "a.id = ca.account_id");
        $this->db->join("customer c" , "c.account_id = a.id");
        $this->db->where("account_type" , CUSTOMER);

        if($this->input->get("submit", TRUE)){
            if($this->input->get("company_name", TRUE)){
                $this->db->like("company_name" , $this->input->get("company_name", TRUE));
            }
            if($this->input->get("registration_number", TRUE)){
                $this->db->where("registration_number" , $this->input->get("registration_number", TRUE));
            }
            if($this->input->get("vat_number", TRUE)){
                $this->db->where("vat_number" , $this->input->get("vat_number", TRUE));
            
            }if($this->input->get("email", TRUE)){
                $this->db->where("email" , $this->input->get("email", TRUE));
            }
        }

        if($this->session->userdata("account_type") != SUPER_ADMIN){
            $this->db->join("users_store us" , "us.account_id = a.id" );
            $this->db->where("store_id" , $this->session->userdata("store_id"));
        }

        $result = $this->db->where('a.company_id' , $this->session->userdata('company_id'))->order_by("name" , 'asc')->get("accounts a")->result();

        foreach($result as $key => $row){
            $result[$key]->status = account_status($row->status);
            $result[$key]->account_type = account_type($row->account_type);
            $result[$key]->billing_address = nl2br($row->billing_address);
            $result[$key]->address = nl2br($row->address);

            if($row->image_thumb){
                $image = full_path_image($row->image_thumb , 'accounts');
                $result[$key]->image_thumb = $image['path'];

                if(!file_exists($image['absolute_path'])){
                    $result[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
                }

            }else{
               $result[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
           }

           if($row->image){
            $image = full_path_image($row->image , 'accounts');
            $result[$key]->image = $image['path'];

            if(!file_exists($image['absolute_path'])){
                $result[$key]->image = $this->config->base_url('/public/images/image-not-found.png');
            }

            }else{
               $result[$key]->image = $this->config->base_url('/public/images/image-not-found.png');
           }
       }

       return $result;
    
    }

    public function insertAccount(){
        $accounts = array(
            "company_id" => htmlentities($this->session->userdata('company_id')),
            "name" => htmlentities($this->input->post("name")),
            "surname" => htmlentities($this->input->post("surname")),
            "email" => htmlentities($this->input->post("email")),
            "address" => htmlentities($this->input->post("address")),
            "username" => htmlentities($this->input->post("username")),
            "password" => htmlentities(md5($this->input->post("password"))),
            "account_type" => CUSTOMER,
            "status" => 1,
            "created" => time()
            );

        $this->db->insert('accounts', $accounts);
        $accounts_id = $this->db->insert_id();

        $customer = array(
            "account_id" => $accounts_id ,
            "company_name" => htmlentities($this->input->post("company_name", TRUE)),
            "account_no" => htmlentities($this->input->post("account_no" , TRUE)),
            "registration_number" => htmlentities($this->input->post("registration_number", TRUE)),
            "vat_number" => htmlentities($this->input->post("vat_number", TRUE)),
            "billing_address" => htmlentities($this->input->post("billing_address", TRUE)),
            );

        $this->db->insert("customer" , $customer);

        $customer_id = $this->db->insert_id();

        $this->db->insert("customer_accounts" , ["account_id" => $accounts_id , "customer_id" => $customer_id]);

        $this->db->insert("users_store" , array(
            "company_id" => $this->session->userdata('company_id') ,
            "account_id" => $accounts_id ,
            "store_id" => $this->session->userdata("store_id")
            ));

        $this->do_upload($accounts_id);

        return $accounts_id;

    }

    public function do_upload($id){
        $year = date("Y");
        $month = date("m");
        $folder = "./public/upload/accounts/".$year."/".$month;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            create_index_html($folder);
        }

        $image_name = $id.'_'.time().'_'.$_FILES['file']['name'];
        $image_name = $year."/".$month.'/'.$image_name;

        $config['upload_path']          = $folder;
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $image_name;

        $this->load->library('upload', $config);
        $this->load->library('image_lib');

        if ( $this->upload->do_upload('file')){
            $data = $this->upload->data();

            $thumb_config['image_library']      = 'gd2';
            $thumb_config['source_image']       = $data['full_path'];
            $thumb_config['create_thumb']       = TRUE;
            $thumb_config['maintain_ratio']     = TRUE;
            $thumb_config['width']              = 75;
            $thumb_config['height']             = 50;

            $this->image_lib->clear(); 
            $this->image_lib->initialize($thumb_config); 

            $this->image_lib->resize();

            $thumb_name = $data['raw_name'].'_thumb'.$data['file_ext'];
            $thumb_name = $year."/".$month.'/'.$thumb_name;
            
            $this->db->where("id" , $id)->update("accounts" , ["image" => $data['file_name'] , "image_thumb" => $thumb_name]);
        }
    
    }
    
    public function getJobNames(){
        $this->db->where("company_id" , $this->session->userdata("company_id"));
        $this->db->where("customer_id" , $this->session->userdata("id"));    

        return $this->db->order_by("job_name" , "ASC")->get("jobs_configuration", TRUE)->result();
    }

    public function addJobNames(){
        $this->db->select("job_configuration_id");
        $this->db->where("company_id" , $this->session->userdata("company_id"));
        $this->db->where("customer_id" , $this->input->post("customer_id", TRUE) );
        $this->db->where("job_name" , $this->input->post("job_name", TRUE));
        $check = $this->db->get("jobs_configuration")->row();

        $arr = array(
            "company_id" => $this->session->userdata("company_id") ,
            "customer_id" =>$this->input->post("customer_id") ,
            "job_name" => htmlentities($this->input->post("job_name")),
            "job_number" => htmlentities($this->input->post("job_number")[0]),
            "job_po_number" => htmlentities($this->input->post("job_po_number")),
            "telephone" => htmlentities($this->input->post("telephone")) ,
            "address" => $this->input->post("address"),
            "notes" => $this->input->post("notes"),
            "type_of_truck" => $this->input->post("type_of_truck"),
            "driver_notes" => htmlentities($this->input->post("driver_notes")),
            "number_of_truck" => $this->input->post("number_of_truck"),
            "load_site" => htmlentities($this->input->post("load_site")),
            "arctic_type" => $this->input->post("arctic_type"),
            "division" => $this->input->post("division"),
            "zip_code" => htmlentities($this->input->post("zip_code")),
            "zip_code_load_site" => htmlentities($this->input->post("zip_code_load_site")),
            "site_contact" => htmlentities($this->input->post("site_contact")),
            "build_dismantle" => ($this->input->post("build_dismantle") == "OTHER") ? $this->input->post("build_dismantle_other") : $this->input->post("build_dismantle") ,
            "build_dismantle_other" => ($this->input->post("build_dismantle") == "OTHER") ? 1 : 0,
            );

        if($check){
            
            $this->db->where("job_configuration_id" , $check->job_configuration_id)->update("jobs_configuration" , $arr);

            return $check->job_configuration_id;

        }else{

            $this->db->insert("jobs_configuration" , $arr);

            return $this->db->insert_id();
        }

    }
    
    public function createJob($id){
        $jobs_configuration = $this->db->where("job_configuration_id" , $id)->get("jobs_configuration")->row();

        $arr = array(
            "job_configuration_id" => $jobs_configuration->job_configuration_id,
            "company_id" => $this->session->userdata("company_id") ,
            "customer_id" => $this->input->post("customer_id") ,
            "account_id" => $this->session->userdata("id"),
            "address" => $jobs_configuration->address ,
            "notes" => $jobs_configuration->notes ,
            "job_name" => $jobs_configuration->job_name ,
            "telephone" => $jobs_configuration->telephone ,
            "number_of_truck" => $jobs_configuration->number_of_truck ,
            "load_site" => $jobs_configuration->load_site,
            "site_contact" => $jobs_configuration->site_contact,
            "zip_code" => $jobs_configuration->zip_code,
            "zip_code_load_site" => $jobs_configuration->zip_code_load_site,
            "created" => time()
            );

        $this->db->insert("jobs_parent" , $arr);

        $jobs_parent_id = $this->db->insert_id();

        $this->addJob($jobs_parent_id , $jobs_configuration);

        $this->notification->add_notification($jobs_parent_id , "new_job");

        return $jobs_parent_id;
    }
    
    private function addJob($jobs_parent , $jobs_configuration){
        $data = $this->input->post();

        $count = $jobs_configuration->number_of_truck;

        $images = $this->do_file_upload($jobs_parent);

        foreach(range(0 , ($count - 1)) as  $row){

            $l_t = (isset($data["loading"]["time"][$row])) ? $data["loading"]["time"][$row]  : $data["loading"]["time"][0]; 
            $d_t = (isset($data["delivery"]["time"][$row])) ? $data["delivery"]["time"][$row]  : $data["delivery"]["time"][0]; 

            if(!preg_match("/^(1[0-2]|0?[1-9]):[0-5][0-9] (AM|PM)$/i", $l_t)){
                $l_t = "12:00 AM";
            }

            if(!preg_match("/^(1[0-2]|0?[1-9]):[0-5][0-9] (AM|PM)$/i", $d_t)){
                $d_t = "12:00 AM";
            }

            $loading_time  = (isset($data["loading"]["date"][$row])) ? $data["loading"]["date"][$row].' '.$l_t : $data["loading"]["date"][0].' '.$l_t;
            $delivery_time = (isset($data["delivery"]["date"][$row])) ? $data["delivery"]["date"][$row].' '.$d_t : $data["delivery"]["date"][0].' '.$d_t;
            $job_number    = (isset($data["job_number"][$row])) ? $data["job_number"][$row] : $data["job_number"][0];

            $arr = array(
                "parent_id" => $jobs_parent ,
                "type_of_truck" => $data['type_of_truck'] ,
                "loading_time" => c_strtotime($loading_time) ,
                "delivery_time" => c_strtotime($delivery_time) ,
                "driver_notes" => $data["driver_notes"],
                "job_notes" => $data["notes"] ,
                "job_number" => $job_number ,
                "job_po_number" => $jobs_configuration->job_po_number,
                "store_id" => $this->session->userdata("store_id") ,
                "arctic_type" => $jobs_configuration->arctic_type,
                "division" => $jobs_configuration->division,
                "build_dismantle" => $jobs_configuration->build_dismantle,
                "build_dismantle_other" => $jobs_configuration->build_dismantle_other,
                "point_destination" => "A" ,
                "job_name" => $jobs_configuration->job_name,
                "telephone" => $jobs_configuration->telephone,
                "load_site" => $jobs_configuration->load_site,
                "address" => $jobs_configuration->address,
                "zip_code" => $jobs_configuration->zip_code,
                "zip_code_load_site" => $jobs_configuration->zip_code_load_site,
                "site_contact" => $jobs_configuration->site_contact
                );

            $this->db->insert("jobs" , $arr);

            $job_id = $this->db->insert_id();

            $arr = array(
                "jobs_id" => $job_id ,
                "status_type" => "new" ,
                "account_id" => $this->session->userdata("id") ,
                "created" => time() ,
                "updated" => time()
                );

            $this->db->insert("jobs_status" , $arr);

            $status_id = $this->db->insert_id();

            $this->db->where("jobs_id" , $job_id)->update("jobs" , array("status_id" => $status_id));

            if($this->input->post("customer_id") == 4 OR $this->input->post("customer_id") == 5){
                $btn_type = (strpos(strtolower($jobs_configuration->address), 'needingworth') !== false) ? "destination" : "load_site";

                if($btn_type == "destination"){
                    $this->db->insert("warehouse_status" , ["jobs_id" => $job_id , "status" => "TO BE UNLOADED" , "created" => time()]);
                }else{
                   $this->db->insert("warehouse_status" , ["jobs_id" => $job_id , "status" => "TO BE LOADED" , "created" => time()]);
                }

                $warehouse_status_id = $this->db->insert_id();
                $this->db->where("jobs_id" , $job_id)->update("jobs" , ['warehouse_status_id' => $warehouse_status_id]);
            }

            

            foreach($images as $k => $r){
                $x = $r;
                $x['jobs_id'] = $job_id;
                $this->db->insert("jobs_images" , $x);
            }
        }
    }

    private function do_file_upload($id){
        $year = date("Y");
        $month = date("m");

        $a = array();

        $path = './public/upload/job/'.$year.'/'.$month.'/';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            create_index_html($path);
        }

        $config['upload_path']          = $path;
        $config['allowed_types']        = 'gif|jpg|png';

        $data = $_FILES['file'];

        $this->load->library('upload', $config);
        $this->load->library('image_lib');

        if(!empty($_FILES['file']['name'])){
            $filesCount = count($_FILES['file']['name']);

            for($i = 0; $i < $filesCount; $i++){
                $_FILES['file']['name'] = $data['name'][$i];
                $_FILES['file']['type'] = $data['type'][$i];
                $_FILES['file']['tmp_name'] = $data['tmp_name'][$i];
                $_FILES['file']['error'] = $data['error'][$i];
                $_FILES['file']['size'] = $data['size'][$i];

                $config['file_name'] = $id.'_'.time().'_'.$data['name'][$i];

                $this->upload->initialize($config);

                if ( $this->upload->do_upload('file')){
                    $image = $this->upload->data();


                    $thumb_config['image_library'] = 'gd2';
                    $thumb_config['source_image'] = $image['full_path'];
                    $thumb_config['create_thumb'] = TRUE;
                    $thumb_config['maintain_ratio'] = TRUE;
                    $thumb_config['width']         = 250;
                    $thumb_config['height']       = 250;

                    $this->image_lib->clear(); 
                    $this->image_lib->initialize($thumb_config); 

                    $this->image_lib->resize();

                    $thumb_name = $image['raw_name'].'_thumb'.$image['file_ext'];

                    $a[] = array(
                        "image" => $year.'/'.$month.'/'.$image['file_name'] ,
                        "image_thumb" => $year.'/'.$month.'/'.$thumb_name ,
                        "created" => time()
                        );

                }
            }//end for loop

        }//end if

        return $a;

    }

    public function countCancelled(){
        $this->db->join("jobs j" , "j.parent_id = jp.job_parent_id");    
        $this->db->where("jp.company_id" , $this->session->userdata("company_id"));
        $this->db->where("deleted !=" , NULL);
        return $this->db->where("j.confirmed_cancel" , 0)->get("jobs_parent jp")->num_rows();
    }


    public function getJobsListV2($customer = false , $id = false , $job_name = false , $count = false , $job_id = false , $outsource = false){
        $p_id = $this->input->get("jobs_id");

        $sort = ($this->input->get("sort", TRUE)) ? $this->input->get("sort", TRUE) : "DESC";
        $sort_by = ($this->input->get("sort_by", TRUE)) ? $this->input->get("sort_by", TRUE) : "jp.created";

        if(!$count){
            $skip = ($this->input->get("per_page", TRUE)) ? $this->input->get("per_page", TRUE) : 0;
            $limit = ($this->input->get("limit", TRUE)) ? $this->input->get("limit", TRUE) : 50;
          

        }else{
            $skip = 0;
            $limit = false;
        }

        $this->db->select("jp.job_parent_id , j.job_name , j.loading_time ,
         j.delivery_time , jp.created , j.jobs_id , c.company_name , js.status_type , j.ready_to_go , j.confirmed_cancel , j.with_outsource , j.job_number , j.outsource_company_name , j.job_po_number , CONCAT(a2.name , '' , a2.surname) as created_by , j.status_update , j.ready_to_go , j.driver_status , j.j_status , j.division , j.build_dismantle , j.arctic_type , j.demurrage , j.load_trailer");
        $this->db->select("o.company_name as outsource_name");
        $this->db->join("jobs j" , "j.parent_id = jp.job_parent_id");
        $this->db->join("accounts a2" , "a2.id = jp.account_id");
        $this->db->join("customer c" , "c.customer_id = jp.customer_id");
        $this->db->join("accounts a" , "a.id = j.driver_id" , "LEFT");
        $this->db->join("outsource o" , "o.outsource_id = j.outsource_company_name" , "LEFT");
        $this->db->join("jobs_status js" , "js.jobs_status_id = j.status_id" , "LEFT");
        $this->db->where("jp.company_id" , $this->session->userdata("company_id"));

        if($job_id){
            $this->db->where("j.jobs_id" , $job_id);
            $result = $this->db->get("jobs_parent jp")->result();
            return $this->initJobsResultv2($result);
        }

        // if($this->session->userdata("account_type") != SUPER_ADMIN){
        //     $this->db->join("users_store us" , "us.account_id = jp.account_id" );
        //     $this->db->where("us.store_id" , $this->session->userdata("store_id"));
        // }

        if($customer){
            $this->db->where("jp.customer_id" , $this->session->userdata("customer_id"));
        }else if($outsource){
            $this->db->where("j.with_outsource" , 1);
            $this->db->where("j.outsource_company_name" , $this->session->userdata("outsource_id"));
        }


        if($this->input->get("submit")){

            if($this->input->get("transaction_number")){
                $this->db->where("j.jobs_id" , $this->input->get("transaction_number", TRUE));
            }

            if($this->input->get("customer_name")){
                $this->db->like("c.company_name" , $this->input->get("customer_name", TRUE));
            }

            if($this->input->get("job_number")){
                $this->db->where("j.jobs_id" , $this->input->get("job_number", TRUE));
            }

            if($this->input->get("job_name")){
                $this->db->like("j.job_name" , trim($this->input->get("job_name", TRUE)));
            }

            if($this->input->get("type_of_truck")){
                $this->db->where("j.type_of_truck" , $this->input->get("type_of_truck", TRUE));
            }

            if($this->input->get("driver_name")){
                $this->db->where("j.driver_id" , $this->input->get("driver_name", TRUE));
            }

            if($this->input->get("truck_registration_number")){
                $this->db->where("v.vehicle_number" , $this->input->get("truck_registration_number", TRUE));
            }

            if($this->input->get("status")){
               if($this->input->get("status") == "unconfirmed"){
                    $this->db->where("js.status_type" , "cancel");
                }else if($this->input->get("status") == "all"){
                    $this->db->where("j.confirmed_cancel" , 0);
                }else if($this->input->get("status") == "finished"){
                    $this->db->where("js.status_type" , "finished");
                }else if($this->input->get("status") == "partially_complete"){
                    $this->db->where("js.status_type" , "partially_complete");
                }else{
                    $this->db->where("js.status_type" , $this->input->get("status"));
                }
            }else{
                $this->db->where("js.status_type !=" , "finished");
                $this->db->where("j.confirmed_cancel" , 0);
            }

            
           if($this->input->get("loading_time_from") AND $this->input->get("loading_time_to")){
                $from = strtotime($this->input->get("loading_time_from").' midnight');
                $to = strtotime($this->input->get("loading_time_to").' 23:59');

                $this->db->where("(j.loading_time >= $from AND j.loading_time <= $to)");
            }else if($this->input->get("loading_time_from")){
                $from = strtotime($this->input->get("loading_time_from").' midnight');
                $this->db->where("j.loading_time >=" , $from);
            }


            if($this->input->get("delivery_time_from") AND $this->input->get("delivery_time_to")){
                $from = strtotime($this->input->get("delivery_time_from").' midnight');
                $to = strtotime($this->input->get("delivery_time_to").' 23:59');

                if($this->input->get("loading_time_from") AND $this->input->get("loading_time_to")){ 
                    $this->db->or_where("(j.delivery_time >= $from AND j.delivery_time <= $to)");
                }else{
                    $this->db->where("(j.delivery_time >= $from AND j.delivery_time <= $to)");
                }

            }else if($this->input->get("delivery_time_from")){
                $from = strtotime($this->input->get("delivery_time_from").' midnight');
                $this->db->where("j.delivery_time >=" , $from);
            }


            if($this->input->get("status") == "unconfirmed"){
               $this->db->where("j.confirmed_cancel" , 0);
            }

            if($this->input->get("created_date_from") AND $this->input->get("created_date_to")){
                $from = strtotime($this->input->get("created_date_from").' midnight');
                $to = strtotime($this->input->get("created_date_to").' 23:59');

                $this->db->where("jp.created >=" , $from);
                $this->db->where("jp.created <=" , $to);
                
            }else if($this->input->get("created_date_from")){

                $from = strtotime($this->input->get("created_date_from").' midnight');
                $this->db->where("jp.created >=" , $from);
            }
            
            if($this->input->get("j_status")){
                switch ($this->input->get("j_status")) {
                    case 'q':
                        $this->db->where("j_status" , 0);
                        break;
                    case 'c':
                        $this->db->where("j_status" , 2);
                        break;
                    case 'x':
                        $this->db->where("j_status" , 3);
                        break;
                    default:
                        # code...
                        break;
                }
                
            }

            
        }else{
            
            if($job_name){
                $this->db->where("j.job_name" , urldecode($job_name));
            }else if($id){
                $this->db->where("jp.job_parent_id" , $id);
            }

            
            if(!$p_id){
                $this->db->where("js.status_type !=" , "finished");

                $this->db->where("j.confirmed_cancel" , 0);
            }


        }

        $this->db->limit($limit , $skip)->order_by($sort_by , $sort);
        
        if($p_id){
            $this->db->where_in("j.jobs_id" , $p_id);
        } 



        if($count){
            $result = $this->db->get("jobs_parent jp")->num_rows();
            return $result;
        }else{
            $result = $this->db->get("jobs_parent jp")->result();
        }

        return $this->initJobsResultV2($result);
    }

    private function initJobsResultV2($result){
        $p_id = $this->input->get("jobs_id");

        foreach($result as $key => $row){
            $result[$key]->loading_time = convert_timezone($row->loading_time , true , false , false , "d M Y D h:i A");

            if(strpos($result[$key]->loading_time , "12:00 AM") !== false){
                $result[$key]->loading_time = '<span class="text-danger">'.$result[$key]->loading_time.'</span>';
            }
            $result[$key]->delivery_time_raw = $row->delivery_time;
            $result[$key]->delivery_time = convert_timezone($row->delivery_time , true , false , false , "d M Y D h:i A");

            if(strpos($result[$key]->delivery_time , "12:00 AM") !== false){
                $result[$key]->delivery_time = '<span class="text-danger">'.$result[$key]->delivery_time.'</span>';
            }
            $result[$key]->created = convert_timezone($row->created , true);
            $result[$key]->job_number = ($row->job_number) ? "#".$row->job_number : "";
            $result[$key]->division = ifNA(@$row->division);
            $result[$key]->build_dismantle = ifNA(@$row->build_dismantle);
            $result[$key]->arctic_type = ifNA(@$row->arctic_type);

            
            $this->db->join("accounts a " , "a.id = js.account_id");

            $status_type = $this->db->select("js.status_type , js.created , js.updated , CONCAT(a.name , ' ' , a.surname) as name , js.account_id")
            ->where("jobs_id" , $row->jobs_id)
            ->get("jobs_status js")->result();
            
            $result[$key]->status_type = array();
            
            foreach($status_type as $k => $r){

                if(!isset($result[$key]->status_type[$r->status_type])){
                    $result[$key]->status_type[$r->status_type] = new stdClass();
                }

                $result[$key]->status_type[$r->status_type]->created = convert_timezone($r->created , true);
                $result[$key]->status_type[$r->status_type]->updated = convert_timezone($r->updated , true);
                $result[$key]->status_type[$r->status_type]->name = $r->name;
                $result[$key]->status_type[$r->status_type]->account_id = $r->account_id;

                if($r->status_type == "cancel"){
                    $result[$key]->status_type["finished"] = new stdClass();

                    $result[$key]->status_type["finished"]->created = convert_timezone($r->created , true);
                    $result[$key]->status_type["finished"]->updated = convert_timezone($r->updated , true);
                    $result[$key]->status_type["finished"]->name = $r->name;
                    $result[$key]->status_type["finished"]->account_id = $r->account_id;
                }

            }

            if($row->driver_status == 1){
                $result[$key]->driver_status = ' <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
            }else if($row->driver_status == 2){
                $result[$key]->driver_status = ' - Rejected By Driver';
            }else{
                $result[$key]->driver_status = '';
            }

            if(isset($result[$key]->status_type["cancel"])){
                $result[$key]->new = '<span class="label bg-red">Cancel</span>';
                $result[$key]->new_type = "cancel";
                $result[$key]->new_icon = "check_circle";
                $result[$key]->new_color = "red";
            }else if(isset($result[$key]->status_type["finished"])){
                $result[$key]->new = '<span class="label bg-green">Complete</span>';
                $result[$key]->new_type = "complete";
                $result[$key]->new_icon = "check_circle";
                $result[$key]->new_color = "green";
            }else if(isset($result[$key]->status_type["partially_complete"])){
                $result[$key]->new = '<span class="label bg-green">Complete</span>';
                $result[$key]->new_type = "for_confirmation";
                $result[$key]->new_icon = "check_circle";
                $result[$key]->new_color = "green";
            }else if(isset($result[$key]->status_type["allocated"])){
                $result[$key]->new = '<span class="label bg-light-green">Truck Allocated</span>';
                $result[$key]->new_type = "allocated";
                $result[$key]->new_icon = "book";
                $result[$key]->new_color = "light-green";
            }else if(isset($result[$key]->status_type["to_be_allocated"])){
                $result[$key]->new = '<span class="label bg-deep-orange">Truck To Be Allocated</span>';
                $result[$key]->new_type = "to_be_allocated";
                $result[$key]->new_icon = "bookmark";
                $result[$key]->new_color = "orange";
            }else{
                $result[$key]->new = '<span class="label bg-blue">New</span>';
                $result[$key]->new_type = "new";
                $result[$key]->new_icon = "bookmark_border";
                $result[$key]->new_color = "blue";
            }

            if($p_id){
                if($row->demurrage == 0){
                    $result[$key]->new_type = "Demurrage";
                }
            }
            
            /*
                ready to go status
                
                0 - new
                1 - no defect
                2 - defect
                3 - fixed
                4 - delivery time
                5 - cancel job
                6 - arrived
                7 - change truck

            */
            
            switch ($row->ready_to_go) {
                case 0:
                    $result[$key]->status_job_icon = "directions_bus";
                    $result[$key]->status_job_class = "white-truck";
                    $result[$key]->status_message = "Waiting for safety check";
                    break;
                case 1:
                    $result[$key]->status_job_icon = "directions_bus";
                    $result[$key]->status_job_class = "green-truck";
                    $result[$key]->status_message = "Passed safety check";
                    break;
                case 2:
                    $result[$key]->status_job_icon = "directions_bus";
                    $result[$key]->status_job_class = "red-truck";
                    $result[$key]->status_message = "Truck has been reported to have a defect";
                    break;
                case 3:
                    $result[$key]->status_job_icon = "build";
                    $result[$key]->status_job_class = "green-truck";
                    $result[$key]->status_message = "Vehicle has been Fixed";
                    break;
                case 4:
                    $result[$key]->status_job_icon = "priority_high";
                    $result[$key]->status_job_class = "ex-truck";
                    $result[$key]->status_message = "ATTENTION! Check delivery time!";
                    break;
                case 5:
                    $result[$key]->status_job_icon = "";
                    $result[$key]->status_job_class = "";
                    $result[$key]->status_message = "";
                    break;
                case 6:
                    $result[$key]->status_job_icon = "directions_bus";
                    $result[$key]->status_job_class = "green-truck";
                    $result[$key]->status_message = "Truck has arrived";
                    break;
                default:
                    $result[$key]->status_job_icon = "directions_bus";
                    $result[$key]->status_job_class = "white-truck";
                    $result[$key]->status_message = "Waiting for safety check";
                    break;
            }

            if( ($result[$key]->delivery_time_raw - 172800) < time() AND $result[$key]->status_update == 0 AND !$row->with_outsource AND $row->ready_to_go != 6 AND (!isset($result[$key]->status_type["finished"]) AND !isset($result[$key]->status_type["partially_complete"])) ){

                switch ($row->ready_to_go) {
                    case 2:
                    case 3:
                        
                        break;
                    
                    default:
                        $result[$key]->status_job_icon = "priority_high";
                        $result[$key]->status_job_class = "ex-truck";
                        $result[$key]->status_message = "ATTENTION! Check delivery time!";
                        break;
                }
                
            }

            if($row->with_outsource){
                $result[$key]->status_job_icon = "account_circle";
            }else{

                switch ($row->ready_to_go) {
                    case 0:
                    case 1:
                    case 4:
                    case 5:
                        if($result[$key]->status_update == 0 AND  ($result[$key]->delivery_time_raw - ( 86400  / 2 )) < time() AND (!isset($result[$key]->status_type["finished"]) AND !isset($result[$key]->status_type["partially_complete"])) ){
                            $result[$key]->status_job_class .= " edit-job-status";
                        }
                        break;
                    case 2:
                        $result[$key]->status_job_class .= " fixed-truck-status";
                        break;
                    case 3:
                        $result[$key]->status_job_class .= " view-truck-status";
                        break;
                     break;
                }
                
            }

            
        }


        return $result;
    }

    
    public function getJobsList($customer = false , $id = false , $job_name = false , $count = false , $job_id = false , $outsource = false , $start = 0){

        $sort = ($this->input->get("sort", TRUE)) ? $this->input->get("sort", TRUE) : "DESC";
        $sort_by = ($this->input->get("sort_by", TRUE)) ? $this->input->get("sort_by", TRUE) : "jp.created";

        if(!$count){
            $skip = ($this->input->get("per_page", TRUE)) ? $this->input->get("per_page", TRUE) : 0;
            $limit = ($this->input->get("limit", TRUE)) ? $this->input->get("limit", TRUE) : 10;

        }else{
            $skip = 0;
            $limit = false;
        }

        $this->db->select("jp.job_parent_id , j.job_name , j.telephone ,
         j.price , j.address , jp.number_of_truck , j.loading_time ,
         j.delivery_time , jp.created , j.jobs_id , CONCAT(a.name , ' ' , a.surname) as driver_name ,
         vehicle_number , trailer_number , type_of_truck , driver_notes , jp.notes , j.driver_id , j.vehicle_id , 
         j.trailer_id , j.show_to_driver , j.job_notes , c.company_name , j.delivered_date , j.delivered_time , j.delivered_fulldate , j.vat , j.total_price , j.demurrage , j.send_invoice , j.time_of_arrival , j.cancel_notes , j.signature , j.signature_name , js.status_type , j.ready_to_go , j.confirmed_cancel , j.delivery_notes , j.trailer_filled , j.latitude , j.longitude , j.with_outsource , j.outsource_price , j.job_number , j.load_site , j.arctic_type , j.division , j.build_dismantle , j.outsource_company_name , j.outsource_truck , j.outsource_driver_name , j.zip_code , j.zip_code_load_site , j.job_po_number , CONCAT(a2.name , '' , a2.surname) as created_by , j.status_update , j.driver_status , j.j_status , j.warehouse_signature_name , j.warehouse_signature , j.load_trailer");
        $this->db->select("o.company_name as outsource_name");
        $this->db->join("jobs j" , "j.parent_id = jp.job_parent_id");
        $this->db->join("accounts a2" , "a2.id = jp.account_id");
        $this->db->join("customer c" , "c.customer_id = jp.customer_id");
        $this->db->join("accounts a" , "a.id = j.driver_id" , "LEFT");
        $this->db->join("vehicle_information v" , "v.id = j.vehicle_id" , "LEFT");
        $this->db->join("trailer_information t" , "t.id = j.trailer_id" , "LEFT");
        $this->db->join("outsource o" , "o.outsource_id = j.outsource_company_name" , "LEFT");
        $this->db->join("jobs_status js" , "js.jobs_status_id = j.status_id" , "LEFT");
        $this->db->where("jp.company_id" , $this->session->userdata("company_id"));

        if($job_id){
            $this->db->where("j.jobs_id" , $job_id);
            $result = $this->db->get("jobs_parent jp")->result();
            return $this->initJobsResult($result);
        }

        // if($this->session->userdata("account_type") != SUPER_ADMIN){
        //     $this->db->join("users_store us" , "us.account_id = jp.account_id" );
        //     $this->db->where("us.store_id" , $this->session->userdata("store_id"));
        // }

        if($customer){
            $this->db->where("jp.customer_id" , $this->session->userdata("customer_id"));
        }else if($outsource){
            $this->db->where("j.with_outsource" , 1);
            $this->db->where("j.outsource_company_name" , $this->session->userdata("outsource_id"));
        }


        if($this->input->get("submit")){

            if($this->input->get("transaction_number")){
                $this->db->where("jp.job_parent_id" , $this->input->get("transaction_number", TRUE));
            }

            if($this->input->get("customer_name")){
                $this->db->like("c.company_name" , $this->input->get("customer_name", TRUE));
            }

            if($this->input->get("job_number")){
                $this->db->where("j.jobs_id" , $this->input->get("job_number", TRUE));
            }

            if($this->input->get("job_name")){
                $this->db->like("j.job_name" , trim($this->input->get("job_name", TRUE)));
            }

            if($this->input->get("type_of_truck")){
                $this->db->where("j.type_of_truck" , $this->input->get("type_of_truck", TRUE));
            }

            if($this->input->get("driver_name")){
                $this->db->where("j.driver_id" , $this->input->get("driver_name", TRUE));
            }

            if($this->input->get("truck_registration_number")){
                $this->db->where("v.vehicle_number" , $this->input->get("truck_registration_number", TRUE));
            }

            if($this->input->get("status")){
                if($this->input->get("status") == "unconfirmed"){
                    $this->db->where("js.status_type" , "cancel");
                }else if($this->input->get("status") == "all"){
                    $this->db->where("j.confirmed_cancel" , 0);
                }else{
                    $this->db->where("js.status_type" , $this->input->get("status"));
                }
            }else{
                $this->db->where("js.status_type !=" , "finished");
                $this->db->where("j.confirmed_cancel" , 0);
            }

            if($this->input->get("loading_time_from") AND $this->input->get("loading_time_to")){
                $from = strtotime($this->input->get("loading_time_from").' midnight');
                $to = strtotime($this->input->get("loading_time_to").' 23:59');

                $this->db->where("j.loading_time >=" , $from);
                $this->db->where("j.loading_time <=" , $to);
            }


            if($this->input->get("delivery_time_from") AND $this->input->get("delivery_time_to")){
                $from = strtotime($this->input->get("delivery_time_from").' midnight');
                $to = strtotime($this->input->get("delivery_time_to").' 23:59');

                $this->db->where("j.delivery_time >=" , $from);
                $this->db->where("j.delivery_time <=" , $to);
            }

            if($this->input->get("status") == "unconfirmed"){
               $this->db->where("j.confirmed_cancel" , 0);
            }

            if($this->input->get("created_date_from") AND $this->input->get("created_date_to")){
                $from = strtotime($this->input->get("created_date_from").' midnight');
                $to = strtotime($this->input->get("created_date_to").' 23:59');

                $this->db->where("jp.created >=" , $from);
                $this->db->where("jp.created <=" , $to);
            }
            
            
        }else{
            
            if($job_name){
                $this->db->where("j.job_name" , urldecode($job_name));
             }else if($id){
                $this->db->where("jp.job_parent_id" , $id);
            }

            $this->db->where("js.status_type !=" , "finished");

            $this->db->where("j.confirmed_cancel" , 0);

        }

        $this->db->limit($limit , $skip)->order_by($sort_by , $sort);
        
        if($count){
            $result = $this->db->get("jobs_parent jp")->num_rows();
            return $result;
        }else{
            $result = $this->db->get("jobs_parent jp")->result();
        }
        
        return $this->initJobsResult($result);
    }

    private function initJobsResult($result){
        foreach($result as $key => $row){
            $result[$key]->address = ($row->address) ? nl2br($row->address) : "NA";
            $result[$key]->loading_time = convert_timezone($row->loading_time , true , false , false , "d M Y D h:i:s A");
            $result[$key]->delivery_time_raw = $row->delivery_time;
            $result[$key]->delivery_time = convert_timezone($row->delivery_time , true , false , false , "d M Y D h:i:s A");
            $result[$key]->delivered_fulldate = convert_timezone($row->delivered_fulldate , true , false , false , "d M Y D h:i:s A");
            $result[$key]->time_of_arrival = convert_timezone($row->time_of_arrival , true , false , false , "d M Y D h:i:s A");
            $result[$key]->created = convert_timezone($row->created , true);
            $result[$key]->price = round($row->price , 2);
            $result[$key]->driver_name = ($row->driver_name) ? $row->driver_name : "NA";
            $result[$key]->vehicle_number = ($row->vehicle_number) ? $row->vehicle_number : "NA";
            $result[$key]->trailer_number = ($row->trailer_number) ? $row->trailer_number : "NA";
            $result[$key]->demurrage = round($row->demurrage , 2);
            $result[$key]->vat = round($row->vat , 2);
            $result[$key]->total_price = round($row->total_price , 2);
            $result[$key]->outsource_price = round($row->outsource_price , 2);
            $result[$key]->delivery_notes = ($row->delivery_notes) ? $row->delivery_notes : "NA";
            $result[$key]->job_number = ($row->job_number) ? "#".$row->job_number : "";
            $result[$key]->job_notes = ifNA(nl2br($result[$key]->job_notes));
            $result[$key]->signature_name = ifNA($row->signature_name);
            
            $this->db->join("accounts a " , "a.id = js.account_id");

            $status_type = $this->db->select("js.status_type , js.created , js.updated , CONCAT(a.name , ' ' , a.surname) as name , js.account_id")
            ->where("jobs_id" , $row->jobs_id)
            ->get("jobs_status js")->result();
            
            $result[$key]->status_type = array();
            
            foreach($status_type as $k => $r){

                if(!isset($result[$key]->status_type[$r->status_type])){
                    $result[$key]->status_type[$r->status_type] = new stdClass();
                }

                $result[$key]->status_type[$r->status_type]->created = convert_timezone($r->created , true);
                $result[$key]->status_type[$r->status_type]->updated = convert_timezone($r->updated , true);
                $result[$key]->status_type[$r->status_type]->name = $r->name;
                $result[$key]->status_type[$r->status_type]->account_id = $r->account_id;

                if($r->status_type == "cancel"){
                    $result[$key]->status_type["finished"] = new stdClass();

                    $result[$key]->status_type["finished"]->created = convert_timezone($r->created , true);
                    $result[$key]->status_type["finished"]->updated = convert_timezone($r->updated , true);
                    $result[$key]->status_type["finished"]->name = $r->name;
                    $result[$key]->status_type["finished"]->account_id = $r->account_id;
                }

            }

            if($row->driver_status == 1){
                $result[$key]->driver_status = ' - Accepted';
            }else if($row->driver_status == 2){
                $result[$key]->driver_status = ' - Rejected';
            }else{
                $result[$key]->driver_status = '';
            }

            if(isset($result[$key]->status_type["cancel"])){
                $result[$key]->new = '<span class="label bg-red">Cancel</span>';
                $result[$key]->new_type = "cancel";
                $result[$key]->new_icon = "check_circle";
                $result[$key]->new_color = "red";
            }else if(isset($result[$key]->status_type["finished"])){
                $result[$key]->new = '<span class="label bg-green">Complete</span>';
                $result[$key]->new_type = "complete";
                $result[$key]->new_icon = "check_circle";
                $result[$key]->new_color = "green";
            }else if(isset($result[$key]->status_type["partially_complete"])){
                $result[$key]->new = '<span class="label bg-green">Complete</span>';
                $result[$key]->new_type = "for_confirmation";
                $result[$key]->new_icon = "check_circle";
                $result[$key]->new_color = "green";
            }else if(isset($result[$key]->status_type["allocated"])){
                $result[$key]->new = '<span class="label bg-light-green">Truck Allocated</span>';
                $result[$key]->new_type = "allocated";
                $result[$key]->new_icon = "book";
                $result[$key]->new_color = "light-green";
            }else if(isset($result[$key]->status_type["to_be_allocated"])){
                $result[$key]->new = '<span class="label bg-deep-orange">Truck To Be Allocated</span>';
                $result[$key]->new_type = "to_be_allocated";
                $result[$key]->new_icon = "bookmark";
                $result[$key]->new_color = "orange";
            }else{
                $result[$key]->new = '<span class="label bg-blue">New</span>';
                $result[$key]->new_type = "new";
                $result[$key]->new_icon = "bookmark_border";
                $result[$key]->new_color = "blue";
            }

            $images = $this->db->where("jobs_id" , $row->jobs_id)->get("jobs_images")->result();

            foreach($images as $k => $r){

                if($r->image_thumb){
                    $image = full_path_image($r->image_thumb , 'job');
                    $images[$k]->image_thumb = $image['path'];

                    if(!file_exists($image['absolute_path'])){
                        $images[$k]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
                    }

                }else{
                   $images[$k]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
               }

               if($r->image){
                    $image = full_path_image($r->image , 'job');
                    $images[$k]->image = $image['path'];

                    if(!file_exists($image['absolute_path'])){
                        $images[$k]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
                    }

                }else{
                   $images[$k]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
               }

            }

            if($row->signature){
               $i = full_path_image($row->signature , "signature");

               $image = new stdClass();
               $image->image_id = $row->jobs_id;
               $image->jobs_id_id = $row->jobs_id;
               $image->image = $i["path"] ;
               $image->image_thumb = $i["path"] ;

               $images[] = $image;
               $result[$key]->signature = full_path_image($row->signature , "signature");
            }

            $result[$key]->images = $images;
            $result[$key]->job_dates = array(
                "A" => $this->jobDate($row->jobs_id , "A") ,
                "B" => $this->jobDate($row->jobs_id , "B")
                );

            /*
                ready to go status
                
                0 - new
                1 - no defect
                2 - defect
                3 - fixed
                4 - delivery time
                5 - cancel job
                6 - arrived
                7 - change truck

            */
            
            switch ($row->ready_to_go) {
                case 0:
                    $result[$key]->status_job_icon = "directions_bus";
                    $result[$key]->status_job_class = "white-truck";
                    $result[$key]->status_message = "Waiting for safety check";
                    break;
                case 1:
                    $result[$key]->status_job_icon = "directions_bus";
                    $result[$key]->status_job_class = "green-truck";
                    $result[$key]->status_message = "Passed safety check";
                    break;
                case 2:
                    $result[$key]->status_job_icon = "directions_bus";
                    $result[$key]->status_job_class = "red-truck";
                    $result[$key]->status_message = "Truck has been reported to have a defect";
                    break;
                case 3:
                    $result[$key]->status_job_icon = "build";
                    $result[$key]->status_job_class = "green-truck";
                    $result[$key]->status_message = "Vehicle has been Fixed";
                    break;
                case 4:
                    $result[$key]->status_job_icon = "priority_high";
                    $result[$key]->status_job_class = "ex-truck";
                    $result[$key]->status_message = "ATTENTION! Check delivery time!";
                    break;
                case 5:
                    $result[$key]->status_job_icon = "";
                    $result[$key]->status_job_class = "";
                    $result[$key]->status_message = "";
                    break;
                case 6:
                    $result[$key]->status_job_icon = "directions_bus";
                    $result[$key]->status_job_class = "green-truck";
                    $result[$key]->status_message = "Truck has arrived";
                    break;
                default:
                    $result[$key]->status_job_icon = "directions_bus";
                    $result[$key]->status_job_class = "white-truck";
                    $result[$key]->status_message = "Waiting for safety check";
                    break;
            }

            if( ($result[$key]->delivery_time_raw - 172800) < time() AND $result[$key]->status_update == 0 AND !$row->with_outsource AND $row->ready_to_go != 6 AND (!isset($result[$key]->status_type["finished"]) AND !isset($result[$key]->status_type["partially_complete"])) ){

                switch ($row->ready_to_go) {
                    case 2:
                    case 3:
                        
                        break;
                    
                    default:
                        $result[$key]->status_job_icon = "priority_high";
                        $result[$key]->status_job_class = "ex-truck";
                        $result[$key]->status_message = "ATTENTION! Check delivery time!";
                        break;
                }
                
            }

            if($row->with_outsource){
                $result[$key]->status_job_icon = "account_circle";
            }else{

                switch ($row->ready_to_go) {
                    case 0:
                    case 1:
                    case 4:
                    case 5:
                        if($result[$key]->status_update == 0 AND  ($result[$key]->delivery_time_raw - ( 86400  / 2 )) < time() AND (!isset($result[$key]->status_type["finished"]) AND !isset($result[$key]->status_type["partially_complete"])) ){
                            $result[$key]->status_job_class .= " edit-job-status";
                        }
                        break;
                    case 2:
                        $result[$key]->status_job_class .= " fixed-truck-status";
                        break;
                    case 3:
                        $result[$key]->status_job_class .= " view-truck-status";
                        break;
                     break;
                }
                
            }


            $result[$key]->driver_last_job = $this->searchDriverLastJob($row->driver_id);
            
        }


        return $result;
    }

    private function searchDriverLastJob($driver_id){
        $from = strtotime("today midnight");
        $to = strtotime("today 23:59");

        $check = $this->db->select("jobs_id , ready_to_go")->where("driver_id" , $driver_id)->where("delivery_time >=" , $from)->where("delivery_time <=" , $to)->get("jobs")->result();
        
        $bool = "ALL_COMPLETE";

        foreach($check as $row){
            if($row->ready_to_go < 5){
                $bool = "NOT_COMPLETE";
            }
        }

        if(!$check){
            $bool = "NO_JOB";
        }

        return $bool;
    }

    private function jobDate($jobs_id , $type){
        $A = $this->db->select("arrived_time , pick_up_time , start_mileage , latitude , longitude")->where("jobs_id" , $jobs_id)->where("point_destination" , $type)->get("jobs_date")->row();

        if($A){
            $A->arrived_time = ($A->arrived_time) ? convert_timezone($A->arrived_time , true , true , false , "d M Y D h:i:s A") : "NA";
            $A->pick_up_time = ($A->pick_up_time) ? convert_timezone($A->pick_up_time , true , true , false , "d M Y D h:i:s A") : "NA";
            $A->latitude = ($A->latitude) ? $A->latitude : "NA";
            $A->longitude = ($A->longitude) ? $A->longitude : "NA";
        }else{
            $A = new stdClass();
            $A->arrived_time = "NA";
            $A->pick_up_time = "NA";
            $A->start_mileage = "NA";
            $A->longitude = "NA";
            $A->latitude = "NA";
        }

        return $A;
    }

    public function getSelectData(){
        $data = array();
        
        $this->db->select("a.id , CONCAT(name , ' ' , surname) as name , store_name");
        $this->db->join("users_store us" , "us.account_id = a.id");
        $this->db->join("store s" , "s.store_id = us.store_id");
        $this->db->where("a.company_id" , $this->session->userdata('company_id'))->where("a.status" , 1)->where("account_type" , DRIVER);
        
        $driver = $this->db->order_by("name" , "ASC")->get("accounts a")->result();
        $data["driver"] = array();
        
        foreach($driver as $key => $row){
            $data["driver"][$row->store_name][] = $row;
        }
        $this->db->select("id , vehicle_number , v.description , v.truck_type , store_name ");
        $this->db->join("store s" , "s.store_id = v.store_id");
        $this->db->where("v.company_id" , $this->session->userdata('company_id'))->where("v.status" , 1);
        $truck = $this->db->order_by("store_name" , "asc")->order_by("vehicle_number" , "asc")->get("vehicle_information v")->result();
        
        $data["truck"] = array();
        
        foreach($truck as $key => $row){
            $data["truck"][$row->store_name][$row->truck_type][] = $row;
        }
        
        $this->db->select("t.id , trailer_number , t.description , store_name");
        $this->db->join("store s" , "s.store_id = t.store_id");
        $this->db->where("t.company_id" , $this->session->userdata('company_id'))->where("t.status" , 1);
        
        $trailer = $this->db->order_by("trailer_number" , "asc")->get("trailer_information t")->result();
        $data["trailer"] = array();
        
        foreach($trailer as $key => $row){
            $data["trailer"][$row->store_name][] = $row;
        }


        $data['job_name'] = $this->autocomplete(true);
        $data['customer_name'] = $this->getCustomerNameList();
        
        return $data;
    }

    private function getCustomerNameList(){
        $this->db->select("c.account_id , c.customer_id , c.company_name");
        $this->db->join("customer c" , "c.account_id = a.id");
        $this->db->where("a.company_id" , $this->session->userdata('company_id'))->where("a.status" , 1);
        return $this->db->get("accounts a")->result();
    }

    public function getJobById($id , $config = true){
        if($config){
            $this->db->where("job_configuration_id" , $id);
            $result = $this->db->get("jobs_configuration")->row();
        }else{
            $this->db->select("j.* , jp.customer_id");
            $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id");
            $this->db->where("j.jobs_id" , $id);
            $result = $this->db->get("jobs j")->row();

            $result->price = round($result->price , 2);
            $result->outsource_price = round($result->outsource_price , 2);

            $result->loading_d = convert_timezone($result->loading_time , false , true , false , false , "d M Y");
            $result->loading_t = convert_timezone($result->loading_time , false , false , true);

            $result->delivery_d = convert_timezone($result->delivery_time, false , true , false , false , "d M Y");
            $result->delivery_t = convert_timezone($result->delivery_time , false , false , true);

        }

        return $result;
    }
    //TODO
    public function getJobByIdWithName($id){
        $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id");
        $this->db->where("jobs_id" , $id);
        $result = $this->db->get("jobs j")->row();

        $result->address = nl2br($result->address);
        $result->price = round($result->price , 2);
        $result->outsource_price = round($result->outsource_price , 2);

        $result->loading_d = convert_timezone($result->loading_time);
        $result->loading_t = convert_timezone($result->loading_time , false , true , true);

        $result->delivery_d = convert_timezone($result->delivery_time);
        $result->delivery_t = convert_timezone($result->delivery_time , false , false , true);

        return $result;
    }

    public function typeOfTruck(){
        $this->db->distinct("truck_type");
        $this->db->select("truck_type");
        $this->db->where("company_id" , $this->session->userdata("company_id"));
        return $this->db->order_by("truck_type")->get("vehicle_information")->result();
    }

    public function updateStatus(){
        $check = $this->db->where("jobs_id" , $this->input->post("jobs_id", TRUE))->where("status_type" , $this->input->post("status_type", TRUE))->get("jobs_status")->row();
        $return = array();

        $c = $this->db->select("with_outsource")->where("jobs_id" , $this->input->post("jobs_id", TRUE))->get("jobs")->row();

        if($this->input->post("status_type", TRUE) == "allocated"){

            if($c->with_outsource){
                $return["status"] = true;
                $return["message"] = "";

                if(!$return["status"]){
                    return $return;
                }
                
            }else{
                if(empty($this->input->post("driver", TRUE))){
                    $return["status"] = false;
                    $return["message"] = "No Driver Selected";
                    return $return;
                }
            }
            
        }else if($this->input->post("status_type", TRUE) == "to_be_allocated"){
            if($this->input->post("with_outsource", TRUE)){
                if(empty($this->input->post("outsource_company_name", TRUE))){
                    $return["status"] = false;
                    $return["message"] = "No Company Inputted";
                    return $return;
                }
            }
        }

        if($check){

            $status_id = $check->jobs_status_id;

            $this->db->where("jobs_status_id" , $check->jobs_status_id)->update("jobs_status" , [
                "updated" => time() , 
                "account_id" => $this->session->userdata("id")
                ]);

            $return = array(
                "status" => true , 
                "updated" => convert_timezone(time() , true) , 
                "type" => "updated"
                );

        }else{

            $arr = array(
                "jobs_id" => $this->input->post("jobs_id", TRUE) ,
                "status_type" => $this->input->post("status_type", TRUE) ,
                "account_id" => $this->session->userdata("id") ,
                "created" => time() ,
                "updated" => time()
                );
            $this->db->insert("jobs_status" , $arr);

            $status_id = $this->db->insert_id();

            $return = array(
                "status" => true , 
                "created" => convert_timezone(time() , true) ,
                "updated" => convert_timezone(time() , true) , 
                "type" => "created"
                );

        }

        switch ($this->input->post("status_type", TRUE)) {
            case 'to_be_allocated':
                //$total = ($this->input->post("with_outsource")) ? ($this->input->post("price") + $this->input->post("outsource_price")) : $this->input->post("price");
                $total = $this->input->post("price", TRUE);
                $vat = ($total * 0.20);
                
                $this->db->where("jobs_id" , $this->input->post("jobs_id", TRUE))->update("jobs" , [
                    "price" => $this->input->post("price", TRUE) ,
                    "total_price" => ($total + $vat),
                    "with_outsource" => ($this->input->post("with_outsource", TRUE)) ? 1 : 0 ,
                    "outsource_price" => ($this->input->post("with_outsource", TRUE)) ? $this->input->post("outsource_price", TRUE) : 0 ,
                    "outsource_company_name" => $this->input->post("outsource_company_name", TRUE) ,
                    "status_id" => $status_id ,
                    "vat" => $vat
                    ]);

                $return = array_merge($return , array(
                    "form_class" => "allocated_form" ,
                    "updated_by" => $this->session->userdata("name") ,
                    "panel_color" => "orange" ,
                    "legend_name" => "Truck To Be Allocated",
                    "price" => $total ,
                    "total_price" => ($total + $vat) , 
                    "vat" => $vat ,
                    ));

                break;

            case 'allocated':

                $arr = array(
                    "driver_id" => $this->input->post("driver", TRUE) ,
                    "load_trailer" => $this->input->post("load_trailer", TRUE) ,
                    "vehicle_id" => $this->input->post("vehicle_number", TRUE) ,
                    "trailer_id" => $this->input->post("trailer_number", TRUE) ,
                    "show_to_driver" => ($this->input->post("displayToDriver", TRUE) ) ? 1 : 0 ,
                    "outsource_truck" => $this->input->post("outsource_truck", TRUE),
                    "outsource_driver_name" => $this->input->post("outsource_driver_name", TRUE),
                    "status_id" => $status_id
                    );

                $this->db->where("jobs_id" , $this->input->post("jobs_id", TRUE))->update("jobs" , $arr);

                if(!empty($this->input->post("driver", TRUE))){
                    send_notification($this->input->post("driver", TRUE) , "new_jobs" , $this->input->post("jobs_id", TRUE));
                }

                $return = array_merge($return , array(
                    "form_class" => "last_form" ,
                    "updated_by" => $this->session->userdata("name") ,
                    "panel_color" => "light-green" ,
                    "legend_name" => "Truck Allocated"
                    ));
                
                break;

            case 'finished':
                
                $arr = array(
                    "demurrage" => $this->input->post("demurrage", TRUE) ,
                    "vat" => $this->input->post("vat", TRUE) ,
                    "total_price" => $this->input->post("total_price", TRUE),
                    "send_invoice" => ($this->input->post("sendInvoice", TRUE) ) ? 1 : 0 ,
                    "status_id" => $status_id ,
                    "job_po_number" => $this->input->post("job_po_number", TRUE)
                    );

                if($c->with_outsource){
                    $arr = array_merge($arr , array_merge(array("delivery_notes" => $this->input->post("delivery_notes", TRUE))));
                }

                $this->db->where("jobs_id" , $this->input->post("jobs_id", TRUE))->update("jobs" , $arr);     

                $this->addInvoice($this->input->post("jobs_id", TRUE));   

                $return = array_merge($return , array(
                    "form_class" => "last_form" ,
                    "updated_by" => $this->session->userdata("name") ,
                    "panel_color" => "bg-green" ,
                    "legend_name" => "Complete" ,
                    "_type" => "create_invoice_pdf" ,
                    "_job_id" => $this->input->post("jobs_id", TRUE)
                    ));

                break;

            case 'cancel':
                if($this->session->userdata("account_type") == SUPER_ADMIN){
                    $arr = array(
                        "cancel_notes" => $this->input->post("notes", TRUE),
                        "deleted" => time() ,
                        "status_id" => $status_id,
                        "with_charge" => ($this->input->post("charge_checkbox", TRUE) === "true") ? 1 : 0 ,
                        "confirmed_cancel" => 1 ,
                        "total_price" => $this->input->post("with_charge", TRUE)
                    );

                    if($this->input->post("charge_checkbox", TRUE) === "true"){

                        $this->addInvoice($this->input->post("jobs_id", TRUE));

                        $return = array_merge($return , array(
                            "_type" => "create_invoice_pdf" ,
                            "_job_id" => $this->input->post("jobs_id", TRUE)
                            ));
                    }
                    

                }else{
                    $arr = array(
                        "cancel_notes" => $this->input->post("notes", TRUE),
                        "deleted" => time() ,
                        "status_id" => $status_id,
                    );

                    $return  = array_merge($return , array(
                        "form_class" => "last_form" ,
                        "updated_by" => $this->session->userdata("name") ,
                        "panel_color" => "red" ,
                        ));
                }
                

                $this->db->where("jobs_id" , $this->input->post("jobs_id", TRUE))->update("jobs" , $arr);
                $this->db->where("jobs_id" , $this->input->post("jobs_id", TRUE))->update("jobs" , ["ready_to_go" => 5 ]);

                $return = array_merge($return , array(
                    "form_class" => "last_form" ,
                    "updated_by" => $this->session->userdata("name") ,
                    "panel_color" => "red" ,
                    "legend_name" => "Cancelled"
                    ));

                break;
            
            default:
                # code...
                break;
        }

        $this->notification->add_notification($this->input->post("jobs_id", TRUE) , $this->input->post("status_type", TRUE));

        $this->add_logs($this->input->post("jobs_id", TRUE) , $this->input->post("status_type", TRUE));

        return $return;
    }

    
    public function getSingleJobParse($job_id){
        return $this->getJobsList(false , false , false , false , $job_id);
    }


    public function addInvoice($job_id){
        $this->db->select("j.with_outsource , j.demurrage , j.vat , j.price , j.total_price , j.outsource_price , jp.customer_id , jp.company_id , j.job_number , j.job_po_number , j.job_name");
        $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id");
        $query = $this->db->where("jobs_id" , $job_id)->get("jobs j")->row();
        $arr = array(
            "job_id" => $job_id ,
            "paid_by" => "UNPAID" ,
            "invoice_date" => time() ,
            "status" => 0,
            "price" => $query->price ,
            "vat" => $query->vat ,
            "demurrage" => $query->demurrage ,
            "total_price" => $query->total_price ,
            "company_id" => $query->company_id ,
            "customer_id" => $query->customer_id ,
            "jpo_number" => $query->job_po_number ,
            "job_number" => $query->job_number,
            "jmerge_name" => $query->job_name
            );

        $this->db->insert("invoice" , $arr);

        $invoice_id = $this->db->insert_id();

        //TODO
        //$this->db->where("invoice_id" , $invoice_id)->update("invoice" , ["job_number" => $query->job_number , "invoice_number" => $invoice_id ]);
        //$this->db->where("invoice_id" , $invoice_id)->update("invoice" , ["job_number" => $query->job_number ]);

        $this->add_invoice_history($invoice_id);

        if($query->with_outsource){
            $this->addInvoiceForOutsource($job_id , $query);
        }
    }

    private function addInvoiceForOutsource($job_id , $query){
        $price = $query->outsource_price;
        $vat = $query->outsource_price * 0.20;
        $demurrage = $query->demurrage;
        $total_price = $price + $vat + $demurrage;

        $arr = array(
            "job_id" => $job_id ,
            "paid_by" => "UNPAID" ,
            "invoice_date" => time() ,
            "status" => 0 ,
            "to_outsource" => "YES",
            "price" => $price,
            "vat" => $vat ,
            "demurrage" => $demurrage,
            "total_price" =>  $total_price ,
            "company_id" => $query->company_id ,
            "customer_id" => $query->customer_id ,
            "jpo_number" => $query->job_po_number ,
            "job_number" => $query->job_number,
            "jmerge_name" => $query->job_name
            );

        $this->db->insert("invoice" , $arr);

        $invoice_id = $this->db->insert_id();

        //TODO
        //$this->db->where("invoice_id" , $invoice_id)->update("invoice" , ["invoice_number" => $invoice_id]);

        $this->add_invoice_history($invoice_id);
    }

    private function add_invoice_history($invoice_id){
        $invoice = $this->db->where("invoice_id" , $invoice_id)->get("invoice")->row_array();
        $invoice['updated'] = time();
        $invoice['updated_by'] = $this->session->userdata("id");

        $this->db->insert("invoice_history" , $invoice);
    }

    public function autocomplete($all = false){
        if($this->input->post("customer_name")){
            $customer_id = $this->db->select("customer_id")->where("company_name" , $this->input->post("customer_name"))->get("customer")->row();
        }

        $this->db->select("job_name , number_of_truck , telephone , site_contact , address , notes , driver_notes , type_of_truck , trailer_filled , load_site , arctic_type , division , build_dismantle , build_dismantle_other , zip_code , zip_code_load_site , job_number , job_po_number");
        $this->db->where("company_id" , $this->session->userdata("company_id"));

        if($this->input->post("customer_name") AND $customer_id){
            $this->db->where("customer_id" , $customer_id->customer_id);
        }

        if($this->session->userdata("account_type") == CUSTOMER){
            $this->db->where("customer_id" , $this->session->userdata("customer_id"));
        }

        // if(!$all){
        //     if($this->session->userdata("account_type") != SUPER_ADMIN){
        //         $this->db->where("customer_id" , $this->session->userdata("customer_id"));
        //     }
        // }
        //$this->db->like("job_name" , $this->input->post("job_name"));
        $result = $this->db->order_by("job_name" , "ASC")->get("jobs_configuration")->result();
        $r = array();

        foreach($result as $key => $row){
            $r[$row->job_name] = $row;
        }

        return $r;
    }

    private function add_logs($job_id , $status_type){
        $this->db->join("jobs_status js" , "js.jobs_id = j.jobs_id" );
        $job = $this->db->where("j.jobs_id" , $job_id)->where("js.status_type" , $status_type)->get("jobs j")->row();

        $arr = array(
            "jobs_id" => $job->jobs_id ,
            "driver_id" => $job->driver_id ,
            "vehicle_id" => $job->vehicle_id ,
            "trailer_id" => $job->trailer_id ,
            "price" => $job->price ,
            "status_type" => $status_type ,
            "show_to_driver" => $job->show_to_driver ,
            "account_id" => $this->session->userdata("id") ,
            "created" => time()
            );

        $this->db->insert("jobs_logs" , $arr);
    }

    public function getJobByIdForInvoice($job_id , $invoice = false){
        $this->db->select("i.paid_by , i.invoice_date , i.invoice_number , i.invoice_id , i.job_id , i.paid_date , i.confirmed_by , i.price , i.demurrage , i.total_price , i.vat , i.to_outsource , i.customer_id , i.job_number as jn , i.notes as inotes , i.jpo_number");
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

    public function getCustomerById($customer_id){
        return $this->db->where("customer_id" , $customer_id)->get("customer")->row();
    }
    
    public function updateCustomer(){
        $arr = array(
            "company_name" => htmlentities($this->input->post("company_name", TRUE)),
            "billing_address" => htmlentities($this->input->post("billing_address", TRUE)),
            "registration_number" => htmlentities($this->input->post("registration_number", TRUE)),
            "account_no" => htmlentities($this->input->post("account_no", TRUE)),
            "vat_number" => htmlentities($this->input->post("vat_number", TRUE)) 
            );
        $this->db->where('customer_id' , $this->input->post('customer_id', TRUE))->update('customer' , $arr);

        return $this->input->post('customer_id', TRUE);
    }

    public function getInvoiceSearchData(){
        $data['job_name'] = $this->autocomplete(true);
        $data['customer_name'] = $this->getCustomerNameList();
        
        return $data;
    }

    public function updateFormJob(){
        $data = $this->input->post();
        $arr = $data['data'];


        if(isset($arr['customer_id'])){
            $parent_id = $this->db->select("parent_id")->where("jobs_id" , $data['jobs_id'])->get("jobs")->row();

            $this->db->where("job_parent_id" , $parent_id->parent_id)->update("jobs_parent" , ["customer_id" => $arr['customer_id']]);

        }

        switch ($data['type']) {
            case 'first':
                
                $arr = array(
                    "loading_time" =>c_strtotime($arr["loading"]["date"].' '.$arr["loading"]["time"]),
                    "delivery_time" =>c_strtotime($arr["delivery"]["date"].' '.$arr["delivery"]["time"]),
                    "driver_notes" => $arr['driver_notes'] ,
                    "telephone" => $arr['telephone'] ,
                    "address" => $arr['address'] ,
                    "zip_code_load_site" => $arr['zip_code_load_site'] ,
                    "load_site" => $arr['load_site'] ,
                    "zip_code" => $arr['zip_code'] ,
                    "site_contact" => $arr['site_contact'],
                    "job_notes" => $arr['notes'],
                    "job_name" => $arr['job_name'],

                    );

                break;
            case 'new':
                $arr = array(
                    "loading_time" =>c_strtotime($arr["loading"]["date"].' '.$arr["loading"]["time"]),
                    "delivery_time" =>c_strtotime($arr["delivery"]["date"].' '.$arr["delivery"]["time"]),
                    "driver_notes" => $arr['driver_notes'] ,
                    "telephone" => $arr['telephone'] ,
                    "address" => $arr['address'] ,
                    "zip_code_load_site" => $arr['zip_code_load_site'] ,
                    "load_site" => $arr['load_site'] ,
                    "zip_code" => $arr['zip_code'] ,
                    "site_contact" => $arr['site_contact'],
                    "job_notes" => $arr['notes'],
                    "type_of_truck" => $arr['type_of_truck'],
                    "loading_time" =>c_strtotime($arr["loading"]["date"].' '.$arr["loading"]["time"]),
                    "delivery_time" =>c_strtotime($arr["delivery"]["date"].' '.$arr["delivery"]["time"]),
                    "job_name" => $arr['job_name']
                    );
                break;
            case 'new_customer':
                $price = $data['data']["price"];
                $vat = ($price * 0.20);

                $arr = array(
                    "loading_time" =>c_strtotime($arr["loading"]["date"].' '.$arr["loading"]["time"]),
                    "delivery_time" =>c_strtotime($arr["delivery"]["date"].' '.$arr["delivery"]["time"]),
                    "driver_notes" => $arr['driver_notes'] ,
                    "telephone" => $arr['telephone'] ,
                    "address" => $arr['address'] ,
                    "zip_code_load_site" => $arr['zip_code_load_site'] ,
                    "load_site" => $arr['load_site'] ,
                    "zip_code" => $arr['zip_code'] ,
                    "site_contact" => $arr['site_contact'],
                    "job_notes" => $arr['notes'],
                    "job_number" => $arr['job_number'],
                    "price" => $price ,
                    "vat" => $vat,
                    "total_price" => ($price + $vat),
                    "outsource_company_name" => @$arr["outsource_company_name"],
                    "outsource_price" => @$arr["outsource_price"],
                    "job_name" => $arr['job_name']
                    );
                break;
            case 'to_be_allocated':
                $old = $data['old'];

                $price = $data['data']["price"];
                $vat = ($price * 0.20);
               
                if($data['with_outsource']){
                    $arr = array(
                        "loading_time" =>c_strtotime($arr["loading"]["date"].' '.$arr["loading"]["time"]),
                        "delivery_time" =>c_strtotime($arr["delivery"]["date"].' '.$arr["delivery"]["time"]),
                        "driver_notes" => $arr['driver_notes'] ,
                        "telephone" => $arr['telephone'] ,
                        "address" => $arr['address'] ,
                        "zip_code_load_site" => $arr['zip_code_load_site'] ,
                        "load_site" => $arr['load_site'] ,
                        "zip_code" => $arr['zip_code'] ,
                        "site_contact" => $arr['site_contact'],
                        "job_notes" => $arr['notes'],
                        "job_number" => $arr['job_number'],
                        "outsource_truck" => $arr["outsource_truck"] ,
                        "outsource_driver_name" => $arr["outsource_driver_name"] ,
                        "trailer_id" => $arr["trailer_id"] ,
                        "price" => $price ,
                        "vat" => $vat,
                        "total_price" => ($price + $vat),
                        "outsource_company_name" => @$arr["outsource_company_name"],
                        "outsource_price" => @$arr["outsource_price"],
                        "job_name" => $arr['job_name'],
                        "load_trailer" => $arr['load_trailer']
                    );
                }else{
                    $arr = array(
                        "loading_time" =>c_strtotime($arr["loading"]["date"].' '.$arr["loading"]["time"]),
                        "delivery_time" =>c_strtotime($arr["delivery"]["date"].' '.$arr["delivery"]["time"]),
                        "driver_notes" => $arr['driver_notes'] ,
                        "telephone" => $arr['telephone'] ,
                        "address" => $arr['address'] ,
                        "zip_code_load_site" => $arr['zip_code_load_site'] ,
                        "load_site" => $arr['load_site'] ,
                        "zip_code" => $arr['zip_code'] ,
                        "site_contact" => $arr['site_contact'],
                        "job_notes" => $arr['notes'],
                        "job_number" => $arr['job_number'],
                        "vehicle_id" => $arr["vehicle_id"] ,
                        "driver_id" => $arr["driver_id"] ,
                        "trailer_id" => $arr["trailer_id"] ,
                        "price" => $price ,
                        "vat" => $vat,
                        "total_price" => ($price + $vat),
                        "outsource_company_name" => @$arr["outsource_company_name"],
                        "outsource_price" => @$arr["outsource_price"],
                        "job_name" => $arr['job_name'],
                        "load_trailer" => $arr['load_trailer']
                    );

                    if($arr['driver_id']){
                        send_notification($arr['driver_id']);
                    }
                }

                if($old['driver_id'] != $arr['driver_id'] || $old['vehicle_id'] != $arr['vehicle_id'] || $old['trailer_id'] != $arr['trailer_id']){
                    $arr['driver_status'] = 0;
                    $this->db->where("job_id" , $data["jobs_id"])->delete("report_jobs");
                }
               
                break;
            default:
                # code...
                break;
        }

        if(!$this->db->where("jobs_id" , $data['jobs_id'])->update("jobs" , $arr)){
            return false;
        }else if(!$this->db->where("jobs_status_id" , $data['status_id'])->update("jobs_status" , ["updated" => time()])){
            return false;
        }else{
            return $arr;
        }

    }




}