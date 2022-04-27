<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PatientSpecialComment extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/PatientSpecialCommentModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listPatientSpecialComment()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["patient_special_comment_id"] = isset($post["patient_special_comment_id"]) ? $post["patient_special_comment_id"] : '';     
	              $result      = $this->PatientSpecialCommentModel->listPatientSpecialComment($post_data);
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
  
	 public function getPatientSpecialComment()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["patient_special_comment_id"] = isset($post["patient_special_comment_id"]) ? $post["patient_special_comment_id"] : '';
	              
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              	  $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           	  $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;
	           	         
	              $result      = $this->PatientSpecialCommentModel->getPatientSpecialComment($post_data);
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
  
  	
  	 public function getPreviousPatientSpecialComment()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["patient_special_comment_id"] = isset($post["patient_special_comment_id"]) ? $post["patient_special_comment_id"] : '';
	              
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              	  $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           	  $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;
	           	         
	              $result      = $this->PatientSpecialCommentModel->getPreviousPatientSpecialComment($post_data);
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
 	 
	 public function savePatientSpecialComment()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["patient_special_comment_id"] = isset($post["patient_special_comment_id"]) ? $post["patient_special_comment_id"] : '';
              
			 	  $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;   
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;   
              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : 0;   
              $post_data["date"] = isset($post["date"]) ? $post["date"] : '';   
              $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';   
                         
              $post_data["special_comment"] = isset($post["special_comment"]) ? $post["special_comment"] : '';                            
              $result = $this->PatientSpecialCommentModel->savePatientSpecialComment($post_data);
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
  
	 public function deletePatientSpecialComment()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["patient_special_comment_id"] = isset($post["patient_special_comment_id"]) ? $post["patient_special_comment_id"] : '';       
	              $result      = $this->PatientSpecialCommentModel->deletePatientSpecialComment($post_data);
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
