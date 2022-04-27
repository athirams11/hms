<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LaboratoryModel extends CI_Model 
{


public function attachradiology($post_data = array())
	{
		$result = array("status"=> "Failed", "data"=> array());	
		if(!empty($post_data['document']))
			$val=1;
		else
			$val=0;
		$post_data["remarks"] = isset($post_data["remarks"]) ? $post_data["remarks"] : '';
		$data=array(
		"PATIENT_VISIT_LIST_ID" 			=> trim($post_data["visit_id"]),
		"OP_REGISTRATION_ID" 		=> trim($post_data["patient_no"]),
		"CURRENT_PROCEDURAL_CODE_ID" 	=> trim($post_data["procedure_code_id"]),
		"IS_ATTACHMENT" 		=> $val,
		"REMARKS" 		=> trim($post_data["remarks"]),
		"CREATED_BY" 				=> (int) $post_data['user_id'],
		"CREATED_ON" => date('Y-m-d H:i:s')
				);
		if($post_data["radiology_id"] > 0)
		{
			
			$this->db->where("RADIOLOGY_ID",$post_data["radiology_id"]);
			$this->db->update("RADIOLOGY",$data);
			$data_id =$post_data["radiology_id"];
			if(!empty($post_data['document']))
			{
				$this->op_attachment($post_data,$data_id);
			}	
			return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated successfully...!")	;
		}
		else
		{
			$this->db->insert("RADIOLOGY",$data)	;		
			$data_id = $this->db->insert_id();	
			if(!empty($post_data['document']))
			{
				$this->op_attachment($post_data,$data_id);
			}	
			return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data inserted successfully...!")	;
		}
		return $result;
	}


function Writebase64FilesUpload($base64_encoded_string,$path)
	{
		if($base64_encoded_string != "")
		{		   
		  if(!file_exists($path))
        {        	   
            mkdir($path);
            //if (!is_dir($path)) mkdir($path, 0777, true);                           
            chmod($path,0755);                    
        }
        if(is_writable($path))
        {
        	
        	$decoded_file = base64_decode($base64_encoded_string); // decode the file
		    $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
		    $extension = $this->mime2ext($mime_type); // extract extension from mime type
		    //filename
		    $filename = uniqid().".".$extension;
		    $filepath = $path.$filename;		            
			 $success = file_put_contents($filepath, $decoded_file); // save
			 if($success)
				return $filename;
			 else
				return '';
				
		   }			
		}
		//echo $path;exit();
		return '';
	}

function op_attachment($post_data,$data_id)
	{
	
		if(is_array($post_data['document']))
		{
			$files = $post_data['document'];
			$attachment_arr = array();
			foreach ($files as $key => $value) 
			{
				if($this->getBase64FileSize($value) > DOCUMENT_UPLOAD_MAX_SIZE)
				{
				  	return  array("status"=> "Failed", "data"=> array("message"=>"Max file upload size exceeded!"));
				}
				else
				{	

					$file_name = $this->Writebase64FilesUpload($value,RADIOLOGY_UPLOAD_PATH);
					
					if($file_name!='')
					{
					 	
						$attachment_arr[$key]["RADIOLOGY_ID"] = $data_id;
						$attachment_arr[$key]["FILE_NAME"] = $file_name;
						$attachment_arr[$key]["CREATED_BY"] =(int) $post_data['user_id'];
						$attachment_arr[$key]["CREATED_ON"] = date('Y-m-d H:i:s');
						
					}

		  		}
			}
			// print_r($post_data['document']);exit;
			$this->db->insert_batch('RADIOLOGY_ATTACHMENT', $attachment_arr);
		}
	}


	public function attachcollection($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		

		if($post_data["files"]!='' && $post_data["attach_id"]!='')
		{			
		  if($this->getBase64FileSize($post_data["files"]) > DOCUMENT_UPLOAD_MAX_SIZE)
		  {
		  		$result = array("status"=> "Failed", "data"=> array("message"=>"Max file upload size exceeded!"));
		  }else{		  		  
				$file_name = $this->Writebase64Files($post_data["files"], strtolower($post_data["attach_id"]) );
				if($file_name!='')
				{
				 	$post_data['file_name'] = $file_name;
				}
				$data_id = $this->save_document_file($post_data);
				if($data_id > 0)
				{
					$result = array("status"=> "Success", "data"=> array("file"=>$file_name) );				
				}
		  }
		}
		return $result;
	}

	public function save_document_file($post_data)
	{	 
	$data = array(
								
								"RESULT_FILE_NAME"=>$post_data["file_name"],
								"CURRENT_STATUS" => (int) $post_data['status'],
								"COLLECTION_TYPE" => trim($post_data["collection_type"]),
								"RESULT_BY"=>	(int) $post_data['user_id'],
								"RESULT_ON"=>date('Y-m-d H:i:s'),
								"RESULT_REMARKS"=>	trim($post_data["remarks"]),
							);	
													
			
		$data_id = $post_data["attach_id"];
			if ($data_id > 0)
			{			
				$this->db->start_cache();	
				$this->db->where("SAMPLE_ID",$data_id);
				$this->db->update("SAMPLE_COLLECTION_AND_RESULT",$data);
				$this->db->stop_cache();
				$this->db->flush_cache();				
			}							
	 	return $data_id; 					  
	}

	public function getBase64FileSize($base64File)
	{
	    try{
	        $size_in_bytes = (int) (strlen(rtrim($base64File, '=')) * 3 / 4);
	        $size_in_kb    = $size_in_bytes / 1024;
	        $size_in_mb    = $size_in_kb / 1024;
	
	        return $size_in_mb;
	    }
	    catch(Exception $e){
	        return $e;
	    }
	}



public function listDocuments($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		if($post_data["module_id"] > 0 )
		{
				               			
			$this->db->select("L.*,DATE_FORMAT(L.RESULT_ON, '%d-%m-%Y')  as DOC_DATE,OP.OP_REGISTRATION_NUMBER,LS.TYPE_NAME,LT.PROCEDURE_CODE_NAME as TEST_NAME");
			$this->db->from("SAMPLE_COLLECTION_AND_RESULT L");	
			$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = L.PATIENT_ID","left");
			$this->db->join("LAB_SAMPLE_TYPE LS","LS.SAMPLE_TYPE_ID = L.SAMPLE_TYPE_ID","left");
			$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS LT","LT.CURRENT_PROCEDURAL_CODE_ID = L.TEST_ID","left");
			//$this->db->join("LAB_TEST LT","LT.TEST_ID = L.TEST_ID","left");
			$this->db->where("L.RESULT_FILE_NAME!=", "");
			if($post_data["patient_id"]!='')
			{
				$this->db->where("L.PATIENT_ID", $post_data["patient_id"]);
			}	
			$this->db->where("L.RESULT_FILE_NAME!=", null);			
			$this->db->order_by("L.RESULT_ON","DESC");			
			$query = $this->db->get();	

			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{				
				$result = array("status"=> "Success", "data"=> $query->result_array());
			}
		}		
		return $result;		
	}


	public function getradiologyReports($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		if($post_data["module_id"] > 0 )
		{
				               			
			$this->db->select("RA.*,DATE_FORMAT(RA.CREATED_ON, '%d-%m-%Y')  as DOC_DATE,OP.OP_REGISTRATION_NUMBER,LT.PROCEDURE_CODE_NAME as TEST_NAME");
			$this->db->from("RADIOLOGY_ATTACHMENT RA");	
			$this->db->join("RADIOLOGY R","R.RADIOLOGY_ID = RA.RADIOLOGY_ID","left");
			$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = R.OP_REGISTRATION_ID","left");
			$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS LT","LT.CURRENT_PROCEDURAL_CODE_ID = R.CURRENT_PROCEDURAL_CODE_ID","left");
			//$this->db->join("LAB_TEST LT","LT.TEST_ID = L.TEST_ID","left");
			//$this->db->where("L.RESULT_FILE_NAME!=", "");
			if($post_data["patient_id"]!='')
			{
				$this->db->where("R.OP_REGISTRATION_ID", $post_data["patient_id"]);
			}	
			$this->db->where("RA.FILE_NAME!=", null);			
			$this->db->order_by("RA.CREATED_ON","DESC");			
			$query = $this->db->get();	

			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{				
				$result = array("status"=> "Success", "data"=> $query->result_array());
			}
		}		
		return $result;		
	}

	public function getattachradio($post_data)
 	{
 		$result = array("status"=> "Failed", "data"=> array());
 		if($post_data["radiology_id"] > 0)
 		{
 			$this->db->where("A.RADIOLOGY_ID",$post_data["radiology_id"]);	
			$this->db->select("A.*");
			$this->db->from("RADIOLOGY_ATTACHMENT A");
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$result = array("status"=> "Success", "data"=> $query->result());
			}
 		}
 		
 			return $result;
 		
 	}

	function Writebase64Files($base64_encoded_string,$module_id)
	{
		if($base64_encoded_string != "")
		{		  
		  $path = LABRESULT_UPLOAD_PATH.$module_id.'/';		  
		  if(!file_exists($path))
        {        	   
            mkdir($path);
            //if (!is_dir($path)) mkdir($path, 0777, true);                           
            chmod($path,0755);                    
        }
        if(is_writable($path))
        {
        	
        	$decoded_file = base64_decode($base64_encoded_string); // decode the file
		    $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
		    $extension = $this->mime2ext($mime_type); // extract extension from mime type
		    //filename
		    $filename = uniqid().".".$extension;
		    $filepath = $path.$filename;		            
			 $success = file_put_contents($filepath, $decoded_file); // save
			 if($success)
				return $filename;
			 else
				return '';
				
		   }			
		}
		//echo $path;exit();
		return '';
	}

