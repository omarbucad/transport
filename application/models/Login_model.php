<?php

class Login_model extends CI_Model {

    public function getAccount($id = false){
        

        $this->db->select('c.company_name , a.* , us.store_id , ca.customer_id');

        if($id){
            $this->db->where("a.id" , $id);    
        }else{
            $username = $this->input->post("username", TRUE);
            $password = $this->input->post("password", TRUE);

            $this->db->where("username" , $username);
            //$this->db->or_where("email" , $username);
            $this->db->where("password" , $password);
        }

        $this->db->where("status" , 1);
        $this->db->join("company c" , "c.id = a.company_id");
        $this->db->join("users_store us" , "us.account_id = a.id" , "LEFT");
        $this->db->join("customer_accounts ca" , "ca.account_id = a.id" , "LEFT");

        $result = $this->db->get('accounts a')->row_array();

        if($result){



            $image = full_path_image($result['image'] , 'accounts');
            $result['image'] = $image['path'];

            if(!file_exists($image['absolute_path'])){
                $result['image'] = $this->config->base_url('/public/images/image-not-found.png');
            }

            if($result["account_type"] == SUPER_ADMIN){
                $store = $this->db->select("store_id")->where("company_id" , $result["company_id"])->get("store")->row_array();
                $result["store_id"] = $store["store_id"];
            }else if($result["account_type"] == OUTSOURCE){
                $outsource = $this->db->where("account_id" , $result["id"])->get("outsource_accounts")->row();
                $result['outsource_id'] = $outsource->outsource_id;
            }else if($result["account_type"] == DRIVER){

                $arr = array(
                    "account_id" => $result['id'], 
                    "login_type" => "MANUAL",
                    "device_id" => $this->input->post("device_id"),
                    "time_in" => time(),
                    "created" => time()
                    );

                $this->db->insert("timetracker_login" , $arr);

                $result["login_id"] = $this->db->insert_id();
            }
        }

        return $result;
    }

    public function updateLastLogin($id){
        $this->db->where('id' , $id)->update('accounts' , ["last_login" => time()]);
    }

    public function registerCompany(){
        $this->db->insert('company' , ["company_name" => $this->input->post('company', TRUE) , "created" => time()]);
        $company_id = $this->db->insert_id();

        $arr = array(
            "company_id" => $company_id,
            "name" => $this->input->post("name", TRUE),
            "username" => $this->input->post("username", TRUE),
            "email" => $this->input->post("email", TRUE),
            "password" => md5($this->input->post("password", TRUE)),
            "account_type" => 'SUPER ADMIN',
            "status" => 1 ,
            "created" => time()
            );

        $this->db->insert("accounts" , $arr);
        return $this->db->insert_id();
    }

    public function getAccountByEmail(){
        return $this->db->where('email' , $this->input->post('email', TRUE))->get('accounts')->row();
    }

    public function generateTokenForgottenPassword($data){

        $token = md5(time().$data->email);

        $arr = array(
            "token" => $token ,
            "account_id" => $data->id,
            "email" => $data->email,
            "created" => time()
            );

        $this->db->insert('forgotpassword' , $arr);

        return $token;

    }

    public function resetPassword($token){
        $this->db->join('accounts' , 'accounts.id = forgotpassword.account_id');
        $result = $this->db->where('token' , $token)->where('forgotpassword.status' , '0')->get('forgotpassword')->row();

        if($result){
            if((intval($result->created)+86400) >= time() ){

                $string = generateRandomString();

                $this->db->where("id" , $result->account_id)->update("accounts" , ["password" => md5($string) , 'status' => 1]);
                
                $result->generated_password = $string;

                return $result;

            }else{
                $this->db->where("id" , $result->account_id)->update("accounts" , ["password" => md5($string) , 'status' => 1]);
            }
        }

        return false;
    }

    public function login_face(){
        if($image_data = $this->uploadImage()){

            $face_data = $this->face->getFaceId($image_data["path"] , $image_data['name'] , false , "find_face");

            if($face_data["status"]){

                $data = $this->getAccount($face_data["account_id"]);

                if($data){

                    $login_id = $this->generateThumbnail($image_data , $face_data["account_id"]);

                    //$data['login_id'] = $login_id;

                    return array("status" => true , "data" => $data);

                }else{

                   return array("status" => false , "message" => "Error in Getting of Account");

                }

            }else{
                $this->deleteImage($image_data["absolute_path"]);

                return array("status" => false , "message" => $face_data["message"]);
            }
        }else{
            return array("status" => false , "message" => "Error in Uploading Image");
        }
    }

    private function uploadImage(){
        $image = $this->input->post("image", TRUE);

          if($this->input->post()){

            $name = time().'.JPG';
            $thumb_name = time().'_thumb.JPG';

            $year = date("Y");
            $month = date("m");
            $folder = "./public/upload/face/".$year."/".$month;

            if (!file_exists($folder)) {
              mkdir($folder, 0777, true);
              create_index_html($folder);
            }

            $path = $folder.'/'.$name;

            $decodedImage = base64_decode("$image");

            file_put_contents($path , $decodedImage);

            return array(
                "path" => $this->config->base_url(ltrim($path, '.')) ,
                "name" => $name ,
                "absolute_path" => $path ,
                "thumb_name" => $thumb_name
                );
          }

        return false;
    }


    private function deleteImage($image_data){
        unlink($image_data);
    }

    private function generateThumbnail($image_data , $account_id){
        $thumb_config['image_library']  = 'gd2';
        $thumb_config['source_image']   = $image_data["path"];
        $thumb_config['create_thumb']   = TRUE;
        $thumb_config['maintain_ratio'] = TRUE;
            //$thumb_config['width']          = 500;
        $thumb_config['height']         = 500;

        $this->load->library('image_lib', $thumb_config);
        $this->image_lib->initialize($thumb_config); 
        $this->image_lib->resize();

        $year = date("Y");
        $month = date("m");

        $thumb_name = $year."/".$month.'/'.$image_data["thumb_name"];
        $image_name = $year."/".$month.'/'.$image_data["name"];

        $arr = array(
            "account_id" => $account_id , 
            "image" => $image_name , 
            "image_thumb" => $thumb_name , 
            "login_type" => "FACE",
            "device_id" => $this->input->post("device_id"),
            "time_in" => time(),
            "created" => time()
            );

        $this->db->insert("timetracker_login" , $arr);

        return $this->db->insert_id();
    }

}