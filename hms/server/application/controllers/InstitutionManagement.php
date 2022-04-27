<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InstitutionManagement extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/InstitutionManagementModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 public function options()
   	{       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);  
               $result["country"]      = $this->MasterModel->master_dropdown_listing("COUNTRY C","C.*","C.COUNTRY_NAME","ASC","C.COUNTRY_STATUS = 1");


              log_activity('API-InstitutionManagement-options',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              $result["status"] = "Success";
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
	 public function listInstitution()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	             // $post_data["diagnosis_id"] = isset($post["diagnosis_id"]) ? $post["diagnosis_id"] : '';     
	              $post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : ''; 

   
	              $result      = $this->InstitutionManagementModel->listInstitution($post_data);
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code	
	              log_activity('API-InstitutionManagement-listInstitution',"Message : ".json_encode($result),"Posted Data :".json_encode($post));             
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;
	          
	      }
	 }
  
	 public function getInstitution()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["hospital_id"] = isset($post["hospital_id"]) ? $post["hospital_id"] : '';       
	              $result      = $this->InstitutionManagementModel->getInstitution($post_data);
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
	               log_activity('API-InstitutionManagement-getInstitution',"Message : ".json_encode($result),"Posted Data :".json_encode($post));    
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
     	  }
 	 }
  
	 public function saveInstitution()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["hospital_id"] = isset($post["hospital_id"]) ? $post["hospital_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              
              $post_data["hospital_name"] = isset($post["hospital_name"]) ? $post["hospital_name"] : '';
              $post_data["hospital_address"] = isset($post["hospital_address"]) ? $post["hospital_address"] : '';
              $post_data["hospital_phone"] = isset($post["hospital_phone"]) ? $post["hospital_phone"] : '';
              $post_data["hospital_city"] = isset($post["hospital_city"]) ? $post["hospital_city"] : '';
              $post_data["hospital_country"] = isset($post["hospital_country"]) ? $post["hospital_country"] : ''; 
              $post_data["hospital_logo"] = isset($post["hospital_logo"]) ? $post["hospital_logo"] : ''; 
              $post_data["hospital_email"] = isset($post["hospital_email"]) ? $post["hospital_email"] : ''; 

              $post_data["dhpo_name"] = isset($post["dhpo_name"]) ? $post["dhpo_name"] : ''; 
              $post_data["dhpo_id"] = isset($post["dhpo_id"]) ? $post["dhpo_id"] : ''; 
              $post_data["dhpo_password"] = isset($post["dhpo_password"]) ? $post["dhpo_password"] : ''; 
		 
              $result = $this->InstitutionManagementModel->saveInstitution($post_data);
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
               log_activity('API-InstitutionManagement-saveInstitution',"Message : ".json_encode($result),"Posted Data :".json_encode($post));    
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;          
        }
    }
   public function saveCustomDate()
   {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["hospital_id"] = isset($post["hospital_id"]) ? $post["hospital_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';

              $post_data["custom_date"] = isset($post["custom_date"]) ? $post["custom_date"] : ''; 
              $post_data["custom_date_status"] = isset($post["custom_date_status"]) ? $post["custom_date_status"] : ''; 
     
              $result = $this->InstitutionManagementModel->saveCustomDate($post_data);
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
               log_activity('API-InstitutionManagement-saveInstitution',"Message : ".json_encode($result),"Posted Data :".json_encode($post));    
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
