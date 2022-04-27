<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LabTestModel extends CI_Model 
{
	public function listLabTest($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		//$data['mode_of_arrivel'] = $this->MasterDataModel->getMasterDataList(1);				
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("LAB_TEST_MASTER DM");
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
	
	public function saveLabTest($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
		$data = array(
								"CODE" => trim($post_data["medicine_code"]),
								"NAME" => trim($post_data["medicine_name"]),
								"DESCRIPTION" => trim($post_data["medicine_description"]),
								"LIST_ORDER" => trim($post_data["medicine_order"]),
							);							
			
			$data_id = trim($post_data["lab_test_id"]);
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("LAB_TEST_ID",$data_id);
				$this->db->update("LAB_TEST_MASTER",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("LAB_TEST_MASTER",$data))
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
	
	public function getLabTest($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["lab_test_id"] > 0)
		{
				
			$data_id = $post_data["lab_test_id"];
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("LAB_TEST_MASTER DM");
			$this->db->where("DM.LAB_TEST_ID",$data_id);			
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
	
	public function deleteLabTest($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["lab_test_id"]
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("CONSULTATION");
			$this->db->where("LAB_TEST_ID",$data_id);			
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
				$this->db->where("LAB_TEST_ID", $data_id);
				$ret = $this->db->delete("LAB_TEST_MASTER");			
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