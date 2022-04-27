<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Insurance_payer extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/Insurance_payersModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listinsurance_payers()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	             // $post_data["insurance_payers_id"] = isset($post["insurance_payers_id"]) ? $post["iinsurance_payers_id"] : '';  
	                 
	             $post_data["start"] = isset($post["start"]) ? $post["start"] : '';     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : '';     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
	              
	              $result      = $this->Insurance_payersModel->listinsurance_payers($post_data);
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code	
	             log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));      
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;
	          
	      }
	  }

	 public function getinsurance_payers()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["insurance_payers_id"] = isset($post["insurance_payers_id"]) ? $post["insurance_payers_id"] : '';       
	              $result      = $this->Insurance_payersModel->getinsurance_payers($post_data);
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
	              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));     
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
     	  }
 	 }


	 
 	 public function saveinsurance_payers()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
               $post_data["insurance_payers_id"] = isset($post["insurance_payers_id"]) ? $post["insurance_payers_id"] : '';
	              $post_data["insurance_payers_eclaim_link_id"] = isset($post["insurance_payers_eclaim_link_id"]) ? $post["insurance_payers_eclaim_link_id"] : '';  
	              $post_data["insurance_payers_name"] = isset($post["insurance_payers_name"]) ? $post["insurance_payers_name"] : '';  
	              $post_data["insurance_payers_classification"] = isset($post["insurance_payers_classification"]) ? $post["insurance_payers_classification"] : '';  
	              $post_data["insurance_payers_status"] = isset($post["insurance_payers_status"]) ? $post["insurance_payers_status"] : '';  
	              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';  
	              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';    
		 
              $result = $this->Insurance_payersModel->saveinsurance_payers($post_data);
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
               log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));     
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;          
        }
    }

    public function deleteinsurance_payers()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["insurance_payers_id"] = isset($post["insurance_payers_id"]) ? $post["insurance_payers_id"] : '';       
	              $result      = $this->Insurance_payersModel->deleteinsurance_payers($post_data);
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
	               log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));      
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
     	  }
 	 }
  
	
  
  
}

?>
