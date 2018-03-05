<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('cut_str'))
{
    function cut_str($str , $limit = 10)
    {
        if (strlen($str) > $limit){
            return substr($str, 0, $limit) . '...';
        }else{
            return $str;
        }
        
    }
}
if ( ! function_exists('warehouse_status'))
{
    function warehouse_status($type)
    {
        switch ($type) {
            case "TO BE LOADED":
                return '<span class="label label-info">To Be Loaded</span>';
                break;
            case "TO BE UNLOADED":
                return '<span class="label label-info">To be Unloaded</span>';
                break;
            case 'UNLOADED':
                return '<span class="label label-info">Unloaded</span>';
                break;
            case 'LOADED':
                return '<span class="label label-info">Loaded</span>';
                break;    
            case 'PICKED UP':
                return '<span class="label label-info">Picked Up</span>';
                break;    
            default:
                return '<span class="label label-info">'.$type.'</span>';
                break;
        }
    }   
}

if ( ! function_exists('account_status'))
{
    function account_status($type)
    {
        switch ($type) {
        	case 1:
        		return '<span class="label label-success">Active</span>';
        		break;
        	case 0:
        		return '<span class="label label-danger">Inactive</span>';
        		break;
        	default:
        		# code...
        		break;
        }
    }   
}

if ( ! function_exists('status_type'))
{
    function status_type($data)
    {
        switch ($data->status_type) {
            case "cancel":
                return $data->job_name." has been cancelled";
                break;
            case "new":
                return $data->company_name.' created new Job ';
                break;
            case 'to_be_allocated':
                return $data->job_name. " has been booked " ;
                break;
            case 'allocated':
                return $data->job_name. " has been allocated " ;
                break;
            case 'partially_finished':
                return $data->job_name. " has been delivered " ;
                break;
            case 'finished':
                return $data->job_name. " has been Completed " ;
                break;
            default:
                # code...
                break;
        }
    }   
}

if ( ! function_exists('invoice_status'))
{
    function invoice_status($type , $raw = false)
    {
        if($raw){
            switch ($type) {
                case 2:
                    return 'COMPLETE';
                    break;
                case 1:
                    return 'NEED CONFIRMATION';
                    break;
                case 0:
                    return 'INCOMPLETE';
                    break;
                default:
                    # code...
                    break;
            }
        }else{
            switch ($type) {
                case 2:
                    return '<span class="label label-success">COMPLETE</span>';
                    break;
                case 1:
                    return '<span class="label label-info">NEED CONFIRMATION</span>';
                    break;
                case 0:
                    return '<span class="label label-warning">INCOMPLETE</span>';
                    break;
                default:
                    # code...
                    break;
            }
        }
    }   
}

if ( ! function_exists('paid_status'))
{
    function paid_status($type , $raw = false , $confirmed = false)
    {
        if($raw){
            switch ($type) {
                case "BANK_TRANSFER":
                    if($confirmed == "COMPLETE"){
                        return "BANK TRANSFER RECIEVED";
                    }else{
                        return "BANK TRANSFER UNCONFIRMED";
                    }

                    break;
                case "PETTY_CASH":
                    if($confirmed == "COMPLETE"){
                        return "PETTY CASH RECIEVED";
                    }else{
                         return "PETTY CASH UNCONFIRMED";
                    }
                    break;
                case "PAID_BY_CHEQUE":
                    if($confirmed == "COMPLETE"){
                        return "PAID BY CHEQUE RECIEVED";
                    }else{
                        return "PAID BY CHEQUE UNCONFIRMED";
                    }
                    break;
                case "UNPAID":
                    return 'UNPAID';
                    break;
                default:
                    # code...
                    break;
            }
        }else{
            switch ($type) {
                case "BANK_TRANSFER":
                    if($confirmed == "COMPLETE"){
                        return '<span class="label bg-green">BANK TRANSFER RECIEVED</span>';
                    }else{
                        return '<span class="label bg-light-green">BANK TRANSFER UNCONFIRMED</span>';
                    }
                    break;
                case "PETTY_CASH":
                   if($confirmed == "COMPLETE"){
                        return '<span class="label bg-blue">PETTY CASH RECIEVED</span>';
                    }else{
                        return '<span class="label bg-light-blue">PETTY CASH UNCONFIRMED</span>';
                    }
                    break;
                case "PAID_BY_CHEQUE":
                    if($confirmed == "COMPLETE"){
                        return '<span class="label bg-brown">PAID BY CHEQUE RECIEVED</span>';
                    }else{
                        return '<span class="label bg-grey">PAID BY CHEQUE UNCONFIRMED</span>';
                    }
                    break;
                case "UNPAID":
                    return '<span class="label label-danger">UNPAID</span>';
                    break;
                default:
                    # code...
                    break;
            }
        }
    }   
}

