<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ReportsAndNotes extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/ReportsAndNotesModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listReportsAndNotes()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["reports_notes_id"] = isset($post["reports_notes_id"]) ? $post["reports_notes_id"] : '';
	                   
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
		           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0; 
		           
	              $result      = $this->ReportsAndNotesModel->listReportsAndNotes($post_data);
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
  
	 public function getReportsAndNotes()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["reports_notes_id"] = isset($post["reports_notes_id"]) ? $post["reports_notes_id"] : '';
	              
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
		           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0; 
		                  
	              $result      = $this->ReportsAndNotesModel->getReportsAndNotes($post_data);
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
  
	 public function saveReportsAndNotes()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["reports_notes_id"] = isset($post["reports_notes_id"]) ? $post["reports_notes_id"] : '';
			 	  $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;   
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;   
                         
              $post_data["investigation_requested"] = isset($post["investigation_requested"]) ? $post["investigation_requested"] : '';    
              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';                            
              $result = $this->ReportsAndNotesModel->saveReportsAndNotes($post_data);
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
  
	 public function deleteReportsAndNotes()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["reports_notes_id"] = isset($post["reports_notes_id"]) ? $post["reports_notes_id"] : '';       
	              $result      = $this->ReportsAndNotesModel->deleteReportsAndNotes($post_data);
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
