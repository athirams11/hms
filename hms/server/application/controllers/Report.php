<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/ReportModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	public function listCashReport()
	{       
	    switch($this->input->method(TRUE))
	    {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["todate"] = isset($post["todate"]) ? $post["todate"] : '';    
	              $post_data["fromdate"] = isset($post["fromdate"]) ? $post["fromdate"] : '';    
	              $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';  
	              $post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
	              $post_data["tpa_id"] = isset($post["tpa_id"]) ? $post["tpa_id"] : '';  
	              $post_data["doctor_id"] = isset($post["doctor_id"]) ? $post["doctor_id"] : '';  
	              $post_data["cashier_id"] = isset($post["cashier_id"]) ? $post["cashier_id"] : '';  
	              $post_data["company_id"] = isset($post["company_id"]) ? $post["company_id"] : '';  
	              $post_data["pay_type"] = isset($post["pay_type"]) ? $post["pay_type"] : 0;  
	              $result      = $this->ReportModel->listCashReport($post_data);
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code	
	               log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));                        
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;
	          
	    }
	}
	public function listCreditReport()
	{       
	    switch($this->input->method(TRUE))
	    {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["todate"] = isset($post["todate"]) ? $post["todate"] : '';    
	              $post_data["fromdate"] = isset($post["fromdate"]) ? $post["fromdate"] : '';    
	              $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';  
	              $post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
	              $post_data["tpa_id"] = isset($post["tpa_id"]) ? $post["tpa_id"] : '';  
	              $post_data["doctor_id"] = isset($post["doctor_id"]) ? $post["doctor_id"] : '';  
	              $post_data["cashier_id"] = isset($post["cashier_id"]) ? $post["cashier_id"] : '';  
	              $post_data["company_id"] = isset($post["company_id"]) ? $post["company_id"] : '';  
	              $post_data["pay_type"] = isset($post["pay_type"]) ? $post["pay_type"] : 0;  
	              $result      = $this->ReportModel->listCreditReport($post_data);
	              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code	
	               log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));                        
	              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	        break;
	        
	        default:                
	              $result = array("status"=> "Failed");
	              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	        break;
	          
	    }
	}
	public function getBill()
   	{       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->ReportModel->getBill($post_data);
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  	}
	  public function downloadmedicalPdf()
	  {       
		  switch($this->input->method(TRUE))
			{     
			case 'POST':          
				  $post_data  = json_decode(file_get_contents("php://input"),true); 
				  $result      = $this->ReportModel->downloadmedicalPdf($post_data);
				  //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
				  //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
					log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
				  $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
			break;
			
			default:                
				  $result     = array("status"=> "Failed");
				  $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
			break;
			  
		  }
	  }

  	// public function downloadmedicalPdf()
    // {           

    //     switch($this->input->method(TRUE))
    //     {     
	//         case 'POST':          
	// 			$post  = json_decode(file_get_contents("php://input"),true);
	// 			$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
	// 			$post_data["todate"] = isset($post["todate"]) ? $post["todate"] : '';    
	//             $post_data["fromdate"] = isset($post["fromdate"]) ? $post["fromdate"] : ''; 
	//             $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : ''; 
	// 			$result = $this->ReportModel->downloadmedicalPdf($post_data);	 
	// 			log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));  
	// 			$this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	//         break;
	        
	//         default:                
	// 			$result     = array("status"=> "Failed");
	// 			$this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	//         break;          
 	//   	}
 	// }

 	public function downloadmedicalPdf_visitdate()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
	            $post_data["visitdate"] = isset($post["visitdate"]) ? $post["visitdate"] : ''; 
	            $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : ''; 
				$result = $this->ReportModel->downloadmedicalPdf_visitdate($post_data);	 
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
