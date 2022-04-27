<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LabInvestigation extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/LabInvestigationModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listLabInvestigation()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
					$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
              $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : 0;
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;              
	              $result      = $this->LabInvestigationModel->listLabInvestigation($post_data);
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
  
	 public function getLabInvestigation()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : '';
	              
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
              	  $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : "";
	           	  $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
	           	  
	           	         
	              $result  = $this->LabInvestigationModel->getLabInvestigation($post_data);
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
	 public function getbillStatus()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              //$post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : '';
	              
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
              	  //$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : "";
	           	  $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
	           	  
	           	         
	              $result  = $this->LabInvestigationModel->getbillStatus($post_data);
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
  
	 public function saveLabInvestigation()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
                  
              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
              $post_data["doctor_id"] = isset($post["doctor_id"]) ? $post["doctor_id"] : "";
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
              
	           $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : 0;     
	           $post_data["current_procedural_code_id_arr"] = isset($post["current_procedural_code_id_arr"]) ? $post["current_procedural_code_id_arr"] : "";
	                       
	           $post_data["description_arr"] = isset($post["description_arr"]) ? $post["description_arr"] : "";            
	           $post_data["quantity_arr"] = isset($post["quantity_arr"]) ? $post["quantity_arr"] : "";
	           $post_data["rate_arr"] = isset($post["rate_arr"]) ? $post["rate_arr"] : "";
	           $post_data["change_to_future_arr"] = isset($post["change_to_future_arr"]) ? $post["change_to_future_arr"] : "";
	           $post_data["remarks_arr"] = isset($post["remarks_arr"]) ? $post["remarks_arr"] : "";
	           $post_data["lab_priority_id_arr"] = isset($post["lab_priority_id_arr"]) ? $post["lab_priority_id_arr"] : "";
	           
	           $post_data["lab_investigation_priority_id"] = isset($post["lab_investigation_priority_id"]) ? $post["lab_investigation_priority_id"] : "";
	                       	
              $post_data["billed_amount"] = isset($post["billed_amount"]) ? $post["billed_amount"] : '';
              $post_data["un_billed_amount"] = isset($post["un_billed_amount"]) ? $post["un_billed_amount"] : '';
              $post_data["instruction_to_cashier"] = isset($post["instruction_to_cashier"]) ? $post["instruction_to_cashier"] : '';
              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';  
               
              $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';
              $post_data["date"] = isset($post["date"]) ? $post["date"] : '';
              		 
              $result = $this->LabInvestigationModel->saveLabInvestigation($post_data);
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
  
	 public function deleteLabInvestigation()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : '';       
	              $result      = $this->LabInvestigationModel->deleteLabInvestigation($post_data);
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
	public function saveInvestigation()
	{       
		switch($this->input->method(TRUE))
		{     
		  case 'POST':          
		    $post  = json_decode(file_get_contents("php://input"),true); 
		      
		    $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
		    $post_data["doctor_id"] = isset($post["doctor_id"]) ? $post["doctor_id"] : "";
		    $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
		    $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
		    $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : 0;
		    $post_data["current_procedural_code_id"] = isset($post["current_procedural_code_id"]) ? $post["current_procedural_code_id"] : 0;
		    $post_data["lab_investigation_details_id"] = isset($post["lab_investigation_details_id"]) ? $post["lab_investigation_details_id"] : 0;
		    $post_data["billedamount"] = isset($post["billedamount"]) ? $post["billedamount"] : 0;
		    $post_data["instruction"] = isset($post["instruction"]) ? $post["instruction"] : 0;
		    $post_data["un_billedamount"] = isset($post["un_billedamount"]) ? $post["un_billedamount"] : 0;
		    $post_data["description"] = isset($post["description"]) ? $post["description"] : "";            
		    $post_data["quantity"] = isset($post["quantity"]) ? $post["quantity"] : "";
		    $post_data["rate"] = isset($post["rate"]) ? $post["rate"] : "";
		    $post_data["change_of_future"] = isset($post["change_of_future"]) ? $post["change_of_future"] : "";
		    $post_data["remarks"] = isset($post["remarks"]) ? $post["remarks"] : "";
		    $post_data["priority"] = isset($post["priority"]) ? $post["priority"] : "";
		 
		    $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';  
		    $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';
		    $post_data["date"] = isset($post["date"]) ? $post["date"] : '';
		      
		    $result = $this->LabInvestigationModel->saveInvestigation($post_data);
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
	public function deleteInvestigation()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["lab_investigation_details_id"] = isset($post["lab_investigation_details_id"]) ? $post["lab_investigation_details_id"] : '';       
	              $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : '';       
	              $post_data["billedamount"] = isset($post["billedamount"]) ? $post["billedamount"] : '';       
	              $result      = $this->LabInvestigationModel->deleteInvestigation($post_data);
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
  

  	//For dental
  	 public function saveDentalInvestigation()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
                  
              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
              $post_data["doctor_id"] = isset($post["doctor_id"]) ? $post["doctor_id"] : "";
	           $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
              $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
              
	           $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : 0;     
	           $post_data["current_procedural_code_id_arr"] = isset($post["current_procedural_code_id_arr"]) ? $post["current_procedural_code_id_arr"] : "";
	                       
	           $post_data["description_arr"] = isset($post["description_arr"]) ? $post["description_arr"] : "";            
	           $post_data["quantity_arr"] = isset($post["quantity_arr"]) ? $post["quantity_arr"] : "";
	           $post_data["rate_arr"] = isset($post["rate_arr"]) ? $post["rate_arr"] : "";
	           
	           $post_data["remarks_arr"] = isset($post["remarks_arr"]) ? $post["remarks_arr"] : "";
	           $post_data["tooth_number"] = isset($post["tooth_number"]) ? $post["tooth_number"] : 0;
	           $post_data["tooth_index"] = isset($post["tooth_index"]) ? $post["tooth_index"] : NULL;
	           
              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';  
               
              $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';
              $post_data["date"] = isset($post["date"]) ? $post["date"] : '';
              		 
              $result = $this->LabInvestigationModel->saveDentalInvestigation($post_data);
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
     public function getDentalInvestigation()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : '';
	              
	              $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
              	  $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : "";
	           	  $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
	           	  
	           	         
	              $result  = $this->LabInvestigationModel->getDentalInvestigation($post_data);
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
