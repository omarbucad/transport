<?php

class Outsource_model extends CI_Model {

	public function getAccount(){
        $this->db->join("outsource_accounts oa" , "oa.account_id = a.id");
        $this->db->join("outsource c" , "c.outsource_id = oa.outsource_id");
        $this->db->where("account_type" , OUTSOURCE);

        if($this->input->get("submit")){
            if($this->input->get("company_name")){
                $this->db->like("company_name" , $this->input->get("company_name"));
            }
            if($this->input->get("registration_number")){
                $this->db->where("registration_number" , $this->input->get("registration_number"));
            }
            if($this->input->get("vat_number")){
                $this->db->where("vat_number" , $this->input->get("vat_number"));
            
            }if($this->input->get("email")){
                $this->db->where("email" , $this->input->get("email"));
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

    public function add(){
    	$accounts = array(
            "company_id" => $this->session->userdata('company_id', TRUE),
            "name" => htmlentities($this->input->post("name", TRUE)),
            "surname" => htmlentities($this->input->post("surname", TRUE)),
            "email" => htmlentities($this->input->post("email", TRUE)),
            "address" => htmlentities($this->input->post("address", TRUE)),
            "username" => htmlentities($this->input->post("username", TRUE)),
            "password" => htmlentities(md5($this->input->post("password", TRUE))),
            "account_type" => OUTSOURCE,
            "status" => 1,
            "created" => time()
            );

        $this->db->insert('accounts', $accounts, TRUE);
        $accounts_id = $this->db->insert_id();

        $customer = array(
            "account_id" => $accounts_id ,
            "company_name" => htmlentities($this->input->post("company_name", TRUE)),
            "registration_number" => htmlentities($this->input->post("registration_number", TRUE)),
            "vat_number" => htmlentities($this->input->post("vat_number", TRUE)),
            "account_no" => htmlentities($this->input->post("account_no", TRUE)),
            "billing_address" => htmlentities($this->input->post("billing_address", TRUE)),
            );

        $this->db->insert("outsource" , $customer);

        $customer_id = $this->db->insert_id();

        $this->db->insert("outsource_accounts" , ["account_id" => $accounts_id , "outsource_id" => $customer_id]);

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
        $folder = "./public/upload/outsource/".$year."/".$month;

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
            
            $this->db->where("id" , $id)->update("outsource" , ["image" => $data['file_name'] , "image_thumb" => $thumb_name]);
        }
    
    }

    public function getOutsourceById($id){
        return $this->db->where("outsource_id" , $id)->get("outsource")->row();
    }

    public function getOutsourceByIdWithEmail($id){
        $data = array();
        $data['parent'] = $this->getOutsourceById($id);

        $this->db->join("accounts a" , "a.id = oa.account_id");
        $data['outsource_data'] = $this->db->where("oa.outsource_id" , $id)->get("outsource_accounts oa")->result();
        
        return $data;
    }

    public function update(){
        $arr = array(
            "company_name" => htmlentities($this->input->post("company_name", TRUE)),
            "billing_address" => htmlentities($this->input->post("billing_address", TRUE)),
            "account_no" => htmlentities($this->input->post("account_no", TRUE)),
            "registration_number" => htmlentities($this->input->post("registration_number", TRUE)),
            "vat_number" => htmlentities($this->input->post("vat_number", TRUE)) 
            );
        $this->db->where('outsource_id' , $this->input->post('outsource_id', TRUE))->update('outsource' , $arr);

        return $this->input->post('outsource_id', TRUE);
    }

    public function getListForSelect(){
    	$this->db->select("o.outsource_id as id , o.company_name");
    	$this->db->join("accounts a " , "a.id = o.account_id");
    	$this->db->where("company_id" , $this->session->userdata("company_id"));
    	$this->db->where("status" , 1);
    	return $this->db->order_by("company_name")->get("outsource o")->result();
    }
}