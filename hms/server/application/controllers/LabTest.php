<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LabTest extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/LabTestModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listLabTest()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["lab_test_id"] = isset($post["lab_test_id"]) ? $post["lab_test_id"] : '';     
	              $result      = $this->LabTestModel->listLabTest($post_data);
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
  
	 public function getLabTest()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["lab_test_id"] = isset($post["lab_test_id"]) ? $post["lab_test_id"] : '';       
	              $result      = $this->LabTestModel->getLabTest($post_data);
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
  
	 public function saveLabTest()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["lab_test_id"] = isset($post["lab_test_id"]) ? $post["lab_test_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              
              $post_data["lab_test_code"] = isset($post["lab_test_code"]) ? $post["lab_test_code"] : '';
              $post_data["lab_test_name"] = isset($post["lab_test_name"]) ? $post["lab_test_name"] : '';
              $post_data["lab_test_description"] = isset($post["lab_test_description"]) ? $post["lab_test_description"] : '';
              $post_data["lab_test_order"] = isset($post["lab_test_order"]) ? $post["lab_test_order"] : '';
		 
              $result = $this->LabTestModel->saveLabTest($post_data);
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
  
	 public function deleteLabTest()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["lab_test_id"] = isset($post["lab_test_id"]) ? $post["lab_test_id"] : '';       
	              $result      = $this->LabTestModel->deleteLabTest($post_data);
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
