<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MedicineGenericTypeModel extends CI_Model 
{
	public function listGenericType($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("MEDICINE_GENERIC_MASTER DM");
		$this->db->where("STAT",1);
		if($post_data["search_text"] != '')
		{
			$this->db->like("DM.NAME",$post_data["search_text"]);
			$this->db->or_like("DM.DESCRIPTION",$post_data["search_text"]);
			
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
		$this->db->order_by("DM.LIST_ORDER","ASC");
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
	
	public function saveGenericType($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(								
								"NAME" => trim($post_data["generic_type_name"]),																								
							);							
			
		   $data_id = trim($post_data["generic_type_id"]);
		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('generic_type_name'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }			
			 
			if($this->utility->is_Duplicate("MEDICINE_GENERIC_MASTER",array_keys($data), array_values($data),"GENERIC_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
				
			$data["DESCRIPTION"] = trim($post_data["generic_type_description"]);
			$data["LIST_ORDER"] = trim($post_data["generic_type_order"]);
			
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("GENERIC_ID",$data_id);
				$this->db->update("MEDICINE_GENERIC_MASTER",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("MEDICINE_GENERIC_MASTER",$data))
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
	
	public function getGenericType($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["generic_type_id"] > 0)
		{
				
			$data_id = $post_data["generic_type_id"];
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("MEDICINE_GENERIC_MASTER DM");
			$this->db->where("DM.GENERIC_ID",$data_id);			
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
	
	public function deleteGenericType($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["generic_type_id"];
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("MEDICINE_MASTER");
			$this->db->where("GENERIC_ID",$data_id);			
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
				$this->db->where("GENERIC_ID", $data_id);
				$ret = $this->db->delete("MEDICINE_GENERIC_MASTER");			
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