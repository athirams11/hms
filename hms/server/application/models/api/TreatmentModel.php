<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TreatmentModel extends CI_Model 
{
	public function listTreatment($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		//$data['mode_of_arrivel'] = $this->MasterDataModel->getMasterDataList(1);				
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("TREATMENT_MASTER DM");
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
	
	public function saveTreatment($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
		$data = array(
								"CODE" => trim($post_data["treatment_code"]),
								"NAME" => trim($post_data["treatment_name"]),
								"DESCRIPTION" => trim($post_data["treatment_description"]),
								"LIST_ORDER" => trim($post_data["treatment_order"]),
							);							
			
			$data_id = trim($post_data["treatment_id"]);
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("TREATMENT_ID",$data_id);
				$this->db->update("TREATMENT_MASTER",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("TREATMENT_MASTER",$data))
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
	
	public function getTreatment($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["treatment_id"] > 0)
		{
				
			$data_id = $post_data["treatment_id"];
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("TREATMENT_MASTER DM");
			$this->db->where("DM.TREATMENT_ID",$data_id);			
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
	
	public function deleteTreatment($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["treatment_id"]
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("CONSULTATION");
			$this->db->where("TREATMENT_ID",$data_id);			
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
				$this->db->where("TREATMENT_ID", $data_id);
				$ret = $this->db->delete("TREATMENT_MASTER");			
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