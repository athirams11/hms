<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_string_between'))
{ 
	function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
	}

}

if ( ! function_exists('format_date'))
{ 
	function format_date($date,$type="")
	{
		if($date){
			$date = str_replace('/', '-', $date);
			if($type == 1) 
			{
		 		$date = date("Y-m-d",strtotime($date)); 
			}
			else if($type == 2)
			{
		 		$date = date("Y-m-d 23:59:59",strtotime($date)); 
			}
			else if($type == 3)
			{
				$date = date("Y/m/d 00:00:00",strtotime($date)); 
			}
			else if($type == 4)
			{
				$date = date("Y/m/d 23:59:59",strtotime($date)); 
			}
			else 
			{
		 		$date = date("Y-m-d H:i:s",strtotime($date)); 
			}
    		return $date;
    	}
	}
}


if ( ! function_exists('format_api_date'))
{ 
	function format_api_date($date)
	{
		if($date){
	 		$date = date("YmdHis",strtotime($date)); 
    		return $date;
    	}
	}
}

if ( ! function_exists('correct_number_format'))
{ 
	function correct_number_format($number)
	{				
		if($number!=''){
			return number_format($number, 0, ',', '.');
    	}else{
    		return 0;
    	}
	}
}


if ( ! function_exists('array_filter_recursive'))
{ 
	 function array_filter_recursive($array) {
	   foreach ($array as $key => &$value) {
	      if (empty($value)) {
	         unset($array[$key]);
	      }
	      else {
	         if (is_array($value)) {
	            $value = array_filter_recursive($value);
	            if (empty($value)) {
	               unset($array[$key]);
	            }
	         }
	      }
	   }
	
	   return $array;
	}
}

if ( ! function_exists('in_array_recursive'))
{ 

	function in_array_recursive($needle, $haystack, $strict = false) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_recursive($needle, $item, $strict))) {
	            return true;
	        }
	    }
	
	    return false;
	}
}

if ( ! function_exists('rep_str'))
{ 

	function rep_str($str, $first=0, $last=0, $rep='*'){
	  $begin = substr($str,0,$first);
	  $middle = str_repeat($rep,strlen(substr($str,$first,$last)));
	  $end = substr($str,$last);
	  $stars = $begin.$middle.$end;
	  return $stars;
	}

}

if ( ! function_exists('get_host_name'))
{ 
	function get_host_name($url){	   
			$urlData = parse_url($url);
			if(isset($urlData['scheme']) && isset($urlData['host'])) {
			return $host = $urlData['scheme'].'://'.$urlData['host'];
			}
			return false;		 
	}
}

if ( ! function_exists('escape_str'))
{ 
	function escape_str($str){	   
		return str_replace(array("'", "\""), "\\", htmlspecialchars($str));  
	}
}

