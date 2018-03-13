<?php

class Report_model extends CI_Model {

    //first step

    public function generateId(){
        $check = $this->db->select("report_change , id")->where("user_id" , $this->input->post("user_id", TRUE))->where("job_id" , $this->input->post("job_id", TRUE))->get("report", TRUE)->row();

        if($check){

           if($check->report_change){
                $store_id = $this->db->select("store_id")->where("account_id" , $this->input->post("user_id", TRUE))->get("users_store")->row();
                $job = $this->db->select("vehicle_id , trailer_id ")->where("jobs_id" , $this->input->post("job_id", TRUE))->get("jobs")->row();

                $arr = array(
                   "user_id" => $this->input->post('user_id', TRUE),
                   "job_id" => $this->input->post("job_id", TRUE),
                   "start_mileage" => $this->input->post('start_mileage', TRUE),
                   "vehicle_registration_number" =>  $job->vehicle_id,
                   "trailer_number" => $job->trailer_id,
                   "start_date" => time() ,
                   "store_id" => $store_id->store_id,
                   "report_type" => 1,
                   "created" => time(),
                   "updated" => time()
                 );

                $this->db->insert('report' , $arr);

                $report_id = $this->db->insert_id();

                $this->insertTempCheckList($report_id);

                return $report_id;
           }else{

              $this->db->where("id" , $check->id)->update("report" , array(
                "start_mileage" => $this->input->post('start_mileage', TRUE),
                "start_date" => time()
                ));

              return $check->id;
           }

        }else{
            $store_id = $this->db->select("store_id")->where("account_id" , $this->input->post("user_id", TRUE))->get("users_store")->row();
            $job = $this->db->select("vehicle_id , trailer_id ")->where("jobs_id" , $this->input->post("job_id", TRUE))->get("jobs")->row();

            $arr = array(
               "user_id" => $this->input->post('user_id', TRUE),
               "job_id" => $this->input->post("job_id", TRUE),
               "start_mileage" => $this->input->post('start_mileage', TRUE),
               "vehicle_registration_number" =>  $job->vehicle_id,
               "trailer_number" => $job->trailer_id,
               "start_date" => c_strtotime($this->input->post('start_date', TRUE)) ,
               "store_id" => $store_id->store_id,
               "report_type" => 1,
               "created" => time(),
               "updated" => time()
             );

            $this->db->insert('report' , $arr);
            $report_id = $this->db->insert_id();
            $this->insertTempCheckList($report_id);
            return $report_id;
        }
        
    }

    public function insertTempCheckList($report_id , $checklist_type = "VEHICLE REPORT"){

        if($checklist_type == "VEHICLE REPORT"){
          $x = 28;
        }else if($checklist_type == "MOFFET REPORT"){
          $x = 33;
        }else{
          $x = 26;
        }

        for($i = 0 ; $i < $x ; $i++){
           $this->db->insert('report_checklist' , array(
               "report_id" => $report_id ,
               "checklist_index" => $i ,
               "checklist_timer" => 0,
               "value" => "TEMP"
             )); 
        }
    }

    //second step
    public function insertChecklist(){
       $report_id = $this->input->post('report_id', TRUE);

       $this->updateStep($report_id , 2);

       $checklist = $this->input->post('checklist', TRUE);
       $timer = $this->input->post("checklisttimer", TRUE);

       $this->removeChecklist($report_id);

       $defect = false;

       foreach($checklist as $key => $row){
          $this->db->insert('report_checklist' , array(
             "report_id" => $report_id ,
             "checklist_index" => $key ,
             "checklist_timer" => c_strtotime($timer[$key]),
             "value" => $row
           ));

          if($row == "DEFECT"){
            $defect = true;
          }
       }

        if($defect){
          $this->db->where("jobs_id" , $this->input->post("job_id", TRUE))->update("jobs" , ["ready_to_go" => 2 ]);
        }else{
          $this->db->where("jobs_id" , $this->input->post("job_id", TRUE))->update("jobs" , ["ready_to_go" => 1 ]);
        }

        $this->db->where("jobs_id" , $this->input->post("job_id", TRUE))->update("jobs" , array("checklist_done" => 1));



    }


    private function updateStep($report_id , $step){
      $check = $this->db->select("report_step")->where("id" , $report_id)->get("report")->row();

      if($check->report_step < $step){
        $this->db->where("id" , $report_id)->update("report", ["report_step" => $step]);
      }

    }
    public function removeChecklist($id){
        $this->db->where('report_id' , $id)->delete('report_checklist');
    }

    /*end second step */


    //third step
    public function insertReport(){
        /*
            0 - Not Complete
            1 - Open
            2 - Under Maintenance
            3 - Fixed
        */


        $c = $this->db->where("report_id" , $this->input->post("report_id", TRUE))->where("status" , 3)->count_all_results("report_status");

        if($c > 0){  
          return true;
        }


        if($this->input->post('report_type', TRUE) == 'DEFECT'){

          $arr = array(
            "status" => 1 ,
            "account_id" => $this->input->post('user_id', TRUE),
            "comment" => nl2br($this->input->post('description', TRUE)),
            "report_id" => $this->input->post('report_id', TRUE),
            "created" => time()
            );

          $this->updateStep($this->input->post('report_id', TRUE) , 2);

        }else{

          $arr = array(
            "status" => 0 ,
            "account_id" => $this->input->post('user_id', TRUE),
            "comment" => nl2br($this->input->post('description', TRUE)),
            "report_id" => $this->input->post('report_id', TRUE),
            "created" => time()
            );

          $this->updateStep($this->input->post('report_id', TRUE) , 3);
        }

        $check = $this->db->where("report_id" , $this->input->post("report_id", TRUE))->count_all_results("report_status");

        if($check > 0){

          $this->db->set("comment" , nl2br($this->input->post('description', TRUE)));

          if($this->input->post('report_type', TRUE) != ""){
            $this->db->set("status" , 1);
          }

          $this->db->where("report_id" , $this->input->post("report_id", TRUE));
          $this->db->update("report_status");

          $this->db->where('id' , $this->input->post('report_id', TRUE))->update('report' , [
            'report_type' => $this->input->post('report_type', TRUE) , 
            'updated' => time()
            ]);

        }else{

          $this->db->insert("report_status" , $arr);

          $this->db->where('id' , $this->input->post('report_id', TRUE))->update('report' , [
            'status_id' => $this->db->insert_id() , 
            'report_type' => $this->input->post('report_type'), 
            'updated' => time()
            ]);
        } 
    }
    //end third step


    public function do_upload($report_id){
        $config['upload_path']          = './public/upload/report/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $report_id.'_'.time().'_'.$_FILES['file']['name'];

        $this->load->library('upload', $config);

        if ( $this->upload->do_upload('file')){
          $data = $this->upload->data();

          $this->db->insert('report_images' , ["images" => $data['file_name'] , 'report_id' => $report_id]);

          return json_encode(["status" => 'success']);
        }else{
          return json_encode(['status' => 'failed' , 'message' => $this->upload->display_errors()]);
        }
    }

    public function uploadImage(){
      $id = $this->input->post('report_id');
      $images = $this->input->post("images");

      if($this->input->post()){
        $name = $id.'_'.time().'.JPG';
        $thumb_name = $id.'_'.time().'_thumb.JPG';
        $year = date("Y");
        $month = date("m");
        $folder = "./public/upload/report/".$year."/".$month;

        if (!file_exists($folder)) {
          mkdir($folder, 0777, true);
          create_index_html($folder);
        }

        $path = $folder.'/'.$name;

        $decodedImage = base64_decode("$images");

        file_put_contents($path , $decodedImage);

        $thumb_config['image_library']  = 'gd2';
        $thumb_config['source_image']   = $path;
        $thumb_config['create_thumb']   = TRUE;
        $thumb_config['maintain_ratio'] = TRUE;
            //$thumb_config['width']          = 500;
        $thumb_config['height']         = 500;

        $this->load->library('image_lib', $thumb_config);
        $this->image_lib->initialize($thumb_config); 
        $this->image_lib->resize();

        $thumb_name = $year."/".$month.'/'.$thumb_name;
        $image_name = $year."/".$month.'/'.$name;

        $this->db->insert('report_images' , ["images" => $image_name , "image_thumb" => $thumb_name , 'report_id' => $id]);

        return $this->db->insert_id();
      }
    }