if ( ! function_exists('jobs_status'))
{
    function jobs_status($type , $label = false)
    {
        /*
            1 - Open
            2 - Booked
            3 - On Delivery
            4 - Delivered
            5 - Cancelled
        */


        if($label){
             switch ($type) {
                case 1:
                    return 'Open';
                break;
                case 2:
                    return 'Booked';
                break;
                case 3:
                    return 'On Delivery';
                break;
                case 4:
                    return 'Delivered';
                break;
                case 5:
                    return 'Cancelled';
                break;
            }

        }else{
            switch ($type) {
                case 1:
                    return 'bg-blue-grey';
                    break;
                case 2:
                    return 'bg-light-blue';
                    break;
                case 3:
                    return 'bg-blue';
                    break;
                case 4:
                    return 'bg-green';
                    break;
                case 5:
                    return 'bg-red';
                    break;
            }
        }
    }   
}

if ( ! function_exists('account_type'))
{
    function account_type($type)
    {
        switch ($type) {
        	case "ADMIN":
        		return '<span class="label bg-deep-purple">Admin</span>';
        		break;
        	case "SUPER ADMIN":
        		return '<span class="label bg-teal">Super Admin</span>';
        		break;
        	case "DRIVER":
        		return '<span class="label bg-deep-orange">Driver</span>';
        		break;
            case "MECHANIC":
                return '<span class="label bg-primary">Mechanic</span>';
                break;
            case "CUSTOMER":
                return '<span class="label bg-green">Customer</span>';
                break;
            case "OUTSOURCE":
                return '<span class="label bg-light-green">Outsouce</span>';
                break;
            case "WAREHOUSE":
                return '<span class="label bg-light-green">Warehouse</span>';
                break;
        	default:
        		# code...
        		break;
        }
    }   
}

if ( ! function_exists('status_notes'))
{
    function status_notes($type)
    {
        switch ($type) {
            case 1:
                return 'You Created a Job';
            break;
            case 2:
                return 'This Jobs has been booked by {company_name}';
            break;
            case 3:
                return 'Truck is on the way';
            break;
            case 4:
                return 'has been delivered';
            break;
            case 5:
                return 'Jobs has been Cancelled';
            break;
        }
    }   
}

if ( ! function_exists('full_path_image'))
{
    function full_path_image($image , $type)
    {
        return array(
            "path" => get_instance()->config->base_url("/public/upload/".$type."/".$image) ,
            "absolute_path" => FCPATH."/public/upload/".$type."/".$image
            );
    }   
}

if ( ! function_exists('print_r_die'))
{
    function print_r_die($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
    }   
}


if ( ! function_exists('custom_set_select'))
{
    function custom_set_select($index = false , $value , $custom_value = false)
    {
        if($custom_value){
            if($value == $custom_value){
                return "selected='selected'";
            }else{
                return false;
            }
        }else{
            if(isset($_GET[$index])){
                if($_GET[$index] == $value){
                    return "selected='selected'";
                }else{
                    return false;
                }
            }else{
                if(!$value){
                    return "selected='selected'";
                }else{
                    //return false;
                }
            }
        }

    }   
}

if ( ! function_exists('get_timezone'))
{
    function get_timezone()
    {
        $CI =& get_instance();

        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else{
          $ip=$_SERVER['REMOTE_ADDR'];
        }

        $check = $CI->db->where('ip_address' , $ip)->get('timezone')->row();

        if($check){
            return $check->timezone;
        }else{
            $ch = curl_init("http://freegeoip.net/json/".$ip);
            curl_setopt($ch, CURLOPT_URL , "http://freegeoip.net/json/".$ip); 
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);

            //$data = @file_get_contents("http://freegeoip.net/json/".$ip);
            $data = json_decode($output);

            $timezone =  ($data->time_zone) ? $data->time_zone : "Asia/Manila";

            $arr = array("ip_address" => $ip , "timezone" => $timezone);

            $CI->db->insert('timezone' , $arr);

            return $timezone;
        }
    }   
}

