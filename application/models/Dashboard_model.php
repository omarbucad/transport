<?php

class Dashboard_model extends CI_Model {

    public function getActiveCount($store_id){
        $from = strtotime("today midnight");
        $to = strtotime("today 23:59");

            // $this->db->join('accounts a', 'a.id = r.user_id');
            // $this->db->where("r.created >=" , $from);
            // $this->db->where("r.created <=" , $to);
            // $this->db->where('a.company_id' , $this->session->userdata('company_id'));
            // $this->db->where("r.store_id" , $store_id);
            // $this->db->where('r.report_type ' , "SERVICEABLE");
            // $result = $this->db->get('report r')->num_rows();



        $this->db->join('accounts a', 'a.id = r.user_id');
        $this->db->join('report_status rs', 'rs.report_status_id = r.status_id');
        $this->db->where("r.created >=" , $from);
        $this->db->where("r.created <=" , $to);
        $this->db->where('a.company_id' , $this->session->userdata('company_id'));
        $this->db->where("r.store_id" , $store_id);
        $this->db->where('rs.status ' , 3);
        $result = $this->db->get('report r')->num_rows();

        return $result;
    }
    public function getActiveList($store_id){
        $from = strtotime("today midnight");
        $to = strtotime("today 23:59");

        $this->db->select('r.id , name , surname , trailer_number , vehicle_registration_number as vehicle_number , start_mileage , end_mileage , start_date , end_date , r.created ');
        $this->db->join('accounts a', 'a.id = r.user_id');
        $this->db->where("r.created >=" , $from);
        $this->db->where("r.created <=" , $to);
        $this->db->where('a.company_id' , $this->session->userdata('company_id'));
        $this->db->where("r.store_id" , $store_id);

        $this->db->order_by("r.updated" , "desc");
        $this->db->where('r.report_type ' , "SERVICEABLE");
        $result = $this->db->get('report r')->result();


        $this->db->select('r.id , name , surname , trailer_number , vehicle_registration_number as vehicle_number , start_mileage , end_mileage , start_date , end_date , r.created ');
        $this->db->join('accounts a', 'a.id = r.user_id');
        $this->db->join('report_status rs', 'rs.report_status_id = r.status_id');
        $this->db->where("r.created >=" , $from);
        $this->db->where("r.created <=" , $to);
        $this->db->where('a.company_id' , $this->session->userdata('company_id'));
        $this->db->where("r.store_id" , $store_id);

        $this->db->order_by("r.updated" , "desc");
        $this->db->where('rs.status ' , 3);
        $result += $this->db->get('report r')->result();

        foreach($result as $key => $row){
        		//$result[$key]->start_mileage = convert_timezone($row->start_mileage);
        		//$result[$key]->end_mileage = convert_timezone($row->end_mileage);
          $result[$key]->created = convert_timezone($row->created);
        		//$result[$key]->status = report_type($row->status);
      }


      return $result;
    }


    public function getDefectCount($store_id){
        $from = strtotime("today midnight");
        $to = strtotime("today 23:59");

        $this->db->join('accounts a', 'a.id = r.user_id');
        $this->db->join('report_status rs', 'rs.report_status_id = r.status_id');
        $this->db->where("r.created >=" , $from);
        $this->db->where("r.created <=" , $to);
        $this->db->where('a.company_id' , $this->session->userdata('company_id'));
        $this->db->where("r.store_id" , $store_id);
        $this->db->where('rs.status !=' , 3);
        return $this->db->get('report r')->num_rows();
    }
    public function getDefectList($store_id){
        $from = strtotime("today midnight");
        $to = strtotime("today 23:59");
    	$this->db->select('r.id , name , surname , trailer_number , vehicle_registration_number as vehicle_number , start_mileage , end_mileage , start_date , end_date , r.created , rs.status , rs.comment');
    	$this->db->join('accounts a', 'a.id = r.user_id');
        $this->db->join('report_status rs', 'rs.report_status_id = r.status_id');
        $this->db->where("r.created >=" , $from);
        $this->db->where("r.created <=" , $to);
        $this->db->where('a.company_id' , $this->session->userdata('company_id'));
        $this->db->where("r.store_id" , $store_id);
        $this->db->order_by("r.updated" , "desc");
        $this->db->where('rs.status !=' , 3);
    	$result = $this->db->get('report r')->result();

    	foreach($result as $key => $row){
    		$result[$key]->created = convert_timezone($row->created);
    		$result[$key]->status = report_type($row->status);
    	}

    	return $result;
    }