    //4th step
    public function insertJobDate(){
        if($this->input->post()){

            if($this->input->post("btn_type", TRUE, TRUE) == "ARRIVED"){

              $arr = array(
                "jobs_id" => $this->input->post("jobs_id", TRUE),
                "point_destination" => $this->input->post("point_destination", TRUE) ,
                "longitude" => $this->input->post("longitude", TRUE) ,
                "latitude" => $this->input->post("latitude", TRUE),
                "arrived_time" => time() ,
                "created" => time()
              );

              $this->db->insert("jobs_date" , $arr);

              $last_id = $this->db->insert_id();

              return array_merge($arr , array("jobs_date_id" => $last_id , "btn_type" => $this->input->post("btn_type", TRUE) , "arrived_time" => convert_timezone(time(), true , true , false , "d M Y D h:i:s A")));

            }else if($this->input->post("btn_type", TRUE) == "PICK_UP"){

              $this->db->where("jobs_id" , $this->input->post("jobs_id"))->where("point_destination" , $this->input->post("point_destination", TRUE))->update("jobs_date" , array("pick_up_time" => time()));

              $data = $this->input->post();
              
              return array_merge($data , array("pick_up_time" => convert_timezone(time() , true , true , false , "d M Y D h:i:s A")));

            }else{

              $this->db->where("jobs_id" , $this->input->post("jobs_id", TRUE))->where("point_destination" , $this->input->post("point_destination", TRUE))->update("jobs_date" , array("start_mileage" => $this->input->post("start_mileage", TRUE)));

              
              $this->db->where("jobs_id" , $this->input->post("jobs_id", TRUE))->update("jobs" , array("point_destination" => "B"));

              // if($this->input->post("point_destination") == "B"){
              //     $this->updateStep($this->input->post('report_id') , 4);
              // }

              $data = $this->input->post();
              
              return array_merge($data , array("end_date" => convert_timezone(time())));
            }

        }else{

            return false;
        }
    }
    //end 4th step

    public function endReport(){
        /*
          0 - Not Complete
          1 - Open
          2 - Under Maintenance
          3 - Fixed
        */

        $arr = array(
          "end_date" => c_strtotime($this->input->post('end_date')),
          "end_mileage" => $this->input->post('end_mileage')
          );

        $this->db->where('id' , $this->input->post('report_id', TRUE))->update('report' , $arr);

        $this->db->where('report_id' , $this->input->post('report_id', TRUE))->update('report_status' , ['status' => 1]);

        $this->db->where("jobs_id" , $this->input->post("job_id", TRUE))->update("jobs" , [ "time_of_arrival" =>  c_strtotime($this->input->post('end_date', TRUE)) ]);

        $this->updateStep($this->input->post('report_id') , 4);
    }

    //5th step
    public function uploadSignature(){

        $job_id = $this->input->post("id", TRUE);
        $images = $this->input->post("image", TRUE);
        $notes = $this->input->post("notes", TRUE);
        $customer_name = $this->input->post("customer_name", TRUE);
        $signature_type = $this->input->post("signature_type" , TRUE);

        $name = $job_id.'_'.time().'.PNG';
        $year = date("Y");
        $month = date("m");
        $folder = "./public/upload/signature/".$year."/".$month;

        $date = time();

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            create_index_html($folder);
        }

        $path = $folder.'/'.$name;

        $decodedImage = base64_decode("$images");

        file_put_contents($path , $decodedImage);


        if($signature_type == "LOADSITE"){

          $this->db->where("jobs_id" , $job_id)->update("jobs" , array(
            "warehouse_signature" => $year."/".$month.'/'.$name,
            "warehouse_signature_name" => $customer_name,
            "warehouse_notes" => $notes
          ));

        }else{

          $this->db->where("jobs_id" , $job_id)->update("jobs" , array(
            "signature" => $year."/".$month.'/'.$name,
            "delivered_fulldate" => $date ,
            "delivery_notes" => $notes ,
            "signature_name" => $customer_name ,
            "job_complete_time" => time()
          ));

          $this->db->insert("jobs_status" , array(
                  "jobs_id" => $this->input->post("id", TRUE) ,
                  "status_type" => $this->input->post("status_type", TRUE) ,
                  "account_id" => $this->input->post("account_id", TRUE) ,
                  "created" => time() ,
                  "updated" => time()
                  ));

          $this->completeJob($date , $this->db->insert_id());

        }
        

