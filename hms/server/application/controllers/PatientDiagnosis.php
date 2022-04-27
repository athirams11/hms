<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PatientDiagnosis extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/PatientDiagnosisModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function savePatientDiagnosis()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
                  
              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
	           $post_data["chief_complaint_id"] = isset($post["chief_complaint_id"]) ? $post["chief_complaint_id"] : 0;            
	           
              $post_data["patient_diagnosis_id"] = isset($post["patient_diagnosis_id"]) ? $post["patient_diagnosis_id"] : 0;              					
              $post_data["patient_other_diagnosis"] = isset($post["patient_other_diagnosis"]) ? $post["patient_other_diagnosis"] : "";              					
              $post_data["diagnosis_id_arr"] = isset($post["diagnosis_id_arr"]) ? $post["diagnosis_id_arr"] : '';              
              $post_data["diagnosis_level_arr"] = isset($post["diagnosis_level_arr"]) ? $post["diagnosis_level_arr"] : '';
              $post_data["diagnosis_type_arr"] = isset($post["diagnosis_type_arr"]) ? $post["diagnosis_type_arr"] : '';

              $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';
              $post_data["date"] = isset($post["date"]) ? $post["date"] : '';
                     
              $result = $this->PatientDiagnosisModel->savePatientDiagnosis($post_data);
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
  
	 public function getPatientDiagnosis()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["patient_diagnosis_id"] = isset($post["patient_diagnosis_id"]) ? $post["patient_diagnosis_id"] : '';
	              
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
		           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;  
	                  
	              $result  = $this->PatientDiagnosisModel->getPatientDiagnosis($post_data);
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
 	 
 	 public function getPreviousDiagnosis()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				//print_r($post ); 
				$post_data["patient_diagnosis_id"] = isset($post["patient_diagnosis_id"]) ? $post["patient_diagnosis_id"] : '';
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
				$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;  
				$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
				$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;  
	            $result  = $this->PatientDiagnosisModel->getPreviousDiagnosis($post_data);
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
  	
  	 public function listPatientDiagnosis()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
				  $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
              $post_data["patient_diagnosis_id"] = isset($post["patient_diagnosis_id"]) ? $post["patient_diagnosis_id"] : 0;
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;              
              $post_data["record_limit"] = isset($post["record_limit"]) ? $post["record_limit"] : 100;              
	           $result      = $this->PatientDiagnosisModel->listPatientDiagnosis($post_data);
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
	 
  
	 public function deletePatientDiagnosis()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["patient_diagnosis_id"] = isset($post["patient_diagnosis_id"]) ? $post["patient_diagnosis_id"] : '';       
	              $result      = $this->PatientDiagnosisModel->deletePatientDiagnosis($post_data);
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