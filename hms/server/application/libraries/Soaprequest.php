<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Soaprequest{
				
	public function soap_call($xml_soap, $service_url, $SOAPAction, $Host, $result_format,$format="json") {
  	 
	   $this->CI =& get_instance();
      $this->CI->load->library('xml2json');
        
	   $header = array(
   	"Content-type: text/xml;charset=\"utf-8\"",
    	"Accept: text/xml",
    	"Cache-Control: no-cache",
    	"Pragma: no-cache",
    	"SOAPAction:".$SOAPAction,
    	"Host: ".$Host,
    	"Content-length: ".strlen($xml_soap),
  		);  
  		
		$soap_do = curl_init();
  		curl_setopt($soap_do, CURLOPT_URL, $service_url );
  		//curl_setopt($soap_do, CURLOPT_URL, "dhpo.eclaimlink.ae/ValidateTransactions.asmx" );
  		curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 60);
  		curl_setopt($soap_do, CURLOPT_TIMEOUT,        60);
  		curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
  		curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
  		curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
  		curl_setopt($soap_do, CURLOPT_POST,           true );
  		curl_setopt($soap_do, CURLOPT_POSTFIELDS,     $xml_soap);
  		curl_setopt($soap_do, CURLOPT_HTTPHEADER,     $header);

  		$response = curl_exec($soap_do);
    		
  		if($response === false || $response =='') {
  			$err = 'Curl error: ' .curl_errno($soap_do)." ".curl_getinfo($soap_do, CURLINFO_HTTP_CODE)." ". curl_error($soap_do);
    		curl_close($soap_do);
    		return $err;//'{"M2":{"Data":{"header":{"returnMsg":"Service not available for this request.","ACTION_ID":"'.$actionId.'","REQUEST_ID":"'.$requestId.'","returnCode":"S-API-00004"},"body":[]}}}';
   
  		} else {
    		curl_close($soap_do);   
    	
    	if($format == 'xml'){    			
    		return $response;    		
    	}else{		
    			// for xml to json
				$xml_head_count=substr_count($response,'xml version');
				$xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
				$xmlArray =  $this->CI->xml2json->xmlToArray($xml,'root');
				$arrayData = $xmlArray;					
				$arr_tags= explode('|',$result_format);
				foreach($arr_tags as $tag)
				{
					if(isset($arrayData[$tag]))
					{				
						$arrayData = $arrayData[$tag];
					}
				}
			 
				if($xml_head_count>1){
					$xml = simplexml_load_string($arrayData);
					$arrayData = $this->CI->xml2json->xmlToArray($xml,'root');	
				}				 
				return json_encode($arrayData);
			}
  		}
	}
	
	public function soap_service_call($service_arr, $params,$bodyparams='')
	{					
			$this->CI =& get_instance();			
			$model_name = 'Model_'.ucfirst(strtolower($service_arr["MODEL_NAME"]));			
      	$this->CI->load->model($model_name);       
			$xml_soap = $this->CI->{$model_name}->xmlSoap($service_arr["SERVICE_NAME"],$params,$bodyparams);
			return $this->soap_call($xml_soap, $service_arr["SERVICE_URL"], $service_arr["SERVICE_NAME"], $params['REQUEST_ID'], $service_arr["SERVICE_RESULT_FORMAT"]);			     	 		
	}
	
	public function soap_response($response,$result_format) 
	{			
		$this->CI =& get_instance();
      $this->CI->load->library('xml2json');
  		
	
		$xml_head_count=substr_count($response,'xml version');
		$xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
		$xmlArray =  $this->CI->xml2json->xmlToArray($xml,'root');
		$arrayData = $xmlArray;
			
		$arr_tags= explode('|',$result_format);
		foreach($arr_tags as $tag)
		{
				if(isset($arrayData[$tag]))
				{				
					$arrayData = $arrayData[$tag];
				}
		}
		
		if($xml_head_count>1){
				$xml = simplexml_load_string($arrayData);
				$arrayData = $this->CI->xml2json->xmlToArray($xml,'root');	
		}			
		return json_encode($arrayData);
  		
	}
	
	
}

?>