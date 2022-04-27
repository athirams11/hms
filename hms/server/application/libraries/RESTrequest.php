<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class RESTrequest{
					
	// Method: POST, PUT, GET etc
	// Data: array("param" => "value") ==> index.php?param=value
	
	function callAPI($method, $url, $data = false)
	{
 
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    //curl_setopt($curl, CURLOPT_HEADER, true);
 
    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
	}
	
	public function RESTCall()
	{
		 
 	 $url = 'http://localhost/movicel/queryconsumptionlimit';
    $url = 'http://localhost/movicel/Accteactivation'; 		
    $data = array("first_name" => "First name","last_name" => "last name","email"=>"email@gmail.com","addresses" => array ("address1" => "some address" ,"city" => "city","country" => "CA", "first_name" =>  "Mother","last_name" =>  "Lastnameson","phone" => "555-1212", "province" => "ON", "zip" => "123 ABC" ) );
    $data_string = json_encode($data);
    // primary iden 912990207
        
	  $url =  'http://172.16.1.244/m2test/querycdr';
     //$url = 'http://localhost/movicel/Aservice'; 
     $url = 'http://172.16.1.244/m2test2/m3/Queryhotbillingstatus'; 
   
   
 	 
 	  $url = 'http://localhost/movicel/Aservice'; 
 	//  $url = 'http://172.16.1.244/m2test/changeofferingproperty'; 
    $data_string =  read_file('CBS.json');
 
    
   
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  //  curl_setopt($ch, CURLOPT_POSTFIELDS, array("customer"=>$data_string));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string );
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,
               array('Content-Type:application/json',
                      'Content-Length: ' . strlen($data_string))
               );

    $result = curl_exec($ch);
    
    curl_close($ch);
    //echo $result ;
		
	}
}

?>