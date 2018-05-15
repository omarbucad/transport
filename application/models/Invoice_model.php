<?php

class Invoice_model extends CI_Model {

    /* Invoice Model */

    public function getInvoiceList($id = array() , $transaction_id = false){
        
        $r = array();

        if($this->input->get("submit")){
            if($this->input->get("job_number")){
                $r = $this->db->select("merge_id")->where("job_id" , $this->input->get("job_number"))->get("invoice")->row_array();
            }
        }

        $total = 0;

        $this->db->select("j.job_name , j.job_number , j.telephone");
        $this->db->select("j.type_of_truck , j.loading_time , j.delivery_time , j.delivered_fulldate , j.signature , j.time_of_arrival , j.with_charge , j.cancel_notes , j.with_outsource , j.outsource_price , j.job_number");
        $this->db->select("i.paid_by , i.invoice_date , i.status , i.paid_date , i.job_id , i.invoice_id , i.confirmed_date , i.generated_pdf , i.generated_pdf_paid , i.price , i.total_price , i.vat , i.demurrage , i.to_outsource , i.invoice_number , i.notes as invoice_notes , i.merge , i.merge_id , i.job_number as jn , i.jpo_number , i.jmerge_name");
        $this->db->select("c.company_name , c.registration_number , c.vat_number , c.billing_address");
        $this->db->select("CONCAT(a.name , ' ' , a.surname) as confirmed_by");
        $this->db->select("o.company_name as outsource_company_name , o.registration_number as outsource_registration_number , o.vat_number as outsource_vat_number , o.billing_address as outsource_billing_address");
        $this->db->join("jobs j" , "j.jobs_id = i.job_id" , "LEFT");
        $this->db->join("outsource o" , "o.outsource_id = j.outsource_company_name" , "LEFT");
        $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id" , "LEFT");
        $this->db->join("customer c" , "c.customer_id = i.customer_id");
        $this->db->join("accounts a" , "a.id = i.confirmed_by" , "LEFT");
        
        // if($this->session->userdata("account_type") != SUPER_ADMIN){
        //     $this->db->join("users_store us" , "us.account_id = jp.account_id" );
        //     $this->db->where("us.store_id" , $this->session->userdata("store_id"));
        // }

        if($this->input->get("submit")){

            if($this->input->get("outsource")){
                switch ($this->input->get("outsource")) {
                    case 'yes':
                        $this->db->where("i.to_outsource" , "YES");
                        break;
                    case 'no':
                        $this->db->where("i.to_outsource" , "NO");
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }

            if($this->input->get("invoice_number")){
                $this->db->where("i.invoice_number" , $this->input->get("invoice_number"));
            }

            if($this->input->get("job_number")){
                
                if($r['merge_id']){
                    $this->db->where("i.invoice_id" , $r['merge_id']);
                }else{
                    $this->db->where("i.job_id" , $this->input->get("job_number"));
                }
            }else{
                //$this->db->where("merge_id" , 0);
            }

            if($this->input->get("job_name")){
                $this->db->like("j.job_name" , $this->input->get("job_name"));
            }

            if($this->input->get("customer_name")){
                $this->db->where("c.company_name" , $this->input->get("customer_name"));
            }

            if($this->input->get("paid_by")){
                $this->db->where("paid_by" , $this->input->get("paid_by"));
            }

            if($this->input->get("invoice_status")){
                switch ($this->input->get("invoice_status")) {
                    case 'INCOMPLETE':
                        $code = 0;
                        break;
                    case 'NEED_CONFIRMATION':
                        $code = 1;
                        break;
                    case 'COMPLETE':
                        $code = 2;
                        break;
                    default:
                        $code = 0;
                    break;
                }
                $this->db->where("i.status" , $code);
            }

            if($this->input->get("invoice_date_from") AND $this->input->get("invoice_date_to")){
                $from = strtotime($this->input->get("invoice_date_from").' midnight');
                $to = strtotime($this->input->get("invoice_date_to").' 23:59');

                $this->db->where("i.invoice_date >=" , $from);
                $this->db->where("i.invoice_date <=" , $to);
            }else if($this->input->get("invoice_date_from")){
                $from = strtotime($this->input->get("invoice_date_from").' midnight');
                $this->db->where("i.invoice_date >=" , $from);
            }

            if($this->input->get("paid_date_from") AND $this->input->get("paid_date_to")){
                $from = strtotime($this->input->get("paid_date_from").' midnight');
                $to = strtotime($this->input->get("paid_date_to").' 23:59');

                $this->db->where("i.paid_date >=" , $from);
                $this->db->where("i.paid_date <=" , $to);
            }else if($this->input->get("paid_date_from")){
                $from = strtotime($this->input->get("paid_date_from").' midnight');
                $this->db->where("i.paid_date >=" , $from);
            }

            if($this->input->get("delivery_date_from") AND $this->input->get("delivery_date_to")){
                $from = strtotime($this->input->get("delivery_date_from").' midnight');
                $to = strtotime($this->input->get("delivery_date_to").' 23:59');

                $this->db->where("j.delivery_time >=" , $from);
                $this->db->where("j.delivery_time <=" , $to);
            }else if($this->input->get("delivery_date_from")){
                $from = strtotime($this->input->get("delivery_date_from").' midnight');
                $this->db->where("j.delivery_time >=" , $from);
            }


            if( ($this->input->get("invoice_date_from") AND $this->input->get("invoice_date_to"))  OR ($this->input->get("paid_date_from") AND $this->input->get("paid_date_to")) OR ($this->input->get("delivery_date_from") AND $this->input->get("delivery_date_to"))){

            }else{
                if(!$this->input->get("outsource")){
                    $last_week = strtotime("last week");
                    $this->db->where("i.invoice_date >" , $last_week);
                }
                
            }

        }else{
            if(!$id AND !$transaction_id){
                $last_week = strtotime("last week");
                $this->db->where("i.invoice_date >" , $last_week);
            }

           //$this->db->where("merge_id" , 0);
        }

        if($id){
            $this->db->where_in("invoice_id" , $this->input->get('id' , TRUE));
            $this->db->where("paid_by" , "UNPAID");
        }

        if($transaction_id){
            $this->db->join("invoice_transaction_invoices iti" , "iti.invoice_id = i.invoice_id");
            $this->db->where("iti.invoice_transaction_id" , $transaction_id);
        }


        $this->db->where("i.company_id" , $this->session->userdata("company_id"));

        if($this->session->userdata("account_type") == CUSTOMER){
            $this->db->where("jp.customer_id" , $this->session->userdata("customer_id"));
            $this->db->where("i.to_outsource" , "NO");
        }else if($this->session->userdata("account_type") == OUTSOURCE){
            $this->db->where("j.outsource_company_name" , $this->session->userdata("outsource_id"));
            $this->db->where("i.to_outsource" , "NO");
        }

        if($this->input->get("is_merge") == false){
            $this->db->where("i.merge","N");
        }
        
        $this->db->order_by("i.invoice_id" , "DESC");
      
        $result = $this->db->get("invoice i")->result();
       
        foreach($result as $key => $row){
            $total += $row->total_price;
           // $result[$key]->job_name = ($row->job_number) ? $row->job_name.' # '.$row->job_number : $row->job_name;
            $result[$key]->paid_date = convert_timezone($row->paid_date , true);
            $result[$key]->confirmed_date = convert_timezone($row->confirmed_date);
            $result[$key]->loading_time = convert_timezone($row->loading_time , true);
            $result[$key]->delivery_time = convert_timezone($row->delivery_time , true);
            $result[$key]->delivered_fulldate = convert_timezone($row->delivered_fulldate , true);
            $result[$key]->time_of_arrival = convert_timezone($row->time_of_arrival , true);
            $result[$key]->invoice_date = convert_timezone($row->invoice_date , true);

            $result[$key]->total_price_raw = round($row->total_price , 2);
            $result[$key]->outsource_price_raw = round($row->outsource_price , 2);
            $result[$key]->price_raw = round($row->price, 2);
            $result[$key]->vat_raw = round($row->vat, 2);
            $result[$key]->demurrage_raw = round($row->demurrage, 2);
            $result[$key]->status_raw = invoice_status( $row->status  , true);
            $result[$key]->paid_status_raw = paid_status($row->paid_by , true , $result[$key]->status_raw);

            $result[$key]->our_price_from_outsource = formatMoney(($row->total_price - $row->outsource_price));

            $result[$key]->outsource_price = formatMoney($row->outsource_price);
            $result[$key]->total_price = formatMoney($row->total_price);
            $result[$key]->status = invoice_status($row->status , false , $result[$key]->status_raw);
            $result[$key]->paid_status = paid_status($row->paid_by , false , $result[$key]->status_raw);
            $result[$key]->price = formatMoney($row->price);
            $result[$key]->vat = formatMoney($row->vat);
            $result[$key]->demurrage = formatMoney($row->demurrage);
            $result[$key]->pdf = ($row->generated_pdf_paid) ? $row->generated_pdf_paid : $row->generated_pdf;

            $result[$key]->pdf = $this->config->base_url(ltrim($result[$key]->pdf , '.'));

            //$result[$key]->company_name = ($row->to_outsource == "NO") ? $row->company_name : $row->outsource_company_name;
            $result[$key]->registration_number = ($row->to_outsource == "NO") ? $row->registration_number : $row->outsource_registration_number;
            $result[$key]->vat_number = ($row->to_outsource == "NO") ? $row->vat_number : $row->outsource_vat_number;
            $result[$key]->billing_address = ($row->to_outsource == "NO") ? $row->billing_address : $row->outsource_billing_address;

            if($row->merge == "Y"){
                $result[$key]->job_name = $row->jmerge_name;
            }
        }

        if($id){
            return array(
                "result" => $result ,
                "total" => formatMoney($total) ,
                "total_price_raw" => $total
                );
        }


        return $result;
    }

    public function gIid($id){
        $this->db->select("j.job_name , j.job_number , j.telephone");
        $this->db->select("j.type_of_truck , j.loading_time , j.delivery_time , j.delivered_fulldate , j.signature , j.time_of_arrival , j.with_charge , j.cancel_notes , j.with_outsource , j.outsource_price , j.job_number");
        $this->db->select("i.paid_by , i.invoice_date , i.status , i.paid_date , i.job_id , i.invoice_id , i.confirmed_date , i.generated_pdf , i.generated_pdf_paid , i.price , i.total_price , i.vat , i.demurrage , i.to_outsource , i.invoice_number , i.notes as invoice_notes , i.merge , i.job_number as jn , i.jpo_number");
        $this->db->select("c.company_name , c.registration_number , c.vat_number , c.billing_address");
        $this->db->select("CONCAT(a.name , ' ' , a.surname) as confirmed_by");
        $this->db->select("o.company_name as outsource_company_name , o.registration_number as outsource_registration_number , o.vat_number as outsource_vat_number , o.billing_address as outsource_billing_address");
        $this->db->join("jobs j" , "j.jobs_id = i.job_id" , "LEFT");
        $this->db->join("outsource o" , "o.outsource_id = j.outsource_company_name" , "LEFT");
        $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id" , "LEFT");
        $this->db->join("customer c" , "c.customer_id = i.customer_id");
        $this->db->join("accounts a" , "a.id = i.confirmed_by" , "LEFT");
        $this->db->where("i.company_id" , $this->session->userdata("company_id"));
        $this->db->where_in("invoice_id" , $id);  

        $this->db->order_by("i.invoice_id" , "DESC");
      
        $result = $this->db->get("invoice i")->result(); 

        foreach($result as $key => $row){

           // $result[$key]->job_name = ($row->job_number) ? $row->job_name.' # '.$row->job_number : $row->job_name;
            $result[$key]->paid_date = convert_timezone($row->paid_date , true);
            $result[$key]->confirmed_date = convert_timezone($row->confirmed_date);
            $result[$key]->loading_time = convert_timezone($row->loading_time , true);
            $result[$key]->delivery_time = convert_timezone($row->delivery_time , true);
            $result[$key]->delivered_fulldate = convert_timezone($row->delivered_fulldate , true);
            $result[$key]->time_of_arrival = convert_timezone($row->time_of_arrival , true);
            $result[$key]->invoice_date = convert_timezone($row->invoice_date , true);

            $result[$key]->total_price_raw = round($row->total_price , 2);
            $result[$key]->outsource_price_raw = round($row->outsource_price , 2);
            $result[$key]->price_raw = round($row->price, 2);
            $result[$key]->vat_raw = round($row->vat, 2);
            $result[$key]->demurrage_raw = round($row->demurrage, 2);
            $result[$key]->status_raw = invoice_status( $row->status  , true);
            $result[$key]->paid_status_raw = paid_status($row->paid_by , true , $result[$key]->status_raw);

            $result[$key]->our_price_from_outsource = formatMoney(($row->total_price - $row->outsource_price));

            $result[$key]->outsource_price = formatMoney($row->outsource_price);
            $result[$key]->total_price = formatMoney($row->total_price);
            $result[$key]->status = invoice_status($row->status , false , $result[$key]->status_raw);
            $result[$key]->paid_status = paid_status($row->paid_by , false , $result[$key]->status_raw);
            $result[$key]->price = formatMoney($row->price);
            $result[$key]->vat = formatMoney($row->vat);
            $result[$key]->demurrage = formatMoney($row->demurrage);
            $result[$key]->pdf = ($row->generated_pdf_paid) ? $row->generated_pdf_paid : $row->generated_pdf;

            $result[$key]->pdf = $this->config->base_url(ltrim($result[$key]->pdf , '.'));

            //$result[$key]->company_name = ($row->to_outsource == "NO") ? $row->company_name : $row->outsource_company_name;
            $result[$key]->registration_number = ($row->to_outsource == "NO") ? $row->registration_number : $row->outsource_registration_number;
            $result[$key]->vat_number = ($row->to_outsource == "NO") ? $row->vat_number : $row->outsource_vat_number;
            $result[$key]->billing_address = ($row->to_outsource == "NO") ? $row->billing_address : $row->outsource_billing_address;
        }

        return $result;
    }

    public function getInvoiceListMerge($id){
        $list = $this->db->select("invoice_id")->where("merge_id" , $id)->get("invoice")->result();
        $tmp = array();

        foreach($list as $row){
            $tmp[] = $row->invoice_id;
        }
        if($tmp){
            return $this->gIid($tmp);
        }else{
            return false;
        }
        
    }


    public function saveInvoicePDF($pdf_path , $invoice_id , $paid = false){
        if($paid){
            $this->db->where("invoice_id" , $invoice_id)->update("invoice" , array("generated_pdf_paid" => $pdf_path));
        }else{
            $this->db->where("invoice_id" , $invoice_id)->update("invoice" , array("generated_pdf" => $pdf_path));
        }
    }

    public function updateInvoice($merge = false){
        $id = $this->input->get("id");
        
        $job_id = array();

        $this->db->insert("invoice_transaction" , array(
            "paid_by" => $this->input->post("paid_by", TRUE) , 
            "paid_date" => strtotime($this->input->post("paid_date", TRUE)) ,
            "notes" => nl2br($this->input->post("notes", TRUE)) ,
            "total_price" => $this->input->post("total_price", TRUE),
            "cheque_number" => $this->input->post("cheque_number", TRUE),
            "account_id" => $this->session->userdata("id"),
            "created" => time() 
            ));

        $transaction_id = $this->db->insert_id();

        $images = $this->uploadBankSlip($transaction_id);

        if(is_array($merge) AND count($merge)  > 1){
            $path = $this->generateMergeInvoice($merge, $transaction_id);
        }
        
        foreach($id as $row){
            $check = $this->db->where("invoice_id" , $row)->where("paid_by" , "UNPAID")->get("invoice")->row();

            if($check){

                $this->db->insert("invoice_transaction_invoices" , array(
                    "invoice_transaction_id" => $transaction_id ,
                    "invoice_id" => $row
                    ));

                foreach($images as $r){

                    $this->db->insert("invoice_images" , array(
                        "invoice_id" => $row ,
                        "image" => $r['image'] ,
                        "image_thumb" => $r['image_thumb']
                        ));
                }


                if(is_array($merge) AND count($merge ) > 1){
                    $this->db->where("invoice_id" , $row)->update("invoice" , array(
                        "paid_by" => $this->input->post("paid_by", TRUE) ,
                        "paid_date" => strtotime($this->input->post("paid_date", TRUE)) ,
                        "cheque_number" => $this->input->post("cheque_number", TRUE),
                        "status" => 1 ,
                        "generated_pdf_paid" => $path['path']
                    ));
                }else{
                    $this->db->where("invoice_id" , $row)->update("invoice" , array(
                        "paid_by" => $this->input->post("paid_by", TRUE) ,
                        "paid_date" => strtotime($this->input->post("paid_date", TRUE)) ,
                        "cheque_number" => $this->input->post("cheque_number", TRUE),
                        "status" => 1
                    ));
                }
                

                $this->add_invoice_history($row);

                $job_id[] = $check->job_id;
            }

        }

        return ['job_id' => $job_id , "transaction_id" => $transaction_id];

    }

    private function uploadBankSlip($id){
        $year = date("Y");
        $month = date("m");

        $a = array();

        $path = './public/upload/invoice/'.$year.'/'.$month.'/';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            create_index_html($path);
        }

        $config['upload_path']          = $path;
        $config['allowed_types']        = 'gif|jpg|png';

        $data = isset($_FILES['file']) ? $_FILES['file'] : array() ;

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


                    $thumb_config['image_library']  = 'gd2';
                    $thumb_config['source_image']   = $image['full_path'];
                    $thumb_config['create_thumb']   = TRUE;
                    $thumb_config['maintain_ratio'] = TRUE;
                    $thumb_config['width']          = 250;
                    $thumb_config['height']         = 250;

                    $this->image_lib->clear(); 
                    $this->image_lib->initialize($thumb_config); 

                    $this->image_lib->resize();

                    $thumb_name = $image['raw_name'].'_thumb'.$image['file_ext'];

                    $a[] = array(
                        "image" => $year.'/'.$month.'/'.$image['file_name'] ,
                        "image_thumb" => $year.'/'.$month.'/'.$thumb_name ,
                        "created" => time() ,
                        "image_type" => "BANK_SLIP"
                        );

                }
            }//end for loop

        }//end if


        return $a;
    }

    public function getInvoiceByIdRaw($id){
        $result = $this->db->where("invoice_id" , $id)->get("invoice")->row();
        
        if($result){
            $result->invoice_date = ($result->invoice_date) ?  date("D d M Y" , $result->invoice_date) : "";
            $result->paid_date = ($result->paid_date) ?  date("D d M Y" , $result->paid_date) : "";
            $result->demurrage = round($result->demurrage , 2);
            $result->price = round($result->price , 2);
            $result->vat = round($result->vat , 2);
            $result->total_price = round($result->total_price , 2);

            if($result->merge == "N"){
                $a = $this->db->where("jobs_id" , $result->job_id)->get("jobs")->row();
                $result->job_name = $a->job_name;
            }else{
                $result->job_name = $result->jmerge_name;
            }

         }


        return $result;
    }

    public function getInvoiceById($id){

        $this->db->select("j.job_name , j.telephone");
        $this->db->select("j.type_of_truck , j.loading_time , j.delivery_time , j.delivered_fulldate , j.signature , j.time_of_arrival , j.demurrage , j.vat , j.total_price , j.with_outsource , j.price , j.outsource_price");
        $this->db->select("i.paid_by , i.invoice_date , i.status , i.paid_date , i.job_id , i.invoice_id , i.confirmed_date");
        $this->db->select("c.company_name , c.registration_number , c.vat_number , c.billing_address");
        $this->db->select("CONCAT(a.name , ' ' , a.surname) as confirmed_by");
        $this->db->join("jobs j" , "j.jobs_id = i.job_id" , "LEFT");
        $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id" , "LEFT");
        $this->db->join("customer c" , "c.customer_id = jp.customer_id" , "LEFT");
        $this->db->join("accounts a" , "a.id = i.confirmed_by" , "LEFT");
        $this->db->where("i.company_id" , $this->session->userdata("company_id"));
        $this->db->where("i.invoice_id" , $id);
        $result = $this->db->get("invoice i")->row();

        $result->paid_date = convert_timezone($result->paid_date);
        $result->confirmed_date = convert_timezone($result->confirmed_date);
        $result->loading_time = convert_timezone($result->loading_time , true);
        $result->delivery_time = convert_timezone($result->delivery_time , true);
        $result->delivered_fulldate = convert_timezone($result->delivered_fulldate , true);
        $result->time_of_arrival = convert_timezone($result->time_of_arrival , true);
        $result->invoice_date = convert_timezone($result->invoice_date );
        $result->total_price = formatMoney($result->total_price);
        $result->status_raw = $result->status;
        $result->status = invoice_status($result->status);
        $result->paid_status = paid_status($result->paid_by , false , $result->status_raw);

        $images = $this->db->select("image , image_thumb , image_id")->where("jobs_id" , $result->job_id)->get("jobs_images", TRUE)->result();

        foreach($images as $key => $row){
            if($row->image_thumb){
                $image = full_path_image($row->image_thumb , 'job');
                $images[$key]->image_thumb = $image['path'];

                if(!file_exists($image['absolute_path'])){
                    $images[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
                }

            }else{
               $images[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
           }
        }

        foreach($images as $key => $row){
            if($row->image){
                $image = full_path_image($row->image , 'job');
                $images[$key]->image = $image['path'];

                if(!file_exists($image['absolute_path'])){
                    $images[$key]->image = $this->config->base_url('/public/images/image-not-found.png');
                }

            }else{
               $images[$key]->image = $this->config->base_url('/public/images/image-not-found.png');
           }
        }

        $result->images = $images;

        $images = $this->db->select("image , image_thumb , invoice_image_id as image_id")->where("invoice_id" , $result->invoice_id)->get("invoice_images")->result();

        foreach($images as $key => $row){
            if($row->image_thumb){
                $image = full_path_image($row->image_thumb , 'invoice');
                $images[$key]->image_thumb = $image['path'];

                if(!file_exists($image['absolute_path'])){
                    $images[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
                }

            }else{
               $images[$key]->image_thumb = $this->config->base_url('/public/images/image-not-found.png');
           }
        }

        foreach($images as $key => $row){
            if($row->image){
                $image = full_path_image($row->image , 'invoice');
                $images[$key]->image = $image['path'];

                if(!file_exists($image['absolute_path'])){
                    $images[$key]->image = $this->config->base_url('/public/images/image-not-found.png');
                }

            }else{
               $images[$key]->image = $this->config->base_url('/public/images/image-not-found.png');
           }
        }

        $result->images = array_merge($result->images , $images);

        if($result->signature){

            $image = full_path_image($result->signature , 'signature');

            $result->signature = $image['path'];

            if(!file_exists($image['absolute_path'])){
                $result->signature = $this->config->base_url('/public/images/image-not-found.png');
            }
        }else{
            $result->signature = $this->config->base_url('/public/images/image-not-found.png');
        }

        return $result;
    }

    public function confirmedInvoice($id){
        $arr = array(
            "confirmed_by" => $this->session->userdata("id"),
            "confirmed_date" => time() ,
            "status" => 2 
            );

        $this->db->where("invoice_id" , $id)->update("invoice" , $arr);

        $j = $this->db->select("job_id")->where("invoice_id" , $id)->get("invoice")->row();

        return $j->job_id;
    }

    public function add_invoice_history($invoice_id){
        $c = $this->db->where("merge_id" , $invoice_id)->get("invoice")->num_rows();

        $invoice = $this->db->where("invoice_id" , $invoice_id)->get("invoice")->row_array();
        $invoice['updated'] = time();
        $invoice['updated_by'] = $this->session->userdata("id");
        if($invoice['merge'] == "Y"){
            $invoice['i_number'] = $c;
        }
        $this->db->insert("invoice_history" , $invoice);
    }

    public function get_invoice_history($id){

        $this->db->select("j.job_name , j.job_number , j.telephone");
        $this->db->select("j.type_of_truck  , j.loading_time , j.delivery_time , j.delivered_fulldate , j.signature , j.time_of_arrival  , j.with_charge , j.cancel_notes");
        $this->db->select("i.paid_by , i.invoice_date , i.status , i.paid_date , i.job_id , i.invoice_id , i.confirmed_date , i.updated , i.price , i.demurrage , i.vat , i.total_price , i.i_number");
        $this->db->select("c.company_name , c.registration_number , c.vat_number , c.billing_address");
        $this->db->select("CONCAT(a.name , ' ' , a.surname) as confirmed_by");
        $this->db->join("jobs j" , "j.jobs_id = i.job_id" , "LEFT");
        $this->db->join("jobs_parent jp" , "jp.job_parent_id = j.parent_id" , "LEFT");
        $this->db->join("customer c" , "c.account_id = i.customer_id" , "LEFT");
        $this->db->join("accounts a" , "a.id = i.confirmed_by" , "LEFT");
        $this->db->where("i.invoice_id" , $id);

        $this->db->order_by("i.updated" , "DESC");
      
        $result = $this->db->get("invoice_history i")->result();

        foreach($result as $key => $row){
            $result[$key]->job_name = ($row->job_number) ? $row->job_name .' # '.$row->job_number : $row->job_name;
            $result[$key]->paid_date = convert_timezone($row->paid_date);
            $result[$key]->confirmed_date = convert_timezone($row->confirmed_date);
            $result[$key]->loading_time = convert_timezone($row->loading_time , true);
            $result[$key]->delivery_time = convert_timezone($row->delivery_time , true);
            $result[$key]->delivered_fulldate = convert_timezone($row->delivered_fulldate , true);
            $result[$key]->time_of_arrival = convert_timezone($row->time_of_arrival , true);
            $result[$key]->invoice_date = convert_timezone($row->invoice_date );
            $result[$key]->updated = convert_timezone($row->updated , true);

            $result[$key]->total_price_raw = round($row->total_price , 2);
            $result[$key]->price_raw = round($row->price, 2);
            $result[$key]->vat_raw = round($row->vat, 2);
            $result[$key]->demurrage_raw = round($row->demurrage, 2);
            $result[$key]->status_raw = invoice_status( $row->status  , true);
            $result[$key]->paid_status_raw = paid_status($row->paid_by , true , $result[$key]->status_raw);

            $result[$key]->total_price = formatMoney($row->total_price);
            $result[$key]->status = invoice_status($row->status);
            $result[$key]->paid_status = paid_status($row->paid_by, false , $result[$key]->status_raw);
            $result[$key]->price = formatMoney($row->price);
            $result[$key]->vat = formatMoney($row->vat);
            $result[$key]->demurrage = formatMoney($row->demurrage);
        }

        return $result;
    }

    public function getSalesForBox2($company_id = 0 , $customer = false , $store_id = false){
        $from = strtotime("-1 month midnight");
        $to = strtotime("today 23:59");

        $week = array(
            "1" => array(
                "from" => $to - 86400,
                "to" => $to - 1
                ),
            "2" => array(
                "from" => $to - (86400 * 2),
                "to" => $to - (86400 - 1)
                ),
            "3" => array(
                "from" => $to - (86400 * 3),
                "to" => $to - ((86400 * 2) - 1),
                ),
            "4" => array(
                "from" => $to - (86400 * 4),
                "to" => $to - ((86400 * 3) - 1),
                ),
            "5" => array(
                "from" => $to - (86400 * 5),
                "to" => $to - ((86400 * 4) - 1),
                ),
            "6" => array(
                "from" => $to - (86400 * 6),
                "to" => $to - ((86400 * 5) - 1),
                )
            );

        $val = $this->getSales(array("from" => $from , "to" => $to) , $company_id , "default" , $customer , $store_id);

        $total_sales["total"] = formatMoney($val);

        foreach($week as $key => $row){
            $total_sales["chart"][] = round(  $this->getSales($row , $company_id , "default" , $customer , $store_id)   , 2);
        }

        $total_sales["chart"] = implode(",", $total_sales["chart"]);

        $data["total_sales"] = $total_sales;

        $total_sales = array();


        $val = $this->getSales(array("from" => $from , "to" => $to) , $company_id , "paid" , $customer , $store_id);

        $total_sales["total"] = formatMoney($val);

        foreach($week as $key => $row){
            $total_sales["chart"][] = round($this->getSales($row , $company_id , "paid" , $customer , $store_id) , 2);
        }
        $total_sales["chart"] = implode(",", $total_sales["chart"]);

        $data["paid_sales"] = $total_sales;

        $total_sales = array();



        $val = $this->getSales(array("from" => $from , "to" => $to) , $company_id , "unpaid" , $customer , $store_id);

        $total_sales["total"] = formatMoney($val);

        foreach($week as $key => $row){
            $total_sales["chart"][] = round($this->getSales($row , $company_id , "unpaid" , $customer , $store_id) , 2);
        }
        $total_sales["chart"] = implode(",", $total_sales["chart"]);
        $data["unpaid_sales"] = $total_sales;

        return $data;
    }

    public function getSalesForBox($company_id = 0 , $customer = false , $store_id = false){
        /*FIRST BOX */
        $from = strtotime("today midnight");
        $to = strtotime("today 23:59");

        $today_array = array(
            "1" => array(
                "from" => $to - 86400,
                "to" => $to - 1
                ),
            "2" => array(
                "from" => $to - (86400 * 2),
                "to" => $to - (86400 - 1)
                ),
            "3" => array(
                "from" => $to - (86400 * 3),
                "to" => $to - ((86400 * 2) - 1),
                ),
            "4" => array(
                "from" => $to - (86400 * 4),
                "to" => $to - ((86400 * 3) - 1),
                ),
            "5" => array(
                "from" => $to - (86400 * 5),
                "to" => $to - ((86400 * 4) - 1),
                ),
            "6" => array(
                "from" => $to - (86400 * 6),
                "to" => $to - ((86400 * 5) - 1),
                )
            );

        $val = $this->getSales(array("from" => $from , "to" => $to) , $company_id , "default" , $customer , $store_id);

        $total_sales["total"] = formatMoney($val);

        foreach($today_array as $key => $row){
            $total_sales["chart"][] = round(  $this->getSales($row , $company_id , "default" , $customer , $store_id)   , 2);
        }

        $total_sales["chart"] = implode(",", $total_sales["chart"]);
        $total_sales['date'] = convert_timezone($from);

        $data["first_box"] = $total_sales;

        /*SECOND BOX */

        $day = date('D' , time());

        if($day == 'Sun'){
            $from = strtotime('Monday last week midnight');
            $to = strtotime('Monday this week midnight -1 seconds');
        }else{
            $from = strtotime('Monday this week midnight');
            $to = strtotime('Monday next week midnight -1 seconds');
        }

        $week_array = array(
            "1" => array(
                "from" => $to - 604800,
                "to" => $to - 1
                ),
            "2" => array(
                "from" => $to - (604800 * 2),
                "to" => $to - (604800 - 1)
                ),
            "3" => array(
                "from" => $to - (604800 * 3),
                "to" => $to - ((604800 * 2) - 1),
                ),
            "4" => array(
                "from" => $to - (604800 * 4),
                "to" => $to - ((604800 * 3) - 1),
                ),
            "5" => array(
                "from" => $to - (604800 * 5),
                "to" => $to - ((604800 * 4) - 1),
                ),
            "6" => array(
                "from" => $to - (604800 * 6),
                "to" => $to - ((604800 * 5) - 1),
                )
            );

        $total_sales = array();


        $val = $this->getSales(array("from" => $from , "to" => $to) , $company_id , "paid" , $customer , $store_id);

        $total_sales["total"] = formatMoney($val);
        $total_sales['date'] = convert_timezone($from).' to '.convert_timezone($to);

         foreach($week_array as $key => $row){
            $total_sales["chart"][] = round($this->getSales($row , $company_id , "paid" , $customer , $store_id) , 2);
        }
        $total_sales["chart"] = implode(",", $total_sales["chart"]);

        $data["second_box"] = $total_sales;

        $from = strtotime('first day of this month midnight');
        $to = strtotime('first day of next month midnight -1 seconds');

        $month_array = array(
            "1" => array(
                "from" => strtotime('first day of last month midnight'),
                "to" => strtotime('first day of this month midnight -1 seconds')
                ),
            "2" => array(
                "from" => strtotime('first day of last 2 months midnight'),
                "to" => strtotime('first day of last month midnight -1 seconds')
                ),
            "3" => array(
                "from" => strtotime('first day of last 3 months midnight'),
                "to" => strtotime('first day of last 2 months midnight -1 seconds')
                ),
            "4" => array(
                "from" => strtotime('first day of last 4 months midnight'),
                "to" => strtotime('first day of last 3 months midnight -1 seconds')
                ),
            "5" => array(
                "from" => strtotime('first day of last 5 months midnight'),
                "to" => strtotime('first day of last 6 months midnight -1 seconds')
                ),
            "6" => array(
                "from" => strtotime('first day of last 6 months midnight'),
                "to" => strtotime('first day of last 5 months midnight -1 seconds')
                )
            );

        $total_sales = array();

        $val = $this->getSales(array("from" => $from , "to" => $to) , $company_id , "unpaid" , $customer , $store_id);

        $total_sales["total"] = formatMoney($val);

        foreach($month_array as $key => $row){
            $total_sales["chart"][] = round($this->getSales($row , $company_id , "unpaid" , $customer , $store_id) , 2);
        }

        $total_sales["chart"] = implode(",", $total_sales["chart"]);
        $total_sales['date'] = convert_timezone($from).' to '.convert_timezone($to);
        $data["third_box"] = $total_sales;

        return $data;
    }

    public function getSales($data , $company_id , $type = "default" , $customer = false , $store_id = false){
        $this->db->select_sum("i.total_price");
        $this->db->join("jobs j" , "jobs_id = job_id");
        $this->db->join("jobs_parent" , "job_parent_id = parent_id");
        // $this->db->join("jobs_status js" , "js.jobs_status_id = j.status_id");
        // $this->db->where("js.status_type" , "finished");
        $this->db->where("i.invoice_date >=" , $data["from"])->where("i.invoice_date <=" , $data["to"]);
        $this->db->where("jobs_parent.company_id" , $company_id);
        $this->db->where("i.merge_id" , 0);

        if($this->session->userdata("account_type") == OUTSOURCE){
            $this->db->where("j.outsource_company_name" , $this->session->userdata("outsource_id"));
            $this->db->where("i.to_outsource" , "NO");
        }

        if($customer){
            $this->db->where("jobs_parent.customer_id" , $this->session->userdata("customer_id"));
            $this->db->where("i.to_outsource" , "NO");
        }else{
            if($this->session->userdata("account_type") == SUPER_ADMIN){
                //$this->db->join("users_store us" , "us.account_id = jobs_parent.account_id" );
                //$this->db->where("us.store_id" , $store_id);
            }
        }
        
        $val = $this->db->get("invoice i")->row_array();

        return $val["total_price"];
    }

    public function getTransactionLogs(){
        $this->db->select("it.invoice_transaction_id , it.total_price , it.paid_by , it.paid_date , it.notes , it.created , it.cheque_number");
        $this->db->select("CONCAT(a.name , ' ' , a.surname) as name");
        $this->db->join("accounts a" , "a.id = it.account_id");
        $this->db->where("a.company_id" , $this->session->userdata("company_id"));

        if($this->session->userdata("account_type") != SUPER_ADMIN){
            $this->db->join("users_store us" , "us.account_id = it.account_id" );
            $this->db->where("us.store_id" , $this->session->userdata("store_id"));
            $this->db->where("it.account_id" , $this->session->userdata("id"));
        }


        if($this->input->get("submit")){
            
            if($this->input->get("transaction_id")){
                $this->db->where("it.invoice_transaction_id" , $this->input->get("transaction_id"));
            }
            if($this->input->get("paid_by")){
                $this->db->where("it.paid_by" , $this->input->get("paid_by"));
            }
            if($this->input->get("cheque_number")){
                $this->db->where("it.cheque_number" , $this->input->get("cheque_number"));
            }
            if($this->input->get("paid_date_from") AND $this->input->get("paid_date_to")){
                $from = strtotime($this->input->get("paid_date_from").' midnight');
                $to = strtotime($this->input->get("paid_date_to").' 23:59');

                $this->db->where("it.paid_date >=" , $from);
                $this->db->where("it.paid_date <=" , $to);
            }
        }


        $this->db->order_by("it.created" , "DESC");
        $result = $this->db->get("invoice_transaction it")->result();
        
        foreach($result as $key => $row){
            $result[$key]->created = convert_timezone($row->created);
            $result[$key]->paid_date = convert_timezone($row->paid_date);
            $result[$key]->paid_by = paid_status($row->paid_by , false , "COMPLETE");
            $result[$key]->total_price = formatMoney($row->total_price);
        }

        return $result;
    }

    private function generateMergeInvoice($jobs , $transaction_id = 0){
        
        if($jobs){
            $data['result'] = $jobs;
            $data['transaction_id'] = $transaction_id;
            $data['price'] = 0;
            $data['vat'] = 0;
            $data['total_price'] = 0;

            foreach($jobs as $row){
                $data['price'] += $row->price;
                $data['vat'] += $row->vat;
                $data['total_price'] += $row->total_price;
            }
            if($path = generatePdfToLocal($data , $jobs[0]->parent_id , $jobs[0]->jobs_id , true)){
                return $path;
            }
        }
    }

    public function add_invoice(){
        $arr = array(
            "customer_id" => $this->input->post("company_name") ,
            "company_id" => $this->session->userdata("company_id"),
            "paid_by" => $this->input->post("paid_by") ,
            "invoice_date" => strtotime($this->input->post("invoice_date")) ,
            "paid_date" => strtotime($this->input->post("paid_date")) ,
            "demurrage" => $this->input->post("demurrage") ,
            "price" => $this->input->post("price") ,
            "vat" => $this->input->post("vat") ,
            "total_price" => $this->input->post("total_price") ,
            "notes" => $this->input->post("notes") ,
            "cheque_number" => $this->input->post("cheque_number") ,
            "i_type" => "MANUAL" ,
            "invoice_number" => $this->input->post("invoice_no" , TRUE),
            "job_number" => $this->input->post("job_no" , TRUE) ,
            "jpo_number" => $this->input->post("jpo_number" , TRUE) ,
        );

        if($this->input->post("paid_by") != "UNPAID"){
            $arr['confirmed_by'] = $this->session->userdata("id");
            $arr['confirmed_date'] = time();
            $arr['status'] = 2;
        }

        $this->db->insert("invoice" , $arr);
        $invoice_id = $this->db->insert_id();

        // if(!$this->input->post("invoice_no")){
        //     $this->db->where("invoice_id" , $invoice_id )->update("invoice" , ["invoice_number" => $invoice_id]);
        // }

        if($this->input->post("paid_by") != "UNPAID"){

             $this->db->insert("invoice_transaction" , array(
                "paid_by" => $this->input->post("paid_by", TRUE) , 
                "paid_date" => strtotime($this->input->post("paid_date", TRUE)) ,
                "notes" => nl2br($this->input->post("notes", TRUE)) ,
                "total_price" => $this->input->post("total_price", TRUE),
                "cheque_number" => $this->input->post("cheque_number", TRUE),
                "account_id" => $this->session->userdata("id"),
                "created" => time()
            ));

            $transaction_id = $this->db->insert_id();

            $this->db->insert("invoice_transaction_invoices" , array(
                    "invoice_transaction_id" => $transaction_id ,
                    "invoice_id" => $invoice_id
                    ));

        }

        $this->add_invoice_history($invoice_id);

        $this->do_upload($invoice_id , 'file');
        $this->do_upload($invoice_id ,'bank_slip');

        return $invoice_id;
    }

    public function do_upload($id , $file = 'file'){
        $year = date("Y");
        $month = date("m");

        $path = './public/upload/invoice/'.$year.'/'.$month.'/';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $config['upload_path']          = $path;
        $config['allowed_types']        = 'gif|jpg|png';

        $data = (@$_FILES[$file]) ? $_FILES[$file] : array();

        if(!$data){
            return false;
        }

        $this->load->library('upload', $config);
        $this->load->library('image_lib');

        if(!empty($_FILES[$file]['name'])){
            $filesCount = count($_FILES[$file]['name']);

            for($i = 0; $i < $filesCount; $i++){
                $_FILES[$file]['name'] = $data['name'][$i];
                $_FILES[$file]['type'] = $data['type'][$i];
                $_FILES[$file]['tmp_name'] = $data['tmp_name'][$i];
                $_FILES[$file]['error'] = $data['error'][$i];
                $_FILES[$file]['size'] = $data['size'][$i];

                $config['file_name'] = $id.'_'.time().'_'.$data['name'][$i];

                $this->upload->initialize($config);

                if ( $this->upload->do_upload($file)){
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
                        "invoice_id" => $id ,
                        "image" => $year.'/'.$month.'/'.$image['file_name'] ,
                        "image_thumb" => $year.'/'.$month.'/'.$thumb_name ,
                        "image_type" => ($file == 'file') ? "INVOICE" : "BANK_SLIP"
                        );

                    $this->db->insert('invoice_images' , $arr);
                }
            }//end for loop

        }//end if

    }

    public function update_invoice(){

        $invoice_id = $this->input->post("invoice_id");

        $arr = array(
            "customer_id" => $this->input->post("company_name") ,
            "paid_by" => $this->input->post("paid_by") ,
            "invoice_date" => strtotime($this->input->post("invoice_date")) ,
            "paid_date" => strtotime($this->input->post("paid_date")) ,
            "demurrage" => $this->input->post("demurrage") ,
            "price" => $this->input->post("price") ,
            "vat" => $this->input->post("vat") ,
            "total_price" => $this->input->post("total_price") ,
            "notes" => $this->input->post("notes") ,
            "cheque_number" => $this->input->post("cheque_number") ,
            "invoice_number" => $this->input->post("invoice_no" , TRUE),
            "job_number" => $this->input->post("job_no" , TRUE),
            "jpo_number" => $this->input->post("jpo_number" , TRUE)
        );

        if($this->input->post("merge") == "Y"){
            $arr["jmerge_name"] = $this->input->post("jmerge_name");
        }else{
            $this->db->where("jobs_id" , $this->input->post("job_id"))->update("jobs" , ["job_name" => $this->input->post("jmerge_name")]);
        }

        if($this->input->post("paid_by") != "UNPAID"){
            $arr['confirmed_by'] = $this->session->userdata("id");
            $arr['confirmed_date'] = time();
            $arr['status'] = 2;
        }

        $this->db->where("invoice_id" , $invoice_id)->update("invoice" , $arr);

        $this->add_invoice_history($invoice_id);

        $this->do_upload($invoice_id , 'file');
        $this->do_upload($invoice_id ,'bank_slip');

        return $invoice_id;
    }

}