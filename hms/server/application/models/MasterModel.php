<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MasterModel extends CI_Model 
{
	function master_dropdown_listing($table_name,$select_column,$order_column,$order_in="ASC",$where_con="") 
	{
		$this->db->start_cache();
		if($table_name == "TPA T")
		{
			$select_column = "T.TPA_ID,T.TPA_NAME,T.TPA_ECLAIM_LINK_ID,CONCAT(T.TPA_ECLAIM_LINK_ID,' - ',T.TPA_NAME) AS TPA";
		}
		if($table_name == "INSURANCE_PAYERS I")
		{
			$select_column = "I.INSURANCE_PAYERS_ID,I.INSURANCE_PAYERS_ECLAIM_LINK_ID,I.INSURANCE_PAYERS_NAME,CONCAT(I.INSURANCE_PAYERS_ECLAIM_LINK_ID,' - ',I.INSURANCE_PAYERS_NAME) AS INSURANCE_PAYER";
		}
		$this->db->select($select_column);
		$this->db->from($table_name);
		if ($where_con!=""){ $this->db->where($where_con); }
		$this->db->order_by($order_column,$order_in);		
		$query = $this->db->get() ;
		
		$this->db->stop_cache();
		$this->db->flush_cache();	
		if($query->num_rows() > 0)
		{
			$result 	= array('data'=>$query->result(),
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
	function master_dropdown_listing_joint($tables,$join_condns="",$select_column,$order_column,$order_in,$where_con="") 
	{
		$len = count($tables);
		//return 1;
		//echo $len."1";exit;
		$this->db->start_cache();
		$this->db->select($select_column, false);
		$this->db->from($tables[0]);
		if($join_condns != "")
		{
			for($i=1;$i<$len;$i++)
			{
				$this->db->join($tables[$i],$join_condns[$i],'left');
			}
		}
		$this->db->order_by($order_column,$order_in);
		//$this->db->where("L.STATUS",1);
		if ($where_con!=""){ $this->db->where($where_con); }
		$query = $this->db->get() ;
		//echo $this->db->last_query();
		$this->db->flush_cache();
		if ($query->num_rows() > 0)
		return $query->result_array();
		else
		return false;
	}
	function master_get_data_by_id($table_name,$select_column,$where_con="") 
	{
		//$this->db->start_cache();

		$this->db->select($select_column);
		//$this->db->order_by($order_column,$order_in);
		//$this->db->where("STATUS",1);
		if ($where_con!=""){ $this->db->where($where_con); }
		$query = $this->db->get($table_name) ;
		//$this->db->stop_cache();
		//$this->db->flush_cache();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0)
		return $query->row()->$select_column;
		else
		return false;
	}
	function generateCompanyCode()
	{
		$year = date('y');
		$month = date('m');
		$code = $this->getLastCompCodeInMonth();
		return $year.$month.sprintf('%03d', $code);
	}
	function getLastCompCodeInMonth()
	{
		$qry = $this->db->query("SELECT max( right(COMPANY_CODE,3) ) + 1 as lastCount FROM COMPANY");
		if ($qry->num_rows() > 0)
		{
			return $qry->row()->lastCount;
		}
		else
		{
			return 1;
		}
	}
	
	function master_data_by_id($table_name,$select_column,$where_con="") 
	{
		$this->db->select($select_column);		
		if ($where_con!=""){ $this->db->where($where_con); }
		$query = $this->db->get($table_name) ;	
		if ($query->num_rows() > 0)
		return $query->row();
		else
		return false;
	}
	
function logo_uplod($name,$path,$Re_width,$Re_height)
	{	
	
	if($_FILES)
	{	

		if($_FILES[$name]["name"]!="")
		 {	
		    if(!file_exists($path))
            {   
                mkdir($path);                           
                chmod($path,0755);
            }
            if(is_writable($path))
	          {
	          	$config['image_library']  		= 'gd2';
				$config['upload_path']          =$path;
				$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
				//$config['max_size']             = 1024;
				$config['file_name'] 			=md5($this->session->userdata("admin_username").date("d-m-y H:i:s"));				

				$this->load->library('upload', $config);

				if ($this->upload->do_upload($name))
					{
							$image_name = $this->upload->data('file_name');
							 $upload_data = $this->upload->data();
						        //resize:						        
						        $config['source_image']   = $upload_data['full_path'];			
						        $config['maintain_ratio'] = TRUE;
						        if($Re_width!="") { $config['width']= $Re_width;} 
						        if($Re_height!="") { $config['width']= $Re_height;}	
						        $this->load->library('image_lib', $config);						       
						       	if($this->image_lib->resize())
						       	{					     
							   		return $image_name;
							  	} 
							  	else
							  	{
							  		return $image_name;
							  	}
							  	//echo $this->image_lib->display_errors();
					}
					else
					{
						return ;
					}	
				}				
		}
	}
		return ;
	}
function file_uplod($name,$path)
	{

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
				$config['upload_path']          =$path;
				$config['allowed_types']        = '*';	
				$config['file_name'] 			=md5($this->session->userdata("admin_username").date("d-m-y H:i:s"));				

				$this->load->library('upload', $config);
				if ($this->upload->do_upload($name))
					{
						$filename = $this->upload->data('file_name');
					  	return $filename;
					} 
					else
					{
						return ;
					}	
				}				
		}
	}
		return ;
	}

	
function file_uplod_2($name,$path)
	{

	if($_FILES)
	{	
	
		if($_FILES[$name]["name"]!="")
		{
			
		    if(!file_exists($path))
            {   
                mkdir($path);                           
                chmod($path,0755);
            }
            if(is_writable($path))
	          {
				$config['upload_path']          =$path;
				$config['allowed_types']        = '*';	
				$config['file_name'] 			=md5($this->session->userdata("admin_username").date("d-m-y H:i:s"));				

				$this->load->library('upload', $config);

				if ($this->upload->do_upload($name))
					{
						//$filename = $this->upload->data('file_name');
					  	return $this->upload->data();
					} 
					else
					{
						return ;
					}	
				}				
		}
	}
		return ;
	}

	/*** Get a Row ***/
	public function getlogo()
	{ 
        $logintype=$this->session->admin_login_type_id;
		$logourl=base_url()."public/assets/icons/mobi.png";

		if($logintype == FRANCHISE_ADMIN) 
		{
			$logName = $this->getfranchiselogo();
			if($logName != "")
               $logourl=base_url().FRANCHISE_LOGO_PATH.$logName;
           
		}
		else  if($this->session->user_company_id)
		{
			$logName = $this->getCmpusrlogo();
            if($logName != "")
               $logourl=base_url().COMPANY_LOGO_PATH.$logName;          
             
		}
		return $logourl;
	}

	/**** Get Franchise logo */
	function getfranchiselogo()
	{
			$this->db->start_cache();
			$this->db->select("FRANCHISE_LOGO");
			$this->db->from("FRANCHISE");	
			$this->db->where("FRANCHISE_ID",$this->session->userdata("user_franchise_id"));
			$query = $this->db->get();			
			$this->db->stop_cache();
			$this->db->flush_cache();
			if ($query->num_rows() == 1)
			return $query->row()->FRANCHISE_LOGO;
			else
			return false;
	} 
/**** Get Company User Logo */
	function getCmpusrlogo()
	{
			$this->db->start_cache();
			$this->db->select("COMPANY_IMAGE");
			$this->db->from("COMPANY");	
			$this->db->where("COMPANY_ID",$this->session->userdata("user_company_id"));
			$query = $this->db->get();			
			$this->db->stop_cache();
			$this->db->flush_cache();
			if ($query->num_rows() == 1)
			return $query->row()->COMPANY_IMAGE;
			else
			return false;
	} 

/****  Save Base 64 image***/
	function Writebase64($base64url,$path)
	{
		if($base64url != "")
		{
		  if(!file_exists($path))
            {   
                mkdir($path);                           
                chmod($path,0755);
            }
            if(is_writable($path))
	          {
					$base64url = str_replace('data:image/png;base64,', '', $base64url);
					$base64url = str_replace(' ', '+', $base64url);
					$data = base64_decode($base64url);
					$filename =md5(uniqid().$this->session->userdata("admin_username").date("d-m-y H:i:s")).'.png';
					$file = $path .$filename;
					$success = file_put_contents($file, $data);
					if($success)
						return $filename;
					else
						return ;
			}
		}

		//echo $path;exit();
		return ;
	}

/****  Save Base 64 file***/
	function Writebase64Files($base64url,$path)
	{
		if($base64url != "")
		{
		  if(!file_exists($path))
            {   
                mkdir($path);                           
                chmod($path,0755);
            }
            if(is_writable($path))
	          {
	          	    $info = explode(',', substr($base64url,5),2);
				    $mime=$info[0];$base64url=$info[1];
				    //get extention
				    $mime_type=explode(';', $mime,2);
				    $mime_split=explode('/', $mime_type[0],2);
				    $extension=$mime_split[1];
				    //filename
				    $filename =uniqid().".".$extension;
				    $filepath = $path .$filename;
					$success = file_put_contents($filepath,base64_decode($base64url));
					if($success)
						return $filename;
					else
						return ;
			}
			
		}

		//echo $path;exit();
		return ;
	} 

} 
?>