if ( ! function_exists('convert_timezone'))
{
    function convert_timezone($time , $with_hours = false , $with_timezone = true , $hour_only = false , $custom_format_date_with_hour = "M d Y h:i:s A" , $custom_format_date = "M d Y" , $custom_format_hour = "h:i:s A")
    {

        if(!$time OR $time == 0){
            return "NA";
        }

        if($with_timezone){
            //$timezone = get_timezone();
            $timezone = "Europe/London";

            if($with_hours){
                $date_format = $custom_format_date_with_hour;
            }else if($hour_only){
                $date_format = $custom_format_hour;
            }else{
                $date_format = $custom_format_date;
            }
            
            $triggerOn = date($date_format , $time);

            $tz = new DateTimeZone($timezone);
            $datetime = new DateTime($triggerOn);
            $datetime->setTimezone($tz);

            return $datetime->format( $date_format );
        }else{
            if($with_hours){
                $date_format = $custom_format_date_with_hour;
            }else if($hour_only){
                $date_format = $custom_format_hour;
            }else{
                $date_format = $custom_format_date;
            }

            return date($date_format , $time);
        }
    }   
}

if ( ! function_exists('report_type'))
{
    function report_type($type , $raw = false)
    {
        if($raw){
            switch ($type) {
                case 3:
                    return 'Fixed';
                    break;
                case 2:
                    return 'Under Maintenance';
                    break;
                case 1:
                    return 'Open';
                    break;
                case 0:
                    return 'New';
                    break;
                default:
                    # code...
                    break;
            }
        }else{
            switch ($type) {
                case 3:
                    return '<span class="label label-success">Fixed</span>';
                    break;
                case 2:
                    return '<span class="label label-warning">Under Maintenance</span>';
                    break;
                case 1:
                    return '<span class="label label-info">Open</span>';
                    break;
                case 0:
                    return '<span class="label label-blue">New</span>';
                    break;
                default:
                    # code...
                    break;
            }
        }
    }   
}


if ( ! function_exists('checklist_array'))
{
    function checklist_array($index)
    {
        switch ($index) {
            case 0:
                return "Lamps/indicators/stop lamps";
                break;
            case 1:
                return "Reflectors/markers/warning devices";
                break;
            case 2:
                return "Battery (security/condition)";
                break;
            case 3:
                return "Mirrors (clean/condition/security)";
                break;
            case 4:
                return "Brakes (Pressure/operation/leaks)";
                break;
            case 5:
                return "Driving control (Steering/wear/operation)";
                break;
            case 6:
                return "Tyres (Inflation/Any Damage/Wear)";
                break;
            case 7:
                return "Wheels/Nuts (Condition/Security)";
                break;
            case 8:
                return "Guards/Wings/Spray Suppression";
                break;
            case 9:
                return "Body/Load (Security/Protection)";
                break;
            case 10:
                return "Number Plates (Condition/Security/Bulbs)";
                break;
            case 11:
                return "Horn/Wipers/Washers/Windscreen";
                break;
            case 12:
                return "Engine Oil/Water (Fuel/level/leaks)";
                break;
            case 13:
                return "Fuel Cap On and Secure";
                break;
            case 14:
                return "Exhaust (condition/smoke/emission)";
                break;
            case 15:
                return "Tachograph/Speedometer Working";
                break;
            case 16:
                return "Speed Limiter Sticker Present";
                break;
            case 17:
                return "Trailer Coupling and Condition";
                break;
            case 18:
                return "Trailer Connection Wear/Leaks";
                break;
            case 19:
                return "Trailer Landing Legs Working";
                break;
            case 20:
                return "Spare Digital Roll";
                break;
            case 21:
                return "Height Indicator Set at Vehicle Height";
                break;
            case 22:
                return "Licence Disk Visible and In Date";
                break;
            case 23:
                return "Reversing Alarm Working";
                break;
            case 24:
                return "No Smoking Sticker in Cab";
                break;
            case 25:
                return "HIABS/Tipper Rams All Working";
                break;
            case 26:
                return "All Fors Cameras and Stickers Working";
                break;
            case 27:
                return "Brakes(warning devices and instruments)";
                break;
        }
    }   
}