    public function getIncompleteCount($store_id){
        $from = strtotime("today midnight");
        $to = strtotime("today 23:59");

        $this->db->join('accounts a', 'a.id = r.user_id');
        $this->db->where('r.report_type ' , "1");
        $this->db->where("r.store_id" , $store_id);
        $this->db->where('a.company_id' , $this->session->userdata('company_id'));
        $this->db->where("r.created >=" , $from);
        $this->db->where("r.created <=" , $to);
        return $this->db->get('report r')->num_rows();
    } 
    public function getIncompleteList($store_id){
        $from = strtotime("today midnight");
        $to = strtotime("today 23:59");

        $this->db->select('r.id , name , surname , trailer_number , vehicle_registration_number as vehicle_number , start_mileage , end_mileage , start_date , end_date , r.created ');
        $this->db->join('accounts a', 'a.id = r.user_id');
        $this->db->where('a.company_id' , $this->session->userdata('company_id'));
        $this->db->where("r.store_id" , $store_id);
        $this->db->where("r.created >=" , $from);
        $this->db->where("r.created <=" , $to);
        $this->db->order_by("r.updated" , "desc");
        $result = $this->db->where('report_type' , '1')->get('report r')->result();

        foreach($result as $key => $row){
          $result[$key]->created = convert_timezone($row->created);
      }

      return $result;
    }

    
    public function getStoreList(){
        if($this->session->userdata('account_type') == 'SUPER ADMIN'){
            $this->db->select('store_id , store_name');
            $this->db->where('company_id' , $this->session->userdata('company_id'));
            $this->db->where('status' , 1);
            return $this->db->get('store')->result();
        }else{
            $this->db->select('s.store_id , s.store_name');
            $this->db->join('store s' , 's.store_id = us.store_id');
            $this->db->where('us.account_id' , $this->session->userdata('id'));
            $this->db->where('s.status' , 1);
            return $this->db->get('users_store us')->result();
        }
    }

    public function getFirstStore($data){
        return ($data) ? $data[0]->store_id : false;
    }

    public function checkSelectedStore($data , $store_id){
        foreach($data as $row){
            if($row->store_id == $store_id){
                return true;
            }
        }
        return false;
    }

    public function getCancelledJobs($store_id){
        $this->db->select("j.cancel_notes , j.jobs_id , CONCAT(a2.name , ' ' , a2.surname) as name");
        $this->db->join("jobs j" , "j.parent_id = jp.job_parent_id");
        $this->db->join("jobs_status js" , "js.jobs_status_id = j.status_id");
        $this->db->join("accounts a" , "a.id = jp.customer_id");
        $this->db->join("accounts a2" , "a2.id = js.account_id");
        $this->db->where("j.store_id" , $store_id);
        $this->db->where("js.status_type" , "cancel");
        return  $this->db->order_by("js.created" , "DESC")->limit(10)->get("jobs_parent jp")->result();
    }

    public function getDailyReportList($store_id){
      $this->db->select('r.id , name , surname , ti.trailer_number , vi.vehicle_number , start_mileage , end_mileage , start_date , end_date , r.created , rs.status , rs.comment');
      $this->db->join('report_status rs', 'rs.report_status_id = r.status_id' , 'left');
      $this->db->join('accounts a', 'a.id = r.user_id');
      $this->db->join('trailer_information ti', 'ti.id = r.trailer_number' , 'LEFT');
      $this->db->join('vehicle_information vi', 'vi.id = r.vehicle_registration_number');
      $this->db->where("r.store_id" , $store_id);
      $this->db->where('rs.status != ' , 0 );

      $this->db->where('r.report_type ' , 'SERVICEABLE');
      $result = $this->db->order_by('r.created' , 'DESC')->limit(10)->get('report r')->result();

      foreach($result as $key => $row){
        $result[$key]->start_date = convert_timezone($row->start_date);
        $result[$key]->created = convert_timezone($row->created , true);

        if($row->status){
          $result[$key]->mileage = (floatval($row->end_mileage) - floatval($row->start_mileage));
          $result[$key]->end_date = convert_timezone($row->end_date);
        }else{
          $result[$key]->mileage = 0;
          $result[$key]->end_date = 'Present';
        }
        
        $result[$key]->status = report_type($row->status);
      }

      return $result;
    }

    public function getRecentActivity($store_id){
        $this->db->select("jp.job_name , js.created , c.company_name , js.status_type");
        $this->db->join("customer c" , "c.account_id = jp.customer_id");
        $this->db->join("jobs j" , "j.parent_id = jp.job_parent_id");
        $this->db->join("jobs_status js" , "js.jobs_status_id = j.status_id");
        $this->db->order_by("js.created" , "DESC");
        $this->db->group_by("c.company_name");
        $this->db->where("j.store_id" , $store_id);
        $result =  $this->db->get("jobs_parent jp")->result();

        foreach($result as $key => $row){
            $result[$key]->created = fromNow($row->created);
            $result[$key]->message = status_type($row);
        }

        return $result;
    }

    public function updateStillActiveAccount(){
        $this->db->where("id" , $this->session->userdata("id"))->update("accounts" , ["still_active" => time()]);
    }

    public function getStillActiveAccount(){
        $result = $this->db->select("CONCAT(name , ' ' , surname) as name , still_active")->order_by("still_active" , "DESC")->where("still_active >=" , (time() - 300))->where("id !=" , $this->session->userdata("id"))->where("company_id" , $this->session->userdata("company_id"))->get("accounts")->result();

        foreach($result as $key => $row){
            $result[$key]->still_active = fromNow($row->still_active);
        }

        return $result;
    }

