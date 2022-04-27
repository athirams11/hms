<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class INS_network extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/INS_networkModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 public function listinsnetwork()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	             // $post_data["ins_network_id"] = isset($post["ins_network_id"]) ? $post["ins_network_id"] : '';  
	             $post_data["start"] = isset($post["start"]) ? $post["start"] : '';     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : '';     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
	              
	              $result      = $this->INS_networkModel->listinsnetwork($post_data);
	              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));     
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code	              
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;
	          
	      }
	  }

	 public function getinsnetwork()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["ins_network_id"] = isset($post["ins_network_id"]) ? $post["ins_network_id"] : '';       
	              $result      = $this->INS_networkModel->getinsnetwork($post_data);
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


 	 public function saveinsnetwork()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);       
                $post_data["ins_network_id"] = isset($post["ins_network_id"]) ? $post["ins_network_id"] : '';     
               
	              $post_data["tpa_id"] = isset($post["tpa_id"]) ? $post["tpa_id"] : '';  
	              $post_data["ins_network_code"] = isset($post["ins_network_code"]) ? $post["ins_network_code"] : '';  
                  $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
	              $post_data["ins_network_name"] = isset($post["ins_network_name"]) ? $post["ins_network_name"] : '';  
	              $post_data["ins_network_classification"] = isset($post["ins_network_classification"]) ? $post["ins_network_classification"] : '';   
	              $post_data["ins_network_status"] = isset($post["ins_network_status"]) ? $post["ins_network_status"] : '';
	               $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';     
	               $post_data["copy_from_network"] = isset($post["copy_from_network"]) ? $post["copy_from_network"] : '';     
		 
              $result = $this->INS_networkModel->saveinsnetwork($post_data);
              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));     
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;          
        }
    }

    public function deleteinsnetwork()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["ins_network_id"] = isset($post["ins_network_id"]) ? $post["ins_network_id"] : '';       
	              $result      = $this->INS_networkModel->deleteinsnetwork($post_data);
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
