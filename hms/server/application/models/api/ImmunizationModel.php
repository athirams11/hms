<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImmunizationModel extends CI_Model 
{
	public function listImmunization($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();	
		$this->db->where("STAT",1);				
		$count = $this->db->count_all('IMMUNIZATION_MASTER');	
		$this->db->stop_cache();
		$this->db->flush_cache();
		$this->db->start_cache();			
		$this->db->select("DM.*,MD1.DATA as VACCINE_AGE,MD2.DATA as PATIENT_TYPE_NAME");
		$this->db->from("IMMUNIZATION_MASTER DM");
		$this->db->join("MASTER_DATA MD1","MD1.MASTER_DATA_ID = DM.VACCINE_AGE_ID  ","left");
		$this->db->join("MASTER_DATA MD2","MD2.MASTER_DATA_ID = DM.PATIENT_TYPE_ID  ","left");
		$this->db->where("STAT",1);
		if($post_data["search_text"] != '')
		{
			$this->db->like("DM.VACCINE_NAME",$post_data["search_text"]);
			
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
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data);
		}
		
		return $result;
	}
	
	public function saveImmunization($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								"PATIENT_TYPE_ID" => trim($post_data["patient_type_id"]),
								"VACCINE_NAME" => trim($post_data["vaccine_name"]),
								"VACCINE_AGE_ID" => trim($post_data["vaccine_age_id"]),
								"CLIENT_DATE" => format_date($post_data["client_date"])																							
							);		
			$order = array(
				"LIST_ORDER" => trim($post_data["vaccine_order"]),
			);						
			
		   $data_id = trim($post_data["immunization_id"]);
		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('patient_type_id','vaccine_name','vaccine_age_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }			
			 
			 if($this->utility->is_Duplicate("IMMUNIZATION_MASTER",array_keys($order), array_values($order),"IMMUNIZATION_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Vaccine order duplicate data found");
			}
			if($this->utility->is_Duplicate("IMMUNIZATION_MASTER",array_keys($data), array_values($data),"IMMUNIZATION_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
				
			$data["VACCINE_OPTIONAL"] = trim($post_data["vaccine_optional"]);			
			$data["PRICE_OF_ONE_ITEM"] = trim($post_data["vaccine_price_of_one_item"]);			
			$data["LIST_ORDER"] = trim($post_data["vaccine_order"]);
									
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("IMMUNIZATION_ID",$data_id);
				$this->db->update("IMMUNIZATION_MASTER",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("IMMUNIZATION_MASTER",$data))
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
	
	
	public function getImmunization($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["immunization_id"] > 0)
		{
				
			$data_id = $post_data["immunization_id"];
			$this->db->start_cache();			
			
			$this->db->select("DM.*,MD1.DATA as VACCINE_AGE,MD2.DATA as PATIENT_TYPE_NAME");
			$this->db->from("IMMUNIZATION_MASTER DM");
			$this->db->join("MASTER_DATA MD1","MD1.MASTER_DATA_ID = DM.VACCINE_AGE_ID  ","left");
			$this->db->join("MASTER_DATA MD2","MD2.MASTER_DATA_ID = DM.PATIENT_TYPE_ID  ","left");
		
			$this->db->where("DM.IMMUNIZATION_ID",$data_id);			
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
	
	public function deleteImmunization($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["immunization_id"];
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("IMMUNIZATION_MASTER");
			$this->db->where("IMMUNIZATION_ID",$data_id);			
			$this->db->where("STAT",2);
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
				$this->db->where("IMMUNIZATION_ID", $data_id);
				$ret = $this->db->delete("IMMUNIZATION_MASTER");			
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