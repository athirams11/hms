<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Insurance_payersModel extends CI_Model 
{
	public function listinsurance_payers($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();					
		// $this->db->where("INSURANCE_PAYERS_STATUS",1);
		$count = $this->db->count_all('INSURANCE_PAYERS');	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("INSURANCE_PAYERS IP");
		// $this->db->where("INSURANCE_PAYERS_STATUS",1);
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("IP.INSURANCE_PAYERS_ECLAIM_LINK_ID",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_ECLAIM_LINK_ID",$post_data["search_text"]);
			$this->db->or_like("IP.INSURANCE_PAYERS_NAME",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_CLASSIFICAION",$post_data["search_text"]);
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
		$this->db->order_by("IP.INSURANCE_PAYERS_ID","ASC");
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



	public function saveinsurance_payers($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(						
				"INSURANCE_PAYERS_ECLAIM_LINK_ID" 	=> trim($post_data["insurance_payers_eclaim_link_id"]),
				"INSURANCE_PAYERS_NAME" 			=> trim($post_data["insurance_payers_name"]),
				"INSURANCE_PAYERS_CLASSIFICATION" 	=> trim($post_data["insurance_payers_classification"]),
				"INSURANCE_PAYERS_STATUS" 			=> trim($post_data["insurance_payers_status"]),
				"CREATED_DATE" 						=> date('Y-m-d H:i:s'),	
				"CREATED_USER" 						=> (int) $post_data['user_id'],
				"CLIENT_DATE"						=> format_date($post_data["client_date"])					
							);							
			
		   $data_id = trim($post_data["insurance_payers_id"]);
		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('insurance_payers_eclaim_link_id','insurance_payers_name','insurance_payers_classification','user_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
        	}			
			 
			if($this->utility->is_Duplicate("INSURANCE_PAYERS",array_keys($data), array_values($data),"INSURANCE_PAYERS_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
				
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("INSURANCE_PAYERS_ID",$data_id);
				$this->db->update("INSURANCE_PAYERS",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("INSURANCE_PAYERS",$data))
				{
					$this->db->start_cache();			
						$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data inserted")	;
				}
			}	
			// $this->db->last_query();
		
		return $result;
	}
	
	public function getinsurance_payers($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["insurance_payers_id"] > 0)
		{
				
			$data_id = $post_data["insurance_payers_id"];
			$this->db->start_cache();			
			$this->db->select("IP.*");
			$this->db->from("INSURANCE_PAYERS IP");
			$this->db->where("IP.INSURANCE_PAYERS_ID",$data_id);			
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
	
	public function deleteinsurance_payers($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["insurance_payers_id"];
		if($data_id > 0)
		{				
			
				$this->db->start_cache();
				$this->db->where("INSURANCE_PAYERS_ID", $data_id);
				$ret = $this->db->delete("INSURANCE_PAYERS");			
				//echo $this->db->last_query();exit;		
				$this->db->stop_cache();
				$this->db->flush_cache();				 
				if(!empty($ret))
					$result = array("status"=> "Success", "data"=> $data);
														
		}
		return $result;
		
	}
	

}