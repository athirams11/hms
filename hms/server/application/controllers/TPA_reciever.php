<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TPA_reciever extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/TPA_receiverModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listTPA()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              //$post_data["tpa_id"] = isset($post["tpa_id"]) ? $post["tpa_id"] : '';  
	                 
	             $post_data["start"] = isset($post["start"]) ? $post["start"] : '';     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : '';     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
	              
	              $result      = $this->TPA_receiverModel->listTPA($post_data);
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


	 public function getTPA()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["tpa_id"] = isset($post["tpa_id"]) ? $post["tpa_id"] : '';       
	              $result      = $this->TPA_receiverModel->getTPA($post_data);
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




 	 public function saveTPA()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
               $post_data["tpa_id"] = isset($post["tpa_id"]) ? $post["tpa_id"] : '';     
               
	              $post_data["tpa_eclaim_link_id"] = isset($post["tpa_eclaim_link_id"]) ? $post["tpa_eclaim_link_id"] : '';  
	              $post_data["tpa_name"] = isset($post["tpa_name"]) ? $post["tpa_name"] : '';
	              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';  
	              $post_data["tpa_classification"] = isset($post["tpa_classification"]) ? $post["tpa_classification"] : '';  
	              $post_data["tpa_status"] = isset($post["tpa_status"]) ? $post["tpa_status"] : '';  
	              $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';    
		 
              $result = $this->TPA_receiverModel->saveTPA($post_data);
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

    public function deleteTPA()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["tpa_id"] = isset($post["tpa_id"]) ? $post["tpa_id"] : '';       
	              $result      = $this->TPA_receiverModel->deleteTPA($post_data);
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