    public function getArticType(){
        return $this->db->where("company_id" , $this->session->userdata("company_id"))->order_by("code" , "ASC")->get("vehicle_artic_type")->result();
    }
    public function getDevisionType(){
        return $this->db->where("company_id" , $this->session->userdata("company_id"))->order_by("code" , "ASC")->get("vehicle_division_type")->result();
    }

    public function demurrageReport($store_id){
        $from = strtotime('first day of this month midnight');
        $to = strtotime('first day of next month midnight -1 seconds');

        $this->db->select("j.jobs_id , j.loading_time , j.delivery_time");
        $this->db->join("jobs_status js" , "js.jobs_status_id = j.status_id");
        $this->db->where_in("js.status_type" , ["finished" , "partially_complete"]);
        $this->db->where("j.delivery_time >=" , $from );
        $this->db->where("j.delivery_time <=" , $to );
        $this->db->where("j.store_id" , $store_id);
        $result = $this->db->order_by("j.delivery_time" , "DESC")->get("jobs j")->result();

        $id = array();

        foreach($result as $key => $row){
            $A = $this->db->select("arrived_time")->where("jobs_id" , $row->jobs_id)->where("point_destination" , "A")->get("jobs_date")->row();
            $B = $this->db->select("arrived_time")->where("jobs_id" , $row->jobs_id)->where("point_destination" , "B")->get("jobs_date")->row();


            if($A AND $A->arrived_time > ($row->loading_time + 7200)){
                $id[] = $row->jobs_id;
            }else if($B AND $B->arrived_time > ($row->delivery_time + 7200)){
                $id[] = $row->jobs_id;
            }

        }

        $return = array(
            "count" => count($id),
            "link" => $this->config->site_url("app/dashboard/index/".$store_id."/")."?".http_build_query(array("jobs_id" => $id))
            );

        return $return;
    }

    public function getDriverLicenseExpiry(){
        $time = strtotime("+2 weeks");
        $this->db->select("account_id");
        $this->db->join("accounts a" , "a.id = d.account_id");
        $result = $this->db->where("license_expiry_date <" , $time)->where("license_expiry_date !=" , 0)->where("a.status" , 1)->get("driver d")->result();

        $a = array();
        foreach($result as $r){
            $a[] = $r->account_id;
        }
        return array(
            "count" => count($a),
            "query" => "?".http_build_query(["id" => $a]).'&submit=submit'
        );
    }


    public function newGetAllVehicle(){
        $company_id = $this->session->userdata('company_id');
       
        $temp = array();

        $start = strtotime("today midnight");
        $end = strtotime("today 23:59");

        $report = $this->db->distinct()->select("vehicle_registration_number")->where("created >=" , $start )->where("created <= ", $end )->get("report")->result();
    
        foreach($report as $row){
            if($row->vehicle_registration_number != ""){
                $temp[] = $row->vehicle_registration_number;
            }
        }

        
        
            $this->db->select("id , vehicle_number");
            if($temp){
                $this->db->where_not_in("vehicle_number" , $temp);
            }
            $vehicle = $this->db->where("store_id" , $company_id)->where("status" , 1)->get("vehicle_information")->result();
        
        

        $t = array();
        foreach($vehicle as $v){
            $t[] = $v->vehicle_number;
        }

        return [
            "active" => $temp ,
            "inactive" => $t
        ];
        
    }

    public function newGetAllTrailer(){
        $company_id = $this->session->userdata('company_id');

        $temp = array();

        $start = strtotime("today 00:00");
        $end = strtotime("today 23:59");

        $report = $this->db->distinct()->select("trailer_number")->where("r.created >=" , $start )->where("r.created <= ", $end )->get("report r")->result();

        foreach($report as $row){
            if($row->trailer_number != ""){
                $temp[] = $row->trailer_number;
            }
        }

        $this->db->select("id , trailer_number , short_trailer_number");

        if($temp){
            $this->db->where_not_in("trailer_number" , $temp);
        }
        $trailer = $this->db->where("store_id" , $company_id)->where("status" , 1)->get("trailer_information")->result();
        $t = array();

        foreach($trailer as $v){
            $t[] = $v->trailer_number.' - '.$v->short_trailer_number;
        }

        return [
            "active" => $temp ,
            "inactive" => $t
        ];
    }

    public function newGetAll(){
        return [
            "vehicle" => $this->newGetAllVehicle(),
            "trailer" => $this->newGetAllTrailer(),
            "advisory" => $this->getAdvisory(),
            "defect"   => $this->getDefectList(4)
        ];
    }

    public function getAdvisory(){
        $company_id = $this->session->userdata('company_id');

        $temp = array();

        $this->db->join("report_status rs" , "rs.report_status_id = r.status_id");
        $report = $this->db->where("advisory IS NOT NULL" , NULL, FALSE)->where("advisory != " , "''" , FALSE)->where("rs.status !=" , 3)->get("report r")->result();

        return $report;
    }
}