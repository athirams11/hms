<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class InsuranceClaim extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/InsuranceClaimModel');
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
              $result      = $this->InsuranceClaimModel->getPatientCptRate($post_data);
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
  public function generateSubmissionFile()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);        
            $result   = $this->InsuranceClaimModel->generateSubmissionFile($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
            $this->ApiModel->response($result, "201"); 
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201');
        break;
          
      }
  }
  public function reGenerateSubmissionXml()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
          $post  = json_decode(file_get_contents("php://input"),true); 
          $post_data["submissin_file_id"] = isset($post["submissin_file_id"]) ? $post["submissin_file_id"] : array();
          $post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
          $post_data["tpa_id"] = isset($post["tpa_id"]) ? $post["tpa_id"] : 0;
          $post_data["tpa_code"] = isset($post["tpa_code"]) ? $post["tpa_code"] : 0;         
          $result      = $this->InsuranceClaimModel->reGenerateSubmissionXml($post_data);
          log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));  
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
              $result      = $this->InsuranceClaimModel->getBill($post_data);
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
              $result      = $this->InsuranceClaimModel->getBillByAssessment($post_data);
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
              $result      = $this->InsuranceClaimModel->getPatientDetails($post_data);
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
              $result      = $this->InsuranceClaimModel->assessmentListByDate($post_data);
             log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
   
  public function nonClaimedinvoiceList()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
          $post  = json_decode(file_get_contents("php://input"),true);
          $post_data["invoice_date_from"] = isset($post["invoice_date_from"]) ? $post["invoice_date_from"] : '';     
          $post_data["invoice_date_to"] = isset($post["invoice_date_to"]) ? $post["invoice_date_to"] : '';      
          $post_data["insurance_payer_id"] = isset($post["insurance_payer_id"]) ? $post["insurance_payer_id"] : ''; 
          $post_data["insurance_tpa_id"] = isset($post["insurance_tpa_id"]) ? $post["insurance_tpa_id"] : '';  
          $post_data["insurance_network_id"] = isset($post["insurance_network_id"]) ? $post["insurance_network_id"] : '';
          $post_data["start"] = isset($post["start"]) ? $post["start"] : 0;     
          $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;
          $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';           
          $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';           
          //print_r($post_data);            
          $result  = $this->InsuranceClaimModel->nonClaimedinvoiceList($post_data);
          log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));  
          $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
  public function submissionFileList()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
            $post  = json_decode(file_get_contents("php://input"),true);
            $post_data["created_date_from"] = isset($post["created_date_from"]) ? $post["created_date_from"] : '';
            $post_data["created_date_to"] = isset($post["created_date_to"]) ? $post["created_date_to"] : '';      
            $post_data["start"] = isset($post["start"]) ? $post["start"] : 0;     
            $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;
            $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';           
            $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';                       
            $result  = $this->InsuranceClaimModel->submissionFileList($post_data);
              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
  public function GetBillverificationDetails()
  {       
     switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);      
            $result      = $this->InsuranceClaimModel->GetBillverificationDetails($post_data);
              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;
        
          
      }
  }
  public function updateBillverificationData()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->updateBillverificationData($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function confirmBillverificationData()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->confirmBillverificationData($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function getFileContent()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->getFileContent($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function saveFileContent()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->saveFileContent($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function submittedFileList()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post  = json_decode(file_get_contents("php://input"),true);
        $post_data["submission_upload_from"] = isset($post["submission_upload_from"]) ? $post["submission_upload_from"] : '';
        $post_data["submission_upload_to"] = isset($post["submission_upload_to"]) ? $post["submission_upload_to "] : '';      
        $post_data["start"] = isset($post["start"]) ? $post["start"] : 0;     
        $post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;
        $post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : '';           
        $post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';  
            $result = $this->InsuranceClaimModel->submittedFileList($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function testUploadSubmissionFile()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->testUploadSubmissionFile($post_data);
           log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
   public function UploadSubmissionFile()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->UploadSubmissionFile($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function searchTransactions()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->searchTransactions($post_data);
          log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function GetNewPriorAuthorizationTransactions()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->GetNewPriorAuthorizationTransactions($post_data);
             log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function CheckForNewPriorAuthorizationTransactions()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->CheckForNewPriorAuthorizationTransactions($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function SetTransactionDownloaded()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->SetTransactionDownloaded($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function GetNewTransactions()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->GetNewTransactions($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function searchTransactionParams()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
          $post_data  = json_decode(file_get_contents("php://input"),true);     
          $result["direction"] = $this->InsuranceClaimModel->searchTransactionDirection();
          $result["callerLicense"] = $this->InsuranceClaimModel->searchTransactioncallerLicense();
          $result["ePartner"] = $this->InsuranceClaimModel->searchTransactionePartner();
          $result["transactionID"] = $this->InsuranceClaimModel->searchTransactiontransactionID();
          $result["transactionStatus"] = $this->InsuranceClaimModel->searchTransactiontransactionStatus();
          log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));
          $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function DownloadTransactionFile()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->DownloadTransactionFile($post_data);
             log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
            $result     = array("status"=> "Failed");
            $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;          
      }
  }
  public function getNewRemittance()
  {       
      switch($this->input->method(TRUE))
      {     
      case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true);     
            $result = $this->InsuranceClaimModel->getNewRemittance($post_data);
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
