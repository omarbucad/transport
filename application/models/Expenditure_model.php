<?php

class Expenditure_model extends CI_Model {

    public function expenditureList(){

        $this->db->trans_start();

        if($exp = $this->input->get("exp_number")){
            $this->db->where("e.exp_number", $exp);
        }

        if($category_id = $this->input->get("category_id")){
            $this->db->where("e.category_id", $category_id);
        }

        if($type = $this->input->get("paid_by")){
            $this->db->where("e.exp_type_id", $type);
        }

        if($this->input->get('created_f') AND $this->input->get('created_to')){
              $from = strtotime($this->input->get('created_f').' 00:00 ');
              $to = strtotime($this->input->get('created_to').' 23:59 ');

              $this->db->where('e.created >=' , $from);
              $this->db->where('e.created <=' , $to);
        }


        $this->db->select("e.*, c.category_name, t.payment_type_name");
        $this->db->join("exp_category c", "c.category_id = e.category_id");
        $this->db->join("exp_payment_type t", "t.payment_type_id = e.exp_type_id");
        $this->db->where("e.deleted IS NULL");
        $result = $this->db->order_by("e.exp_id","DESC")->get("expenditure e")->result();

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
            foreach ($result as $key => $value) {
                $result[$key]->created = convert_timezone($value->created);
                $result[$key]->invoice_date = convert_timezone($value->invoice_date);
                $result[$key]->discount = formatMoney($value->discount);
                $result[$key]->vat = formatMoney($value->vat);
                $result[$key]->subtotal = formatMoney($value->subtotal);
                $result[$key]->total = formatMoney($value->total);

                if($value->paid_date != ''){
                    $result[$key]->paid_date = convert_timezone($value->paid_date);
                }
            
                $this->db->select('es.*, a.name, a.surname');
                $this->db->join("accounts a","a.id = es.updated_by");

                // search
                if($status = $this->input->get("status")){
                    $this->db->where("es.status");
                }

                $result[$key]->statuses = $this->db->where("es.exp_id",$value->exp_id)->order_by("es.created", "DESC")->get("exp_status es")->result();
                foreach ($result[$key]->statuses as $row => $val) {
                    $result[$key]->statuses[$row]->created = convert_timezone($val->created);
                    $result[$key]->statuses[$row]->status = convert_paid_status($val->status);

                    $this->db->select("ei.*, a.name, a.surname");
                    $this->db->join("accounts a","a.id = ei.uploaded_by");

                    $result[$key]->statuses[$row]->images = $this->db->where("ei.exp_status_id",$val->paid_status_id)->get("expenditure_images ei")->result();
                }
            }

            return $result;
        }        
    }

     public function get_expenditure($exp_id){

        $this->db->trans_start();

        $this->db->select("e.*, c.category_name, t.payment_type_name");
        $this->db->join("exp_category c", "c.category_id = e.category_id");
        $this->db->join("exp_payment_type t", "t.payment_type_id = e.exp_type_id");
        $this->db->where("exp_id",$exp_id);
        $result = $this->db->get("expenditure e")->row();

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
            $result->discount_raw = $result->discount;
            $result->vat_raw = $result->vat;
            $result->subtotal_raw = $result->subtotal;
            $result->total_raw = $result->total;
            $result->created = convert_timezone($result->created);
            $result->invoice_date = convert_timezone($result->invoice_date);
            $result->discount = formatMoney($result->discount);
            $result->vat = formatMoney($result->vat);
            $result->subtotal = formatMoney($result->subtotal);
            $result->total = formatMoney($result->total);
            

            if($result->paid_date != ''){
                $result->paid_date = convert_timezone($result->paid_date);
            }
        
            $this->db->select('es.*, a.name, a.surname');
            $this->db->join("accounts a","a.id = es.updated_by");
            $result->statuses = $this->db->where("es.exp_id",$result->exp_id)->order_by("es.created", "DESC")->get("exp_status es")->result();
            foreach ($result->statuses as $row => $val) {
                $result->statuses[$row]->created = convert_timezone($val->created);
                $result->statuses[$row]->status = convert_paid_status($val->status);

                $this->db->select("ei.*, a.name, a.surname");
                $this->db->join("accounts a","a.id = ei.uploaded_by");
                $result->statuses[$row]->images = $this->db->where("ei.exp_id",$val->exp_id)->get("expenditure_images ei")->result();
            }

            return $result;
        }        
    }

    public function categoryList(){

        $result = $this->db->get("exp_category")->result();
        return $result;
    }

    public function typeList(){

        $result = $this->db->get("exp_payment_type")->result();
        return $result;
    }

    public function add(){
        $this->db->trans_start();

        $add = $this->db->insert("expenditure", [
            "category_id" => $this->input->post("category"),
            "exp_type_id" => $this->input->post("paid_by"),
            "cheque_no" => ($this->input->post("cheque_no") == '') ? NULL : $this->input->post("cheque_no"),
            "subtotal" => $this->input->post("subtotal"),
            "vat" => ($this->input->post("vat") == '') ? 0.00 : $this->input->post("vat"),
            "discount" => ($this->input->post("discount") == '') ? 0.00 : $this->input->post("discount"),
            "total" => ($this->input->post("total") == '') ? 0.00 : $this->input->post("total"),
            "invoice_date" => strtotime($this->input->post("invoice_date")),
            "paid_date" => ($this->input->post("paid_date") == '') ? NULL : strtotime($this->input->post("paid_date")),
            "created" => time()
        ]);

        $id = $this->db->insert_id();

        if($add){
            $update = $this->db->where("exp_id", $id)->update("expenditure",[
                "exp_number" => date("dmY").'-'.sprintf('%06d', $id)
            ]);

            if($update){
                $status = $this->db->insert("exp_status",[
                    "status" => $this->input->post("status"),
                    "exp_id" => $id,
                    "updated_by" => $this->session->userdata('id'),
                    "notes" => $this->input->post("notes"),
                    "created" => time()
                ]);

                $status_id = $this->db->insert_id();
                if($status){
                    $this->do_upload($id, $status_id, 'file');
                    $this->do_upload($id, $status_id, 'bank_slip');
                }else{
                    return false;
                }
            }else{
                return false;
            }            
        }else{
            return false;
        }

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{

            $this->db->select("e.*, c.category_name, t.payment_type_name");
            $this->db->join("exp_category c", "c.category_id = e.category_id");
            $this->db->join("exp_payment_type t", "t.payment_type_id = e.exp_type_id");
            $result = $this->db->where("exp_id",$id)->get("expenditure e")->row();

            $result->created = convert_timezone($result->created);
            $result->invoice_date = convert_timezone($result->invoice_date);
            $result->discount = formatMoney($result->discount);
            $result->vat = formatMoney($result->vat);
            $result->subtotal = formatMoney($result->subtotal);
            $result->total = formatMoney($result->total);

            if($result->paid_date != ''){
                $result->paid_date = convert_timezone($result->paid_date);
            }
        
            $this->db->select('es.*, a.name, a.surname');
            $this->db->join("accounts a","a.id = es.updated_by");
            $result->statuses = $this->db->where("es.exp_id",$result->exp_id)->order_by("es.created", "DESC")->get("exp_status es")->result();
            foreach ($result->statuses as $row => $val) {
                $result->statuses[$row]->created = convert_timezone($val->created);
                $result->statuses[$row]->status_lbl = convert_paid_status($val->status);

                $this->db->select("ei.*, a.name, a.surname");
                $this->db->join("accounts a","a.id = ei.uploaded_by");
                $result->statuses[$row]->images = $this->db->where("ei.exp_status_id",$val->paid_status_id)->get("expenditure_images ei")->result();
            }

            return $result;
        }
    }

    public function edit($exp_id){
        $this->db->trans_start();
        $this->db->where("exp_id", $exp_id);
        $update = $this->db->update("expenditure", [
            "category_id" => $this->input->post("category"),
            "exp_type_id" => $this->input->post("paid_by"),
            "cheque_no" => ($this->input->post("cheque_no") == '') ? NULL : $this->input->post("cheque_no"),
            "subtotal" => $this->input->post("subtotal"),
            "vat" => ($this->input->post("vat") == '') ? 0.00 : $this->input->post("vat"),
            "discount" => ($this->input->post("discount") == '') ? 0.00 : $this->input->post("discount"),
            "total" => ($this->input->post("total") == '') ? 0.00 : $this->input->post("total"),
            "invoice_date" => strtotime($this->input->post("invoice_date")),
            "paid_date" => ($this->input->post("paid_date") == '') ? NULL : strtotime($this->input->post("paid_date"))
        ]);


        if($update){
            $status = $this->db->insert("exp_status",[
                "status" => $this->input->post("status"),
                "exp_id" => $exp_id,
                "updated_by" => $this->session->userdata('id'),
                "notes" => $this->input->post("notes"),
                "created" => time()
            ]);

            $status_id = $this->db->insert_id();
            if($status){
                $this->do_upload($exp_id, $status_id, 'file');
                $this->do_upload($exp_id, $status_id, 'bank_slip');
            }else{
                return false;
            }      
        }else{
            return false;
        }

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{

            $this->db->select("e.*, c.category_name, t.payment_type_name");
            $this->db->join("exp_category c", "c.category_id = e.category_id");
            $this->db->join("exp_payment_type t", "t.payment_type_id = e.exp_type_id");
            $result = $this->db->where("exp_id",$exp_id)->get("expenditure e")->row();

            $result->created = convert_timezone($result->created);
            $result->invoice_date = convert_timezone($result->invoice_date);
            $result->discount = formatMoney($result->discount);
            $result->vat = formatMoney($result->vat);
            $result->subtotal = formatMoney($result->subtotal);
            $result->total = formatMoney($result->total);

            if($result->paid_date != ''){
                $result->paid_date = convert_timezone($result->paid_date);
            }
        
            $this->db->select('es.*, a.name, a.surname');
            $this->db->join("accounts a","a.id = es.updated_by");
            $result->statuses = $this->db->where("es.exp_id",$result->exp_id)->order_by("es.created", "DESC")->get("exp_status es")->result();
            foreach ($result->statuses as $row => $val) {
                $result->statuses[$row]->created = convert_timezone($val->created);
                $result->statuses[$row]->status_lbl = convert_paid_status($val->status);

                $this->db->select("ei.*, a.name, a.surname");
                $this->db->join("accounts a","a.id = ei.uploaded_by");
                $result->statuses[$row]->images = $this->db->where("ei.exp_status_id",$val->paid_status_id)->get("expenditure_images ei")->result();
            }

            return $result;
        }
    }

    public function delete($exp_id){
        $this->db->trans_start();
            $this->db->where("exp_id",$exp_id);
            $this->db->update("expenditure",[
                "deleted" => time(),
                "deleted_by" => $this->session->userdata('id')
            ]);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }



    public function do_upload($id, $status, $file){
        $year = date("Y");
        $month = date("m");

        $path = './public/upload/expenditure/'.$year.'/'.$month.'/';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            create_index_html($path);
        }

        $config['upload_path']    = $path;
        $config['allowed_types']  = 'gif|jpg|png';

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
                        "exp_id" => $id ,
                        "exp_status_id" => $status,
                        "uploaded_by" => $this->session->userdata('id'),
                        "image_path" => $year.'/'.$month,
                        "image_name" => $image['file_name'] ,
                        "image_thumb" => $year.'/'.$month.'/'.$thumb_name ,
                        "image_type" => ($file == 'file') ? "INVOICE" : "BANK_SLIP",
                        "created" => time()
                        );

                    $this->db->insert('expenditure_images' , $arr);
                }
            }//end for loop

        }//end if
    }
}