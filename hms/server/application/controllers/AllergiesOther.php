<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AllergiesOther extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/AllergiesOtherModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listAllergiesOther()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["allergies_other_id"] = isset($post["allergies_other_id"]) ? $post["allergies_other_id"] : '';     
	              $result      = $this->AllergiesOtherModel->listAllergiesOther($post_data);
	             log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code	              
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;
	          
	      }
	 }
  
	 public function getAllergiesOther()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["allergies_other_id"] = isset($post["allergies_other_id"]) ? $post["allergies_other_id"] : '';       
	              $result      = $this->AllergiesOtherModel->getAllergiesOther($post_data);
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
	              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
     	  }
 	 }
  
	 public function saveAllergiesOther()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["allergies_other_id"] = isset($post["allergies_other_id"]) ? $post["allergies_other_id"] : 0;
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              
              $post_data["allergies_other_code"] = isset($post["allergies_other_code"]) ? $post["allergies_other_code"] : '';
              $post_data["allergies_other_name"] = isset($post["allergies_other_name"]) ? $post["allergies_other_name"] : '';
              $post_data["allergies_other_description"] = isset($post["allergies_other_description"]) ? $post["allergies_other_description"] : '';
              $post_data["allergies_other_order"] = isset($post["allergies_other_order"]) ? $post["allergies_other_order"] : '';
              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';
		 
              $result = $this->AllergiesOtherModel->saveAllergiesOther($post_data);
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;          
        }
    }
  
	 public function deleteAllergiesOther()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["allergies_other_id"] = isset($post["allergies_other_id"]) ? $post["allergies_other_id"] : '';       
	              $result      = $this->AllergiesOtherModel->deleteAllergiesOther($post_data);
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
	             log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
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
