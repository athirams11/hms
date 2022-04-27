<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Laboratory extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/LaboratoryModel');        
        $this->load->model('api/MasterDataModel');        
        
	 }
	 
	 public function listType()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              
	                 
	             $post_data["start"] = isset($post["start"]) ? $post["start"] : '';     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : '';     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
	              
	              $result      = $this->LaboratoryModel->listType($post_data);
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


public function attachradiology()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true);           
              $result      = $this->LaboratoryModel->attachradiology($post_data);
                log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }

  public function getattachradio()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->LaboratoryModel->getattachradio($post_data);
              //print_r($result);
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

	public function listReports()
		 {       
		      switch($this->input->method(TRUE))
		      {     
		        case 'POST':          
		              $post  = json_decode(file_get_contents("php://input"),true); 
		              $post_data["module_id"] = isset($post["module_id"]) ? $post["module_id"] : '';
					  $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
		              $result      = $this->LaboratoryModel->listDocuments($post_data);	    
		              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));              
		              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
		        break;
		        
		        default:                
		              $result     = array("status"=> "Failed");
		              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
		        break;
		          
		      }
		 }



public function getradiologyReports()
		 {       
		      switch($this->input->method(TRUE))
		      {     
		        case 'POST':          
		              $post  = json_decode(file_get_contents("php://input"),true); 
		              $post_data["module_id"] = isset($post["module_id"]) ? $post["module_id"] : '';
					  $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
		              $result      = $this->LaboratoryModel->getradiologyReports($post_data);	    
		              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));              
		              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
		        break;
		        
		        default:                
		              $result     = array("status"=> "Failed");
		              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
		        break;
		          
		      }
		 }
		 
 public function changeStatus()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->LaboratoryModel->changeStatus($post_data);
        log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
        //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
        $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
        $result   = array('message'=>"Failed",
            'status'=>'Failed',
            );
        $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;
          
    }
  }

  public function removefile()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->LaboratoryModel->removefile($post_data);
        log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
        //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
        $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
        $result   = array('message'=>"Failed",
            'status'=>'Failed',
            );
        $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;
          
    }
  }

   public function removeradiofile()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->LaboratoryModel->removeradiofile($post_data);
        log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
        //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
        $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
        $result   = array('message'=>"Failed",
            'status'=>'Failed',
            );
        $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;
          
    }
  }

	 public function getType()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["type_id"] = isset($post["type_id"]) ? $post["type_id"] : '';       
	              $result      = $this->LaboratoryModel->getType($post_data);
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


 	 public function getlab()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["p_number"] = isset($post["p_number"]) ? $post["p_number"] : ''; 
	              $post_data["doctor"] = isset($post["doctor"]) ? $post["doctor"] : '';       
	              $result      = $this->LaboratoryModel->getlab($post_data);
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




 	 public function saveType()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
               $post_data["type_id"] = isset($post["type_id"]) ? $post["type_id"] : '';     
               
	              $post_data["type"] = isset($post["type"]) ? $post["type"] : '';  
	              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';  
	              $post_data["type_status"] = isset($post["type_status"]) ? $post["type_status"] : '';  
	             
		 
              $result = $this->LaboratoryModel->saveType($post_data);
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