if ( ! function_exists('checklist_array_trailer'))
{
    function checklist_array_trailer($index)
    {
        switch ($index) {
            case 0:
                return "Tyres & Wheel Nuts ";
                break;
            case 1:
                return "Pin/Coupling Condition";
                break;
            case 2:
                return "Spray Suppression";
                break;
            case 3:
                return "Anti-Under Run Bars";
                break;
            case 4:
                return "Condition of Curtains*";
                break;
            case 5:
                return "Condition of Curtain Straps*";
                break;
            case 6:
                return "Load Straps*";
                break;
            case 7:
                return "Mot Plate & Disc";
                break;
            case 8:
                return "Landing Legs";
                break;
            case 9:
                return "Roof Pole*";
                break;
            case 10:
                return "Doors, Hinges & Locks";
                break;
            case 11:
                return "Lights & Reflectors";
                break;
            case 12:
                return "Air Leaks";
                break;
            case 13:
                return "Brakes";
                break;
            case 14:
                return "Air/Electrical Couplings";
                break;
            case 15:
                return "Body Condition";
                break;
            case 16:
                return "Load & Security";
                break;
            case 17:
                return "Tir Cord*";
                break;
            case 18:
                return "Slats/Boards All Present*";
                break;
            case 19:
                return "Internal/External Bulkhead";
                break;
            case 20:
                return "Rear Marker Boards";
                break;
            case 21:
                return "Mud Wings & Stays";
                break;
            case 22:
                return "Spray Suspension";
                break;
            case 23:
                return "Number Plate Holder";
                break;
            case 24:
                return "Fire Extinguishers (ADR)*";
                break;
            case 25:
                return "Fridge Unit Operation*";
                break; 
        }
    }   
}

if ( ! function_exists('checklist_array_moffet'))
{
    function checklist_array_moffet($index)
    {
        switch ($index) {
            case 0:
                return "Obvious Leaks";
                break;
            case 1:
                return "Hydraulic Fluid Levels";
                break;
            case 2:
                return "Mast & Carriage";
                break;
            case 3:
                return "Chains & Fixing Bolts";
                break;
            case 4:
                return "Forks";
                break;
            case 5:
                return "Backrest/Extension";
                break;
            case 6:
                return "Attachments";
                break;
            case 7:
                return "Tyres/Wheels/Nuts";
                break;
            case 8:
                return "Seat & Seat Belts";
                break;
            case 9:
                return "Steering";
                break;
            case 10:
                return "Service Brakes";
                break;
            case 11:
                return "Parking Brake";
                break;
            case 12:
                return "Operating Sys Controls";
                break;
            case 13:
                return "Warning Lights";
                break;
            case 14:
                return "Gauges/Instruments";
                break;
            case 15:
                return "Lights Beacon";
                break;
            case 16:
                return "Horn";
                break;
            case 17:
                return "Alarms";
                break;
            case 18:
                return "Other Warning Devices";
                break;
            case 19:
                return "Safety Guards/Covers";
                break;
            case 20:
                return "Bodywork";
                break;
            case 21:
                return "Fuel Level*";
                break;
            case 22:
                return "Fuel Connections*";
                break;
            case 23:
                return "Engine Oil Levels*";
                break;
            case 24:
                return "Coolant Level*";
                break;
            case 25:
                return "Battery*";
                break;
            case 26:
                return "Fan/Other Belts*";
                break;
            case 27:
                return "Inching Pedal*";
                break;
            case 28:
                return "LPG Bottle Security*";
                break;
            case 29:
                return "Electrolyte Levels**";
                break;
            case 30:
                return "Cable Connections**";
                break;
            case 31:
                return "Battery Cleanliness**";
                break;
            case 32:
                return "Battery Security**";
                break;
           
        }
    }   
}

if ( ! function_exists('checklist_list'))
{
    function checklist_list($array , $implode = false , $trailer = false)
    {
        $temp = array();

        foreach($array as $row){
            if($trailer){
                $temp[] = checklist_array_trailer($row['checklist_index']);
            }else{
                $temp[] = checklist_array($row['checklist_index']);
            }
        }

        return ($implode) ? implode(" <br>", $temp) : $temp;
    }   
}

if ( ! function_exists('checklist_moffet'))
{
    function checklist_moffet($array , $implode = false )
    {
        $temp = array();

        foreach($array as $row){
            $temp[] = checklist_array_moffet($row['checklist_index']);
        }

        return ($implode) ? implode(" <br>", $temp) : $temp;
    }   
}



if( ! function_exists('generateRandomString')){
    
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if( ! function_exists('create_index_html')){
    
    function create_index_html($folder) {
        $content = "<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>";
        $folder = $folder.'/index.html';
        $fp = fopen($folder , 'w');
        fwrite($fp , $content);
        fclose($fp);
    }
}

if( ! function_exists('formatMoney')){
    
    function formatMoney($money , $locale = "en_GB" , $with_locale = true){

        if(!$money){
            $money = "0.00";
        }
        switch($locale){
            case "en_GB":
                $symbol = "&#163;";
            break;
        }
        
        $formatted = ($with_locale) ? $symbol : "";

        $formatted .= number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $money)), 2);
        return $money < 0 ? "({$formatted})" : "{$formatted}";
    }
}


