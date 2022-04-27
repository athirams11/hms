<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Medicine extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/MedicineModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listMedicine()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["medicine_id"] = isset($post["medicine_id"]) ? $post["medicine_id"] : '';    
	              $post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
	              $result      = $this->MedicineModel->listMedicine($post_data);
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
  
	 public function getMedicine()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["medicine_id"] = isset($post["medicine_id"]) ? $post["medicine_id"] : '';       
	              $result      = $this->MedicineModel->getMedicine($post_data);
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
  
	 public function saveMedicine()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["medicine_id"] = isset($post["medicine_id"]) ? $post["medicine_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              
              $post_data["ddc_code"] = isset($post["ddc_code"]) ? $post["ddc_code"] : '';
              $post_data["trade_name"] = isset($post["trade_name"]) ? $post["trade_name"] : '';
              $post_data["scientific_code"] = isset($post["scientific_code"]) ? $post["scientific_code"] : '';
              $post_data["scientific_name"] = isset($post["scientific_name"]) ? $post["scientific_name"] : '';
              $post_data["ingredient_strength"] = isset($post["ingredient_strength"]) ? $post["ingredient_strength"] : '';
                                   
              $post_data["dosage_from_package"] = isset($post["dosage_from_package"]) ? $post["dosage_from_package"] : '';
              $post_data["route_of_admin"] = isset($post["route_of_admin"]) ? $post["route_of_admin"] : '';
              $post_data["package_price"] = isset($post["package_price"]) ? $post["package_price"] : 0;
              $post_data["granular_unit"] = isset($post["granular_unit"]) ? $post["granular_unit"] : 0;
              $post_data["manufacturer"] = isset($post["manufacturer"]) ? $post["manufacturer"] : '';
              $post_data["registered_owner"] = isset($post["registered_owner"]) ? $post["registered_owner"] : '';
              $post_data["source"] = isset($post["source"]) ? $post["source"] : '';
              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';
		 
              $result = $this->MedicineModel->saveMedicine($post_data);
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
  
	 public function deleteMedicine()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["medicine_id"] = isset($post["medicine_id"]) ? $post["medicine_id"] : '';       
	              $result      = $this->MedicineModel->deleteMedicine($post_data);
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
