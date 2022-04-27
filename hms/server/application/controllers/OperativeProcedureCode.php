<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class OperativeProcedureCode extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/OperativeProcedureCodeModel');        
        
	 }
	 
	 public function listOperativeProcedureCode()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["operative_procedure_code_id"] = isset($post["operative_procedure_code_id"]) ? $post["operative_procedure_code_id"] : '';     
	              $post_data["procedure_code_id"] = isset($post["procedure_code_id"]) ? $post["procedure_code_id"] : '';     
	              $result      = $this->OperativeProcedureCodeModel->listOperativeProcedureCode($post_data);
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
  
	 public function getOperativeProcedureCode()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["operative_procedure_code_id"] = isset($post["operative_procedure_code_id"]) ? $post["operative_procedure_code_id"] : '';       
	              $result      = $this->OperativeProcedureCodeModel->getOperativeProcedureCode($post_data);
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
  
	 public function saveOperativeProcedureCode()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["operative_procedure_code_id"] = isset($post["operative_procedure_code_id"]) ? $post["operative_procedure_code_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              
              $post_data["procedure_code_id"] = isset($post["procedure_code_id"]) ? $post["procedure_code_id"] : 0;              
              $post_data["operative_procedure_code_category"] = isset($post["operative_procedure_code_category"]) ? $post["operative_procedure_code_category"] : '';
              $post_data["operative_procedure_code_description"] = isset($post["operative_procedure_code_description"]) ? $post["operative_procedure_code_description"] : '';                                   
              $post_data["operative_procedure_code_order"] = isset($post["operative_procedure_code_order"]) ? $post["operative_procedure_code_order"] : 0;
              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';
		 
              $result = $this->OperativeProcedureCodeModel->saveOperativeProcedureCode($post_data);
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
  
	 public function deleteOperativeProcedureCode()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["operative_procedure_code_id"] = isset($post["operative_procedure_code_id"]) ? $post["operative_procedure_code_id"] : '';       
	              $result      = $this->OperativeProcedureCodeModel->deleteOperativeProcedureCode($post_data);
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
