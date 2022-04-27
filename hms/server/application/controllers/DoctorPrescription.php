<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DoctorPrescription extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/DoctorPrescriptionModel');        
        $this->load->model('api/MasterDataModel');        
        
	}

	public function listRouteOfAdmin()
	{       
	  	switch($this->input->method(TRUE))
	  	{     
		    case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true); 
				//print_r($post_data);                 
				$post_data["routeofadmin_id"] = isset($post["routeofadmin_id"]) ? $post["routeofadmin_id"] : '';    
				$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
				$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
				$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
				$post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';  
				$result      = $this->DoctorPrescriptionModel->listRouteOfAdmin($post_data);
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
	public function getRouteOfAdmin()
	{       
		switch($this->input->method(TRUE))
		{     
		    case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true); 
				//print_r($post_data);                 
				$post_data["route_arr_id"] = isset($post["route_arr_id"]) ? $post["route_arr_id"] : '';    
				$result      = $this->DoctorPrescriptionModel->getRouteOfAdmin($post_data);
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
	public function listPrescription()
	{       
		switch($this->input->method(TRUE))
		{     
			case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true); 
				//print_r($post_data);       
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
				$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;            
				$post_data["doctor_prescription_id"] = isset($post["doctor_prescription_id"]) ? $post["doctor_prescription_id"] : 0;
				$post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;              
				$result      = $this->DoctorPrescriptionModel->listPrescription($post_data);
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

 	public function getPrescription()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				//print_r($post ); 
				$post_data["doctor_prescription_id"] = isset($post["doctor_prescription_id"]) ? $post["doctor_prescription_id"] : '';

				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
				$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : "";
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";

				 
				$result  = $this->DoctorPrescriptionModel->getPrescription($post_data);
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
	public function savePrescription()
	{       
		switch($this->input->method(TRUE))
		{     
			case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true); 
				//print_r($post['prescription_array']);exit;

				if(!empty($post['prescription_array']))
				{
					$result = $this->DoctorPrescriptionModel->savePrescription($post);
				}
				else
				{
					$result = array("status"=> "Failed" ,"msg" => "Please enter prescription details");
				}
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

	public function deletePrescription()
	{  
		switch($this->input->method(TRUE))
		{     
		    case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				//print_r($post ); 
				$post_data["doctor_prescription_id"] = isset($post["doctor_prescription_id"]) ? $post["doctor_prescription_id"] : '';       
				$result      = $this->DoctorPrescriptionModel->deletePrescription($post_data);
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
	public function getPreviousPrescription()
	{           
		switch($this->input->method(TRUE))
		{     
		    case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
				$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
				$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;  
				$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
				$post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';  
				 
				$result  = $this->DoctorPrescriptionModel->getPreviousPrescription($post_data);
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



	public function getCancelPrescription()
	{           
		switch($this->input->method(TRUE))
		{     
		    case 'POST':          
				$post  = json_decode(file_get_contents("php://input"),true);
				$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
				$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
				$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
				$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;  
				$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
				$post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';  
				 
				$result  = $this->DoctorPrescriptionModel->getCancelPrescription($post_data);
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
	 
	public function generateRxFile()
	{       
		switch($this->input->method(TRUE))
		{     
			case 'POST':          
			    $post_data  = json_decode(file_get_contents("php://input"),true);        
			    $result   = $this->DoctorPrescriptionModel->generateRxFile($post_data);
			    log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
			    $this->ApiModel->response($result, "201"); 
			break;

			default:                
			      $result     = array("status"=> "Failed");
			      $this->ApiModel->response($result,'201');
			break;
		  
		}
	}
	/*public function uploadeRxFile()
	{       
	switch($this->input->method(TRUE))
	{     
	case 'POST':          
	    $post_data  = json_decode(file_get_contents("php://input"),true);     
	    $result = $this->DoctorPrescriptionModel->uploadeRxFile($post_data);
	    log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));
	    $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	break;

	default:                
	    $result     = array("status"=> "Failed");
	    $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	break;          
	}
	}*/

	}

	?>
