<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InsurancePriceModel extends CI_Model 
{

	public function saveInsurancePrice($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());	
		if(!empty($post_data))
   		{	
   			$data = array(
				"TPA_ID" 							=> trim($post_data["tpa_id"]),
				"NETWORK_ID" 						=> trim($post_data["network_id"]),
				"CURRENT_PROCEDURAL_CODE"			=> trim($post_data["cpt_code"]),
				"CPT_RATE" 							=> trim($post_data["cpt_rate"]),	
				"CURRENT_PROCEDURAL_CODE_ID" 		=> trim($post_data["cpt_id"]),
				"STATUS" 							=> trim($post_data["active_status"]),
				"LAST_UPDATED_ON"					=> date('Y-m-d H:i:s'),
				"CLIENT_DATE" 						=> format_date($post_data["client_date"]),
					);	

   			$dupli_data = array(
				"TPA_ID" 							=> trim($post_data["tpa_id"]),
				"NETWORK_ID" 						=> trim($post_data["network_id"]),
				"CURRENT_PROCEDURAL_CODE"			=> trim($post_data["cpt_code"]),
				"CURRENT_PROCEDURAL_CODE_ID" 		=> trim($post_data["cpt_id"]),
				"STATUS" 							=> trim($post_data["active_status"])
					);	
   			$data_id = trim($post_data["cpt_rate_id"]);

   			if($this->utility->is_Duplicate("CPT_RATE",array_keys($dupli_data), array_values($dupli_data),"CPT_RATE_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("CPT_RATE_ID",$data_id);
				$this->db->update("CPT_RATE",$data);
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("CPT_RATE",$data))
				{
					$this->db->start_cache();			
					$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data inserted")	;
				}
			}			
		}
		return $result;
	}

	public function getInsurancePrice($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["cpt_rate_id"] > 0)
		{
				
			$data_id = $post_data["cpt_rate_id"];
			$this->db->start_cache();			
			$this->db->select("CR.*,T.TPA_NAME,T.TPA_ECLAIM_LINK_ID,N.INS_NETWORK_NAME,N.INS_NETWORK_CODE,CPT.PROCEDURE_CODE_NAME");
			$this->db->from("CPT_RATE CR");
			$this->db->join("TPA T","CR.TPA_ID = T.TPA_ID","left");	
			$this->db->join("INS_NETWORK N","CR.NETWORK_ID = N.INS_NETWORK_ID","left");	
			$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CR.CURRENT_PROCEDURAL_CODE_ID = CPT.CURRENT_PROCEDURAL_CODE_ID","left");	
			$this->db->where("CR.CPT_RATE_ID",$data_id);			
			$query = $this->db->get();	
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
	public function listInsurancePrice($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();
		$this->db->where("CR.TPA_ID >0");
		$this->db->where("CR.NETWORK_ID >0");			
		$this->db->from("CPT_RATE CR");				
		$count = $this->db->count_all_results();
		// echo $this->db->last_query();exit;	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();	
		// $count = $this->db->count_all('CPT_RATE');		
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();	
			$this->db->like("T.TPA_NAME",$post_data["search_text"]);
			$this->db->or_like("T.TPA_ECLAIM_LINK_ID",$post_data["search_text"]);
			$this->db->or_like("N.INS_NETWORK_NAME",$post_data["search_text"]);
			$this->db->or_like("CR.CURRENT_PROCEDURAL_CODE",$post_data["search_text"]);
			$this->db->or_like("CPT.PROCEDURE_CODE_NAME",$post_data["search_text"]);
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
					
		
		$this->db->select("CR.*,T.TPA_NAME,T.TPA_ECLAIM_LINK_ID,N.INS_NETWORK_NAME,N.INS_NETWORK_CODE,
			CPT.PROCEDURE_CODE_NAME");
		$this->db->from("CPT_RATE CR");
		$this->db->join("TPA T","CR.TPA_ID = T.TPA_ID","left");	
		$this->db->join("INS_NETWORK N","CR.NETWORK_ID = N.INS_NETWORK_ID","left");	
		$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CR.CURRENT_PROCEDURAL_CODE_ID = CPT.CURRENT_PROCEDURAL_CODE_ID","left");	
		$this->db->where("CR.TPA_ID >0");
		$this->db->where("CR.NETWORK_ID >0");
		$this->db->order_by("CR.CPT_RATE_ID","ASC");
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


}