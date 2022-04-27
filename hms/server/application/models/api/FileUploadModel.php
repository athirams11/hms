<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FileUploadModel extends CI_Model 
{
	public function saveFile($param) 
	{
		$result = array("status"=> "Failed", "data"=> array());
		if($param["base64_file_str"]!='' && $param["module_id"]!='')
		{			
		  if($this->getBase64FileSize($param["base64_file_str"]) > DOCUMENT_UPLOAD_MAX_SIZE)
		  {
		  		$result = array("status"=> "Failed", "data"=> array("message"=>"Max file upload size exceeded!"));
		  }else if($param['refer_id']==''){ 
				$result = array("status"=> "Failed", "data"=> array("message"=>"Reference Id not available!")); 			
		  }else{		  		  
				$file_name = $this->Writebase64Files($param["base64_file_str"], strtolower($param["module_id"]) );
				if($file_name!='')
				{
				 	$param['file_name'] = $file_name;
				}
				$data_id = $this->save_document_file($param);
				if($data_id > 0)
				{
					$result = array("status"=> "Success", "data"=> array("document_id"=>$data_id) );				
				}
		  }
		}
		return $result;
	}
	
	public function deleteFile($param) 
	{
		$result = array("status"=> "Failed", "data"=> array());
		if($param["document_id"]>0)
		{			
			$del_stat = $this->delete_document_file($param["document_id"]);
			if($del_stat){
			$result = array("status"=> "Success", "data"=> array() );			
			}			
		}
		return $result;
	}
			
	
	public function listDocuments($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		if($post_data["module_id"] > 0 )
		{
				               			
			$this->db->select("D.*,DATE_FORMAT(D.DOCUMENTS_DATE, '%d-%m-%Y')  as DOC_DATE,MO.MASTER");
			$this->db->select("MD1.DATA as DOCUMENTS_TYPE_NAME");
			$this->db->from("DOCUMENTS D");			
			$this->db->join("MASTER_OPTIONS MO","D.MODULE_ID = MO.ID","left");				
			$this->db->join("MASTER_DATA MD1","MD1.MASTER_DATA_ID = D.DOCUMENTS_TYPE  ","left");
					
			$this->db->where("D.MODULE_ID", $post_data["module_id"]);	
			if($post_data["patient_id"]!='')
			{
				$this->db->where("D.PATIENT_ID", $post_data["patient_id"]);
			}					
			if($post_data["consultation_id"]!='')
			{
				$this->db->where("D.CONSULTATION_ID", $post_data["consultation_id"]);
			}
			
			$this->db->order_by("D.TIME","DESC");			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{				
				$result = array("status"=> "Success", "data"=> $query->result_array());
			}
		}		
		return $result;		
	}
	
	public function save_document_file($upload_data)
	{	  		
		$document_id = 0;
		if($upload_data["document_id"]>0)
		{
			$document_id = $upload_data["document_id"];
			$data = array(																								
								"DOCUMENTS_DATE"   => format_date($upload_data['doc_date']),
								"DOCUMENTS_DESCRIPTION" => $upload_data['doc_description']
						    );
						    
			if($upload_data['file_name']!=''){
				$data["DOCUMENTS_TITLE"] = $upload_data['file_name'];			
			}						    									   					
													
			$this->db->where("DOCUMENTS_ID", $document_id);
			$this->db->update("DOCUMENTS", $data);
		} else {
			$data = array(
								"MODULE_ID" 		 => $upload_data['module_id'],
								"REFER_ID" 			 => $upload_data['refer_id'],
								"PATIENT_ID" 		 => $upload_data['patient_id'],
								"CONSULTATION_ID"  => $upload_data['consultation_id'],								
								"DOCUMENTS_TITLE"  => $upload_data['file_name'],
								"DOCUMENTS_TYPE" 	 => $upload_data['doc_type'],
								"DOCUMENTS_DATE"   => toUtc(trim($upload_data["doc_date"]),$upload_data["timeZone"]),
								"DOCUMENTS_DESCRIPTION" => $upload_data['doc_description'],
								"DOCUMENTS_STATUS" => 1,
								"TIME" 				=> toUtc(trim($upload_data["doc_date"]),$upload_data["timeZone"]),//date("Y-m-d H:i:s"),
								"CLIENT_DATE" 		=> format_date($upload_data['client_date']),
								"CREATED_BY" 		=> $upload_data['user_id'],
						    );									   										
			$this->db->insert("DOCUMENTS", $data);
			$document_id = $this->db->insert_id(); 					  		
		}		  												   																		
		return $document_id; 					  
	}
	
	public function delete_document_file($document_id)
	{		
	 	$delet_resp = 0;
		if($document_id>0)
		{	
			$this->db->start_cache();
			$this->db->select("MODULE_ID, DOCUMENTS_TITLE, DOCUMENTS_ID");
			$this->db->where("DOCUMENTS_ID",$document_id);	
			$this->db->from("DOCUMENTS");					
			$this->db->limit(1);					
			$query = $this->db->get();					
			// echo $this->db->last_query(); exit;	
			$this->db->stop_cache();
			$this->db->flush_cache();
			$doc_old = array();
			if ($query->num_rows() > 0)
			{
				$doc_old  = $query->row_array();								
				if(isset($doc_old['MODULE_ID']) && isset($doc_old['DOCUMENTS_TITLE']) )
				{
					if($doc_old['DOCUMENTS_TITLE']!='')
					{																						
						$this->db->start_cache();
						$this->db->where_in("DOCUMENTS_ID", $doc_old['DOCUMENTS_ID']);
						$del_stat = $this->db->delete("DOCUMENTS");
						// echo $this->db->last_query(); exit;	
						$this->db->stop_cache();
						$this->db->flush_cache();				
						$this->db->error();				
						if($del_stat)
						{
							$upload_path = FCPATH.DOCUMENT_UPLOAD_PATH.$doc_old['MODULE_ID'].'/';											
							if($doc_old['DOCUMENTS_TITLE'])
							{
								if(unlink($upload_path.$doc_old['DOCUMENTS_TITLE']))
								{
									$delet_resp = 1;
								}
							}														
						}
					}
				}
			}else{
				$delet_resp = 0;
			}
			return $delet_resp;
		}
	}
	
	 
	/****  Save Base 64 file***/
	function Writebase64Files($base64_encoded_string,$module_id)
	{
		if($base64_encoded_string != "")
		{		  
		  $path = DOCUMENT_UPLOAD_PATH.$module_id.'/';		  
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
	
	/*
	to take mime type as a parameter and return the equivalent extension
	*/
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

	//return memory size in B, KB, MB	 
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
	
	
	
		

} 
?>