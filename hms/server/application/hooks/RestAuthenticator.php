<?php
/**
 * 
 */
class RestAuthenticator 
{
	
	function requestChecker(){
        
        $CI = & get_instance();

        $header = $CI->input->request_headers();
        //$header = $_SERVER;
        //print_r($header);
        $header = array_change_key_case($header,CASE_UPPER);
        //echo "<pre>";print_r($CI->input->request_headers());
        // && $class != "Login"
        $class = $CI->router->fetch_class();
        $methode = $CI->input->method(TRUE);
        $post  = json_decode(file_get_contents("php://input"),true); 
       
        if($class != "ApiAuth" && $methode != "OPTIONS")
        {
        	
	        $CI->load->model("api/ApiModel");
		    if($header[strtoupper(API_KEY)] == API_KEY_VALUE)
		    {
		    	
		        if($class != "Login"  && strtolower($class) != 'consent')
        		{
			        $auth = explode(" ",$header["AUTHORIZATION"]);
			        $mode = $auth[0];
			        $key = base64_decode($auth[1]);
			        //$key = $auth[1];
			        $key_array = explode(":",$key);
			       // print_r($key_array);
			        if(!check_api_user_auth($key_array[0],$key_array[1]))
			        {
			        	redirect(base_url()."ApiAuth/error_501");
			        }
			        log_activity_save('API',$post,$key_array[0]);
			    }
		    }
		    else
		    {
		    	 redirect(base_url()."ApiAuth/error_500");
		    	// $result     = array("Status"=> false, "response"=>"Invalid authentication key", "message"=>"Failed");
      			//  		$CI->ApiModel->response($result,'200');
      			// 		exit;
		    }
        }
    }
}
?>