<?php

class Vehicle_model extends CI_Model {

        public function InsertVehicle(){
        	$arr = array(
                "vehicle_number" => htmlentities($this->input->post('vehicle_number', TRUE)) ,
                "truck_make" => htmlentities($this->input->post('truck_make', TRUE)) ,
        		"truck_type" => htmlentities($this->input->post('truck_type', TRUE)) ,
        		"description" => htmlentities(nl2br($this->input->post('description', TRUE) )),
        		"status" => 1 ,
                "store_id" => $this->input->post("store_id", TRUE),
                "company_id" => $this->session->userdata('company_id', TRUE),
        		"created" => time()
        		);

           	$this->db->insert('vehicle_information', $arr);
                
            $last_id = $this->db->insert_id();
            
            $this->do_upload($last_id);

            return $last_id;
        }

        public function do_upload($id){
            $year = date("Y");
            $month = date("m");

            $path = './public/upload/vehicles/'.$year.'/'.$month.'/';

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
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

                        $arr = array(
                            "vehicle_id" => $id ,
                            "image" => $year.'/'.$month.'/'.$image['file_name'] ,
                            "image_thumb" => $year.'/'.$month.'/'.$thumb_name
                            );

                        $this->db->insert('vehicle_images' , $arr);
                    }
                }//end for loop

            }//end if

        }

        public function getVehicleImages(){
            if(!empty($this->input->get("vin"))){
                $this->db->like("vehicle_information.vehicle_number" , $this->input->get("vin"));
            }

            $this->db->from('vehicle_information');
            $this->db->join('vehicle_images' , 'vehicle_information.id = vehicle_images.vehicle_id');
            $this->db->where('company_id' , $this->session->userdata('company_id'))->order_by('vehicle_number' , 'asc');

            $result = $this->db->get()->result();

            foreach($result as $key => $row){
                if($row->image){
                    $image = full_path_image($row->image , 'vehicle');
                    $result[$key]->image = $image['path'];

                    if(!file_exists($image['absolute_path'])){
                        $result[$key]->image = $this->config->base_url('/public/images/image-not-found.png');
                    }

                }else{
                   $result[$key]->image = $this->config->base_url('/public/images/image-not-found.png');
               }

               if($row->image_thumb){
                    $image = full_path_image($row->image_thumb , 'vehicle');
                    $result[$key]->image_thumb = $image['path'];

                    if(!file_exists($image['absolute_path'])){
                        $result[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
                    }

                }else{
                   $result[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
               }


            }

            $temp_array = array();
            
            foreach($result as $key => $row){
                $temp_array[$row->vehicle_number][] = $row;
            }

            return $temp_array;
        }

        public function getVehicle(){
            
            if(!empty($this->input->get("vin"))){
                $this->db->like("vehicle_number" , $this->input->get("vin"));
            }

            $result = $this->db->where('company_id' , $this->session->userdata('company_id'))->order_by('vehicle_number' , 'asc')->get('vehicle_information')->result();
            foreach($result as $key => $row){
                $result[$key]->status = account_status($row->status);
                $result[$key]->description = nl2br($row->description);
            }
            return $result;
        }

        public function getVehicleNumber($id){
            $this->db->select('vi.vehicle_number , vi.description , vi.id');
            $this->db->join('accounts a ' , 'a.company_id = vi.company_id');
            $result = $this->db->where('a.id' , $id)->where('vi.status' , 1)->order_by('vehicle_number' , 'asc')->get('vehicle_information vi')->result_array();
            return $result;
        }

        public function removeVehicle($id){
            $this->db->where("id" , $id)->delete("vehicle_information");
            $this->db->where("vehicle_id" , $id)->delete("vehicle_images");
        }

        public function getVehicleById($id){
            return $this->db->where('company_id' , $this->session->userdata('company_id'))->where('id' , $id)->get('vehicle_information')->row();
        }
        public function UpdateVehicle(){
             $arr = array(
                "vehicle_number" => htmlentities($this->input->post('vehicle_number', TRUE)) ,
                "truck_make" => htmlentities($this->input->post('truck_make', TRUE)) ,
                "truck_type" => htmlentities($this->input->post('truck_type', TRUE)) ,
                "description" => nl2br($this->input->post('description', TRUE)) ,
                "status" =>  $this->input->post('status', TRUE)  ,
                "updated" => time()
                );

            $this->db->where('id' , $this->input->post('id', TRUE))->update('vehicle_information' , $arr);

            $this->do_upload($this->input->post('id', TRUE));

            return $this->input->post('id', TRUE);
        }
    
        public function getDriver(){
            $this->db->select("a.id , CONCAT(name , ' ' ,surname) as name , a.image , a.image_thumb , a.status , vehicle_number , trailer_number , s.store_name  , d.license_number , d.license_expiry_date");
            $this->db->join("driver d" , "d.account_id = a.id" , "LEFT");
            $this->db->join("vehicle_information vi" , 'vi.id = d.vehicle_id' , "LEFT");
            $this->db->join("trailer_information ti" , 'ti.id = d.trailer_id' , "LEFT");
            $this->db->join("users_store us" , "us.account_id = a.id");
            $this->db->join("store s" , "s.store_id = us.store_id");
            $this->db->where("account_type" , DRIVER);

            if($this->input->get("submit")){
                if($this->input->get("id")){
                    $this->db->where_in("d.account_id " , $this->input->get("id"));
                }
            }

            $result = $this->db->order_by("name" , "ASC")->get("accounts a")->result();
            
            foreach($result as $key => $row){
                $result[$key]->status = account_status($row->status);
                $result[$key]->license_expiry_date = convert_timezone($row->license_expiry_date);

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
    
        public function getSelectVehicleAndTrailer(){
            $id = $this->input->post("id", TRUE);
            $company_id = $this->db->select("store_id")->where("account_id" , $id)->get("users_store")->row();
            $selected = $this->db->where("account_id" , $id)->get("driver")->row();
            $data = array();
            $data["selected_vehicle"] = ($selected) ? $selected->vehicle_id : NULL;
            $data["selected_trailer"] = ($selected) ? $selected->trailer_id : NULL;
            $data['license_expiry_date'] = ($selected) ? ((convert_timezone($selected->license_expiry_date) != "NA") ? convert_timezone($selected->license_expiry_date, false , true , false ,false ,  "D d M Y ") : "") : NULL;
            $data['license_number'] = ($selected) ? $selected->license_number : NULL;
            $files = $this->db->where("driver_id" , $id)->get("driver_images")->result();
            $tmp = "";

            foreach($files as $r){
                $image = full_path_image($r->image , 'driver');
                $tmp .= '<div class="row"><div class="col-xs-10">';
                    $tmp .= '<a href="'.$image['path'].'" target="_blank">'.$r->image.'</a>';
                $tmp .= '</div>';
                $tmp .= '<div class="col-xs-2">';
                     $tmp .= '<a href="javascript:void(0);" class="remove-file" data-id="'.$r->driver_image_id.'">Remove</a>';
                $tmp .= '</div></div>';
            }
            $data['files'] = $tmp;
            $data["vehicle"] = $this->db->select("id , vehicle_number , description")->where("store_id" , $company_id->store_id)->where("status" , 1)->order_by("vehicle_number" , "asc")->get("vehicle_information")->result();
            $data["trailer"] = $this->db->select("id , trailer_number , description")->where("store_id" , $company_id->store_id)->where("status" , 1)->order_by("trailer_number" , "asc")->get("trailer_information")->result();
            return $data;
        }
    
        public function setDefaultTruck(){
            if($this->input->post()){

                $id = $this->input->post("id", TRUE);
                $check = $this->db->where('account_id' , $id)->get("driver")->num_rows();
                
                if($check){
                    $this->db->where("account_id" , $id)->update("driver" , [
                        "vehicle_id" => $this->input->post("vehicle_id", TRUE) , 
                        "trailer_id" => $this->input->post("trailer_id", TRUE) ,
                        "license_number" => $this->input->post("license_number" , TRUE),
                        "license_expiry_date" => strtotime($this->input->post("license_expiry_date" , TRUE))
                    ]);
                }else{
                    $this->db->insert("driver" , [
                        "vehicle_id" => $this->input->post("vehicle_id", TRUE) , 
                        "trailer_id" => $this->input->post("trailer_id", TRUE) , 
                        "account_id" => $id ,
                        "license_number" => $this->input->post("license_number" , TRUE),
                        "license_expiry_date" => strtotime($this->input->post("license_expiry_date" , TRUE))
                    ]);
                }

                $this->upload_documents($id);

            }
            
        }


        public function upload_documents($driver_id){
            $year = date("Y");
            $month = date("m");

            $path = './public/upload/driver/'.$year.'/'.$month.'/';

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $config['upload_path']          = $path;
            $config['allowed_types']        = 'gif|jpg|png|pdf';

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

                    $config['file_name'] = $driver_id.'_'.time().'_'.$data['name'][$i];

                    $this->upload->initialize($config);

                    if ( $this->upload->do_upload('file')){
                        $image = $this->upload->data();

                        $arr = array(
                            "driver_id" => $driver_id ,
                            "image" => $year.'/'.$month.'/'.$image['file_name'] ,
                            "image_type" => $data['type'][$i]
                            );

                        $this->db->insert('driver_images' , $arr);
                    }
                }//end for loop

            }//end if
        }
        
        public function getStore(){
            return $this->db->select("store_name , store_id")->where("company_id" , $this->session->userdata('company_id'))->where("status" , 1)->get("store")->result();
        }
}