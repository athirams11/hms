<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/BillingModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	  }

  public function options()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);  
              $result["INSURANCE_CONST"] = INSURANCE_ID_CONSTANT;          
              $result["specialized_in"]         = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 8 AND O.OPTIONS_STATUS = 1");
              
              

              $result["tpa_receiver"]     = $this->MasterModel->master_dropdown_listing("TPA T","T.TPA_ID,T.TPA_NAME,T.TPA_ECLAIM_LINK_ID","T.TPA_ECLAIM_LINK_ID","ASC","T.TPA_STATUS = 1");
              $result["networks"]     = $this->MasterModel->master_dropdown_listing("INS_NETWORK N","N.INS_NETWORK_ID,N.INS_NETWORK_NAME,N.INS_NETWORK_CODE,N.TPA_ID","N.INS_NETWORK_NAME ","ASC","N.INS_NETWORK_STATUS = 1");
              $result["ins_com_pay"]     = $this->MasterModel->master_dropdown_listing("INSURANCE_PAYERS I","I.INSURANCE_PAYERS_ID,I.INSURANCE_PAYERS_ECLAIM_LINK_ID,I.INSURANCE_PAYERS_NAME","I.INSURANCE_PAYERS_ECLAIM_LINK_ID","ASC","I.INSURANCE_PAYERS_STATUS = 1");


              $result["co_in_types"]     = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 9 AND O.OPTIONS_STATUS = 1");


              $result["payment_modes"]     = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 10 AND O.OPTIONS_STATUS = 1");


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
  public function getPatientCptRate()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->BillingModel->getPatientCptRate($post_data);
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
              $result      = $this->BillingModel->saveBillresult($post_data);
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
              $result      = $this->BillingModel->generatePatientBill($post_data);
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
              $result      = $this->BillingModel->savePatientBill($post_data);
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
                
                       
                $result  = $this->BillingModel->getLabInvestigation($post_data);
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
                
                       
                $result  = $this->BillingModel->getLabInvestigationResults($post_data);
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
                
                
                       
                $result  = $this->BillingModel->saveLabInvestigationResults($post_data);
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
              $result      = $this->BillingModel->getBill($post_data);
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
              $result      = $this->BillingModel->getBillByAssessment($post_data);
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
              $result      = $this->BillingModel->getPatientDetails($post_data);
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
              $result      = $this->BillingModel->getPendingAmount($post_data);
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
  
  public function assessmentListByDate()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->BillingModel->assessmentListByDate($post_data);
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
              $result  = $this->BillingModel->invoiceList($post_data);
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
          
        $result = $this->BillingModel->saveInvestigationByCashier($post_data);
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
              $result      = $this->BillingModel->deleteInvestigationByCashier($post_data);
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
