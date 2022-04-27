<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PatientSickLeave extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/PatientSickLeaveModel');        
        $this->load->model('api/MasterDataModel');        
        
	}
	 
	public function listPatientSickLeave()
	{       
		switch($this->input->method(TRUE))
		{     
			case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true); 
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
				$post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;              
				$result      = $this->PatientSickLeaveModel->listPatientSickLeave($post_data);
				log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));        
				$this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
			break;

			default:                
				$result     = array("status"=> "Failed");
				$this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
			break;
		  
		}
	}

	public function getPatientSickLeave()
	{           
		switch($this->input->method(TRUE))
		{     
		    case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
				$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
				$post_data["patient_sick_leave_id"] = isset($post["patient_sick_leave_id"]) ? $post["patient_sick_leave_id"] : 0;       
				$result  = $this->PatientSickLeaveModel->getPatientSickLeave($post_data);
				log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));  
				$this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
		    break;
		    
		    default:                
		        $result     = array("status"=> "Failed");
		        $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
		    break;          
		}
	}

	public function savePatientSickLeave()
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
				$post_data["patient_sick_leave_id"] = isset($post["patient_sick_leave_id"]) ? $post["patient_sick_leave_id"] : 0;              					
				$post_data["issueddate"] = isset($post["issueddate"]) ? $post["issueddate"] : '';              
				$post_data["sickReason"] = isset($post["sickReason"]) ? $post["sickReason"] : '';
				$post_data["sickFromdate"] = isset($post["sickFromdate"]) ? $post["sickFromdate"] : '';
				$post_data["sickTodate"] = isset($post["sickTodate"]) ? $post["sickTodate"] : '';
				$post_data["duration"] = isset($post["duration"]) ? $post["duration"] : '';

				$post_data["date"] = isset($post["date"]) ? $post["date"] : '';   
				$post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';   


				$result = $this->PatientSickLeaveModel->savePatientSickLeave($post_data);
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
	 public function downloaSickleavePdf()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				               	              
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
				$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
				$post_data["patient_sick_leave_id"] = isset($post["patient_sick_leave_id"]) ? $post["patient_sick_leave_id"] : 0;     

				$result = $this->PatientSickLeaveModel->downloaSickleavePdf($post_data);	 
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
