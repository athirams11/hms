<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MedicineGenericType extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/MedicineGenericTypeModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listGenericType()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["generic_type_id"] = isset($post["generic_type_id"]) ? $post["generic_type_id"] : '';    
	              $post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
	              $result      = $this->MedicineGenericTypeModel->listGenericType($post_data);
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
  
	 public function getGenericType()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["generic_type_id"] = isset($post["generic_type_id"]) ? $post["generic_type_id"] : '';       
	              $result      = $this->MedicineGenericTypeModel->getGenericType($post_data);
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
  
	 public function saveGenericType()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["generic_type_id"] = isset($post["generic_type_id"]) ? $post["generic_type_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              
              $post_data["generic_type_generic_id"] = isset($post["generic_type_generic_id"]) ? $post["generic_type_generic_id"] : '';
              $post_data["generic_type_name"] = isset($post["generic_type_name"]) ? $post["generic_type_name"] : '';
              $post_data["generic_type_description"] = isset($post["generic_type_description"]) ? $post["generic_type_description"] : '';
              $post_data["generic_type_price_of_one_item"] = isset($post["generic_type_price_of_one_item"]) ? $post["generic_type_price_of_one_item"] : 0;
              $post_data["generic_type_order"] = isset($post["generic_type_order"]) ? $post["generic_type_order"] : 0;
		 
              $result = $this->MedicineGenericTypeModel->saveGenericType($post_data);
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
  
	 public function deleteGenericType()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["generic_type_id"] = isset($post["generic_type_id"]) ? $post["generic_type_id"] : '';       
	              $result      = $this->MedicineGenericTypeModel->deleteGenericType($post_data);
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
