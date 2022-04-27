<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class 	Consultation extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/ConsultationModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listConsultation()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : '';     
	              $result      = $this->ConsultationModel->listConsultation($post_data);
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
  
	 public function getConsultation()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : '';       
	              $result      = $this->ConsultationModel->getConsultation($post_data);	              
	              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));   
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
     	  }
 	 }
 	 
 	 public function getPreviousConsultation()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	               
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : '';       
	              $result      = $this->ConsultationModel->getPreviousConsultation($post_data);	              
	                log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));   
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
     	  }
 	 }
	 
	
 	   
	 public function saveConsultation()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : '';              
              $post_data["presenting_complaints"] = isset($post["presenting_complaints"]) ? $post["presenting_complaints"] : '';
				  $post_data["examination_notes"] = isset($post["examination_notes"]) ? $post["examination_notes"] : '';
				    
				  $post_data["diagnosis_details_arr"] = isset($post["diagnosis_details_arr"]) ? $post["diagnosis_details_arr"] : '';  
				  $post_data["diagnosis_description_arr"] = isset($post["diagnosis_description_arr"]) ? $post["diagnosis_description_arr"] : '';  
				  $post_data["treatment_plans_arr"] = isset($post["treatment_plans_arr"]) ? $post["treatment_plans_arr"] : '';
				  $post_data["treatment_prescription_arr"] = isset($post["treatment_prescription_arr"]) ? $post["treatment_prescription_arr"] : '';
				  $post_data["drug_details_arr"] = isset($post["drug_details_arr"]) ? $post["drug_details_arr"] : '';
				  $post_data["drug_prescription_arr"] = isset($post["drug_prescription_arr"]) ? $post["drug_prescription_arr"] : '';
				  $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : ''; 
				  
				  $result = $this->ConsultationModel->saveConsultation($post_data);
				    log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));   
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;          
        }
    }
    
    public function getConsultationDetails()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : '';              
	              $result      = $this->ConsultationModel->getConsultationDetails($post_data);
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
  
	 public function deleteConsultation()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	             //print_r($post ); 
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : '';       
	              $result      = $this->ConsultationModel->deleteConsultation($post_data);
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
 	 
  	 public function options()
    {       
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);  
              $result["INSURANCE_CONST"] = INSURANCE_ID_CONSTANT;          
              $result["specialized_in"] = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 8 AND O.OPTIONS_STATUS = 1");                          
              $result["tpa_receiver"] = $this->MasterModel->master_dropdown_listing("TPA T","T.TPA_ID,T.TPA_NAME,T.TPA_ECLAIM_LINK_ID","T.TPA_ECLAIM_LINK_ID","ASC","T.TPA_STATUS = 1");
              $result["networks"] = $this->MasterModel->master_dropdown_listing("INS_NETWORK N","N.INS_NETWORK_ID,N.INS_NETWORK_NAME,N.INS_NETWORK_CODE,N.TPA_ID","N.INS_NETWORK_NAME ","ASC","N.INS_NETWORK_STATUS = 1");
              $result["ins_com_pay"]  = $this->MasterModel->master_dropdown_listing("INSURANCE_PAYERS I","I.INSURANCE_PAYERS_ID,I.INSURANCE_PAYERS_ECLAIM_LINK_ID,I.INSURANCE_PAYERS_NAME","I.INSURANCE_PAYERS_ECLAIM_LINK_ID","ASC","I.INSURANCE_PAYERS_STATUS = 1");
              $result["co_in_types"]  = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 9 AND O.OPTIONS_STATUS = 1");

              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));   
              $result["status"] = "Success";
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              
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