public function listTest()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              
	                 
	             $post_data["start"] = isset($post["start"]) ? $post["start"] : '';     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : '';     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
	              
	              $result      = $this->LaboratoryModel->listTest($post_data);
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


	 public function getTest()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["test_id"] = isset($post["test_id"]) ? $post["test_id"] : '';       
	              $result      = $this->LaboratoryModel->getTest($post_data);
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




 	 public function saveTest()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
               $post_data["test_id"] = isset($post["test_id"]) ? $post["test_id"] : '';     
               
	              $post_data["test"] = isset($post["test"]) ? $post["test"] : '';  
	              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';  
	              $post_data["test_status"] = isset($post["test_status"]) ? $post["test_status"] : '';  
	             
		 
              $result = $this->LaboratoryModel->saveTest($post_data);
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
  
	public function testoptions()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
        //print_r($post_data);  
        //$result["op_no"] = $this->OpRegistrationModel->GenerateOpNo($post_data);          

        //$result["test_list"]         = $this->MasterModel->master_dropdown_listing("LAB_TEST DS","DS.TEST_ID,DS.TEST_NAME","DS.TEST_NAME","ASC","DS.STATUS = 1");
        $result["type_list"]         = $this->MasterModel->master_dropdown_listing("LAB_SAMPLE_TYPE DS","DS.SAMPLE_TYPE_ID,DS.TYPE_NAME","DS.TYPE_NAME","ASC","DS.STATUS = 1");
        $result["mr_no"] = $this->LaboratoryModel->GenerateNo($post_data); 

        log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
        $result["status"] = "Success";
        //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
        $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
        $result     = array("status"=> "Failed");
        $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;
        
    }
  }
  


   public function listCollection()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              
	                 
	             $post_data["start"] = isset($post["start"]) ? $post["start"] : '';     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : '';     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';  
	              
	              $result      = $this->LaboratoryModel->listCollection($post_data);
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

	 public function searchCollection()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              
	                 
	             $post_data["start"] = isset($post["start"]) ? $post["start"] : '';     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : '';     
	              //$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : ''; 
	              $post_data["mrno"] = isset($post["mrno"]) ? $post["mrno"] : ''; 
	              $post_data["patient_no"] = isset($post["patient_no"]) ? $post["patient_no"] : ''; 
	              $post_data["sample_type"] = isset($post["sample_type"]) ? $post["sample_type"] : ''; 
	              $post_data["test"] = isset($post["test"]) ? $post["test"] : '';  
	              
	              $result      = $this->LaboratoryModel->searchCollection($post_data);
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

	 public function searchradiology()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	             $post_data["start"] = isset($post["start"]) ? $post["start"] : '';     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : '';  
	              $post_data["dateval"] = isset($post["dateval"]) ? $post["dateval"] : ''; 
	               $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : ''; 
	              $post_data["patient_no"] = isset($post["patient_no"]) ? $post["patient_no"] : ''; 
	              $result      = $this->LaboratoryModel->searchradiology($post_data);
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


	 // public function getCollection()
  //   {           
  //       switch($this->input->method(TRUE))
  //       {     
	 //        case 'POST':          
	 //              $post  = json_decode(file_get_contents("php://input"),true);
	 //              //print_r($post ); 
	 //              $post_data["collection_id"] = isset($post["collection_id"]) ? $post["collection_id"] : '';       
	 //              $result      = $this->LaboratoryModel->getCollection($post_data);
	 //              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
	 //                log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));       
	 //              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
	 //        break;
	        
	 //        default:                
	 //              $result     = array("status"=> "Failed");
	 //              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
	 //        break;          
  //    	  }
 	//  }




 	 public function saveCollection()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);       
              
               $post_data["collection_id"] = isset($post["collection_id"]) ? $post["collection_id"] : '';     
               
	              $post_data["mrno"] = isset($post["mrno"]) ? $post["mrno"] : '';  
	              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';  
	              $post_data["collection_type"] = isset($post["collection_type"]) ? $post["collection_type"] : ''; 
	              $post_data["sel_pay_type"] = isset($post["sel_pay_type"]) ? $post["sel_pay_type"] : '';
	              $post_data["collected_date"] = isset($post["collected_date"]) ? $post["collected_date"] : '';
	              $post_data["p_number"] = isset($post["p_number"]) ? $post["p_number"] : '';
	              $post_data["doctor"] = isset($post["doctor"]) ? $post["doctor"] : '';
	              $post_data["sample_type"] = isset($post["sample_type"]) ? $post["sample_type"] : '';
	              $post_data["test"] = isset($post["test"]) ? $post["test"] : '';
	              $post_data["status"] = isset($post["status"]) ? $post["status"] : ''; 
	               $post_data["remarks"] = isset($post["remarks"]) ? $post["remarks"] : ''; 
              $result = $this->LaboratoryModel->saveCollection($post_data);
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


     public function attachcollection()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);       
              
              	 $post_data["attach_id"] = isset($post["attach_id"]) ? $post["attach_id"] : '';     
               
	              $post_data["mrno"] = isset($post["mrno"]) ? $post["mrno"] : '';  
	              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';  
	              $post_data["collection_type"] = isset($post["collection_type"]) ? $post["collection_type"] : ''; 
	              $post_data["status"] = isset($post["status"]) ? $post["status"] : '';
	              $post_data["collected_date"] = isset($post["collected_date"]) ? $post["collected_date"] : '';
	              $post_data["patient_no"] = isset($post["patient_no"]) ? $post["patient_no"] : '';
	              $post_data["remarks"] = isset($post["remarks"]) ? $post["remarks"] : '';
	              $post_data["files"] = isset($post["files"]) ? $post["files"] : '';
	              $post_data["doc_type"] = isset($post["doc_type"]) ? $post["doc_type"] : '';
	              $post_data["doc_description"] = isset($post["doc_description"]) ? $post["doc_description"] : ''; 
              $result = $this->LaboratoryModel->attachcollection($post_data);
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