function mime2ext($mime)
	{
	    $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
	    "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
	    "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
	    "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
	    "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
	    "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
	    "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
	    "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
	    "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
	    "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
	    "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
	    "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
	    "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
	    "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
	    "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
	    "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
	    "pdf":["application\/pdf","application\/octet-stream"],
	    "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
	    "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
	    "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
	    "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
	    "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
	    "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
	    "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
	    "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
	    "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
	    "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
	    "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
	    "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
	    "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
	    "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
	    "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
	    "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
	    "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
	    "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
	    "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
	    "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
	    "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
	    "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
	    "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
	    "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
	    "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
	    "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
	    "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
	    "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
	    $all_mimes = json_decode($all_mimes,true);
	    foreach ($all_mimes as $key => $value) {
	        if(array_search($mime,$value) !== false) return $key;
	    }
	    return false;
	}
	
	public function listType($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();					
		//$this->db->where("STATUS",1);
		$count = $this->db->count_all('LAB_SAMPLE_TYPE');	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("LAB_SAMPLE_TYPE L");
		//$this->db->where("STATUS",1);
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("L.TYPE_NAME",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_ECLAIM_LINK_ID",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_NAME",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_CLASSIFICAION",$post_data["search_text"]);
			$this->db->group_end();
			if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"]);
			}
		}	
		else
		{
			if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"],$post_data["start"]);
			}
		}
		$this->db->order_by("L.SAMPLE_TYPE_ID","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data);
		}
		
		return $result;
	}



	public function saveType($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								
								"TYPE_NAME" => trim($post_data["type"]),
								"STATUS" => trim($post_data["type_status"]),
								

							);	
													
			
		   $data_id = trim($post_data["type_id"]);
		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('type','user_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }			
			 
			if($this->utility->is_Duplicate("LAB_SAMPLE_TYPE",array_keys($data), array_values($data),"SAMPLE_TYPE_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
				
			if ($data_id > 0)
			{			
				$data["UPDATED_BY"]	=	(int) $post_data['user_id'];
				$data["UPDATED_ON"]	=	date('Y-m-d H:i:s');
				$this->db->start_cache();	
				$this->db->where("SAMPLE_TYPE_ID",$data_id);
				$this->db->update("LAB_SAMPLE_TYPE",$data);
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{		
				$data["CREATED_BY"]	=	(int) $post_data['user_id'];
				$data["CREATED_ON"]	=	date('Y-m-d H:i:s');		
				if($this->db->insert("LAB_SAMPLE_TYPE",$data))
				{
					$this->db->start_cache();			
						$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data inserted")	;
				}
			}			
		
		return $result;
	}
	
	public function getType($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["type_id"] > 0)
		{
				
			$data_id = $post_data["type_id"];
			$this->db->start_cache();			
			$this->db->select("L.*");
			$this->db->from("LAB_SAMPLE_TYPE L");
			$this->db->where("L.SAMPLE_TYPE_ID",$data_id);			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();				
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
				
		}
		return $result;
		
	}




	public function getlab($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$p_number = $post_data["p_number"];
		$doctor = $post_data["doctor"];
		if($p_number > 0)
		{
			$this->db->start_cache();			
			$this->db->select("LS.*");
			$this->db->from("LAB_INVESTIGATION L");
			$this->db->join("LAB_INVESTIGATION_DETAILS LS","LS.LAB_INVESTIGATION_ID = L.LAB_INVESTIGATION_ID","left");
			$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS M","M.CURRENT_PROCEDURAL_CODE_ID = LS.CURRENT_PROCEDURAL_CODE_ID","left");
			$this->db->join("PATIENT_VISIT_LIST P","P.PATIENT_ID = L.PATIENT_ID","left");
			
			if($doctor > 0){
			// 	$this->db->join("SAMPLE_COLLECTION_AND_RESULT K","K.TEST_ID = M.CURRENT_PROCEDURAL_CODE_ID AND K.PATIENT_ID ==".$p_number." AND K.DOCTOR_ID ==".$doctor,"LEFT");
			// $this->db->where("K.TEST_ID !=","M.CURRENT_PROCEDURAL_CODE_ID");
			$this->db->where("P.DOCTOR_ID",$doctor);		
			}
			$this->db->where("L.PATIENT_ID",$p_number);	
			$this->db->where("M.PROCEDURE_CODE_CATEGORY=",31);	
			$this->db->group_by("M.PROCEDURE_CODE");		
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result();				
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
		}
		
		return $result;
		
	}



	public function listTest($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();					
		//$this->db->where("STATUS",1);
		$count = $this->db->count_all('LAB_TEST');	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("LAB_TEST L");
		//$this->db->where("STATUS",1);
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("L.TEST_NAME",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_ECLAIM_LINK_ID",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_NAME",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_CLASSIFICAION",$post_data["search_text"]);
			$this->db->group_end();
			if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"]);
			}
		}	
		else
		{
			if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"],$post_data["start"]);
			}
		}
		$this->db->order_by("L.TEST_ID","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data);
		}
		
		return $result;
	}



	public function saveTest($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								
								"TEST_NAME" => trim($post_data["test"]),
								"STATUS" => trim($post_data["test_status"]),
								

							);	
													
			
		   $data_id = trim($post_data["test_id"]);
		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('test','user_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }			
			 
			if($this->utility->is_Duplicate("LAB_TEST",array_keys($data), array_values($data),"TEST_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
				
			if ($data_id > 0)
			{			
				$data["UPDATED_BY"]	=	(int) $post_data['user_id'];
				$data["UPDATED_ON"]	=	date('Y-m-d H:i:s');
				$this->db->start_cache();	
				$this->db->where("TEST_ID",$data_id);
				$this->db->update("LAB_TEST",$data);
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{		
				$data["CREATED_BY"]	=	(int) $post_data['user_id'];
				$data["CREATED_ON"]	=	date('Y-m-d H:i:s');		
				if($this->db->insert("LAB_TEST",$data))
				{
					$this->db->start_cache();			
						$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data inserted")	;
				}
			}			
		
		return $result;
	}
	
	public function getTest($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["test_id"] > 0)
		{
				
			$data_id = $post_data["test_id"];
			$this->db->start_cache();			
			$this->db->select("L.*");
			$this->db->from("LAB_TEST L");
			$this->db->where("L.TEST_ID",$data_id);			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();				
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
				
		}
		return $result;
		
	}


	function GenerateNo($post_data=array())
	{
		$this->db->start_cache();
		$this->db->select("max(RIGHT(MR_NO,6)) as MR_NO");
		$this->db->from("SAMPLE_COLLECTION_AND_RESULT");
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$MR_NO = (int) $query->row()->MR_NO + 1;

			// $prefix = date('ym');
			$prefix = MR_NO_PREFIX;
			// print_r($MR_NO);
			// exit();
			$result 	= array('data'=> $prefix.sprintf('%06d', $MR_NO),
								'status'=>'Success',
								);

			return $result;				
		}
		else 
		{
			$result 	= array('data'=>"",
								'status'=>'Failed',
								);
			return $result;
		}
	}

	public function searchCollection($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();					
		//$this->db->where("STATUS",1);
		$this->db->join("DOCTORS D","D.DOCTORS_ID = S.DOCTOR_ID","left");
		$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = S.PATIENT_ID","left");
		$this->db->join("LAB_SAMPLE_TYPE LS","LS.SAMPLE_TYPE_ID = S.SAMPLE_TYPE_ID","left");
		$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS L","L.CURRENT_PROCEDURAL_CODE_ID = S.TEST_ID","left");
		if($post_data["mrno"] != '')
		{
			$this->db->group_start();
			$this->db->or_like("S.MR_NO",trim($post_data["mrno"]));
			$this->db->group_end();
		}
		if($post_data["patient_no"] != '')
		{
			$this->db->group_start();
			$this->db->or_like("OP.OP_REGISTRATION_NUMBER",trim($post_data["patient_no"]));
			$this->db->group_end();
		}
		if($post_data["sample_type"] != '')
		{
			
			$this->db->where("S.SAMPLE_TYPE_ID",$post_data["sample_type"]);
			
		}
		if($post_data["test"] != '')
		{
			$this->db->where("S.TEST_ID",$post_data["test"]);
			
		}
		$count = $this->db->count_all('SAMPLE_COLLECTION_AND_RESULT S');	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("SAMPLE_COLLECTION_AND_RESULT S");
		$this->db->join("DOCTORS D","D.DOCTORS_ID = S.DOCTOR_ID","left");
		$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = S.PATIENT_ID","left");
		$this->db->join("LAB_SAMPLE_TYPE LS","LS.SAMPLE_TYPE_ID = S.SAMPLE_TYPE_ID","left");
		$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS L","L.CURRENT_PROCEDURAL_CODE_ID = S.TEST_ID","left");
		if($post_data["mrno"] != '')
		{
			$this->db->group_start();
			$this->db->like("S.MR_NO",trim($post_data["mrno"]));
			$this->db->group_end();
			
		}
		if($post_data["patient_no"] != '')
		{
			$this->db->group_start();
			$this->db->like("OP.OP_REGISTRATION_NUMBER",trim($post_data["patient_no"]));
			$this->db->group_end();
			
		}
		if($post_data["sample_type"] != '')
		{
			$this->db->where("S.SAMPLE_TYPE_ID",$post_data["sample_type"]);
			
		}
		if($post_data["test"] != '')
		{
			$this->db->where("S.TEST_ID",$post_data["test"]);
			
		}
		if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"],$post_data["start"]);
			}
		$this->db->order_by("S.SAMPLE_ID","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data);
		}
		
		return $result;
	}

	public function searchradiology($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();					
		$this->db->select("*");
		$this->db->from("LAB_INVESTIGATION L");
		$this->db->join("LAB_INVESTIGATION_DETAILS LS","LS.LAB_INVESTIGATION_ID = L.LAB_INVESTIGATION_ID","left");
		$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS M","M.CURRENT_PROCEDURAL_CODE_ID = LS.CURRENT_PROCEDURAL_CODE_ID","left");
		$this->db->join("PATIENT_VISIT_LIST P","P.PATIENT_ID = L.PATIENT_ID","left");
		$this->db->join("DOCTORS D","D.DOCTORS_ID = P.DOCTOR_ID","left");
		$this->db->join("RADIOLOGY R","R.PATIENT_VISIT_LIST_ID = P.PATIENT_VISIT_LIST_ID","left");
		$this->db->where("M.PROCEDURE_CODE_CATEGORY=",32);	
		if($post_data["patient_no"] != '')
		{
			$this->db->group_start();
			$this->db->or_like("L.PATIENT_ID",trim($post_data["patient_no"]));
			$this->db->group_end();
		}
		if($post_data["dateval"] != '')
		{
			$this->db->where("DATE(CONVERT_TZ(P.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateval"],1));	
		}
		
		$count = $this->db->count_all('LAB_INVESTIGATION');	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();			
		$this->db->select("O.OP_REGISTRATION_ID,O.OP_REGISTRATION_NUMBER,O.FIRST_NAME,O.MOBILE_NO,R.RADIOLOGY_ID, R.REMARKS,P.PATIENT_VISIT_LIST_ID, LS.CURRENT_PROCEDURAL_CODE_ID,P.DOCTOR_NAME,M.PROCEDURE_CODE_NAME");
		$this->db->from("LAB_INVESTIGATION L");
		$this->db->join("LAB_INVESTIGATION_DETAILS LS","LS.LAB_INVESTIGATION_ID = L.LAB_INVESTIGATION_ID","left");
		$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS M","M.CURRENT_PROCEDURAL_CODE_ID = LS.CURRENT_PROCEDURAL_CODE_ID","left");
		$this->db->join("PATIENT_VISIT_LIST P","P.PATIENT_ID = L.PATIENT_ID","left");
		$this->db->join("OP_REGISTRATION O","O.OP_REGISTRATION_ID = P.PATIENT_ID","left");
		$this->db->join("RADIOLOGY R","R.PATIENT_VISIT_LIST_ID = P.PATIENT_VISIT_LIST_ID","left");
		$this->db->where("M.PROCEDURE_CODE_CATEGORY=",32);	
		if($post_data["patient_no"] != '')
		{
			$this->db->group_start();
			$this->db->or_like("L.PATIENT_ID",trim($post_data["patient_no"]));
			$this->db->group_end();
		}
		if($post_data["dateval"] != '')
		{
			
			$this->db->where("DATE(CONVERT_TZ(P.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateval"],1));
			
		}
		if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"],$post_data["start"]);
			}
		$this->db->order_by("P.PATIENT_VISIT_LIST_ID","ASC");
		$query = $this->db->get();
		// echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data);
		}
		
		return $result;
	}



	public function listCollection($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();					
		//$this->db->where("STATUS",1);
		$this->db->join("DOCTORS D","D.DOCTORS_ID = S.DOCTOR_ID","left");
		$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = S.PATIENT_ID","left");
		$this->db->join("LAB_SAMPLE_TYPE LS","LS.SAMPLE_TYPE_ID = S.SAMPLE_TYPE_ID","left");
		$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS L","L.CURRENT_PROCEDURAL_CODE_ID = S.TEST_ID","left");
		$count = $this->db->count_all('SAMPLE_COLLECTION_AND_RESULT S');	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("SAMPLE_COLLECTION_AND_RESULT S");
		$this->db->join("DOCTORS D","D.DOCTORS_ID = S.DOCTOR_ID","left");
		$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = S.PATIENT_ID","left");
		$this->db->join("LAB_SAMPLE_TYPE LS","LS.SAMPLE_TYPE_ID = S.SAMPLE_TYPE_ID","left");
		$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS L","L.CURRENT_PROCEDURAL_CODE_ID = S.TEST_ID","left");
		//$this->db->where("STATUS",1);
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("S.MR_NO",$post_data["search_text"]);
			$this->db->or_like("OP.OP_REGISTRATION_NUMBER",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_NAME",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_CLASSIFICAION",$post_data["search_text"]);
			$this->db->group_end();
			if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"]);
			}
		}	
		else
		{
			if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"],$post_data["start"]);
			}
		}
		$this->db->order_by("S.SAMPLE_ID","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data);
		}
		
		return $result;
	}



	public function saveCollection($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								
								"MR_NO" => trim($post_data["mrno"]),
								"PATIENT_TYPE_ID" => trim($post_data["sel_pay_type"]),
								"SAMPLE_COLLECTED_DATE" => trim($post_data["collected_date"]),
								"PATIENT_ID" => trim($post_data["p_number"]),
								"DOCTOR_ID" => trim($post_data["doctor"]),
								"SAMPLE_TYPE_ID" => trim($post_data["sample_type"]),
								"TEST_ID" => trim($post_data["test"]),
								"CURRENT_STATUS" => 1,
								"COLLECTION_TYPE" => trim($post_data["collection_type"]),
								"LAB_ID" => 0,
								"COLLECTED_BY"=>	(int) $post_data['user_id'],
								"COLLECTED_ON"=>date('Y-m-d H:i:s'),
								"COLLECTION_REMARKS"=>	trim($post_data["test"]),
							);	
													
			
		$data_id = $post_data["collection_id"];
		// $ret = $this->ApiModel->mandatory_check( $post_data , array('mrno','sel_pay_type','collected_date,p_number,doctor,sample_type,test,collection_type'));		  
		// if($ret!='')
		// {		  	 		  		                         	 		  
  //   	  return array("status" => "Failed", "msg"=> $ret.' : Mandatory field missing');   
  //    	}			
		if($this->mrip_number($post_data["p_number"]))
		{								
			return array("status"=> "Failed", "msg"=> "Invalid Patient Number");
		} 
		if($this->mri($post_data["p_number"],$post_data["doctor"],$post_data["test"],$data_id))
		{								
			return array("status"=> "Failed", "msg"=> "Duplicate Record Found");
		}	


		  
		  
			if ($data_id > 0)
			{			
				// $data["RESULT_BY"]	=	(int) $post_data['user_id'];
				// $data["RESULT_ON"]	=	date('Y-m-d H:i:s');
				// $data["COLLECTION_REMARKS"]	=	trim($post_data["test"]);
				$this->db->start_cache();	
				$this->db->where("SAMPLE_ID",$data_id);
				$this->db->update("SAMPLE_COLLECTION_AND_RESULT",$data);
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Sample Collection updated successfully..!")	;
			}
			else
			{		
						
				if($this->db->insert("SAMPLE_COLLECTION_AND_RESULT",$data))
				{
					$this->db->start_cache();			
						$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Sample Collection saved successfully..!")	;
				}
			}			
		
		return $result;
	}
	

		public function mri($value,$doc,$tes,$id)
	{
		
			
		$this->db->where("PATIENT_ID",$value);
		$this->db->where("DOCTOR_ID",$doc);
		$this->db->where("TEST_ID",$tes);
		if($id > 0){
			$this->db->where("SAMPLE_ID!=",$id);
		}
		$this->db->from("SAMPLE_COLLECTION_AND_RESULT");
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return true; 
		}
		else
			return false;
		
	}
	public function mrip_number($value)
	{
		
			
		$this->db->where("OP_REGISTRATION_ID",trim($value));
		$this->db->from("OP_REGISTRATION");
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return false; 
		}
		else
			return true;
		
	}


  public function changeStatus($post_data = array())
 	{
 		$result = array('status'=>'Failed','message'=> "Failed");
 		if($post_data["sample_id"] != "" && $post_data["status"] > 0)
		{
	 		$this->db->where("SAMPLE_ID",$post_data["sample_id"]);	
			$query =  $this->db->update("SAMPLE_COLLECTION_AND_RESULT",array("CURRENT_STATUS"=>$post_data["status"]));
			if ($this->db->affected_rows() > 0)
			{	
	 			$result = array('message'=> "Status changed successfully",'status'=>'Success');
	 		}
		}
		return $result;
 	}

public function removefile($post_data = array())
 	{
 		$result = array('status'=>'Failed','message'=> "Failed");
 		if($post_data["sample_id"] >0)
		{
	 		$this->db->where("SAMPLE_ID",$post_data["sample_id"]);	
			$query =  $this->db->update("SAMPLE_COLLECTION_AND_RESULT",array("RESULT_FILE_NAME"=>""));
			if ($this->db->affected_rows() > 0)
			{	
	 			$result = array('message'=> "Report Removed successfully",'status'=>'Success');
	 		}
		}
		return $result;
 	}


public function removeradiofile($post_data = array())
 	{
 		$result = array('status'=>'Failed','message'=> "Failed");
 		if($post_data["attach_id"] >0)
		{
	 		$this->db->where("ATTACHMENT_ID",$post_data["attach_id"]);	
	 		$this->db->delete("RADIOLOGY_ATTACHMENT");
			if ($this->db->affected_rows() > 0)
			{	
	 			$result = array('message'=> "Report Removed successfully",'status'=>'Success');
	 		}
		}
		return $result;
 	}
	
	

}