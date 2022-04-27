<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class MasterController extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/AppointmentModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        $this->load->library("Soaprequest");
        //$this->load->model('api/ItemsModel');
        
	  }

 /* public function SearchTransactions($post_data=array())
   {       

      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);  
              //$result["op_no"] = $this->OpRegistrationModel->GenerateOpNo($post_data);          
              
              $result["doctors_list"]         = $this->MasterModel->master_dropdown_listing("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID,DS.DOCTORS_NAME,DS.SPECIALIZED_IN","DS.DOCTORS_NAME","ASC","DS.DOCTORS_SCHEDULE_STATUS = 1");
              
              log_activity('API-Appointment-options',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
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
  }*/
  public function SearchTransactions($post_data=array())
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
    //{     
      /* $body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                      <SearchTransactions xmlns="http://www.eClaimLink.ae/">
                        <login>'.SOAP_USER.'</login>
                        <pwd>'.SOAP_PASS.'</pwd>
                        <direction>1</direction>
                        <callerLicense></callerLicense>
                        <ePartner></ePartner>
                        <transactionID>2</transactionID>
                        <TransactionStatus>2</TransactionStatus>
                        <transactionFileName>METLIFE-ALICO-2019-AUG-2.xml</transactionFileName>
                        <transactionFromDate>01/09/2019 02:08:30</transactionFromDate>
                        <transactionToDate>30/09/2019 05:12:20</transactionToDate>
                        <minRecordCount>5</minRecordCount>
                        <maxRecordCount>10</maxRecordCount>
                      </SearchTransactions>
                    </soap:Body>
                  </soap:Envelope>';

        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/SearchTransactions"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
       
        $result_content =  get_string_between($result,"<foundTransactions>","</foundTransactions>");
        print_r($result_content);
        $xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
       echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
       //print_r($this->xml2json->xmlToArray($xml,"root"));
       // echo "<textarea>".$body;
    //}*/
  }
  public function GetNewPriorAuthorizationTransactions($post_data=array())
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
    //{     
      /* $body = '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                  <soap:Body>
                    <GetNewPriorAuthorizationTransactions xmlns="http://www.eClaimLink.ae/">
                    <login>'.SOAP_USER.'</login>
                      <pwd>'.SOAP_PASS.'</pwd>
                    </GetNewPriorAuthorizationTransactions>
                  </soap:Body>
                </soap:Envelope>';
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/GetNewPriorAuthorizationTransactions"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
        print_r($result);
        $result_content =  get_string_between($result,"<xmlTransaction>","</xmlTransaction>");
        $xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
        echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
        print_r($this->xml2json->xmlToArray($xml,"root"));
        //echo "<textarea>".$body;
    //}*/
  }
  
  public function UploadTransaction($user_id,$filename,$file_content)
  {  
    
   
    $this->load->library("Soaprequest");
    //$result = $this->RequestModel->UploadTransaction($user_id,$filename,$file_content);
    //if(!empty($post_data))
   // {     
        
        //echo "<textarea>".$body;

  }
  public function CheckForNewPriorAuthorizationTransactions()
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
   // {     
        /*$body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                      <CheckForNewPriorAuthorizationTransactions xmlns="http://www.eClaimLink.ae/">
                        <login>'.SOAP_USER.'</login>
                        <pwd>'.SOAP_PASS.'</pwd>  
                      </CheckForNewPriorAuthorizationTransactions>
                    </soap:Body>
                  </soap:Envelope>';
                //<fileContent>'.$post_data["file_content"].'</fileContent>
                  //<fileName>'.$post_data["filr_name"].'</fileName>
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/CheckForNewPriorAuthorizationTransactions"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
        print_r($result);
        $result_content =  get_string_between($result,"<xmlTransaction>","</xmlTransaction>");
        $xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
       // echo "<textarea>"; 
        echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
        print_r($this->xml2json->xmlToArray($xml,"root"));*/
        //echo "<textarea>".$body;
  }
  public function SetTransactionDownloaded($file_id)
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
   // {     
       /* $body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                      <SetTransactionDownloaded xmlns="http://www.eClaimLink.ae/">
                       <login>'.SOAP_USER.'</login>
                        <pwd>'.SOAP_PASS.'</pwd>  
                         <fieldId>cdff0246-170b-4679-bc20-898fa380e5e3</fieldId>
                      </SetTransactionDownloaded>
                    </soap:Body>
                  </soap:Envelope>';
                //<fileContent>'.$post_data["file_content"].'</fileContent>
                  //<fileName>'.$post_data["filr_name"].'</fileName>
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/SetTransactionDownloaded"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
         print_r($result);
       // $result_content =  get_string_between($result,"<xmlTransaction>","</xmlTransaction>");
        
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
       // echo "<textarea>"; 
       // print_r($this->xml2json->xmlToArray($xml,"root"));
        //echo "<textarea>".$body;*/
  }
  public function DownloadTransactionFile($file_id)
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
   // {     
        /*$body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                      <DownloadTransactionFile xmlns="http://www.eClaimLink.ae/">
                        <login>'.SOAP_USER.'</login>
                        <pwd>'.SOAP_PASS.'</pwd>  
                        <fileId>8d24d05a-33ee-453d-8e20-cd1779f0b43d</fileId>
                      </DownloadTransactionFile>
                  </soap:Body>
                </soap:Envelope>';
                //<fileContent>'.$post_data["file_content"].'</fileContent>
                  //<fileName>'.$post_data["filr_name"].'</fileName>
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/DownloadTransactionFile"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
        print_r($result); 
        $result_content =  get_string_between($result,"<soap:Body>","</soap:Body>");
        //$result_file_contents =  get_string_between($result,"<file>","</file>");
        $xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
        //echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
        print_r($this->xml2json->xmlToArray($xml,"root"));
        //echo "<textarea>".$body;*/
  }

  public function GetNewTransactions($post_data=array())
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
   // {     
       /* $body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                      <GetNewTransactions xmlns="http://www.eClaimLink.ae/">
                        <login>'.SOAP_USER.'</login>
                        <pwd>'.SOAP_PASS.'</pwd>  
                      </GetNewTransactions>
                    </soap:Body>
                  </soap:Envelope>';
                //<fileContent>'.$post_data["file_content"].'</fileContent>
                  //<fileName>'.$post_data["filr_name"].'</fileName>
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/GetNewTransactions"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
         print_r($result);
       // $result_content =  get_string_between($result,"<xmlTransaction>","</xmlTransaction>");
        
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
       // echo "<textarea>"; 
       // print_r($this->xml2json->xmlToArray($xml,"root"));
        //echo "<textarea>".$body;*/
  }
} 
?>