function get_extension_from_mime($imagetype)
{
       if(empty($imagetype)) return false;
       switch($imagetype)
       {
           case 'image/bmp': return '.bmp';
           case 'image/cis-cod': return '.cod';
           case 'image/gif': return '.gif';
           case 'image/ief': return '.ief';
           case 'image/jpeg': return '.jpg';
           case 'image/pipeg': return '.jfif';
           case 'image/tiff': return '.tif';
           case 'image/x-cmu-raster': return '.ras';
           case 'image/x-cmx': return '.cmx';
           case 'image/x-icon': return '.ico';
           case 'image/x-portable-anymap': return '.pnm';
           case 'image/x-portable-bitmap': return '.pbm';
           case 'image/x-portable-graymap': return '.pgm';
           case 'image/x-portable-pixmap': return '.ppm';
           case 'image/x-rgb': return '.rgb';
           case 'image/x-xbitmap': return '.xbm';
           case 'image/x-xpixmap': return '.xpm';
           case 'image/x-xwindowdump': return '.xwd';
           case 'image/png': return '.png';
           case 'image/x-jps': return '.jps';
           case 'image/x-freehand': return '.fh';
           default: return false;
       }
}
function DataPort_FirebaseToMongoDB($firebase_msg_thread_id,$path="messages/message_thread/",$location="message_threads")
{

	$CI 		= & get_instance();
	$FIREBASE 	= FIREBASE_URL.$path;
	$NODE_GET 	= $firebase_msg_thread_id.".json";
	$curl 		= curl_init();
	//******************** INSERT TO MONGODB FROM FIREBASE ********************** BEGIN ***********
	curl_setopt( $curl, CURLOPT_URL, $FIREBASE . $NODE_GET );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	// Make request
	$response = curl_exec( $curl );
	// Close connection
	curl_close( $curl );
	//Load MongoDB Library	
	$CI->load->library('Mongo_db',array('activate'=>'default'),'mongo_db');
	$arr[$firebase_msg_thread_id]=json_decode($response,true);
	//INSERT TO MONGODB
	$result = $CI->mongo_db->insert($location,array('thread_id'=>$firebase_msg_thread_id,'data'=>$arr));
	//********************INSERT TO MONGODB FROM FIREBASE********************** END ***********

	//********************DELETE FROM FIREBASE********************** BEGIN ***********
	$curl 			= curl_init();
	$NODE_DELETE	= $firebase_msg_thread_id.".json";
    curl_setopt( $curl, CURLOPT_URL, $FIREBASE . $NODE_DELETE);
    curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    // Make request
	$response = curl_exec( $curl );
	// Close connection
	curl_close( $curl );
	//********************DELETE FROM FIREBASE********************** END ***********
	return $result;
}
//ENCRYPT A STRING
function encryptor($action, $string) {
    $output = false;
 
    $encrypt_method = "AES-256-CBC";
    //pls set your unique hashing key
    $secret_key = '9jju89u8h77$BG6';
    $salt		= "1DS96Bxd98ERTnm98ut36mkDFg9863YUOP&@#96&*(-+&RG";
 
    // hash
    $key 		= hash('sha256', $secret_key);
 
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv 		= substr(hash('sha256', $salt), 0, 16);
 
    //do the encyption given text/string/number
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
    	//decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
 
    return $output;
}
//common curl function  
function CurlUse($firebase_msg_thread_id,$path,$Request,$data="")
{
	$firebase 	= FIREBASE_URL."messages/message_thread/";
	$node       = $firebase_msg_thread_id."/".$path.".json";
	$curl 		= curl_init();
    curl_setopt( $curl, CURLOPT_URL, $firebase .$node);
    curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, $Request);
    if(!empty($data))
    {curl_setopt( $curl, CURLOPT_POSTFIELDS,json_encode($data));}
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);
    
	$response = curl_exec( $curl );// Make request
	curl_close( $curl );	// Close connection
	return $response;

}

