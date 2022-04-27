<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DentalComplaints extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/DentalComplaintsModel');        
        $this->load->model('api/MasterDataModel');        
        
 	}
	 
 	
  	public function listDentalProcedure()
   	{   
      	switch($this->input->method(TRUE))
        {     
	        case 'POST':          
				$post_data  = json_decode(file_get_contents("php://input"),true); 

				$result = $this->DentalComplaintsModel->listDentalProcedure();

				log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
				$this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
				$result     = array("status"=> "Failed");
				$this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;
      	}
  	}

  	public function getCDTByProcedureId()
	{       
		switch($this->input->method(TRUE))
		{     
			case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true); 
				
				$post_data["procedure_code_category"] = isset($post["procedure_code_category"]) ? $post["procedure_code_category"] : 0;     
				$post_data["dental_procedure_id"] = isset($post["dental_procedure_id"]) ? $post["dental_procedure_id"] : 0;         
				$result  = $this->DentalComplaintsModel->getCDTByProcedureId($post_data);
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
  
	public function saveDentalComplaints()
	{       
        switch($this->input->method(TRUE))
        {     
        	case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);                     
				
				$result = $this->DentalComplaintsModel->saveDentalComplaints($post);
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
    
    public function getDentalComplaints()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              	               	              
		           $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
		           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;                   
	           	  $post_data["dental_id"] = isset($post["dental_id"]) ? $post["dental_id"] : 0;   
           	        
	              $result = $this->DentalComplaintsModel->getDentalComplaints($post_data);	              
	             

 				 log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));  
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
 	  	}
 	}
 	
 	public function listNotAllowedProcedure()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				               	              
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
				$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;                   
				$post_data["dental_id"] = isset($post["dental_id"]) ? $post["dental_id"] : 0;   

				$result = $this->DentalComplaintsModel->listNotAllowedProcedure($post_data);	 
				log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));  
				$this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
				$result     = array("status"=> "Failed");
				$this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
 	  	}
 	}
 	public function saveDentalHistory()
	{       
        switch($this->input->method(TRUE))
        {     
        	case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);     
				$post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
				$post_data["date"] = isset($post["date"]) ? $post["date"] : '';
				$post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
				$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
				$post_data["dental_id"] = isset($post["dental_id"]) ? $post["dental_id"] : '';       
				$post_data["FDI"] = isset($post["FDI"]) ? $post["FDI"] : ''; 
				$post_data["palmer"] = isset($post["palmer"]) ? $post["palmer"] : ''; 
				$post_data["tooth_complaint"] = isset($post["tooth_complaint"]) ? $post["tooth_complaint"] : ''; 
				$post_data["tooth_issue"] = isset($post["tooth_issue"]) ? $post["tooth_issue"] : ''; 
				$post_data["universal"] = isset($post["universal"]) ? $post["universal"] : ''; 
				$post_data["procedure"] = isset($post["procedure"]) ? $post["procedure"] : ''; 
				$post_data["color"] = isset($post["color"]) ? $post["color"] : ''; 
				$post_data["child_tooth_value"] = isset($post["child_tooth_value"]) ? $post["child_tooth_value"] : ''; 
				$post_data["child_tooth_number"] = isset($post["child_tooth_number"]) ? $post["child_tooth_number"] : ''; 
				$post_data["dental_complaint_id"] = isset($post["dental_complaint_id"]) ? $post["dental_complaint_id"] : '';
				$post_data["patient_type"] = isset($post["patient_type"]) ? $post["patient_type"] : ''; 
				$post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : ''; 
				$post_data["complients"] = isset($post["complients"]) ? $post["complients"] : ''; 
				$post_data["child_complients"] = isset($post["child_complients"]) ? $post["child_complients"] : ''; 
				$post_data["dental_id"] = isset($post["dental_id"]) ? $post["dental_id"] : ''; 
		
				$result = $this->DentalComplaintsModel->saveDentalHistory($post_data);
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

 	public function getDentalHistory()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				               	              
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;                   
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;   

				$result = $this->DentalComplaintsModel->getDentalHistory($post_data);	 
				log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));  
				$this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
				$result     = array("status"=> "Failed");
				$this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
 	  	}
 	}
 	
 	public function downloadDentalHistoryPdf()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				               	              
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;                   
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;   

				$result = $this->DentalComplaintsModel->downloadDentalHistoryPdf($post_data);	 
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