if( ! function_exists('c_strtotime')){
    
    function c_strtotime($time , $raw = false){

        if(!$time){
            return 0;
        }
        
        $timezone = "Europe/London";
        $triggerOn = date('M d Y h:i:s A' , strtotime($time));

        $tz = new DateTimeZone($timezone);
        $datetime = new DateTime($triggerOn);
        $datetime->setTimezone($tz);

        if($raw){
            return $datetime->format( 'M d Y h:i:s A');
        }else{
            return strtotime($datetime->format( 'M d Y h:i:s A'));
        }
    }
}

if( ! function_exists('send_notification')){
    
    function send_notification($id , $type = "new_jobs" , $job_id = 1 , $arr = array()){
        $obj =& get_instance();

        $c = $obj->db->select("device_token")->where("account_id" , $id)->get("device_info")->result();

        if($c){

            if($type == "new_jobs"){
                $data['title'] = "You have A New Job";
                $data['body'] = "Click to view the job";
            }else if($type == "send_instructions"){
                $data['title'] = "You have New Instructions";
                $data['body']  = $arr['message'];
            }else{
                $data['title'] = "Your Truck has been Fixed";
                $data['body'] = "Click to view the Truck";
            }

            $headers = array(
                "Authorization:key=" . FIREBASE_SERVER_ID ,
                "Content-Type:application/json" 
            );

            foreach($c as $id){
                $fields = array("to" => $id->device_token , "notification" => $data);
                $payload = json_encode($fields);

                $curl_session = curl_init();
                curl_setopt($curl_session, CURLOPT_URL, FCM_PATH );
                curl_setopt($curl_session, CURLOPT_POST, true );
                curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers );
                curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true );
                curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
                curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload );

                $result = curl_exec($curl_session);
                curl_close($curl_session);
            }
            
        }
    }
}


if(!function_exists("generatePdf")){

    function generatePdf($htmlData , $transaction_id ,  $job_id ){
        $obj =& get_instance();

        $name = $transaction_id.'_'.$job_id.'_'.time().'.pdf';

        $year = date("Y");
        $month = date("m");
        $folder = "./public/pdf/".$year."/".$month.'/';

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            create_index_html($folder);
        }

        $obj->html2pdf->folder($folder);
        $obj->html2pdf->filename($name);

        $obj->html2pdf->paper('a4', 'portrait');

        //Load html view
        $obj->html2pdf->html($htmlData);

        return $obj->html2pdf->create('save');
    }
}

if(!function_exists("generatePdfToLocal")){
    
    function generatePdfToLocal($data , $transaction_id ,  $job_id , $merge = false ){
        $obj =& get_instance();
        $filename = "transport_app_invoice_".$transaction_id.'_'.$job_id.'_'.time().'.pdf';

        if($merge){
            if($path = $obj->pdf->create_invoice_merge($data , $filename)){
                return array(
                    "absolute_path" => FCPATH.$path  ,
                    "path" => $path 
                    );
            }
        }else{
 
            if($path = $obj->pdf->create_invoice($data , $filename)){
                return array(
                    "absolute_path" => FCPATH.$path  ,
                    "path" => $path 
                    );
            }
        }

        return false;
    }
}

if(! function_exists('httppost')){
    function httppost($url , $data){

        $curl = curl_init($url);
        
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}

if(!function_exists("downloadPdf")){

    function downloadPdf($folder , $path){
        //http://192.168.1.147/codeigniter//public/pdf/2017/06/1498807130.pdf
        file_put_contents($folder, fopen($path , 'r'));
    }
}

if(!function_exists("array2csv")){

    function array2csv($array)
    {
       if (count($array) == 0) {
         return null;
       }
       ob_start();
       $df = fopen("php://output", 'w');
       fputcsv($df, array_keys(reset($array)));
       foreach ($array as $row) {
          fputcsv($df, $row);
       }
       fclose($df);
       return ob_get_clean();
    }
}

if(!function_exists("download_send_headers")){

    function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }
}


if(!function_exists("fromNow")){

    function fromNow($time) {
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
        }
    }
}

if(!function_exists("ifNA")){

    function ifNA($data){
        return ($data) ? $data : "NA";
    }
}


if(!function_exists("send_message_to_developer")){

    function send_message_to_developer($heading, $body){
        $obj =& get_instance();

        $obj->load->library('email');

        $obj->email->from( DEFAULT_EMAIL , 'Trackerteer Web Developer');

        $obj->email->to('mhar0987@live.com'); 
        $obj->email->set_mailtype("html");

        $obj->email->subject($heading);
        $obj->email->message($body);  


        $obj->email->send();

        $obj->email->clear();
    }
}




    
    