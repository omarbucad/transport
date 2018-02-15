<?php

class Account_model extends CI_Model {

    public function insertAccount(){
            $arr = array(
                    "name" => htmlentities($this->input->post("name", TRUE)),
                    "surname" => htmlentities($this->input->post("surname", TRUE)),
                    "email" => htmlentities($this->input->post("email", TRUE)),
                    "address" => htmlentities($this->input->post("address", TRUE)),
                    "username" => htmlentities($this->input->post("username", TRUE)),
                    "password" => md5($this->input->post("password", TRUE)),
                    "account_type" => $this->input->post("account_type", TRUE),
                    "company_id" =>  $this->session->userdata('company_id'),
                    "status" => 1 ,
                    "created" => time()
                    );

            $this->db->insert('accounts', $arr);
            
            $last_id = $this->db->insert_id();

            if($this->input->post('store_id', TRUE)){
                $store = array();
                foreach($this->input->post('store_id', TRUE) as $row){
                    $store[] = array(
                        "company_id" => $this->session->userdata('company_id') ,
                        "account_id" => $last_id ,
                        "store_id" => $row
                        );
                }
                $this->db->insert_batch('users_store', $store); 
            }

            if($this->input->post("account_type", TRUE) == CUSTOMER){
                $this->db->insert("customer_accounts" , ["customer_id" => $this->input->post("customer_id", TRUE) , "account_id" => $last_id]);
            }
            
            $this->do_upload($last_id , "add_face");

            return $last_id;
    }

    public function removeAccount($id){
        $this->db->where('id' , $id);
        $check = $this->db->where('company_id' , $this->session->userdata('company_id'))->count_all_results('accounts');

        if($check){
            
            // $result = $this->db->where("id" , $id)->get("accounts")->row();

            // $path = FCPATH.'public/upload/accounts/';

            // $images = $path.$result->image;
            // $images_thumb = $path.$result->image_thumb;

            // if(file_exists($images)){
            //     unlink($images);  
            // }
            // if(file_exists($images_thumb)){
            //     unlink($images_thumb);  
            // }

            // $this->db->where("id" , $id)->delete("accounts");
            // $this->db->where("account_id" , $id)->delete('users_store');

            $this->db->where("id" , $id)->update("accounts" , ["status" => 0]);

            return true;
        }
        
        return false;
    }

    public function getAccount(){
            if(!empty($this->input->get("name"))){
                $this->db->like("name" , $this->input->get("name"));
                $this->db->or_like("surname" , $this->input->get("name"));
            }
            if(!empty($this->input->get("account_type"))){
                $this->db->where("account_type" , $this->input->get("account_type"));
            }
            if(!empty($this->input->get("status"))){
                if($this->input->get("status") == "active"){
                    $this->db->where("status" , 1);
                }else{
                    $this->db->where("status" , 0);
                }
            }
            //$this->db->where("account_type !=" , CUSTOMER);
            $result = $this->db->where('company_id' , $this->session->userdata('company_id'))->order_by("name" , 'asc')->get("accounts")->result();

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

    public function do_upload($id , $update = "update_face"){
        $year = date("Y");
        $month = date("m");
        $folder = "./public/upload/accounts/".$year."/".$month;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            create_index_html($folder);
        }
    
        $image_name = $id.'_'.time().'_'.$_FILES['file']['name'];
        $image_name = str_replace("^", "_", $image_name);
       
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
            $thumb_config['width']              = 200;
            $thumb_config['height']             = 200;

            $this->image_lib->clear(); 
            $this->image_lib->initialize($thumb_config); 

            $this->image_lib->resize();

            $thumb_name = $data['raw_name'].'_thumb'.$data['file_ext'];
            $thumb_name = $year."/".$month.'/'.$thumb_name;

            $this->db->where("id" , $id)->update("accounts" , ["image" => $year."/".$month.'/'.$image_name , "image_thumb" => $thumb_name]);

            $image_path = $this->config->base_url(ltrim($folder, '.').'/'.$data['file_name']);

            $this->face->getFaceId($image_path , $data['file_name'] , $id , $update);
        }
    }

    public function getAccountById($id , $raw = false){
        $this->db->where('id' , $id);
        $this->db->where('company_id' , $this->session->userdata('company_id'));
        $result = $this->db->get('accounts')->row();

        if(!$raw AND $result){

            $result->status = account_status($result->status);
            $result->account_type = account_type($result->account_type);
            $result->last_login = convert_timezone($result->last_login , true);

            if($result->image_thumb){
                $image = full_path_image($result->image_thumb , 'accounts');
                $result->image_thumb = $image['path'];

                if(!file_exists($image['absolute_path'])){
                    $result->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
                }

            }else{
               $result->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
           }
        }

        return $result;
    }

    public function updateAccount($mobile = false){

        if($mobile){
            $arr = array(
                    "name" => htmlentities($this->input->post("name", TRUE)),
                    "surname" => htmlentities($this->input->post("surname", TRUE)),
                    "email" => htmlentities($this->input->post("email", TRUE)),
                    "address" => htmlentities($this->input->post("address", TRUE)),
                    "updated" => time()
                    );    
        }else{
            $arr = array(
                    "name" => htmlentities($this->input->post("name", TRUE)),
                    "surname" =>htmlentities( $this->input->post("surname", TRUE)),
                    "email" => htmlentities($this->input->post("email", TRUE)),
                    "address" => htmlentities($this->input->post("address", TRUE)),
                    "account_type" => $this->input->post("account_type", TRUE),
                    "status" => $this->input->post("status", TRUE) ,
                    "updated" => time()
                    );
        }

        $this->db->where('id' , $this->input->post('id', TRUE));

        $this->db->update('accounts' , $arr);

        if(!$mobile){
            $this->do_upload($this->input->post('id', TRUE));
        }

        return $this->input->post('id', TRUE);
    }

    public function getAccountList($driver = false){
        $this->db->select("id , CONCAT(name, ' ', surname) AS name");
        if($driver){
            $this->db->where("account_type" , "DRIVER");
        }
        return $this->db->where('company_id' , $this->session->userdata('company_id'))->where('status' , 1)->order_by("name" , "ASC")->get('accounts')->result();
    }

    public function changepassword($id = false){
        
        if(!$id){
            $id = $this->session->userdata('id');
        }

        $this->db->where('id' , $id)->update('accounts' , ["password" => md5($this->input->post('password', TRUE))]);
    }
}