//send push notification
 function SendPushNotification($PushData=array())
    {
      
     if(empty($PushData)){return false;}

      $url =  FIREBASE_PUSH_NOTIFY_URL;
      $headers=array(
        'Authorization: key= '.FIREBASE_SERVER_KEY,
        'Content-Type: application/json',
        );
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_PORT, 443);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($PushData));

      $result = curl_exec($curl);
      curl_close($curl);
      if($result===FALSE)
        die('Curl failed :-'.curl_error($curl));
      else
       // echo 
      	return $result;
      
    }


 //get Customer Device OS Details
 function getCustomerDeviceOS($deviceToken="")
 {
 		$CI = & get_instance();

		$CI->db->select("C.CUSTOMERS_OS");
		$CI->db->from("CUSTOMERS C");
		$CI->db->where("C.CUSTOMERS_DEVICE_TOKEN",$deviceToken);
		$query = $CI->db->get();
		if ($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
 }

 /**
 	*
 	* Function for get Support center Details
 	*@param Call By Value
 	*@return array
 	*
 	**/
 function getSupportCenterDetails($SupportCenter="")
 {
 		$CI = & get_instance();

		$CI->db->select("SC.*");
		$CI->db->from("SUPPORT_CENTER SC");
		$CI->db->where("SC.SUPPORT_CENTER_ID",$SupportCenter);
		$query = $CI->db->get();
		if ($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
 }//get Company Details
 function getCompanytimezone($companyId="")
 {
 		$CI = & get_instance();

		$CI->db->select("T.TZ_TIMEZONE");
		$CI->db->from("COMPANY C");
		$CI->db->join("TIME_ZONE T","T.TZ_ID = C.COMPANY_TIMEZONE_ID","INNER");
		$CI->db->where("C.COMPANY_ID",$companyId);
		$query = $CI->db->get();
		if ($query->num_rows()==1)
		{
			return $query->row()->TZ_TIMEZONE;
		}
		else
		{
			return TIMEZONE;
		}
 }
//get timezone
 function gettimezone($timezoneId="")
 {
 		$CI = & get_instance();

		$CI->db->select("TZ_TIMEZONE");
		$CI->db->from("TIME_ZONE ");
		$CI->db->where("TZ_ID",$timezoneId);
		$query = $CI->db->get();
		if ($query->num_rows()==1)
		{
			return $query->row()->TZ_TIMEZONE;
		}
		else
		{
			return TIMEZONE;
		} 
 }//get timezone


 //changing time zone
 function DBTimeFormater($NONUTC,$UTC,$TIMEZONE=TIMEZONE,$FORMAT='d-m-Y h:i A')
 {	
 	if($NONUTC != "")
 	$newdate = new DateTime(str_replace('/','-',$NONUTC.TIMEZONE_UTC));
 	else 
 		$newdate = new DateTime(str_replace('/','-',$UTC));
	$newdate->setTimezone(new DateTimeZone(trim($TIMEZONE)));
	return $newdate->format($FORMAT);
 }
 function TimezoneOffset($TIMEZONE)
 {
	//target time zone
	$target_time_zone = new DateTimeZone($TIMEZONE);
	//find kolkata time
	$timezone_offset  = new DateTime('now', $target_time_zone);
	$timeoffset = $timezone_offset->format('O'); // Format :  -0400
	return $timeoffset;
 }
 //GET SUPPORT CENTER TIMEZONE
 function getSupportCenterTimezone($SUPPORT_CENTER_ID="")
 {
 		$CI = & get_instance();

		$CI->db->select("T.TZ_TIMEZONE");
		$CI->db->from("SUPPORT_CENTER SC");
		$CI->db->join("TIME_ZONE T","T.TZ_ID = SC.SUPPORT_CENTER_TIMEZONE","INNER");
		$CI->db->where("SC.SUPPORT_CENTER_ID",$SUPPORT_CENTER_ID);
		$query = $CI->db->get();
		if ($query->num_rows()==1)
		{
			return $query->row()->TZ_TIMEZONE;
		}
		else
		{
			return TIMEZONE;
		}
 }
 
 //Get User CompanyCountry
 function GetCompanyCountry($COMPANY_ID="")
 {
 		$CI = & get_instance();
		$CI->db->select("COMPANY_COUNTRY_ID");
		$CI->db->from("COMPANY");
		$CI->db->where("COMPANY_ID",$COMPANY_ID);
		$query = $CI->db->get();
		if ($query->num_rows()==1)
		{
			return $query->row()->COMPANY_COUNTRY_ID;
		}
		else
		{
			return 0;
		}
 }

 //"Your Ticket Number is " string in company language
 function getCompanyLanguageString($lang_id,$lang_key)
 {
 		$CI = & get_instance();

		$CI->db->select("ML.*");
		$CI->db->from("MULTY_LANGUAGE ML");
		$CI->db->join("LANGUAGE_KEY LK","LK.LANGUAGE_KEY_ID=ML.LANGUAGE_KEY_ID","RIGHT");
		$CI->db->where("ML.LANGUAGE_MASTER_ID",$lang_id);
		$CI->db->where("LK.LANGUAGE_KEY_NAME",$lang_key);
		$query = $CI->db->get();
		if ($query->num_rows()>0)
		{
			foreach ($query->result() as $row) 
			{
					return $row->LANGUAGE_TEXT;
			}
		}
		else
		{
			return false;
		}
 }
 //FOR FILE UPLOADING 
 function UploadFiles($name,$path)
 {
	$CI =& get_instance();
 	if(!empty($_FILES))
	{		
		if($_FILES[$name]["name"]!="")
		{
			
		    if(!file_exists($path))
            {   
                mkdir($path);                           
                chmod($path,0777);
            }
            if(is_writable($path))
	          	{
	          		$config['upload_path']          = $path;
					$config['allowed_types']        = '*';	
					$config['file_name'] 			= md5($CI->session->userdata("admin_username").date("d-m-y H:i:s:u"));	
					$CI->load->library('upload', $config);

	          		$file_extension	= pathinfo($_FILES[$name]["name"], PATHINFO_EXTENSION);
	          		$file_extension	= strtolower($file_extension);
	          		//Image file upload
	          		if(($file_extension=='png')||($file_extension=='jpg')||($file_extension=='jpeg')||($file_extension=='gif'))
					{
						if(!$CI->upload->do_upload($name))
						{  
							//$CI->upload->display_errors()
	                     	return;  
		                }  
		                else  
		                {  
							$data = $CI->upload->data();  
							$config['image_library'] = 'gd2';  
							$config['source_image'] = $_FILES[$name]["tmp_name"]; //$path.$data["file_name"];  
							$config['quality'] = '50%';  
							$config['maintain_ratio'] = TRUE;
							$config['width'] = 1000;  
							$config['height'] = 1000;  
							//$config['create_thumb'] = TRUE;
							$config['new_image'] = $path.$data["file_name"];  
							$CI->load->library('image_lib', $config);  
							$CI->image_lib->resize();  
							$filename = $CI->upload->data('file_name');
						  	return $filename;
	                 	}
					}
					else if($file_extension=='mp4')// Video Upload and create thumbnail
					{
						$dest_file_name 			= md5($CI->session->userdata("admin_username").date("d-m-y H:i:s:u")).'.mp4';				
						$video_source_file_path		= $_FILES[$name]["tmp_name"];
						$video_dest_file_path		= $path.$dest_file_name; 

						$thumb_file_name    		= 'thumb_'.md5($CI->session->userdata("admin_username").date("d-m-y H:i:s:u")).".jpg";				
						$thumb_file_path    		= $path."/".$thumb_file_name;
						$second          			= 3;
						$return 					=array(); 	
						//ffmpeg -i input.mp4 -vcodec libx264 -crf 20 output.mp4
						//ffmpeg -i input.mp4 -vcodec h264 -acodec mp2 output.mp4
						//ffmpeg -i <inputfilename> -s 640x480 -b 512k -vcodec mpeg1video -acodec copy <outputfilename>
						$cmd_compress 	= "ffmpeg -i $video_source_file_path -strict -2 -s 320x240 -vcodec libx264 -crf 20 $video_dest_file_path 2>&1";
						//$cmd_compress 	= "ffmpeg -i $video_source_file_path -s 640x480 -vcodec libx264 -crf 20 $video_dest_file_path 2>&1";
						$cmd_thumbnail 	= "ffmpeg -i $video_dest_file_path -deinterlace -an -ss $second -t 00:00:01  -filter:v scale='-1:280' -r 1 -y -vcodec mjpeg -f mjpeg $thumb_file_path 2>&1";
						exec($cmd_compress,$output_compress, $retval_compress);
						exec($cmd_thumbnail,$output_thumb, $retval_thumb);

						if ($retval_compress)
						   	return ;
						else
							$return['file_name'] 	= $video_dest_file_path;
						if ($retval_thumb)
						   	return ;
						else
							$return['thumb_file_name'] 	= $thumb_file_path;
						return $return;
						
					}
					else //Document Upload
					{
						$config['upload_path']          = $path;
						$config['allowed_types']        = '*';	
						$config['file_name'] 			= md5($CI->session->userdata("admin_username").date("d-m-y H:i:s:u"));				

						$CI->load->library('upload', $config);
						if ($CI->upload->do_upload($name))
						{
							$filename = $CI->upload->data('file_name');
						  	return $filename;
						} 
						else
						{
							return ; 
						}
					}
				}				
		}
	}
	return ;
 }/*
function getVideoThumbnail($file_url,$file_path)
{
	$CI =& get_instance();
 	if(!empty($file_url))
	{
		$thumb_file_name    = 'thumb_'.md5($CI->session->userdata("admin_username").date("d-m-y H:i:s:u")).".jpg";				
		$thumb_file_path    = $file_path."/".$thumb_file_name;
		$video_file_path 	= $file_url;
		$second          	= 3;
		$thumbSize       	= '100%x100%';

		if($file_path!="")
		{
			if(!file_exists($file_path))
            {   
                mkdir($file_path);                           
                chmod($file_path,0777);
            }
            if(is_writable($file_path))
          	{
      			$cmd = "ffmpeg -i $video_file_path -deinterlace -an -ss $second -t 00:00:01  -filter:v scale='-1:280' -r 1 -y -vcodec mjpeg -f mjpeg $thumb_file_path 2>&1";
				exec($cmd,$output, $retval);
				if ($retval)
				   	$outputs="";
				else
					$outputs= $thumb_file_name;
			}
		}
		return $outputs;
	}		
}*/
	function base64String($file_path)
	{

		$path = $file_path ;
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		return $base64;
	}
	//GET COUNTRY ID USING COUNTRY ISO CODE 
 	function getCountryId($CountryISOCode)
 	{
 		$CI = & get_instance();

 		$CI->db->where("COUNTRY_ISO",$CountryISOCode);
		$CI->db->select("*");
		$CI->db->from("COUNTRY");
		$query = $CI->db->get();
		$return = $query->row();
		return $return->COUNTRY_ID;
 	}
 	//GET COUNTRY ID USING COUNTRY ISO CODE 
 	function CountryCodetoID($Country_tel_code)
 	{
 		$CI = & get_instance();

 		$CI->db->where("COUNTRY_PHONECODE",$Country_tel_code);
		$CI->db->select("*");
		$CI->db->from("COUNTRY");
		$query = $CI->db->get();
		$return = $query->row();
		return $return->COUNTRY_ID;
 	}
	//get Company Details
	 function getCompanyDetails($companyId="")
	 {
	 		$CI = & get_instance();

			$CI->db->select("C.*");
			$CI->db->from("COMPANY C");
			$CI->db->where("C.COMPANY_ID",$companyId);
			$query = $CI->db->get();
			if ($query->num_rows()==1)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
	 }
 	//SEND SMS
 	function SendSMS($Mobile_No=array(),$Message)
 	{

 		$MOBILE_NO 	= "";
 		if(count($Mobile_No)==1)
 		{
 			$MOBILE_NO = $Mobile_No[0];
 		}
 		else
 		{	
 			foreach ($Mobile_No as $key => $value) {
 				if($MOBILE_NO =="")
 					$MOBILE_NO = $value;
 				else
 					$MOBILE_NO .= ','.$value;
 			}

 		}

 		$SMS_MESSAGE	= $Message ;
		$SMS_DATA 		= SMS_API.'&number='.$MOBILE_NO.'&text='.$SMS_MESSAGE;
        $SMS_URL 		= str_replace(" ", '%20', $SMS_DATA);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$SMS_URL);
		curl_setopt($ch, CURLOPT_HEADER,0);
		// Include header in result? (0 = yes, 1 = no)
		curl_setopt($ch, CURLOPT_HEADER, 0);
		// Should cURL return or print out the data? (true = return, false = print)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		$output 		= curl_exec($ch);
		$curl_result 	= json_decode($output);
		// Close the cURL resource, and free system resources
		curl_close($ch);
		if($curl_result->ErrorCode=='000')
		{
			return true;
		}
		else
		{
			return false;
		}
 	}
 	//SEND FIREBASE CLOUD MESSAGING (NOTIFICATION)
 	function SendNotification($DeviceTokens = array(),$Title,$Message,$NotifyType,$OS_Type,$Addl_Parameters=array())
 	{
 		if($OS_Type=='ANDROID')
 		{
	 		#prep the bundle
	     	$msg 	= array(
							'body' 	=> 'Symp Notification',
							'title'	=> 'Title Of Notification',
			            	'icon'	=> 'myicon',/*Default Icon*/
			            	'sound' => 'mySound',/*Default sound*/
			            	'type'	=> $NotifyType
				          	);
	         
			$data 	= array(
							"title" 		=> $Title,
							"message" 		=> $Message,
							"notifytype"   	=> $NotifyType,  //CATEGORY,PROMOTION
							"addlparameters"=> $Addl_Parameters  
							);       

						      		

			$fields = array(
							'registration_ids'	=> $DeviceTokens,
							//'notification'	=> $msg,
							'data'				=> $data,
							'priority' 			=> "high"
							);	
			$headers = array(
							'Authorization: key=' . FCM_SERVER_KEY,
							'Content-Type: application/json'
							);
		}
		else if($OS_Type=='IOS')
 		{
	 		#prep the bundle
	     	$msg 	= array(
							//'body' 	=> ($NotifyType == 'CATEGORY' ? 'New category available': 'New post available'),
	     					'body'  => $Message,							
	     					'title'	=> $Title,
			            	'icon'	=> 'myicon',/*Default Icon*/
			            	'sound' => 'mySound',/*Default sound*/
			            	'type'	=> $NotifyType
				          	);
	         
			$data 	= array(
							"title" 		=> $Title,
							"message" 		=> $Message,
							"notifytype"   	=> $NotifyType,  //CATEGORY,PROMOTION
							"addlparameters"=> $Addl_Parameters  
							);       

			$fields = array(
							'registration_ids'	=> $DeviceTokens,
							'notification'		=> $msg,
							'data'				=> $data,
							'priority' 			=> "high",
							'mutable_content'	=> false,
							'content_available' => true,							
							);	
			$headers = array(
							'Authorization: key=' . FCM_SERVER_KEY,
							'Content-Type: application/json'
							);
		}
		

 		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, FCM_URL);
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
 	}
 	//GET CUSTOMER MOBILE DEVICE TOKEN
 	function getMobileDeviceToken($CustomerId = NULL)
 	{
 		$CI 		= & get_instance();
 		$data 		= array();

 		if($CustomerId!=NULL)
 			$CI->db->where("C.CUSTOMER_ID",$CustomerId);
 				
		$CI->db->select("CUSTOMER_OS,CUSTOMER_DEVICE_TOKEN");
		$CI->db->from("CUSTOMERS C");
		$CI->db->where("C.CUSTOMER_DEVICE_TOKEN IS NOT NULL");

		$query = $CI->db->get();

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row) {
				if($row->CUSTOMER_OS==0)
            		$data['ANDROID'][] 	= $row->CUSTOMER_DEVICE_TOKEN;
            	else if($row->CUSTOMER_OS==1)
            		$data['IOS'][] 		= $row->CUSTOMER_DEVICE_TOKEN;
        	}

			return $data;
		}
		else
		{
			return false;
		}
 	} 
 	function CreateRandomOTP($Mobile_No)
 	{
 		$CI 		= & get_instance();
		$MOBILE_NO_WITH_CODE 	= $Mobile_No; // Mobile No Format is +91 9999999999
 		$MOBILE_NO  			= $MOBILE_NO_WITH_CODE[1];
 		$OTP_MEDIA_TYPE			= 0; //  0 = SMS, 1 = EMAIL
 		$CUSTOMER_OTP_STATUS	= false;

		//GENERATE NEW OTP
		if($MOBILE_NO!='9497266762')
			$OTP 	= rand(100000,999999);					
		else
			$OTP 	= '246810';		
		if($MOBILE_NO_WITH_CODE[0]!='+91')
		{
			$OTP_MEDIA_TYPE		 = 1; 
		}
		$data 	= array("CUSTOMER_MOBILE_NO" 	=> $MOBILE_NO_WITH_CODE,
						"CUSTOMER_OTP" 		 	=> $OTP,
						"CUSTOMER_OTP_TYPE"	 	=> 'NEW_REG',
						"CUSTOMER_OTP_STATUS"	=> 0,
						"CUSTOMER_OTP_TO_MEDIA"	=> $OTP_MEDIA_TYPE);
		$CI->db->insert("CUSTOMER_OTP",$data);    
		$insert_id = $CI->db->insert_id();	
		if($insert_id)
		{
			return $OTP;
		}
		else
		{
			return false;
		}
 	}
 	//GET CUSTOMER ID FROM MOBILENO
	function getCustomerIdFromMobileNo($customer_mobileno)
	{
 		$CI = & get_instance();

		$CI->db->select("CUSTOMER_ID");
		$CI->db->from("CUSTOMERS");
		$CI->db->where("CUSTOMER_MOBILE_NO",trim($customer_mobileno));
		$query = $CI->db->get();
		if ($query->num_rows()==1)
		{
			return $query->row()->CUSTOMER_ID;
		}
		else
		{
			return false;
		}
	}
	//GET PRIVATE PROMOTION CUSTOMERS
	function getPrivatePromotionCustomers($promotionId)
	{
		$CI 	= & get_instance();
		$data 	= array();
		//GET PRIVATE PROMOTIONS
		$CI->db->select("CPP_CUSTOMER_ID");
		$CI->db->from("CUSTOMER_PRIVATE_PROMOTION");
		$CI->db->where("CPP_PROMOTION_ID",$promotionId);
		$query 	= $CI->db->get();
		//echo $this->db->last_query();	 
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row) {
            	$data[] 	= $row->CPP_CUSTOMER_ID;
        	}

			
		}	
		return $data;
	}
 	//GET ATTACHMENT
	function getAttachments($promotionId)
	{
		$CI 	= & get_instance();
		$data 	= array();
		//GET PRIVATE PROMOTIONS
		$CI->db->select("PROMOTIONS_ATTACH_NAME,EXTENSION,FILE_TYPE,PA_ID");
		$CI->db->from("PROMOTIONS_ATTACHMENTS");
		$CI->db->where("PROMOTIONS_ID",$promotionId);
		$query 	= $CI->db->get();
		//echo $this->db->last_query();	 
		if($query->num_rows() > 0) $data = $query->result();
		return $data;
	}
	function mapSubmissionArray($input_array = array())
	{
		$map_array = array(
			"Header"	=>	"Header",
			"SENDER_ID"	=>	"SenderID",
			"RECEIVER_ID"	=>	"ReceiverID",
			"TRANSACTION_DATE"	=>	"TransactionDate",
			"RECORD_COUNT"	=>	"RecordCount",
			"DISPOSITION_FLAG"	=>	"DispositionFlag",
			"Claim"	=>	"Claim",
			"ClaimDetails"	=>	"ClaimDetails",
			"CLAIM_ID"	=>	"ID",
			"PATIENT_INS_NO"	=>	"MemberID",
			"PAYER_ID"	=>	"PayerID",
			"PROVIDER_ID"	=>	"ProviderID",
			"EMIRITES_ID_NUMBER"	=>	"EmiratesIDNumber",
			"GROSS_AMOUNT"	=>	"Gross",
			"PATIENT_SHARE"	=>	"PatientShare",
			"NET_AMOUNT"	=>	"Net",
			"Encounter"	=>	"Encounter",
			"FACILITY_ID"	=>	"FacilityID",
			"TYPE"	=>	"Type",
			"OP_REGISTRATION_NUMBER"	=>	"PatientID",
			"CREATED_TIME"	=>	"Start",
			"END_TIME"	=>	"End",
			"START_TYPE"	=>	"StartType",
			"TRANSFER_SOURCE"	=>	"TransferSource",
			"TRANSFER_DESTINATION"	=>	"TransferDestination",
			"Diagnosis"	=>	"Diagnosis",
			"CODE"	=>	"Code",
			"DATE_LOG"	=>	"Start",
			"LAB_INVESTIGATION_DETAILS_ID"	=>	"ID",
			"PROCEDURE_CODE"	=>	"Code",
			"PROCEDURE_CODE"	=>	"Code",
			"QUANTITY"	=>	"Quantity",
			"NET"	=>	"Net",
			"DOCTOR"	=>	"Clinician",
			"VALUE"	=>	"Value",
			"TYPE"	=>	"Type",
			"VALUE_TYPE"	=>	"ValueType",
			"PRIOR_AUTHORIZATION"	=>	"PriorAuthorizationID",
		);
		$result_array = array();
		foreach($input_array as $key => $value){
			if(is_array($value)) {
				if(!is_numeric($key))
				{
					if(isset($map_array[$key]))
					{
						$result_array[$map_array[$key]] = mapSubmissionArray($value);
					}
					else
					{
						$result_array[$key] = mapSubmissionArray($value);
					}
				}else
				{
					$result_array[$key] = mapSubmissionArray($value);
				}
			}
			else
			{
				if(isset($map_array[$key]))
				{
					$result_array[$map_array[$key]] = $value;
				}
				else
				{
					$result_array[$key] = $value;
				}
			}
		}
		return $result_array;
	}
	function mapingSubmissionArray($input_array = array())
	{
		$map_array = array(
			"Header"	=>	"Header",
			"SENDER_ID"	=>	"SenderID",
			"RECEIVER_ID"	=>	"ReceiverID",
			"TRANSACTION_DATE"	=>	"TransactionDate",
			"RECORD_COUNT"	=>	"RecordCount",
			"DISPOSITION_FLAG"	=>	"DispositionFlag",
			"Prescription"	=>	"Prescription",
			"ID"	=>	"ID",
			"TYPE"	=>	"Type",
			"PAYER_ID"	=>	"PayerID",
			"CLINICIAN"	=>	"Clinician",
			"Patient"	=>	"Patient",
			"MEMBER_ID"	=>	"MemberID",
			"EMIRITES_ID_NUMBER"	=>	"EmiratesIDNumber",
			"DATE_OF_BIRTH"	=>	"DateOfBirth",
			"PATIENT_WEIGHT"	=>	"Weight",
			"EMAIL"	=>	"Email",
			"Encounter"	=>	"Encounter",
			"FACILITY_ID"	=>	"FacilityID",
			"TYPE"	=>	"Type",
			"Diagnosis"	=>	"Diagnosis",
			"TYPE"	=>	"Type",
			"CODE"	=>	"Code",
			"Activity"	=>	"Activity",
			"ID"	=>	"ID",
			"START"	=>	"Start",
			"TYPE"	=>	"Type",
			"CODE"	=>	"Code",
			"QUANTITY"	=>	"Quantity",
			"DURATION"	=>	"Duration",
			"REFILLS"	=>	"Refills",
			"ROUTE_OF_ADMIN"	=>	"RoutOfAdmin",
			"Frequency"	=>	"Frequency",
			"UNIT_PER_FREQUENCY"	=>	"UnitPerFrequency",
			"FREQUENCY_VALUE"	=>	"FrequencyValue",
			"FREQUENCY_TYPE"	=>	"FrequencyType",
			"INSTRUCTIONS"	=>	"Instructions",
			"Observation"	=>	"Observation",
			"TYPE"	=>	"Type",
			"CODE"	=>	"Code",
			"VALUE"	=>	"Value",
			"VALUE_TYPE"	=>	"ValueType",
		);
		$result_array = array();
		foreach($input_array as $key => $value){
			if(is_array($value)) {
				if(!is_numeric($key))
				{
					if(isset($map_array[$key]))
					{
						$result_array[$map_array[$key]] = mapingSubmissionArray($value);
					}
					else
					{
						$result_array[$key] = mapingSubmissionArray($value);
					}
				}else
				{
					$result_array[$key] = mapingSubmissionArray($value);
				}
			}
			else
			{
				if(isset($map_array[$key]))
				{
					$result_array[$map_array[$key]] = $value;
				}
				else
				{
					$result_array[$key] = $value;
				}
			}
		}
		return $result_array;
	}
	function array_to_xml($array, &$xml_user_info,$parent="") {
	    foreach($array as $key => $value) {
	        if(is_array($value)) {
	            if(!is_numeric($key)){
	                $subnode = $xml_user_info->addChild("$key");
	                array_to_xml($value, $subnode,$key);
	            }else{

	                $subnode = $xml_user_info->addChild("$parent");
	                array_to_xml($value, $subnode, $parent);
	            }
	        }else {
	            $xml_user_info->addChild("$key",htmlspecialchars("$value"));
	        }
	    }
	}
 function toConfigTimezone($date,$timezone = CONF_TIME_ZONE_TEXT){
 	$date = new DateTime($date);
 	$date->setTimezone(new DateTimeZone($timezone));
	return $date->format('Y-m-d H:i:s');
 }
  function toConfigTimezoneDate($date,$timezone = CONF_TIME_ZONE_TEXT){
 	$date = new DateTime($date);
 	$date->setTimezone(new DateTimeZone($timezone));
	return $date->format('Y-m-d');
 }
 function toUtc($date,$timezone = CONF_TIME_ZONE_TEXT){
 	$date = new DateTime($date,new DateTimeZone($timezone));
 	$date->setTimezone(new DateTimeZone('GMT'));
	return $date->format('Y-m-d H:i:s');
 }
 function toUtcDate($date,$timezone = CONF_TIME_ZONE_TEXT){
 	$date = new DateTime($date,new DateTimeZone($timezone));
 	$date->setTimezone(new DateTimeZone('GMT'));
	return $date->format('Y-m-d');
 }
 function timezoneToOffset($timezone){
	$time = new DateTime('now', new DateTimeZone($timezone));
	return $timezone = $time->format('P');
}
function getInstitutionSettings()
{
	$ci = & get_instance();
    $ci->load->database();     
	$data 	= array();
	$ci->db->select("INSTITUTION_DHPO_ID,INSTITUTION_DHPO_LOGIN,INSTITUTION_DHPO_PASS");
	$ci->db->from("INSTITUTION_SETTINGS");
	$query 	= $ci->db->get();
	return $query->row();
}
function getClinicainDetails($doctor_id)
{
	$ci = & get_instance();
    $ci->load->database();     
	$data 	= array();
	$ci->db->select("CLINICIAN_USER,CLINICIAN_PASS");
	$ci->db->from("DOCTORS");
	$ci->db->where("LOGIN_ID",$doctor_id);
	$query 	= $ci->db->get();
	return $query->row();
}
function isClinicain($doctor_id)
{
	$ci = & get_instance();
    $ci->load->database();     
	$data 	= array();
	$ci->db->select("CLINICIAN_USER,CLINICIAN_PASS");
	$ci->db->from("DOCTORS");
	$ci->db->where("LOGIN_ID",$doctor_id);
	$query 	= $ci->db->get();
	if($query->num_rows() > 0 )
	{
		return true;
	}
	else
	{
		return false;
	}
}
