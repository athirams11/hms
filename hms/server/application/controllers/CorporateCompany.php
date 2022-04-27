
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CorporateCompany extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/CorporateCompanyModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	public function listCorporateCompany()
	{       
	    switch($this->input->method(TRUE))
	    {     
	        case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true); 
				$post_data["start"] = isset($post["start"]) ? $post["start"] : '';     
				$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : '';     
				$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
				$post_data["company_status"] = isset($post["company_status"]) ? $post["company_status"] : '';  
                $result    = $this->CorporateCompanyModel->listCorporateCompany($post_data);
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

	public function getCorporateCompany()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				$post_data["company_id"] = isset($post["company_id"]) ? $post["company_id"] : '';       
				$result      = $this->CorporateCompanyModel->getCorporateCompany($post_data);
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


	 
 	 public function saveCorporateCompany()
	 {       
        switch($this->input->method(TRUE))
        {     
        	case 'POST':          
              	$post  = json_decode(file_get_contents("php://input"),true); 

               	$post_data["company_id"] = isset($post["company_id"]) ? $post["company_id"] : '';
              	$post_data["company_name"] = isset($post["company_name"]) ? $post["company_name"] : '';  
              	$post_data["company_address"] = isset($post["company_address"]) ? $post["company_address"] : '';  
              	$post_data["company_code"] = isset($post["company_code"]) ? $post["company_code"] : '';  
              	$post_data["company_status"] = isset($post["company_status"]) ? $post["company_status"] : '';  
				$post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';  
				$post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';    
		 
              	$result = $this->CorporateCompanyModel->saveCorporateCompany($post_data);
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
