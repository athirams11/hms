<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CurrentProceduralCode extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/CurrentProceduralCodeModel');        
        
	 }
	 public function options()
   {       

      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);  
              //$result["op_no"] = $this->OpRegistrationModel->GenerateOpNo($post_data);          
              
              //$result["CPT_CATEGORYS"]         = $this->MasterModel->master_dropdown_listing("CPT_CATEGORY CC","CC.CPT_CATEGORY_ID,CC.CPT_CATEGORY_NAME,CC.CPT_CATEGORY_CODE","CC.CPT_CATEGORY_NAME","ASC","CC.CPT_CATEGORY_STATUS = 1");
              $result["CPT_CATEGORYS"]     = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 9 AND O.OPTIONS_STATUS = 1");
              $result["CPT_GROUP"]     = $this->MasterModel->master_dropdown_listing("CPT_GROUP G","G.CPT_GROUP_CODE,G.CPT_GROUP_NAME,G.CPT_GROUP_ID","G.CPT_GROUP_NAME","ASC","G.CPT_GROUP_STATUS = 1");
              $result["DENTAL_PROCEDURE"] = $this->MasterModel->master_dropdown_listing("MASTER_DENTAL_PROCEDURE","*","PROCEDURE_NAME","ASC","STATUS = 1");
            //   $result["DISCOUNT_SITE"]   = $this->MasterModel->master_dropdown_listing("MASTER_DISCOUNT_SITES","DISCOUNT_SITES_ID,DISCOUNT_SITES_NAME,STATUS","DISCOUNT_SITES_NAME","ASC","STATUS = 1");
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
	 
	 public function listCurrentProceduralCode()
	 {       
	      switch($this->input->method(TRUE))
	      {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true); 
	              //print_r($post_data);       
	              $post_data["current_procedural_code_id"] = isset($post["current_procedural_code_id"]) ? $post["current_procedural_code_id"] : '';     
	              $post_data["procedure_code_id"] = isset($post["procedure_code_id"]) ? $post["procedure_code_id"] : '';     
                $post_data["procedure_code_category"] = isset($post["procedure_code_category"]) ? $post["procedure_code_category"] : 0;     
                $post_data["dental_procedure_id"] = isset($post["dental_procedure_id"]) ? $post["dental_procedure_id"] : 0;     
	              $post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
	              $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
	              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';     
	              $result      = $this->CurrentProceduralCodeModel->listCurrentProceduralCode($post_data);
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
   public function listCurrentProceduralCodeforTreatment()
   {       
        switch($this->input->method(TRUE))
        {     
          case 'POST':          
                $post  = json_decode(file_get_contents("php://input"),true); 
                //print_r($post_data);       
                $post_data["current_procedural_code_id"] = isset($post["current_procedural_code_id"]) ? $post["current_procedural_code_id"] : '';     
                $post_data["procedure_code_id"] = isset($post["procedure_code_id"]) ? $post["procedure_code_id"] : '';     
                $post_data["procedure_code_category"] = isset($post["procedure_code_category"]) ? $post["procedure_code_category"] : 0;     
                $post_data["dental_procedure_id"] = isset($post["dental_procedure_id"]) ? $post["dental_procedure_id"] : 0;     
                $post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
                $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
                $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';     
                $result      = $this->CurrentProceduralCodeModel->listCurrentProceduralCodeforTreatment($post_data);
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
  
	 public function getCurrentProceduralCode()
    {           
        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["current_procedural_code_id"] = isset($post["current_procedural_code_id"]) ? $post["current_procedural_code_id"] : '';       
	              $result      = $this->CurrentProceduralCodeModel->getCurrentProceduralCode($post_data);
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
   
   public function getCPTByCode()
  {           
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post  = json_decode(file_get_contents("php://input"),true);
        //print_r($post ); 
        $post_data["current_procedure_code"] = isset($post["current_procedure_code"]) ? $post["current_procedure_code"] : '';       
        $result      = $this->CurrentProceduralCodeModel->getCPTByCode($post_data);
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
  public function getCurrentDentalByDentalCode()
  {           
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post  = json_decode(file_get_contents("php://input"),true);
        //print_r($post ); 
        $post_data["dental_code"] = isset($post["dental_code"]) ? $post["dental_code"] : '';       
        $result      = $this->CurrentProceduralCodeModel->getCurrentDentalByDentalCode($post_data);
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
	 public function saveCurrentProceduralCode()
	 {       
        switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["current_procedural_code_id"] = isset($post["current_procedural_code_id"]) ? $post["current_procedural_code_id"] : '';
              $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : '';
              /*
              $post_data["procedure_code_id"] = isset($post["procedure_code_id"]) ? $post["procedure_code_id"] : 0;              
              $post_data["current_procedural_code_category"] = isset($post["current_procedural_code_category"]) ? $post["current_procedural_code_category"] : '';
              */
              $post_data["current_procedural_code_type"] = isset($post["current_procedural_code_type"]) ? $post["current_procedural_code_type"] : '';                                   
              $post_data["current_procedural_code_group"] = isset($post["current_procedural_code_group"]) ? $post["current_procedural_code_group"] : '';                                   
              $post_data["current_procedural_code"] = isset($post["current_procedural_code"]) ? $post["current_procedural_code"] : '';                                   
              $post_data["current_procedural_code_name"] = isset($post["current_procedural_code_name"]) ? $post["current_procedural_code_name"] : '';                                   
              $post_data["current_procedural_code_alias_name"] = isset($post["current_procedural_code_alias_name"]) ? $post["current_procedural_code_alias_name"] : '';                                   
              $post_data["current_procedural_code_description"] = isset($post["current_procedural_code_description"]) ? $post["current_procedural_code_description"] : '';                                   
              $post_data["current_procedural_code_order"] = isset($post["current_procedural_code_order"]) ? $post["current_procedural_code_order"] : 0;
              $post_data["current_procedural_code_rate"] = isset($post["current_procedural_code_rate"]) ? $post["current_procedural_code_rate"] : 0;
              $post_data["current_dental_procedure"] = isset($post["current_dental_procedure"]) ? $post["current_dental_procedure"] : 0;
              $post_data["current_procedure_discount_site"] = isset($post["current_procedure_discount_site"]) ? $post["current_procedure_discount_site"] : 0;
               $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : ''; 
		 
              $result = $this->CurrentProceduralCodeModel->saveCurrentProceduralCode($post_data);
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
  
	 public function deleteCurrentProceduralCode()
    {           

        switch($this->input->method(TRUE))
        {     
	        case 'POST':          
	              $post  = json_decode(file_get_contents("php://input"),true);
	              //print_r($post ); 
	              $post_data["current_procedural_code_id"] = isset($post["current_procedural_code_id"]) ? $post["current_procedural_code_id"] : '';       
	              $result      = $this->CurrentProceduralCodeModel->deleteCurrentProceduralCode($post_data);
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
