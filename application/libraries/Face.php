<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Face {

	public $CI;
	public $session;

	function __construct( ) {
		$this->CI =& get_instance();	
		$this->session = $this->CI->session->userdata();
	}

	public function getFaceId($image_path , $image_name , $account_id = false , $action = "add_face"){
		$request = new Http_Request2(GET_FACE_URL);
        $url = $request->getUrl();

		$headers = array(
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => SUBSCRIPTION_KEY,
        );

        $request->setHeader($headers);

        $parameters = array(
		    'returnFaceId' => 'true',
		    'returnFaceLandmarks' => 'true',
		    'returnFaceAttributes' => '',
		);

        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_POST);

        $data = array("url" => $image_path);

        $request->setBody(json_encode($data));

        try{

		    $response = $request->send();
		    $queryResponse = array();

		    if(200 == $response->getStatus()){

		        $body = json_decode($response->getBody());
		       
		        if(!$body){
		        	return array("status" => false , "message" => "No Response");
		        }else if($body[0]->{'faceId'} == null){
		        	return array("status" => false , "message" => "No Response");
		        }else{
		        	$faceId = $body[0]->{'faceId'};

		        	switch ($action) {
						case 'add_face':
							return $this->add_face($faceId , $account_id , $image_path);
							break;
						case 'update_face':
							return $this->getFaceIdInDB($faceId , $account_id , $image_path);
							break;			
						default:
							return $this->similar_face($faceId);
							break;
					}
		        }   
		    }
		}catch (HttpException $ex){
		    return array("status" => false , "message" => $ex->getMessage());
		}

	}

	private function similar_face($face_id){
		$request = new Http_Request2(SIMILAR_URL);
        $url = $request->getUrl();

        $headers = array(
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => SUBSCRIPTION_KEY,
        );

        $request->setHeader($headers);

        $parameters = array();

        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_POST);

 		$data = array(
 			"faceId" => $face_id ,
 			"faceListId" => GROUP_KEY ,
 			"mode" => "matchPerson" ,
 			"maxNumOfCandidatesReturned" => 1
 			);

        $request->setBody(json_encode($data));

        try{
        	$response = $request->send();
			$queryResponse = array();
			$body = json_decode($response->getBody());

			if(isset($body->error)){
				return array("status" => false , "message" => "No Response");
			}else if(!$body[0]){
				return array("status" => false , "message" => "No Response");
			}else if( $body[0]->{'confidence'} < '0.6' ){
				return array("status" => false , "message" => "No Response");
			}else if($response->getStatus() != 200  && count($body) == 0){
				return array("status" => false , "message" => "No Response");
			}else{
				$persistedFaceId = $body[0]->{'persistedFaceId'};

				$result = $this->CI->db->select("id")->where("face_id" , $persistedFaceId)->get("accounts")->row();

				return ["status" => true , "account_id" => $result->id ];
			}

        }catch (HttpException $ex){
		  	return array("status" => false , "message" => $ex->getMessage());
		}

	}

	private function add_face($face_id , $account_id , $image_path){
		$request = new Http_Request2(FACE_URL);
        $url = $request->getUrl();

        $headers = array(
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => SUBSCRIPTION_KEY,
        );

        $request->setHeader($headers);

        $parameters = array(
            'faceListId' => GROUP_KEY ,
            'userData' => $account_id,
            );

        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_POST);

        $request->setBody(json_encode(["url" => $image_path]));

        try{
            $response = $request->send();
            
            $body = json_decode($response->getBody());
            
            if(!isset($body->error)){
            	$persistedFaceId = $body->{'persistedFaceId'};
            	$this->CI->db->where("id" , $account_id)->update("accounts" , ["face_id" => $persistedFaceId]);

            	return $persistedFaceId;

            }else{
            	return array("status" => false , "message" => "No Response");
            }

        }catch (HttpException $ex){
            return array("status" => false , "message" => "No Response");
        }
	
	}

	private function getFaceIdInDB($face_id , $account_id , $image_path){
		$response = $this->CI->db->select("face_id")->where("id" , $account_id)->get("accounts")->row();
		if($response){
			return $this->removed_face($response->face_id , $account_id , $face_id , $image_path);
		}else{
			return array("status" => false , "message" => "Error IN DB");
		}
	}

	private function removed_face($face_id_from_db , $account_id , $face_id , $image_path){

		if($face_id_from_db == NULL){
			return $this->add_face($face_id , $account_id , $image_path);
		}
		
		$request = new Http_Request2(REMOVE_FACE_URL);
        $url = $request->getUrl();
        
        $headers = array(
            'Ocp-Apim-Subscription-Key' => SUBSCRIPTION_KEY,
        );

        $request->setHeader($headers);

        $parameters = array(
            'faceListId' => GROUP_KEY ,
            'persistedFaceId' => $face_id_from_db,
            );

        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_DELETE);

        $request->setBody("{body}");

        try{
        	$response = $request->send();
			$body = json_decode($response->getBody());

			if(!isset($body->error)){

				$this->CI->db->where("id" , $account_id)->update("accounts" , ["face_id" => NULL]);

				return $this->add_face($face_id , $account_id , $image_path);
			}else{
				return array("status" => false , "message" => "No Response");
			}

        }catch(HttpException $ex){
        	return array("status" => false , "message" => $ex->getMessage());
        }
	}
}