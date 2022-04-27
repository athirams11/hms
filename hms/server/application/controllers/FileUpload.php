<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FileUpload extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();        
        $this->load->model('api/ApiModel');       
        $this->load->model('api/MasterDataModel');        
        $this->load->model('api/FileUploadModel');        
        
	 }
	 	  
	 public function saveFile()
	 {       
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);            
	              $post_data["document_id"] = isset($post["document_id"]) ? $post["document_id"] : '';
	              $post_data["module_id"] = isset($post["module_id"]) ? $post["module_id"] : '';
				  $post_data["refer_id"] = isset($post["refer_id"]) ? $post["refer_id"] : '';
				  $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
                  $post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
	              $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0; 
				  $post_data["file_name"] = isset($post["file_name"]) ? $post["file_name"] : '';
				  $post_data["doc_type"] = isset($post["doc_type"]) ? $post["doc_type"] : '';
				  $post_data["doc_date"] = isset($post["doc_date"]) ? $post["doc_date"] : '';
				  $post_data["doc_description"] = isset($post["doc_description"]) ? $post["doc_description"] : '';
				  $post_data["base64_file_str"] = isset($post["base64_file_str"]) ? $post["base64_file_str"] : '';
				  

	              $result = $this->FileUploadModel->saveFile($post_data);       
	             log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));     
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
        }
    }
  
	 public function deleteFile()
	 {           	
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["document_id"] = isset($post["document_id"]) ? $post["document_id"] : '';
	              $result      = $this->FileUploadModel->deleteFile($post_data);
	              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));     
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result     = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;          
     	  }
	 }

  
	 public function viewFile()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post_data  = json_decode(file_get_contents("php://input"),true); 
	              $post_data["module_id"] = isset($post["module_id"]) ? $post["module_id"] : '';
					  $post_data["refer_id"] = isset($post["refer_id"]) ? $post["refer_id"] : '';
					  $post_data["doc_type"] = isset($post["doc_type"]) ? $post["doc_type"] : '';
	              $result      = $this->FileUploadModel->listDocuments($post_data);	            
	              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));      
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
