<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class NursingAssessmentNotes extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/NursingAssesmentNotesModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function getAssesmentParameters()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : '';     
	              $result      = $this->NursingAssesmentNotesModel->getAssesmentParameters($post_data);
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
  
	 public function editAssesmentValues()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : '';       
	              $result      = $this->NursingAssesmentNotesModel->editAssesmentValues($post_data);
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
  
	 public function saveAssesmentParameters()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              $post_data["assessment_notes_id"] = isset($post["assessment_notes_id"]) ? $post["assessment_notes_id"] : '';
				  $post_data["next_of_kin"] = isset($post["next_of_kin"]) ? $post["next_of_kin"] : '';  
				  $post_data["next_of_kin_mobile"] = isset($post["next_of_kin_mobile"]) ? $post["next_of_kin_mobile"] : '';  
				  $post_data["chief_complaints"] = isset($post["chief_complaints"]) ? $post["chief_complaints"] : '';  
				  $post_data["nursing_notes"] = isset($post["nursing_notes"]) ? $post["nursing_notes"] : '';  
				  $post_data["past_history"] = isset($post["past_history"]) ? $post["past_history"] : '';  
				  $post_data["family_history"] = isset($post["family_history"]) ? $post["family_history"] : '';  
				  $post_data["mode_arrival"] = isset($post["mode_arrival"]) ? $post["mode_arrival"] : '';  
          $post_data["mode_arrival_other_value"] = isset($post["mode_arrival_other_value"]) ? $post["mode_arrival_other_value"] : '';  
				  $post_data["accompanied_by"] = isset($post["accompanied_by"]) ? $post["accompanied_by"] : '';  
          $post_data["accompanied_other_value"] = isset($post["accompanied_other_value"]) ? $post["accompanied_other_value"] : '';
          $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';                
          $post_data["date"] = isset($post["date"]) ? $post["date"] : '';                
          $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';                
				  $post_data["patient_waiting_time_informed"] = isset($post["patient_waiting_time_informed"]) ? $post["patient_waiting_time_informed"] : '';  				    
				  $post_data["expected_waiting_time"] = isset($post["expected_waiting_time"]) ? $post["expected_waiting_time"] : '';  
              $result = $this->NursingAssesmentNotesModel->saveAssesmentParameters($post_data);
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
  
	 public function deleteAssesmentValues()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["assessment_notes_id"] = isset($post["assessment_notes_id"]) ? $post["assessment_notes_id"] : '';       
	              $result      = $this->NursingAssesmentNotesModel->deleteAssesmentValues($post_data);
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
 	 


  	 public function options()
    {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);  
              $result["INSURANCE_CONST"] = INSURANCE_ID_CONSTANT;          
              $result["specialized_in"]         = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 8 AND O.OPTIONS_STATUS = 1");
              
              

              $result["tpa_receiver"]     = $this->MasterModel->master_dropdown_listing("TPA T","T.TPA_ID,T.TPA_NAME,T.TPA_ECLAIM_LINK_ID","T.TPA_ECLAIM_LINK_ID","ASC","T.TPA_STATUS = 1");
              $result["networks"]     = $this->MasterModel->master_dropdown_listing("INS_NETWORK N","N.INS_NETWORK_ID,N.INS_NETWORK_NAME,N.INS_NETWORK_CODE,N.TPA_ID","N.INS_NETWORK_NAME ","ASC","N.INS_NETWORK_STATUS = 1");
              $result["ins_com_pay"]     = $this->MasterModel->master_dropdown_listing("INSURANCE_PAYERS I","I.INSURANCE_PAYERS_ID,I.INSURANCE_PAYERS_ECLAIM_LINK_ID,I.INSURANCE_PAYERS_NAME","I.INSURANCE_PAYERS_ECLAIM_LINK_ID","ASC","I.INSURANCE_PAYERS_STATUS = 1");


              $result["co_in_types"]     = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 9 AND O.OPTIONS_STATUS = 1");


               log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));     
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
  
  public function getDrByDateDept()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->NursingAssesmentNotesModel->getDrByDateDept($post_data);
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
  
  public function startAssesment()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->NursingAssesmentNotesModel->startAssesment($post_data);
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
  
  public function updateInsuranceDetails()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->NursingAssesmentNotesModel->updateInsuranceDetails($post_data);
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
  
  public function getPatientDetails()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->NursingAssesmentNotesModel->getPatientDetails($post_data);
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
  
  public function getAssesmentListByDate()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->NursingAssesmentNotesModel->getAssesmentListByDate($post_data);
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
  
  
  public function getAssesmentParameterValues()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->NursingAssesmentNotesModel->getAssesmentParameterValues($post_data);
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