        return $path;
    }

    private function completeJob($date , $status_id){
        $jobs = $this->db->where("jobs_id" , $this->input->post("id", TRUE))->get("jobs")->row();

        $demurrage = 0;
        $total_price = $jobs->total_price;
        $vat = $jobs->vat;

        $date = convert_timezone($date  , true);
        $date = c_strtotime($date);

        $date2 = $jobs->delivery_time + 7200;

        if($date > $date2 ){
          
          $time = ($date - $date2);
          $time = ($time / 60);

          $hours = intval($time/60);
          $minutes = intval(($time - ($hours * 60)) / 30);

          $demurrage = ($hours * 30) + ($minutes * 15);
          
          $total = $jobs->price + $demurrage;
          $vat = 0.20 * $total;

          $total_price = ($demurrage + $jobs->price + $vat);

        }

        if($jobs){
            $arr = array(
              "demurrage" => $demurrage ,
              "total_price" => $total_price ,
              "vat" => $vat,
              "status_id" => $status_id,
              "ready_to_go" => 6
              );

            $this->db->where("jobs_id" , $this->input->post("id", TRUE))->update("jobs" , $arr);

            $this->notification->add_notification($this->input->post("id", TRUE) , $this->input->post("status_type", TRUE));
            
            $this->add_logs($this->input->post("id", TRUE) , $this->input->post("status_type", TRUE));   
        }
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
            "account_id" => $this->input->post("account_id") ,
            "created" => time()
            );

        $this->db->insert("jobs_logs" , $arr);
    }

    //end 5th step

    public function removeReport(){
      $this->db->where('id' , $this->input->post('id'))->delete('report');
      $this->db->where('report_id' , $this->input->post('id'))->delete('report_checklist');
      $this->db->where('report_id' , $this->input->post('id'))->delete('report_images');
      $this->db->where('report_id' , $this->input->post('id'))->delete('report_status');
    }

    public function getChecklistReportList($prevNext = false , $id = false){
      if($prevNext == "NEXT"){
        $this->db->select_min("r.id");
      }else if($prevNext == "PREV"){
        $this->db->select_max("r.id");
      }else{
        $this->db->select('r.id , a.name , a.surname , trailer_number , r.checklist_type , r.report_type, vehicle_registration_number , start_mileage , end_mileage , start_date , end_date , r.created , rs.status , rs.comment , a2.name as fixed_by_name , a2.surname as fixed_by_surname');
      }
      
      $this->db->join('report_status rs', 'rs.report_status_id = r.status_id' , 'left');
      $this->db->join('accounts a2', 'a2.id = rs.account_id');
      $this->db->join('accounts a', 'a.id = r.user_id');

      /* SEARCH AREA */
      if($this->input->get('status') == 'all' || !$this->input->get('status', TRUE)){
          $this->db->where('rs.status != ' , 0 );
      }else{
          //$this->db->where('rs.status ' , $this->input->get('status', TRUE) );
      }

      if($this->input->get('trailer_number')){
          $this->db->where('r.trailer_number' , $this->input->get('trailer_number', TRUE));
      }

      if($this->input->get('vehicle')){
          $this->db->where('r.vehicle_registration_number' , $this->input->get('vehicle', TRUE));
      }

      if($this->input->get('name')){
          $this->db->where('r.user_id' , $this->input->get('name', TRUE));
      }

      if($this->input->get('report_id')){
          $this->db->where('r.id' , $this->input->get('report_id', TRUE));
      }

      if($this->input->get('date_from', TRUE) AND $this->input->get('date_to', TRUE)){
          $from = strtotime($this->input->get('date_from', TRUE).' 00:00 ');
          $to = strtotime($this->input->get('date_to', TRUE).' 23:59 ');

          $this->db->where('r.created >=' , $from);
          $this->db->where('r.created <=' , $to);
      }else{
        $this->db->limit(200);
      }
      /* END SEARCH AREA */

      $this->db->where('r.report_type !=' , '1');
      $this->db->where('a.company_id ' , $this->session->userdata('company_id'));
      
      if($prevNext == "NEXT"){
         $result = $this->db->where("r.id > " , $id)->get('report r')->row_array();
      }else if($prevNext == "PREV"){
         $result = $this->db->where("r.id < " , $id)->get('report r')->row_array();
      }else{

          $result = $this->db->order_by('r.created' , 'DESC')->get('report r')->result();

          foreach($result as $key => $row){
            $result[$key]->start_date = convert_timezone($row->start_date);
            $result[$key]->end_date = convert_timezone($row->end_date);
            $result[$key]->created = convert_timezone($row->created , true);
            $result[$key]->status = ($row->report_type == "SERVICEABLE") ? report_type(4) : report_type($row->status);
          }

        
      }

      return $result;
    }

    public function getDefectReportList(){
      $this->db->select('r.id , a.name , a.surname , trailer_number , r.checklist_type , vehicle_registration_number , start_mileage , end_mileage , start_date , end_date , r.created , rs.status , rs.comment , a2.name as fixed_by_name , a2.surname as fixed_by_surname , r.report_type');
      $this->db->join('report_status rs', 'rs.report_status_id = r.status_id' , 'left');
      $this->db->join('accounts a2', 'a2.id = rs.account_id');
      $this->db->join('accounts a', 'a.id = r.user_id');

      /* SEARCH AREA */
      if($this->input->get('status') == 'all' || !$this->input->get('status')){
          $this->db->where('rs.status != ' , 0 );
      }else{
          $this->db->where('rs.status ' , $this->input->get('status') );
      }

      if($this->input->get('trailer_number')){
          $this->db->where('r.trailer_number' , $this->input->get('trailer_number'));
      }

      if($this->input->get('vehicle')){
          $this->db->where('r.vehicle_registration_number' , $this->input->get('vehicle'));
      }

      if($this->input->get('name')){
          $this->db->where('r.user_id' , $this->input->get('name'));
      }

      if($this->input->get('report_id')){
          $this->db->where('r.id' , $this->input->get('report_id'));
      }

      if($this->input->get('date_from') AND $this->input->get('date_to')){
          $from = strtotime($this->input->get('date_from').' 00:00 ');
          $to = strtotime($this->input->get('date_to').' 23:59 ');

          $this->db->where('r.created >=' , $from);
          $this->db->where('r.created <=' , $to);
      }else{
        $this->db->limit(200);
      }
      /* END SEARCH AREA */

      $this->db->where('r.report_type ' , 'DEFECT');
      $this->db->where('a.company_id ' , $this->session->userdata('company_id'));
      $result = $this->db->order_by('r.created' , 'DESC')->get('report r')->result();

      foreach($result as $key => $row){
        $result[$key]->start_date = convert_timezone($row->start_date);
        $checklist = $this->db->select('checklist_index')->where('report_id' , $row->id)->where('value' , 'defect')->get('report_checklist')->result_array();

        if($row->checklist_type == "VEHICLE REPORT"){
          $result[$key]->checklist =  checklist_list($checklist , true);
        }else if($row->checklist_type == "MOFFET REPORT"){
          $result[$key]->checklist =  checklist_moffet($checklist , true);
        }else{
          $result[$key]->checklist =  checklist_list($checklist , true , true);
        }
        
        $result[$key]->created = convert_timezone($row->created , true);
        $result[$key]->status = ($row->report_type == "SERVICEABLE") ? report_type(4) : report_type($row->status);
      }


      return $result;
    }

    public function getDailyReportList(){
        $this->db->select("tl.* , CONCAT(a.name , ' ' , a.surname) as name ");
        $this->db->join("accounts a" , "a.id = tl.account_id");


            if($this->input->get("date_from") AND $this->input->get("date_to")){
                $from = strtotime($this->input->get("date_from").' midnight');
                $to = strtotime($this->input->get("date_to").' 23:59');

                $this->db->where("tl.created >=" , $from);
                $this->db->where("tl.created <=" , $to);

            }else if($this->input->get("date_from")){
                $from = strtotime($this->input->get("date_from").' midnight');
                $this->db->where("tl.created >=" , $from);
            }else{
              $this->db->limit(200);
            }

            if($this->input->get("name")){
                $this->db->where("account_id" , $this->input->get("name"));
            }
        

        $result = $this->db->order_by("created" , "DESC")->get("timetracker_login tl")->result();

        foreach($result as $key => $row){
            $result[$key]->time_in = "<nobr>".convert_timezone($row->time_in , true)."</nobr>";
            $result[$key]->time_out = "<nobr>".convert_timezone($row->time_out , true)."</nobr>";
            $result[$key]->end_mileage = ifNA($row->end_mileage);
            $result[$key]->checklist_report = $this->getDailyReportTrailer($row);
            $result[$key]->job_report = $this->getDailyReportJob($row);
        }

       return $result;
    }

    private function getDailyReportJob($row){
      $from = strtotime(date('Y-m-d', $row->created).' 00:00:00');
      $to = strtotime(date('Y-m-d', $row->created).' 23:59:59');

      $this->db->select("j.jobs_id , j.job_number , j.job_notes , j.signature_name , j.signature , j.warehouse_signature , j.warehouse_signature_name , j.begin_driving_time , j.job_complete_time , j.delivery_notes");
      $this->db->select("j.job_name , j.load_site , j.address as destination , j.zip_code_load_site , j.zip_code , j.delivery_notes");
      $this->db->select("jd.arrived_time , jd.pick_up_time , jd.point_destination , jd.start_mileage");
      $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id" );
      $this->db->join("jobs_date jd" , "jd.jobs_id = j.jobs_id");
      $this->db->join("vehicle_information v" , "v.id = j.vehicle_id" , "LEFT");
      $this->db->join("trailer_information t" , "t.id = j.trailer_id" , "LEFT");
      $this->db->where("jd.created >=" , $from);
      $this->db->where("jd.created <=" , $to);
      $this->db->where("j.driver_id" , $row->account_id);
      $result = $this->db->order_by("jp.created" , "DESC")->get("jobs j")->result();

      $a = array();

      foreach($result as $key => $row){
        $result[$key]->arrived_time = convert_timezone($row->arrived_time , true);
        $result[$key]->pick_up_time = convert_timezone($row->pick_up_time , true);
        $result[$key]->begin_driving_time = convert_timezone($row->begin_driving_time , true);
        $result[$key]->job_complete_time = convert_timezone($row->job_complete_time , true);

        $result[$key]->load_site = ($row->load_site) ? $row->load_site.' , '.$row->zip_code_load_site : "NA";
        $result[$key]->destination = ($row->destination) ? $row->destination.' , '.$row->zip_code : "NA";

        if($row->signature){
           $result[$key]->signature = full_path_image($row->signature , "signature");
           $result[$key]->signature = $result[$key]->signature['path'];
         }

         if($row->warehouse_signature){
           $result[$key]->warehouse_signature = full_path_image($row->warehouse_signature , "signature");
           $result[$key]->warehouse_signature = $result[$key]->warehouse_signature['path'];
         }

         $a[$row->jobs_id]["jobs_id"] = ifNA($result[$key]->jobs_id);
         $a[$row->jobs_id]["job_number"] = ifNA($result[$key]->job_number);
         $a[$row->jobs_id]["job_notes"] = ifNA($result[$key]->job_notes);
         $a[$row->jobs_id]["delivery_notes"] = ifNA($result[$key]->delivery_notes);
         $a[$row->jobs_id]["signature_name"] = ifNA($result[$key]->signature_name);
         $a[$row->jobs_id]["signature"] = ifNA($result[$key]->signature);
         $a[$row->jobs_id]["warehouse_signature"] = ifNA($result[$key]->warehouse_signature);
         $a[$row->jobs_id]["warehouse_signature_name"] = ifNA($result[$key]->warehouse_signature_name);
         $a[$row->jobs_id]["begin_driving_time"] = "<nobr>".ifNA($result[$key]->begin_driving_time)."</nobr>";
         $a[$row->jobs_id]["job_complete_time"] = "<nobr>".ifNA($result[$key]->job_complete_time)."</nobr>";
         $a[$row->jobs_id]["job_name"] = ifNA($result[$key]->job_name);
         $a[$row->jobs_id]["load_site"] = "<nobr>".$result[$key]->load_site."</nobr>";
         $a[$row->jobs_id]["destination"] = "<nobr>".$result[$key]->destination."</nobr>";

         if($row->point_destination == "A"){
           
            $a[$row->jobs_id]["a_arrived_time"] = "<nobr>".ifNA($result[$key]->arrived_time)."</nobr>";
            $a[$row->jobs_id]["a_pick_up_time"] = "<nobr>".ifNA($result[$key]->pick_up_time)."</nobr>";
            $a[$row->jobs_id]["a_start_mileage"] = "<nobr>".ifNA($result[$key]->start_mileage)."</nobr>";

         }else{

            $a[$row->jobs_id]["a_arrived_time"] = "NA";
            $a[$row->jobs_id]["a_pick_up_time"] = "NA";
            $a[$row->jobs_id]["a_start_mileage"] = "NA";

            
         }

         if($row->point_destination == "B"){
            $a[$row->jobs_id]["b_arrived_time"] = "<nobr>".ifNA($result[$key]->arrived_time)."</nobr>";
            $a[$row->jobs_id]["b_pick_up_time"] = "<nobr>".ifNA($result[$key]->pick_up_time)."</nobr>";
            $a[$row->jobs_id]["b_start_mileage"] = "<nobr>".ifNA($result[$key]->start_mileage)."</nobr>";
         }else{
            $a[$row->jobs_id]["b_arrived_time"] = "NA";
            $a[$row->jobs_id]["b_pick_up_time"] = "NA";
            $a[$row->jobs_id]["b_start_mileage"] = "NA";
         }
        
      }

      $b = array();

      foreach($a as $key => $row){
          $b["jobs_id"][] = $row['jobs_id'];
          $b["job_number"][] = $row['job_number'];
          $b["job_notes"][] = $row['job_notes'];
          $b["delivery_notes"][] = $row['delivery_notes'];
          $b["signature_name"][] = $row['signature_name'];
          $b["signature"][] = '<img src="'.$row['signature'].'" style="width:100px;">';
          $b["warehouse_signature"][] = '<img src="'.$row['warehouse_signature'].'" style="width:100px;">';
          $b["warehouse_signature_name"][] = $row['warehouse_signature_name'];
          $b["begin_driving_time"][] = $row['begin_driving_time'];
          $b["job_complete_time"][] = $row['job_complete_time'];
          $b["job_name"][] = $row['job_name'];
          $b["load_site"][] = $row['load_site'];
          $b["destination"][] = $row['destination'];
          $b["a_arrived_time"][] = $row['a_arrived_time'];
          $b["a_pick_up_time"][] = $row['a_pick_up_time'];
          $b["a_start_mileage"][] = $row['a_start_mileage'];
          $b["b_arrived_time"][] = $row['b_arrived_time'];
          $b["b_pick_up_time"][] = $row['b_pick_up_time'];
          $b["b_start_mileage"][] = $row['b_start_mileage'];
      }

      if($b){
        $c["jobs_id"] = implode("<br>", $b["jobs_id"]);
        $c["job_number"] = implode("<br>", $b["job_number"]);
        $c["job_notes"] = implode("<br>", $b["job_notes"]);
        $c["delivery_notes"] = implode("<br>", $b["delivery_notes"]);
        $c["signature_name"] = implode("<br>", $b["signature_name"]);
        $c["signature"] = implode("<br>", $b["signature"]);
        $c["warehouse_signature"] = implode("<br>", $b["warehouse_signature"]);
        $c["warehouse_signature_name"] = implode("<br>", $b["warehouse_signature_name"]);
        $c["begin_driving_time"] = implode("<br>", $b["begin_driving_time"]);
        $c["job_complete_time"] = implode("<br>", $b["job_complete_time"]);
        $c["job_name"] = implode("<br>", $b["job_name"]);
        $c["load_site"] = implode("<br>", $b["load_site"]);
        $c["destination"] = implode("<br>", $b["destination"]);
        $c["a_arrived_time"] = implode("<br>", $b["a_arrived_time"]);
        $c["a_pick_up_time"] = implode("<br>", $b["a_pick_up_time"]);
        $c["a_start_mileage"] = implode("<br>", $b["a_start_mileage"]);
        $c["b_arrived_time"] = implode("<br>", $b["b_arrived_time"]);
        $c["b_pick_up_time"] = implode("<br>", $b["b_pick_up_time"]);
        $c["b_start_mileage"] = implode("<br>", $b["b_start_mileage"]);
      }else{
        $c["jobs_id"] = "NA";
        $c["job_number"] = "NA";
        $c["job_notes"] = "NA";
        $c["delivery_notes"] = "NA";
        $c["signature_name"] = "NA";
        $c["signature"] = "NA";
        $c["warehouse_signature"] = "NA";
        $c["warehouse_signature_name"] = "NA";
        $c["begin_driving_time"] = "NA";
        $c["job_complete_time"] = "NA";
        $c["job_name"] = "NA";
        $c["load_site"] = "NA";
        $c["destination"] = "NA";
        $c["a_arrived_time"] = "NA";
        $c["a_pick_up_time"] = "NA";
        $c["a_start_mileage"] = "NA";
        $c["b_arrived_time"] = "NA";
        $c["b_pick_up_time"] = "NA";
        $c["b_start_mileage"] = "NA";
      }

      

      return $c;
    }



    private function getDailyReportTrailer($row){
      $from = strtotime(date('Y-m-d', $row->created).' 00:00:00');
      $to = strtotime(date('Y-m-d', $row->created).' 23:59:59');

      $this->db->where("r.created >= " , $from);
      $this->db->where("r.created <= " , $to);
      $this->db->where("r.user_id" , $row->account_id);

      if($this->uri->segment(3) == "daily"){
          if($this->input->get("vehicle")){
              $this->db->where("r.vehicle_registration_number" , $this->input->get("vehicle"));
          }

          if($this->input->get("trailer_number")){
              $this->db->where("r.trailer_number" , $this->input->get("trailer_number"));
          }
      }

      $result = $this->db->get("report r")->result();

      $a = array();

      foreach($result as $key => $row){
        $result[$key]->safety_check = ($result[$key]->report_type == 1) ? "NO" : "YES";
        $result[$key]->start_date = convert_timezone($row->start_date , true);
        $result[$key]->end_date = convert_timezone($row->end_date , true);
        $result[$key]->start_mileage = ($row->start_mileage == 0) ? "NA" : $row->start_mileage;

        $a["start_mileage"][] = ifNA($result[$key]->start_mileage);
        $a["end_mileage"][] = ifNA($result[$key]->end_mileage);
        $a["vehicle_registration_number"][] = ifNA($result[$key]->vehicle_registration_number);
        $a["trailer_number"][] = ifNA($result[$key]->trailer_number);
        $a["start_date"][] = "<nobr>".ifNA($result[$key]->start_date)."</nobr>";
        $a["end_date"][] = ifNA($result[$key]->end_date);
        $a["status_id"][] = ifNA($result[$key]->status_id);
        $a["report_type"][] = ifNA($result[$key]->report_type);
        $a["checklist_type"][] = "<nobr>".ifNA($result[$key]->checklist_type)."</nobr>";
        $a["safety_check"][] = ($result[$key]->safety_check == "YES") ? '<span class="label label-success">Success</span>' : '<span class="label label-danger">Failed</span>' ;
      }

      if(!$a){
        $b["start_mileage"] = "NA";
        $b["end_mileage"] = "NA";
        $b["vehicle_registration_number"] = "NA";
        $b["trailer_number"] = "NA";
        $b["start_date"] = "NA";
        $b["end_date"] = "NA";
        $b["status_id"] = "NA";
        $b["report_type"] = "NA";
        $b["checklist_type"] = "NA";
        $b["safety_check"] = '<span class="label label-danger">Failed</span>';

      }else{
        $b["start_mileage"] = implode("<br>", $a["start_mileage"]);
        $b["end_mileage"] = implode("<br>", $a["end_mileage"]);
        $b["vehicle_registration_number"] = implode("<br>", $a["vehicle_registration_number"]);
        $b["trailer_number"] = implode("<br>", $a["trailer_number"]);
        $b["start_date"] = implode("<br>", $a["start_date"]);
        $b["end_date"] = implode("<br>", $a["end_date"]);
        $b["status_id"] = implode("<br>", $a["status_id"]);
        $b["report_type"] = implode("<br>", $a["report_type"]);
        $b["checklist_type"] = implode("<br>", $a["checklist_type"]);
        $b["safety_check"] = implode("<br>", $a["safety_check"]);
      }
      
      return $b;
    }



    public function getReportById($id , $login = true){
      $this->db->select('r.id , name , surname , r.vehicle_registration_number , start_mileage , end_mileage , start_date , end_date , r.created , rs.status , rs.comment  , r.job_id , r.report_step , r.report_type , r.trailer_number as trailer_number , r.checklist_type , r.advisory');
      $this->db->select("j.jobs_id , j.job_name , j.loading_time , j.delivery_time , r.report_change , j.delivered_fulldate , j.time_of_arrival , j.job_notes , j.point_destination , j.checklist_done , j.load_site");
      $this->db->join('report_status rs', 'rs.report_status_id = r.status_id' , 'left');
      $this->db->join('accounts a', 'a.id = r.user_id');
      $this->db->join("jobs j" , "j.jobs_id = r.job_id" , "LEFT");
      $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id" , "LEFT");
      
      if($login){
        $result = $this->db->where('r.id' , $id)->where('a.company_id' , $this->session->userdata('company_id'))->get('report r')->row();
      }else{
        $result = $this->db->where('r.id' , $id)->get('report r')->row();
      }

      if(!$result){
        return ["status" => 0 , "Message" => "Invalid Report ID"];
      }

      $checklist = $this->db->select('checklist_index')->where('report_id' , $result->id)->where('value' , 'defect')->get('report_checklist')->result_array();

      $x = false;
      $limit = 28;

      if($result->checklist_type == "TRAILER REPORT"){
        $x = true;
        $limit = 26;
      }else if($result->checklist_type == "MOFFET REPORT"){
        $limit = 33;
      }
      
      $result->title = ucfirst( strtolower($result->checklist_type)).' Checklist';

      

      if($result->checklist_type == "MOFFET REPORT"){
        $result->checklist = checklist_moffet($checklist , false);
      }else{
        $result->checklist = checklist_list($checklist , false , $x);
      }
      
      $all_checklist = $this->db->select("checklist_index , checklist_timer , value")->where('report_id' , $result->id)->order_by("checklist_index" , "asc")->limit($limit)->get('report_checklist')->result_array();

      foreach($all_checklist as $k => $r){
        if($result->checklist_type == "MOFFET REPORT"){
            $all_checklist[$k]['checklist_index'] = ($login) ? checklist_array_moffet($r['checklist_index'])  : $r['checklist_index'] ;
        }else{
          if($x){
            $all_checklist[$k]['checklist_index'] = ($login) ? checklist_array_trailer($r['checklist_index'])  : $r['checklist_index'] ;
          }else{
            $all_checklist[$k]['checklist_index'] = ($login) ? checklist_array($r['checklist_index'])  : $r['checklist_index'] ;
          }
        }
        $all_checklist[$k]['checklist_timer'] = date('M d Y h:i:s A' , $r['checklist_timer']);
      }

      $result->all_checklist = $all_checklist;

      $images = $this->db->where('report_id' , $result->id)->get('report_images')->result();

      foreach($images as $key => $row){

        if($row->images){

            $image = full_path_image($row->images , 'report');
            $images[$key]->images = $image['path'];

            if(!file_exists($image['absolute_path'])){
              $images[$key]->images = $this->config->base_url('/public/images/image-not-found.png');
            }

        }else{
           $images[$key]->images = $this->config->base_url('/public/images/image-not-found.png');
        }

        if($row->image_thumb){

            $image = full_path_image($row->image_thumb , 'report');
            $images[$key]->image_thumb = $image['path'];

            if(!file_exists($image['absolute_path'])){
              $images[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
            }

        }else{
           $images[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
        }

      }
      $this->db->select("rs.status , rs.created , CONCAT(a.name , ' ' , a.surname) as name , rs.comment");
      $this->db->join("accounts a" , "a.id = rs.account_id" , "LEFT");
      $status = $this->db->where('report_id' , $id)->order_by('created' , 'desc')->get('report_status rs')->result();

      foreach($status as $key => $row){
        $status[$key]->status = ($login) ? report_type($row->status) : report_type($row->status , true);
        $status[$key]->created = convert_timezone($row->created , true);
      }

      $result->status_list = $status;
      $result->images =  $images;
      $result->status = ($login) ? report_type($result->status) : report_type($result->status , true);
      $result->created = convert_timezone($result->created , true , false , false , "d M Y D h:i:s A");
      $result->start_date = convert_timezone($result->start_date , true , false , false , "d M Y D h:i:s A" );
      $result->end_date = convert_timezone($result->end_date , true , false , false , "d M Y D h:i:s A");

      $result->loading_time = convert_timezone($result->loading_time , true , false , false , "d M Y D h:i:s A");
      $result->delivery_time = convert_timezone($result->delivery_time , true , false , false , "d M Y D h:i:s A");
      $result->time_of_arrival = convert_timezone($result->time_of_arrival , true , false , false , "d M Y D h:i:s A");
      $result->delivered_fulldate = convert_timezone($result->delivered_fulldate , true , false , false , "d M Y D h:i:s A");
      $result->comment = ($result->comment) ? $result->comment : "NA";
      $result->checklist_done = ($result->checklist_done) ? true : false;

      $bool = true;
      $A = $this->jobDate($result->job_id , "A");
      $B = $this->jobDate($result->job_id , "B");

      if($A->arrived_time == "NA"){
        $bool = false;
      }else if($B->arrived_time == "NA"){
        $bool = false;
      }else{
        $bool = true;
      }

      $result->jobs_date = array(
          "A" => $A ,
          "B" => $B
        );

      $result->jobs_complete = $bool;
      
      return $result;
    }

    public function getPrevNextButton($current_id){

    }


    public function getCountReport($id){
      $defect = $this->db->where('user_id' , $id)->where('report_type' , 'DEFECT')->count_all_results("report");
      $service = $this->db->where('user_id' , $id)->where('report_type' , 'SERVICEABLE')->count_all_results("report");

      return array(
        "defect" => $defect ,
        "service" => $service ,
        "total" => (intval($defect) + intval($service))
        );
    }

    public function getVehicleReport($id){
      $skip = ($this->input->post("skip", TRUE)) ? $this->input->post("skip", TRUE) : 0;

      $this->db->select('r.id , name , surname , trailer_number , vehicle_registration_number , start_mileage , end_mileage , start_date , end_date , r.created , rs.status , rs.comment , r.report_type');
      $this->db->join('report_status rs', 'rs.report_status_id = r.status_id' , 'left');
      $this->db->join('accounts a', 'a.id = r.user_id');
      
      $result = $this->db->where('vehicle_registration_number' , $id)->where('a.company_id' , $this->session->userdata('company_id'))->where("report_type !=" , "")->order_by('created' , 'desc')->limit(10 , $skip)->get('report r')->result();

      foreach($result as $key => $res){
        $checklist = $this->db->select('checklist_index')->where('report_id' , $res->id)->where('value' , 'defect')->get('report_checklist')->result_array();
        $result[$key]->checklist = checklist_list($checklist);
        $all_checklist = $this->db->select("checklist_index , checklist_timer , value")->where('report_id' , $res->id)->order_by("checklist_index" , "asc")->get('report_checklist')->result_array();

        foreach($all_checklist as $k => $r){
          $all_checklist[$k]['checklist_index'] = checklist_array($r['checklist_index']);
          $all_checklist[$k]['checklist_timer'] = date('M d Y h:i:s A' , $r['checklist_timer']);
        }

        $result[$key]->all_checklist = $all_checklist;
        $images = $this->db->where('report_id' , $res->id)->get('report_images')->result();

        foreach($images as $k => $row){

          if($row->images){

              $image = full_path_image($row->images , 'report');
              $images[$k]->images = $image['path'];

              if(!file_exists($image['absolute_path'])){
                $images[$k]->images = $this->config->base_url('/public/images/image-not-found.png');
              }

          }else{
             $images[$k]->images = $this->config->base_url('/public/images/image-not-found.png');
          }

          if($row->image_thumb){
              $image = full_path_image($row->image_thumb , 'report');
              $images[$k]->image_thumb = $image['path'];

              if(!file_exists($image['absolute_path'])){
                $images[$k]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
              }
          }else{
            $images[$k]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
          }

        }

        $status = $this->db->where('report_id' ,  $res->id)->order_by('created' , 'desc')->get('report_status')->result();

        foreach($status as $k => $row){
          $status[$k]->status = report_type($row->status);
          $status[$k]->created = convert_timezone($row->created , true);
        }

        $result[$key]->mileage = (floatval($res->end_mileage) - floatval($res->start_mileage));
        $result[$key]->images = $images;
        $result[$key]->status = $status;
        $result[$key]->created = convert_timezone($res->created , true);
      }

      return $result;
    }

    public function updateReport(){

      $arr = array(
        "report_id" => $this->input->post("id", TRUE),
        "status" => $this->input->post("status", TRUE),
        "account_id" => $this->session->userdata('id'),
        "comment" => $this->input->post("comment", TRUE),
        "created" => time()
        );


      $this->db->insert('report_status' , $arr);

      $last_id = $this->db->insert_id();

      $this->db->where("id" , $this->input->post("id", TRUE))->update('report' , ["status_id" => $last_id]);

      if($this->input->post("status", TRUE) == 3){

        $this->db->select("j.driver_id , rj.job_id");
        $this->db->join("jobs j" , "j.jobs_id = rj.job_id");
        $check = $this->db->where("report_id" , $this->input->post("id", TRUE))->get("report_jobs rj")->result();

        foreach($check as $job){
            $this->db->where("jobs_id" , $job->job_id)->update("jobs" , ["ready_to_go" => 3 ]);
        }

        if($check){
          send_notification($check[0]->driver_id , "fixed_truck" , 0);
        }
      }

      return $this->input->post("id", TRUE);

    }

    public function getCountReportByVehicle($id){
      $this->db->join('accounts a', 'a.id = r.user_id');
      $defect = $this->db->where('vehicle_registration_number' , $id)->where('a.company_id' , $this->session->userdata('company_id'))->where('report_type' , 'DEFECT')->count_all_results("report r");

      $this->db->join('accounts a', 'a.id = r.user_id');
      $service = $this->db->where('vehicle_registration_number' , $id)->where('a.company_id' , $this->session->userdata('company_id'))->where('report_type' , 'SERVICEABLE')->count_all_results("report r");

      return array(
        "defect" => $defect ,
        "service" => $service ,
        "total" => (intval($defect) + intval($service))
        );
    }

    public function getMyReport($myId = false , $sort_by = "r.created"){

      if(!$myId){
        $myId = $this->input->post("id", TRUE);
      }

      $this->db->select('r.id , ti.trailer_number , vi.vehicle_number , r.created , j.jobs_id , r.report_type , j.load_site , j.zip_code_load_site , j.address , j.zip_code , j.job_name , j.job_number , j.loading_time , j.delivery_time ');
      
      $this->db->join("report_jobs rj" , "rj.report_id = r.id");
      $this->db->join("jobs j" , 'j.jobs_id = rj.job_id');
      $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id");
      $this->db->join('trailer_information ti', 'ti.id = j.trailer_id' , 'LEFT');
      $this->db->join('vehicle_information vi', 'vi.id = j.vehicle_id');
      $this->db->where('j.driver_id ' , $myId);
      $result = $this->db->order_by($sort_by , 'DESC')->get('report r')->result();

      foreach($result as $key => $row){

        $result[$key]->job_name = $row->job_name.' #'.$row->job_number;
        $result[$key]->created = convert_timezone($row->created , true);
        $result[$key]->loading_time = convert_timezone($row->loading_time , true , false);
        $result[$key]->delivery_time = convert_timezone($row->delivery_time , true , false);
        $result[$key]->load_site = $row->load_site.' , '.$row->zip_code_load_site;
        $result[$key]->address = $row->address . ' , '.$row->zip_code;
        $result[$key]->jobs_date = array(
          "A" => $this->jobDate($row->jobs_id , "A") ,
          "B" => $this->jobDate($row->jobs_id , "B")
        );  

        $checklist = $this->db->select('checklist_index')->where('report_id' , $row->id)->where('value' , 'defect')->get('report_checklist')->result_array();
        $result[$key]->checklist = checklist_list($checklist);
        
      }

      return $result;
    }

    public function getMyReportById($id){

      $this->db->select('r.id , user_id ,r.report_type , trailer_number , vehicle_registration_number , start_mileage , end_mileage , start_date , end_date , r.created ,rs.comment , r.checklist_type');
      $this->db->join('report_status rs', 'rs.report_status_id = r.status_id' , 'left');

      $result = $this->db->where('r.id' , $id)->get('report r')->row();

      if(!$result){
        return (object)["status" => false , "Message" => "Invalid Report ID"];
      }
      $result->status = true;
      $result->comment = ($result->comment) ? $result->comment : "NA";


      if($result->checklist_type == "MOFFET REPORT"){
        $limit = 33;
      }else if($result->checklist_type == "VEHICLE REPORT"){
        $limit = 28;
      }else{
        $limit = 26;
      }

      $result->all_checklist = $this->db->where('report_id' , $result->id)->limit($limit)->order_by("checklist_index")->get('report_checklist')->result_array();

      $images = $this->db->where('report_id' , $result->id)->get('report_images')->result();

      foreach($images as $key => $row){

        if($row->image_thumb){

            $image = full_path_image($row->image_thumb , 'report');
            $images[$key]->image_thumb = $image['path'];

            if(!file_exists($image['absolute_path'])){
              $images[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
            }

        }else{
           $images[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
        }

      }

      $result->images =  $images;
      $result->created = convert_timezone($result->created , true);
      $result->start_date = convert_timezone($result->start_date , true);
      $result->end_date = convert_timezone($result->end_date , true);

      return $result;
    }

    public function removeImage($id){
        $result = $this->db->where("report_images_id" , $id)->get("report_images")->row();

        $path = FCPATH.'public/upload/report/';

        if($result){
          $images = $path.$result->images;
          $images_thumb = $path.$result->images_thumb;

          if(file_exists($images)){
            unlink($images);  
          }
          if(file_exists($images_thumb)){
            unlink($images_thumb);  
          }

          $this->db->where("report_images_id" , $id)->delete("report_images");
        }
    }

    public function getDriverJobs($driver_id){
        $this->db->select("j.jobs_id , j.job_number , CONCAT(j.job_name , ' #' , j.job_number) as job_name , j.address , j.job_notes , j.driver_notes , j.loading_time , j.delivery_time , j.show_to_driver , v.vehicle_number , t.trailer_number, j.time_of_arrival , jp.telephone , js.status_type as status , j.load_site , j.zip_code , j.zip_code_load_site , j.point_destination , j.vehicle_id , j.trailer_id , j.driver_id , j.driver_status");
        $this->db->join("jobs j" , "j.parent_id = jp.job_parent_id");
        $this->db->join("vehicle_information v" , "v.id = j.vehicle_id" , "LEFT");
        $this->db->join("trailer_information t" , "t.id = j.trailer_id" , "LEFT");
        $this->db->join("jobs_status js" , "js.jobs_status_id = j.status_id");
        $this->db->where("js.status_type" , "allocated");
        $this->db->where("j.driver_id" , $driver_id);
        $this->db->where("j.driver_status !=" , 2);
        $this->db->order_by("delivery_time" , "ASC");

        $result = $this->db->get("jobs_parent jp")->result();

        foreach($result as $key => $row){
          $result[$key]->loading_time = convert_timezone($row->loading_time , true , false, false , "d M Y D h:i:s A");
          $result[$key]->delivery_time = convert_timezone($row->delivery_time , true , false, false , "d M Y D h:i:s A");
          $result[$key]->time_of_arrival = convert_timezone($row->time_of_arrival , true , false, false , "d M Y D h:i:s A");
          $result[$key]->trailer_number = ($row->trailer_number) ? $row->trailer_number : "NA";
          $result[$key]->address = nl2br($row->address) . " " . $row->zip_code;
          $result[$key]->load_site = $row->load_site . " " . $row->zip_code_load_site;
          $result[$key]->zip_code_load_site = ($row->zip_code_load_site) ? nl2br($row->zip_code_load_site) : "NA";
          $result[$key]->zip_code = ($row->zip_code) ? $row->zip_code : "NA";
          $result[$key]->jobs_date = array(
                "A" => $this->jobDate($row->jobs_id , "A") ,
                "B" => $this->jobDate($row->jobs_id , "B")
                );
          $result[$key]->report_status = $this->checkIfChecklistIsDone($row);
          $result[$key]->report_data =  $this->getMyReportById($result[$key]->report_status->report_id);
        }

        return $result;
    }

    private function checkIfChecklistIsDone($data){

        $check = $this->db->where("job_id" , $data->jobs_id)->get("report_jobs")->row();

        if($check){
            $this->db->select("id as report_id , status , report_type  , checklist_type");
            $this->db->join("report_status rs" , "rs.report_status_id = r.status_id" , 'LEFT');
            $this->db->where("r.id" , $check->report_id);

            $result = $this->db->get("report r")->row();
        }else{

            $from = strtotime("today 00:00:00");
            $to = strtotime("today 23:59:59");

            $this->db->select("id as report_id , status , report_type , checklist_type");
            $this->db->join("report_status rs" , "rs.report_status_id = r.status_id");
            $this->db->where("r.user_id" , $data->driver_id);
            $this->db->where("r.vehicle_registration_number" , $data->vehicle_number);
            $this->db->where("r.trailer_number" , $data->trailer_number);
            $this->db->where("r.created >=" , $from);
            $this->db->where("r.created <=" , $to);

            $result = $this->db->get("report r")->row();

            if($result){
                $this->insertReportJobs($result->report_id , $data->jobs_id);
            }else{

              $result = new stdClass();
              $result->report_id = 0;
              $result->status = 0;
              $result->report_type = 0;
              $result->checklist_type = "VEHICLE REPORT";
            }
        }
        return $result;
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

    public function getSingleJobs($job_id){
        $this->db->select("j.jobs_id , CONCAT(j.job_name , ' #' , j.job_number) as job_name  , j.address , j.job_notes , j.driver_notes , j.loading_time , j.delivery_time , j.delivered_fulldate , j.show_to_driver , v.vehicle_number , t.trailer_number , j.time_of_arrival , j.telephone , j.load_site , j.driver_id");
        $this->db->join("jobs j" , "j.parent_id = jp.job_parent_id");
        $this->db->join("vehicle_information v" , "v.id = j.vehicle_id" , "LEFT");
        $this->db->join("trailer_information t" , "t.id = j.trailer_id" , "LEFT");
        $this->db->where("j.jobs_id" , $job_id);
        $result = $this->db->get("jobs_parent jp")->row();

        $result->loading_time = convert_timezone($result->loading_time , true , false, false , "d M Y D h:i:s A");
        $result->delivery_time = convert_timezone($result->delivery_time , true , false, false , "d M Y D h:i:s A");
        $result->delivered_fulldate = convert_timezone($result->delivered_fulldate , true , false, false , "d M Y D h:i:s A");
        $result->time_of_arrival = convert_timezone($result->time_of_arrival , true , false, false , "d M Y D h:i:s A");

        $status = $this->db->select("status_type")->where("jobs_id" , $result->jobs_id)->order_by("created" , "DESC")->get("jobs_status")->row();
        $result->status = $status->status_type;
        $result->trailer_number = ($result->trailer_number) ? $result->trailer_number : "NA";
        $result->report_status = "NA";
        $result->report_type = "NA";

        return $result;
    }

    public function getLastChecklist($driver_id){
        $this->db->select("r.id as report_id , start_mileage , end_mileage , start_date , end_date , report_type , trailer_number , vehicle_registration_number as vehicle_number , r.checklist_type");

        $result = $this->db->where("user_id" , $driver_id)->order_by("start_date" , "DESC")->get("report r")->row();

        if($result){
            $result->trailer_number = ($result->trailer_number) ? $result->trailer_number : "NA";
            $result->start_date_raw = convert_timezone($result->start_date , false , true);
            $result->start_date = convert_timezone($result->start_date , true , true, false , "d M Y D h:i:s A");
            $result->end_date = convert_timezone($result->end_date , true , true, false , "d M Y D h:i:s A");
            $result->today_date = date('M d Y' , strtotime(convert_timezone(time() , true , true )));
            $result->checklist_done = ($result->today_date == $result->start_date_raw AND $result->report_type != "1") ? true : false;
            $result->status = true;
            $result->report_data = $this->getMyReportById($result->report_id);
            $result->default = $this->defaultVehicle($driver_id);
            
        }

        return $result;
    }

    public function defaultVehicle($driver_id){
          $this->db->select("vehicle_number , trailer_number ");
          $this->db->join("vehicle_information vi" , 'vi.id = d.vehicle_id' , "LEFT");
          $this->db->join("trailer_information ti" , 'ti.id = d.trailer_id' , "LEFT");
          $result = $this->db->where("account_id" , $driver_id)->get("driver d")->row();


          return $result;
    }

    /* CHECKLIST */
    public function first(){
       $store_id = $this->db->select("store_id")->where("account_id" , $this->input->post("user_id", TRUE))->get("users_store")->row();

       $arr = array(
         "user_id" => $this->input->post('user_id', TRUE),
         "start_mileage" => $this->input->post('start_mileage', TRUE),
         "vehicle_registration_number" =>  $this->input->post('vehicle_id', TRUE),
         "trailer_number" => $this->input->post('trailer_id', TRUE),
         "start_date" => time() ,
         "store_id" => $store_id->store_id,
         "checklist_type" => $this->input->post("report_type"),
         "report_type" => 1,
         "created" => time(),
         "updated" => time()
         );

       $this->db->insert('report' , $arr);

       $report_id = $this->db->insert_id();

       $this->insertTempCheckList($report_id , $this->input->post("report_type"));

       if($this->input->post("job_id") != "0"){
          $this->insertReportJobs($report_id , $this->input->post("job_id"));
       }

       return $report_id;
    }

    public function second(){
       $report_id = $this->input->post('report_id', TRUE);

       $this->updateStep($report_id , 2);

       $this->db->where("id" , $report_id)->update("report" , ["advisory" => $this->input->post("advisory")]);

       $checklist = $this->input->post('checklist', TRUE);
       $timer = $this->input->post("checklisttimer", TRUE);

       $this->removeChecklist($report_id);

       foreach($checklist as $key => $row){
          $this->db->insert('report_checklist' , array(
             "report_id" => $report_id ,
             "checklist_index" => $key ,
             "checklist_timer" => c_strtotime($timer[$key]),
             "value" => $row
           ));
       }
    }

    public function third(){
      
        /*
            0 - Not Complete
            1 - Open
            2 - Under Maintenance
            3 - Fixed
        */


        $c = $this->db->where("report_id" , $this->input->post("report_id", TRUE))->where("status" , 3)->count_all_results("report_status");

        if($c > 0){  
          return true;
        }


        if($this->input->post('report_type', TRUE) == 'DEFECT'){

          $arr = array(
            "status" => 1 ,
            "account_id" => $this->input->post('user_id', TRUE),
            "comment" => nl2br($this->input->post('description', TRUE)),
            "report_id" => $this->input->post('report_id', TRUE),
            "created" => time()
            );

          $this->updateStep($this->input->post('report_id', TRUE) , 2);

        }else{

          $arr = array(
            "status" => 3 ,
            "account_id" => $this->input->post('user_id', TRUE),
            "comment" => nl2br($this->input->post('description', TRUE)),
            "report_id" => $this->input->post('report_id', TRUE),
            "created" => time()
            );

          $this->updateStep($this->input->post('report_id', TRUE) , 3);
        }

        $this->updateReadyToGo($this->input->post('report_type', TRUE) , $this->input->post('report_id', TRUE));

        $check = $this->db->where("report_id" , $this->input->post("report_id", TRUE))->count_all_results("report_status");

        if($check > 0){

          $this->db->set("comment" , nl2br($this->input->post('description', TRUE)));

          if($this->input->post('report_type', TRUE) != ""){
            $this->db->set("status" , 1);
          }

          $this->db->where("report_id" , $this->input->post("report_id", TRUE));
          $this->db->update("report_status");

          $this->db->where('id' , $this->input->post('report_id', TRUE))->update('report' , [
            'report_type' => $this->input->post('report_type', TRUE) , 
            'updated' => time()
            ]);

        }else{

          $this->db->insert("report_status" , $arr);

          $this->db->where('id' , $this->input->post('report_id'))->update('report' , [
            'status_id' => $this->db->insert_id() , 
            'report_type' => $this->input->post('report_type'), 
            'updated' => time()
            ]);
        } 
    }

    public function insertReportJobs($report_id , $job_id){
        $this->db->insert("report_jobs" , ["report_id" => $report_id , "job_id" => $job_id]);
    }

    public function updateReadyToGo($type , $report_id){
        $check = $this->db->where("report_id" , $report_id)->get("report_jobs")->result();

        foreach($check as $job){
            if($type == "DEFECT"){
                $this->db->where("jobs_id" , $job->job_id)->update("jobs" , ["ready_to_go" => 2 ]);
            }else{
                $this->db->where("jobs_id" , $job->job_id)->update("jobs" , ["ready_to_go" => 1 ]);
            }
        }

    }

    public function getChecklist(){
        $this->db->select("r.id , r.start_mileage , r.start_date , r.vehicle_registration_number , r.trailer_number , r.end_date , r.status_id , r.checklist_type , rs.status , rs.comment , r.report_type , r.advisory");
        $this->db->select("CONCAT(a.name , ' ' , a.surname) as driver_name");
        $this->db->join("report_status rs" , "rs.report_status_id = r.status_id" , "LEFT");
        $this->db->join("accounts a" , "a.id = r.user_id");
        $this->db->where("user_id" , $this->input->post("id"))->order_by("start_date" , "DESC")->where("report_type !=" , "1");
        $result = $this->db->limit(10)->get("report r")->result();

        foreach($result as $key => $row){
          $result[$key]->start_date = convert_timezone($row->start_date);
          $result[$key]->end_date = convert_timezone($row->end_date);
          $result[$key]->status = report_type($row->status, true);
          $checklist = $this->db->where("report_id" , $row->id)->order_by("checklist_index" , "ASC")->get("report_checklist")->result();

           if($row->checklist_type == "VEHICLE REPORT"){
              foreach($checklist as $k => $r){
                  if($k < 28){
                      $result[$key]->checklist[] = array(
                          "checklist_index" => checklist_array($r->checklist_index),
                          "checklist_timer" => convert_timezone($r->checklist_timer , true),
                          "value"           => $r->value
                      );
                  }
              }
           }else if($row->checklist_type == "MOFFET REPORT"){

              foreach($checklist as $k => $r){
                  if($k < 33){
                      $result[$key]->checklist[] = array(
                          "checklist_index" => checklist_array_moffet($r->checklist_index),
                          "checklist_timer" => convert_timezone($r->checklist_timer , true),
                          "value"           => $r->value
                      );
                  }
              }

           }else{
              foreach($checklist as $k => $r){
                  if($k < 26){
                       $result[$key]->checklist[] = array(
                          "checklist_index" => checklist_array_trailer($r->checklist_index),
                          "checklist_timer" => convert_timezone($r->checklist_timer),
                          "value"           => $r->value
                      );
                  }
              }
           }
        }

        return $result;
    }


    public function get_active_driver(){
        $this->db->select("CONCAT(a.name , ' ' , a.surname) as driver_name , a.status , a.id , a.image , a.image_thumb");
        $this->db->where("a.account_type" , "DRIVER");
        $this->db->where('a.company_id ' , $this->session->userdata('company_id'));
        $this->db->order_by("driver_name" , "ASC");
        $result = $this->db->get("accounts a")->result();

        foreach($result as $key => $row){
           $from = strtotime('00:00:00');
           $to = strtotime('23:59:59');

           $checklist = $this->db->select("id")->where(["user_id" => $row->id , "created >=" => $from , "created <=" => $to])->order_by("created" , "DESC")->get("report")->result();
           $result[$key]->checklist = count($checklist);
           $last_active = $this->db->select("created")->where("user_id" , $row->id)->order_by("created" , "DESC")->limit(1)->get("report")->row(); 

           if($last_active){
              $result[$key]->last_active = convert_timezone($last_active->created , true);

              $first_mileage = $this->db->select("start_mileage")->where("user_id" , $row->id)->where("start_mileage !=" , 0)->order_by("created" , "ASC")->limit(1)->get("report")->row();
              $last_mileage = $this->db->select("start_mileage")->where("user_id" , $row->id)->where("start_mileage !=" , 0)->order_by("created" , "DESC")->limit(1)->get("report")->row();


              $result[$key]->mileage = $first_mileage->start_mileage - $last_mileage->start_mileage;
           }else{
              $result[$key]->last_active = '<span class="label label-danger">Not Active</span>';
              $result[$key]->mileage = 0;
           }

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

           $report_id = array();

           foreach($checklist as $r){
              $report_id[] = $r->id;
           }

           if($report_id){
              $result[$key]->job = $this->db->select("job_id")->where_in("report_id" , $report_id)->get("report_jobs")->num_rows();
           }else{
              $result[$key]->job = 0;
           }
        }

        return $result;
    }
}