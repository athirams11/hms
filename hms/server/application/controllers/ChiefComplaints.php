<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ChiefComplaints extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/ChiefComplaintsModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listComplaints()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : '';     
	              $post_data["complaint_id"] = isset($post["complaint_id"]) ? $post["complaint_id"] : '';        
	              $post_data["appointment_date"] = isset($post["appointment_date"]) ? $post["appointment_date"] : '';  
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;      
	              $result      = $this->ChiefComplaintsModel->listComplaints($post_data);
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response 
	             log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));           
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;
	          
	      }
	 }
  
	 public function getComplaints()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              	               	              
		           $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
	              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
		           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;                   
	           	  $post_data["complaint_id"] = isset($post["complaint_id"]) ? $post["complaint_id"] : 0;   
           	        
	              $result = $this->ChiefComplaintsModel->getComplaints($post_data);	              
	             

 				 log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));  
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
     	  }
 	 }
 	 
 	public function getPreviousComplaints()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);	              
					                  
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
				$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;
				$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
				$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;        
				$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';
				$post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';                                 	           	     	           	     
              	$result = $this->ChiefComplaintsModel->getPreviousComplaints($post_data);	  
                log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));               	              
              	$this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
              	$result     = array("status"=> "Failed");
         	 	$this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
     	  }
 	 }
	 
	
 	   
	 public function saveComplaints()
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
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;                   
           	  $post_data["complaint_id"] = isset($post["complaint_id"]) ? $post["complaint_id"] : 0;       
	           $post_data["complaint_data"] = isset($post["complaint_data"]) ? $post["complaint_data"] : ''; 
	            $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : ''; 
				  $result = $this->ChiefComplaintsModel->saveComplaints($post_data);
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
 
  
	 public function deleteComplaints()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	             //print_r($post ); 
           	  		$post_data["complaint_id"] = isset($post["complaint_id"]) ? $post["complaint_id"] : '';       
	              $result      = $this->ChiefComplaintsModel->deleteComplaints($post_data);
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
 	 
 	 public function changeComplaints()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	             //print_r($post ); 
           	  		$post_data["complaint_id"] = isset($post["complaint_id"]) ? $post["complaint_id"] : '';       
           	  		$post_data["complaint_status"] = isset($post["complaint_status"]) ? $post["complaint_status"] : 1;       
	              $result      = $this->ChiefComplaintsModel->changeComplaints($post_data);
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
				
				$result = $this->ChiefComplaintsModel->saveDentalComplaints($post);
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
           	        
	              $result = $this->ChiefComplaintsModel->getDentalComplaints($post_data);	              
	             

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
