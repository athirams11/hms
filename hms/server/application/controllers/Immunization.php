<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Immunization extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/ImmunizationModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listImmunization()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["immunization_id"] = isset($post["immunization_id"]) ? $post["immunization_id"] : ''; 
	              $post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';     
	              $result      = $this->ImmunizationModel->listImmunization($post_data);
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
  
	 public function getImmunization()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["immunization_id"] = isset($post["immunization_id"]) ? $post["immunization_id"] : '';       
	              $result      = $this->ImmunizationModel->getImmunization($post_data);
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
  
	 public function saveImmunization()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["immunization_id"] = isset($post["immunization_id"]) ? $post["immunization_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              
              $post_data["patient_type_id"] = isset($post["patient_type_id"]) ? $post["patient_type_id"] : '';              
              $post_data["vaccine_name"] = isset($post["vaccine_name"]) ? $post["vaccine_name"] : '';
              $post_data["vaccine_age_id"] = isset($post["vaccine_age_id"]) ? $post["vaccine_age_id"] : '';
                            
              $post_data["vaccine_optional"] = isset($post["vaccine_optional"]) ? $post["vaccine_optional"] : 0;
              $post_data["vaccine_price_of_one_item"] = isset($post["vaccine_price_of_one_item"]) ? $post["vaccine_price_of_one_item"] : 0;
              $post_data["vaccine_order"] = isset($post["vaccine_order"]) ? $post["vaccine_order"] : 0;
              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : ''; 
		 
              $result = $this->ImmunizationModel->saveImmunization($post_data);
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
  
	 public function deleteImmunization()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["immunization_id"] = isset($post["immunization_id"]) ? $post["immunization_id"] : '';       
	              $result      = $this->ImmunizationModel->deleteImmunization($post_data);
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
