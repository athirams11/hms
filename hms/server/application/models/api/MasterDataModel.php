<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MasterDataModel extends CI_Model 
{
		 
	function getMasterDataList($master_id)
	{		
		$this->db->start_cache();
		$this->db->select("*");
		$this->db->from("MASTER_DATA M");
		$this->db->where("M.MASTER_ID", $master_id);
		$this->db->where("M.DATA_STATUS", 1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$this->db->stop_cache();
		$this->db->flush_cache();
		$data = array();
		if($query->num_rows() > 0)
		{
			$data = $query->result();				
		}	
		return $data;				
	}
	
	function getMasterDataValue($data_id)
	{		
		$this->db->start_cache();
		$this->db->select("*");
		$this->db->from("MASTER_DATA M");
		$this->db->where("M.MASTER_DATA_ID", $data_id);			
		$query = $this->db->get();
		//echo $this->db->last_query();
		$this->db->stop_cache();
		$this->db->flush_cache();
 			$data = array();
		if($query->num_rows() > 0)
		{
			$data = $query->row_array();				
		}	
		return $data;	
	}	

} 
?>