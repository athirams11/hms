<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AllergiesOtherModel extends CI_Model 
{
	public function listAllergiesOther($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		//$data['mode_of_arrivel'] = $this->MasterDataModel->getMasterDataList(1);				
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("ALLERGIES_OTHER_MASTER DM");
		$this->db->where("DM.LIST_ORDER","ASC");
		$this->db->where("STAT",1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$result = array("status"=> "Success", "data"=> $data);
		}
		
		return $result;
	}
	
	public function saveAllergiesOther($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
		$data = array(
								"NAME" => trim($post_data["allergies_other_name"]),
								"DESCRIPTION" => trim($post_data["allergies_other_description"]),
								"LIST_ORDER" => trim($post_data["allergies_other_order"]),
								"CLIENT_DATE" => format_date($post_data["client_date"])	
							);							
			
			$data_id = trim($post_data["allergies_other_id"]);
			
			$dp_data = array(
									"NAME" => trim($post_data["allergies_other_name"])
						 		 );
			
			$ret = $this->ApiModel->mandatory_check( $post_data , array('allergies_other_name'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }	
         			 												
			if($this->utility->is_Duplicate("ALLERGIES_OTHER_MASTER",array_keys($dp_data), array_values($dp_data),"ALLERGIES_OTHER_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}	
			
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("ALLERGIES_OTHER_ID",$data_id);
				$this->db->update("ALLERGIES_OTHER_MASTER",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("ALLERGIES_OTHER_MASTER",$data))
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
	
	public function getAllergiesOther($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["allergies_other_id"] > 0)
		{
				
			$data_id = $post_data["allergies_other_id"];
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("ALLERGIES_OTHER_MASTER DM");
			$this->db->where("DM.ALLERGIES_OTHER_ID",$data_id);			
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
	
	public function deleteAllergiesOther($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["allergies_other_id"];
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("PATIENTS_ALLERGIES_OTHER");
			$this->db->where("ALLERGIES_OTHER_ID",$data_id);			
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
				$this->db->where("ALLERGIES_OTHER_ID", $data_id);
				$ret = $this->db->delete("ALLERGIES_OTHER_MASTER");			
				//echo $this->db->last_query();exit;		
				$this->db->stop_cache();
				$this->db->flush_cache();				 
				if(!empty($ret))
					$result = array("status"=> "Success", "data"=> $data);
				
			}										
		}
		return $result;
		
	}
	


} 
?>