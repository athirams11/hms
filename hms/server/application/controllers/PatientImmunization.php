<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PatientImmunization extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/PatientImmunizationModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listPatientImmunization()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
					$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
              $post_data["patient_immunization_id"] = isset($post["patient_immunization_id"]) ? $post["patient_immunization_id"] : 0;
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;              
	              $result      = $this->PatientImmunizationModel->listPatientImmunization($post_data);
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
  
	 public function getPatientImmunization()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["patient_immunization_id"] = isset($post["patient_immunization_id"]) ? $post["patient_immunization_id"] : '';
	              
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
		           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
	              $post_data["patient_immunization_id"] = isset($post["patient_immunization_id"]) ? $post["patient_immunization_id"] : 0;
                     
	              $result  = $this->PatientImmunizationModel->getPatientImmunization($post_data);
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
  
	 public function savePatientImmunization()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
                  
              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
	           
	           $post_data["vaccine_optional"] = isset($post["vaccine_optional"]) ? $post["vaccine_optional"] : 0;
              $post_data["patient_immunization_id"] = isset($post["patient_immunization_id"]) ? $post["patient_immunization_id"] : "";
              $post_data["immunization_ids"] = isset($post["immunization_ids"]) ? $post["immunization_ids"] : "";
              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : "";

              $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';
              $post_data["date"] = isset($post["date"]) ? $post["date"] : "";
              		 
              $result = $this->PatientImmunizationModel->savePatientImmunization($post_data);
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
  
	 public function deletePatientImmunization()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["patient_immunization_id"] = isset($post["patient_immunization_id"]) ? $post["patient_immunization_id"] : '';       
	              $result      = $this->PatientImmunizationModel->deletePatientImmunization($post_data);
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
