<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RequestModel extends CI_Model 
{
	function __construct()
	{
		$this->load->library("Soaprequest");
	    parent::__construct();
	    $this->load->database();
	}
	public function UploadTransaction($user_id,$filename,$file_content)
  	{ 
  		//echo $user_id;
  		$body = '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                  <soap:Body>
                    <UploadTransaction xmlns="http://www.eClaimLink.ae/">
                      <login>'.getInstitutionSettings()->INSTITUTION_DHPO_LOGIN.'</login>
                      <pwd>'.getInstitutionSettings()->INSTITUTION_DHPO_PASS.'</pwd>  
                      <fileName>'.$filename.'</fileName>
                       <fileContent>'.$file_content.'</fileContent>
                    </UploadTransaction>
                  </soap:Body>
                </soap:Envelope>';
                //<fileContent>'.$post_data["file_content"].'</fileContent>
                  //<fileName>'.$post_data["filr_name"].'</fileName>
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/UploadTransaction";   
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
        //echo $body; 
        //echo $result; 
        save_sop_request($Host, $url, "POST", $SOAPAction, $body, $result, $user_id);
       return $result;

  	}    
 public function SearchTransactions($post_data=array())
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
    //{     
        $body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                      <SearchTransactions xmlns="http://www.eClaimLink.ae/">
                        <login>'.getInstitutionSettings()->INSTITUTION_DHPO_LOGIN.'</login>
                        <pwd>'.getInstitutionSettings()->INSTITUTION_DHPO_PASS.'</pwd>
                        <direction>'.$post_data["direction"].'</direction>
                        <callerLicense>'.$post_data["callerLicense"].'</callerLicense>
                        <ePartner>'.$post_data["ePartner"].'</ePartner>
                        <transactionID>'.$post_data["transactionID"].'</transactionID>
                        <TransactionStatus>'.$post_data["transactionStatus"].'</TransactionStatus>
                        <transactionFileName>'.$post_data["transactionFileName"].'</transactionFileName>
                        <transactionFromDate>'.format_date($post_data["transactionFromDate"],3).'</transactionFromDate>
                        <transactionToDate>'.format_date($post_data["transactionToDate"],4).'</transactionToDate>
                        <minRecordCount>'.$post_data["minRecordCount"].'</minRecordCount>
                        <maxRecordCount>'.$post_data["maxRecordCount"].'</maxRecordCount>
                      </SearchTransactions>
                    </soap:Body>
                  </soap:Envelope>';

        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/SearchTransactions"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
        save_sop_request($Host, $url, "POST", $SOAPAction, $body, $result, $user_id);
       // echo $result;
        return $result;
        //filename METLIFE-ALICO-2019-AUG-2.xml
        //date format 01/09/2019 02:08:30
       // echo "<textarea>".$body;
    //}
  }
 public function GetNewPriorAuthorizationTransactions()
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
    //{     
        $body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                  <soap:Body>
                    <GetNewPriorAuthorizationTransactions xmlns="http://www.eClaimLink.ae/">
                    <login>'.getInstitutionSettings()->INSTITUTION_DHPO_LOGIN.'</login>
                      <pwd>'.getInstitutionSettings()->INSTITUTION_DHPO_PASS.'</pwd>
                    </GetNewPriorAuthorizationTransactions>
                  </soap:Body>
                </soap:Envelope>';
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/GetNewPriorAuthorizationTransactions"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
        save_sop_request($Host, $url, "POST", $SOAPAction, $body, $result, $user_id);

       // print_r($result);
         return $result;
        //$result_content =  get_string_between($result,"<xmlTransaction>","</xmlTransaction>");
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
        //echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
       // print_r($this->xml2json->xmlToArray($xml,"root"));
        //echo "<textarea>".$body;
    //}
  }
   public function CheckForNewPriorAuthorizationTransactions($post_data=array())
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
   // {     
        $body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                      <CheckForNewPriorAuthorizationTransactions xmlns="http://www.eClaimLink.ae/">
                        <login>'.getInstitutionSettings()->INSTITUTION_DHPO_LOGIN.'</login>
                        <pwd>'.getInstitutionSettings()->INSTITUTION_DHPO_PASS.'</pwd>  
                      </CheckForNewPriorAuthorizationTransactions>
                    </soap:Body>
                  </soap:Envelope>';
                //<fileContent>'.$post_data["file_content"].'</fileContent>
                  //<fileName>'.$post_data["filr_name"].'</fileName>
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/CheckForNewPriorAuthorizationTransactions"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
        save_sop_request($Host, $url, "POST", $SOAPAction, $body, $result, $user_id);
       // print_r($result);
         return $result;
        //$result_content =  get_string_between($result,"<xmlTransaction>","</xmlTransaction>");
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
       // echo "<textarea>"; 
       // echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
        //print_r($this->xml2json->xmlToArray($xml,"root"));
        //echo "<textarea>".$body;
  }
  public function SetTransactionDownloaded($file_id)
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
   // {     
    print_r($file_id);
        $body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                      <SetTransactionDownloaded xmlns="http://www.eClaimLink.ae/">
                       <login>'.getInstitutionSettings()->INSTITUTION_DHPO_LOGIN.'</login>
                        <pwd>'.getInstitutionSettings()->INSTITUTION_DHPO_PASS.'</pwd>  
                         <fieldId>'.$file_id.'</fieldId>
                      </SetTransactionDownloaded>
                    </soap:Body>
                  </soap:Envelope>';
                  //<file_id>cdff0246-170b-4679-bc20-898fa380e5e3</file_id>
                //<fileContent>'.$post_data["file_content"].'</fileContent>
                  //<fileName>'.$post_data["filr_name"].'</fileName>
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/SetTransactionDownloaded"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
         //print_r($result);
         return $result;
       // $result_content =  get_string_between($result,"<xmlTransaction>","</xmlTransaction>"); 
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
       // echo "<textarea>"; 
       // print_r($this->xml2json->xmlToArray($xml,"root"));
        //echo "<textarea>".$body;
  }
  public function GetNewTransactions($post_data=array())
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
   // {     
        $body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                      <GetNewTransactions xmlns="http://www.eClaimLink.ae/">
                        <login>'.getInstitutionSettings()->INSTITUTION_DHPO_LOGIN.'</login>
                        <pwd>'.getInstitutionSettings()->INSTITUTION_DHPO_PASS.'</pwd>  
                      </GetNewTransactions>
                    </soap:Body>
                  </soap:Envelope>';
                //<fileContent>'.$post_data["file_content"].'</fileContent>
                  //<fileName>'.$post_data["filr_name"].'</fileName>
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/GetNewTransactions"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
        //print_r($result);
         return $result;
       // $result_content =  get_string_between($result,"<xmlTransaction>","</xmlTransaction>");
        
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
       // echo "<textarea>"; 
       // print_r($this->xml2json->xmlToArray($xml,"root"));
        //echo "<textarea>".$body;
  }
  public function DownloadTransactionFile($file_id)
  {       
    $this->load->library("Soaprequest");
    //if(!empty($post_data))
   // {     
        $body = '<?xml version="1.0" encoding="utf-8"?>
                  <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                      <DownloadTransactionFile xmlns="http://www.eClaimLink.ae/">
                        <login>'.getInstitutionSettings()->INSTITUTION_DHPO_LOGIN.'</login>
                        <pwd>'.getInstitutionSettings()->INSTITUTION_DHPO_PASS.'</pwd>  
                         <fileId>'.$file_id.'</fileId>
                       <fileId>8d24d05a-33ee-453d-8e20-cd1779f0b43d</fileId>
                      </DownloadTransactionFile>
                  </soap:Body>
                </soap:Envelope>';
                //<fileId>8d24d05a-33ee-453d-8e20-cd1779f0b43d</fileId>
        $url = SOAP_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/DownloadTransactionFile"; 
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
        //print_r($result); 
        return $result;
        //$result_content =  get_string_between($result,"<soap:Body>","</soap:Body>");
        //$result_file_contents =  get_string_between($result,"<file>","</file>");
        //$xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement', LIBXML_NOCDATA);
        //echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
        //print_r($this->xml2json->xmlToArray($xml,"root"));
        //echo "<textarea>".$body;
  }
  public function UploadERxRequest($user_id,$filename,$file_content)
    { 
      //echo $user_id;
      $body = '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                  <soap:Body>
                    <UploadERxRequest xmlns="http://www.eClaimLink.ae/">
                      <facilityLogin>'.getInstitutionSettings()->INSTITUTION_DHPO_LOGIN.'</facilityLogin>
                      <facilityPwd>'.getInstitutionSettings()->INSTITUTION_DHPO_PASS.'</facilityPwd>
                      <clinicianLogin>'.getClinicainDetails($user_id)->CLINICIAN_USER.'</clinicianLogin>
                      <clinicianPwd>'.getClinicainDetails($user_id)->CLINICIAN_PASS.'</clinicianPwd>
                      <fileContent>'.$file_content.'</fileContent>
                      <fileName>'.$filename.'</fileName>
                    </UploadERxRequest>
                  </soap:Body>
                </soap:Envelope>';
                //<fileContent>'.$post_data["file_content"].'</fileContent>
                  //<fileName>'.$post_data["filr_name"].'</fileName>
        $url = ERX_URL;//"dhpo.eclaimlink.ae/ValidateTransactions.asmx"; 
        $Host = SOAP_HOST;//"dhpo.eclaimlink.ae"; 
        $SOAPAction = "http://www.eClaimLink.ae/UploadERxRequest";   
        $result = $this->soaprequest->soap_call($body,$url,$SOAPAction,$Host,"Envelope|soap:Body","xml");
        //echo $body; 
        //echo $result; 
        save_sop_request($Host, $url, "POST", $SOAPAction, $body, $result, $user_id);

      return $result;
       

    }    
} 
?>