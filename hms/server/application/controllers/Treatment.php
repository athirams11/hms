<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Treatment extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/TreatmentModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listTreatment()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["treatment_id"] = isset($post["treatment_id"]) ? $post["treatment_id"] : '';     
	              $result      = $this->TreatmentModel->listTreatment($post_data);
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
  
	 public function getTreatment()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["treatment_id"] = isset($post["treatment_id"]) ? $post["treatment_id"] : '';       
	              $result      = $this->TreatmentModel->getTreatment($post_data);
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
  
	 public function saveTreatment()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["treatment_id"] = isset($post["treatment_id"]) ? $post["treatment_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              
              $post_data["treatment_code"] = isset($post["treatment_code"]) ? $post["treatment_code"] : '';
              $post_data["treatment_name"] = isset($post["treatment_name"]) ? $post["treatment_name"] : '';
              $post_data["treatment_description"] = isset($post["treatment_description"]) ? $post["treatment_description"] : '';
              $post_data["treatment_order"] = isset($post["treatment_order"]) ? $post["treatment_order"] : '';
		 
              $result = $this->TreatmentModel->saveTreatment($post_data);
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
  
	 public function deleteTreatment()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["treatment_id"] = isset($post["treatment_id"]) ? $post["treatment_id"] : '';       
	              $result      = $this->TreatmentModel->deleteTreatment($post_data);
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
