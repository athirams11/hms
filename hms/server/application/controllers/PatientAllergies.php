<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PatientAllergies extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/PatientAllergiesModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listPatientAllergies()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
				  $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
              $post_data["patient_allergies_id"] = isset($post["patient_allergies_id"]) ? $post["patient_allergies_id"] : 0;
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;              
	              $result      = $this->PatientAllergiesModel->listPatientAllergies($post_data);
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
  
	 public function getPatientAllergies()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
		           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
      	        $post_data["patient_allergies_id"] = isset($post["patient_allergies_id"]) ? $post["patient_allergies_id"] : 0;       
	              $result  = $this->PatientAllergiesModel->getPatientAllergies($post_data);
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
  
	 public function savePatientAllergies()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
                  
              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
	           $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';            
	           
              $post_data["patient_allergies_id"] = isset($post["patient_allergies_id"]) ? $post["patient_allergies_id"] : 0;              					
              $post_data["patient_no_known_allergies"] = isset($post["patient_no_known_allergies"]) ? $post["patient_no_known_allergies"] : '';              
              $post_data["patient_drug_allergies_generic_name"] = isset($post["patient_drug_allergies_generic_name"]) ? $post["patient_drug_allergies_generic_name"] : '';
              $post_data["patient_drug_allergies_brand_name"] = isset($post["patient_drug_allergies_brand_name"]) ? $post["patient_drug_allergies_brand_name"] : '';
              
              $post_data["patient_other_allergies_detail_id"] = isset($post["patient_other_allergies_detail_id"]) ? $post["patient_other_allergies_detail_id"] : '';
              $post_data["patient_other_allergies_item"] = isset($post["patient_other_allergies_item"]) ? $post["patient_other_allergies_item"] : '';
              
		 		$post_data["date"] = isset($post["date"]) ? $post["date"] : '';   
		 		$post_data["timezone"] = isset($post["timezone"]) ? $post["timezone"] : '';   


              $result = $this->PatientAllergiesModel->savePatientAllergies($post_data);
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
  
	 public function deletePatientAllergies()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["patient_allergies_id"] = isset($post["patient_allergies_id"]) ? $post["patient_allergies_id"] : '';       
	              $result      = $this->PatientAllergiesModel->deletePatientAllergies($post_data);
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
