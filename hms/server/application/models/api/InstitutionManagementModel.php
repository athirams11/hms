<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InstitutionManagementModel extends CI_Model 
{
	public function listInstitution($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();		
		$count = $this->db->count_all('INSTITUTION_SETTINGS');	
		$this->db->stop_cache();
		$this->db->flush_cache();
		$this->db->start_cache();			
		$this->db->select("DM.*,C.COUNTRY_NAME as INSTITUTION_COUNTRY_NAME");
		$this->db->from("INSTITUTION_SETTINGS DM");
		$this->db->join("COUNTRY C","DM.INSTITUTION_COUNTRY = C.COUNTRY_ID","left");
		
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("DM.INSTITUTION_NAME",$post_data["search_text"]);
			$this->db->or_like("DM.INSTITUTION_ADDRESS",$post_data["search_text"]);
			$this->db->or_like("DM.INSTITUTION_PHONE_NO",$post_data["search_text"]);
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
		$this->db->order_by("DM.INSTITUTION_NAME","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->row_array();				
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data,"logo_path"=>base_url().LOGO_FILE_PATH);
		}
		
		return $result;
	}
	
	public function saveInstitution($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								"INSTITUTION_NAME" => trim($post_data["hospital_name"]),
								"INSTITUTION_ADDRESS" => trim($post_data["hospital_address"]),
								"INSTITUTION_COUNTRY" => trim($post_data["hospital_country"]),
								"INSTITUTION_CITY" => trim($post_data["hospital_city"]),
								"INSTITUTION_PHONE_NO" => trim($post_data["hospital_phone"]),
								"INSTITUTION_EMAIL" => trim($post_data["hospital_email"]),
								"INSTITUTION_DHPO_ID" => trim($post_data["dhpo_id"]),
								"INSTITUTION_DHPO_LOGIN" => trim($post_data["dhpo_name"]),
								"INSTITUTION_DHPO_PASS" => trim($post_data["dhpo_password"])																								
							);							
			
			
			$data_id = trim($post_data["hospital_id"]);
				
			if(trim($post_data["hospital_logo"]) !='' && trim($post_data["hospital_logo"]) !='1')
			{		
				$this->load->model("MasterModel");						
				$data["INSTITUTION_LOGO"] = $this->MasterModel->Writebase64($post_data["hospital_logo"],LOGO_FILE_PATH);
			}
			if(trim($post_data["hospital_logo"]) =='1')
			{							
				$data["INSTITUTION_LOGO"] = NULL;
			}
			
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("INSTITUTION_SETTINGS_ID",$data_id);
				$this->db->update("INSTITUTION_SETTINGS",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("INSTITUTION_SETTINGS",$data))
				{
					$this->db->start_cache();			
						$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}			
		
		return $result;
	}
	public function saveCustomDate($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								"CUSTOM_DATE" => trim($post_data["custom_date"]),
								"CUSTOM_DATE_STATUS" => trim($post_data["custom_date_status"])
							);							
			
			
			$data_id = trim($post_data["hospital_id"]);
			
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("INSTITUTION_SETTINGS_ID",$data_id);
				$this->db->update("INSTITUTION_SETTINGS",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("INSTITUTION_SETTINGS",$data))
				{
					$this->db->start_cache();			
						$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}			
		
		return $result;
	}
	
	public function getInstitution($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["hospital_id"] > 0)
		{
				
			$data_id = $post_data["hospital_id"];
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("INSTITUTION_SETTINGS DM");
			$this->db->where("DM.INSTITUTION_SETTINGS_ID",$data_id);			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();				
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data,"logo_path"=>base_url().LOGO_FILE_PATH);
				
		}
		return $result;
		
	}
	
	public function deleteDiagnosis($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["diagnosis_id"];
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("PATIENT_DIAGNOSIS_DETAILS");
			$this->db->where("DIAGNOSIS_ID",$data_id);			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{				
				$result = array("status"=> "Failed","msg"=>"Record Can't be deleted, Reference data available", "data"=> array());
				return $result;	
			}else
			{
				$this->db->start_cache();
				$this->db->where("DIAGNOSIS_ID", $data_id);
				$ret = $this->db->delete("DIAGNOSIS_MASTER");			
				//echo $this->db->last_query();exit;		
				$this->db->stop_cache();
				$this->db->flush_cache();				 
				if(!empty($ret))
					$result = array("status"=> "Success", "data"=> $data);
				
			}										
		}
		return $result;
		
	}
	
	public function getOneInstitution($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		
				
			$this->db->start_cache();			
			$this->db->select("DM.INSTITUTION_NAME,INSTITUTION_ADDRESS,INSTITUTION_LOGO");
			$this->db->from("INSTITUTION_SETTINGS DM");
			if($post_data["hospital_id"] > 0)
			{
				$data_id = $post_data["hospital_id"];
				$this->db->where("DM.INSTITUTION_SETTINGS_ID",$data_id);			
			}
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();				
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data,"logo_path"=>base_url().LOGO_FILE_PATH);
				
		return $result;
		
	}


} 
?>