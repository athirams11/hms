<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class Bill extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/BillModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	  }

 
  public function getPatientCptRate()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->BillModel->getPatientCptRate($post_data);
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
              $result["status"] = "Success";
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
  public function saveBillresult()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $post_data["lab_details_id"] = (isset($post_data["lab_details_id"]))? $post_data["lab_details_id"] : '';
              $post_data["billing_details_id"] = (isset($post_data["billing_details_id"]))? $post_data["billing_details_id"] : '';
              $post_data["prior_authorization"] = (isset($post_data["prior_authorization"]))? $post_data["prior_authorization"] : '';
              $post_data["test_result"] = (isset($post_data["test_result"]))? $post_data["test_result"] : '';
              $result      = $this->BillModel->saveBillresult($post_data);
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
  public function generatePatientBill()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);    
              $post_data["payment_mode"] = (isset($post_data["payment_mode"]))? $post_data["payment_mode"] : 0;        
              $result      = $this->BillModel->generatePatientBill($post_data);
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
   public function savePatientBill()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
             //print_r($post_data);            
              $result      = $this->BillModel->savePatientBill($post_data);
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

  public function savediscount()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
             //print_r($post_data);            
              $result      = $this->BillModel->savediscount($post_data);
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
  public function getLabInvestigation()
    {           
        switch($this->input->method(TRUE))
        {     
          case 'POST':          
                $post  = json_decode(file_get_contents("php://input"),true);
                //print_r($post ); 
                $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : '';
                
                $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
                $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
                $post_data["department_id"] = isset($post["department_id"]) ? $post["department_id"] : "";
                
                       
                $result  = $this->BillModel->getLabInvestigation($post_data);
                log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
                //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                
                $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
          break;
          
          default:                
                $result     = array("status"=> "Failed");
                $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
          break;          
        }
   }
  public function getLabInvestigationResults()
    {           
        switch($this->input->method(TRUE))
        {     
          case 'POST':          
                $post  = json_decode(file_get_contents("php://input"),true);
                //print_r($post ); 
                $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : '';
                
                $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
                $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
                
                       
                $result  = $this->BillModel->getLabInvestigationResults($post_data);
                 log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
                //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                
                $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
          break;
          
          default:                
                $result     = array("status"=> "Failed");
                $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
          break;          
        }
   }
  public function saveLabInvestigationResults()
    {           
        switch($this->input->method(TRUE))
        {     
          case 'POST':          
                $post  = json_decode(file_get_contents("php://input"),true);
                //print_r($post ); 
                $post_data["save_details"] = isset($post["save_details"]) ? $post["save_details"] : '';
                
                
                       
                $result  = $this->BillModel->saveLabInvestigationResults($post_data);
                //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                 log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
                
                $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
          break;
          
          default:                
                $result     = array("status"=> "Failed");
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
              $result      = $this->BillModel->getBill($post_data);
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

  public function getBillByAssessment()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->BillModel->getBillByAssessment($post_data);
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
  public function getPatientDetails()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->BillModel->getPatientDetails($post_data);
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


  public function getPendingAmount()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->BillModel->getPendingAmount($post_data);
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
  
  public function assessmentListByDatefordept()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->BillModel->assessmentListByDatefordept($post_data);
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response)
              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));    ;
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
  
  public function invoiceList()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true);
              $post_data["invoice_date_from"] = isset($post["invoice_date_from"]) ? $post["invoice_date_from"] : '';      
              $post_data["invoice_date_to"] = isset($post["invoice_date_to"]) ? $post["invoice_date_to"] : '';      
              $post_data["insurance_payer_id"] = isset($post["insurance_payer_id"]) ? $post["insurance_payer_id"] : '';      
              $post_data["insurance_network_id"] = isset($post["insurance_network_id"]) ? $post["insurance_network_id"] : '';
              $post_data["start"] = isset($post["start"]) ? $post["start"] : 0;     
	           $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;
              $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';           
              //print_r($post_data);            
              $result  = $this->BillModel->invoiceList($post_data);
               log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
  public function saveInvestigationByCashier()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post  = json_decode(file_get_contents("php://input"),true); 
          
        $post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
        $post_data["doctor_id"] = isset($post["doctor_id"]) ? $post["doctor_id"] : "";
        $post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
        $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
        $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : 0;
        $post_data["current_procedural_code_id"] = isset($post["current_procedural_code_id"]) ? $post["current_procedural_code_id"] : 0;
        $post_data["lab_investigation_details_id"] = isset($post["lab_investigation_details_id"]) ? $post["lab_investigation_details_id"] : 0;
        $post_data["description"] = isset($post["description"]) ? $post["description"] : "";            
        $post_data["quantity"] = isset($post["quantity"]) ? $post["quantity"] : "";
        $post_data["rate"] = isset($post["rate"]) ? $post["rate"] : "";
        $post_data["change_of_future"] = isset($post["change_of_future"]) ? $post["change_of_future"] : "";
        $post_data["remarks"] = isset($post["remarks"]) ? $post["remarks"] : "";
        $post_data["priority"] = isset($post["priority"]) ? $post["priority"] : "";
     
        $post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';  
        $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';
        $post_data["date"] = isset($post["date"]) ? $post["date"] : '';
          
        $result = $this->BillModel->saveInvestigationByCashier($post_data);
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
  public function deleteInvestigationByCashier()
    {   
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true);
              //print_r($post ); 
              $post_data["lab_investigation_details_id"] = isset($post["lab_investigation_details_id"]) ? $post["lab_investigation_details_id"] : '';       
              $post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : '';       
              $post_data["rate"] = isset($post["rate"]) ? $post["rate"] : '';       
              $result      = $this->BillModel->deleteInvestigationByCashier($post_data);
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
