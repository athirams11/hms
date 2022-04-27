<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DiagnosisModel extends CI_Model 
{
	public function listDiagnosis($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();		
		$this->db->where("STAT",1);			
		$count = $this->db->count_all('DIAGNOSIS_MASTER');	
		$this->db->stop_cache();
		$this->db->flush_cache();
		$this->db->start_cache();	

		$sql = "SELECT * FROM `DIAGNOSIS_MASTER` `DM`
				WHERE `STAT` = 1
					AND `DM`.`CODE` LIKE '%".trim($post_data["search_text"])."%'
					OR  `DM`.`NAME` LIKE '%".trim($post_data["search_text"])."%' 
					OR  `DM`.`DESCRIPTION` LIKE '%".trim($post_data["search_text"])."%'
                ORDER BY
                  	CASE
						WHEN `DM`.`CODE` LIKE '".trim($post_data["search_text"])."%' THEN 1
						WHEN `DM`.`NAME` LIKE '".trim($post_data["search_text"])."%' THEN 2
						WHEN `DM`.`DESCRIPTION` LIKE '".trim($post_data["search_text"])."%' THEN 3
						WHEN `DM`.`CODE` LIKE '%".trim($post_data["search_text"])."' THEN 4
						WHEN `DM`.`NAME` LIKE '%".trim($post_data["search_text"])."' THEN 5
						WHEN `DM`.`DESCRIPTION` LIKE '%".trim($post_data["search_text"])."' THEN 7
						ELSE 6
                  	END
			LIMIT ".$post_data["start"].",".$post_data["limit"]."";	


		$query = $this->db->query($sql);
		// $this->db->select("*");
		// $this->db->from("DIAGNOSIS_MASTER DM");
		// $this->db->where("STAT",1);
		// if($post_data["search_text"] != '')
		// {
		// 	$this->db->group_start();
		// 	$this->db->like("DM.CODE",$post_data["search_text"]);
		// 	$this->db->or_like("DM.NAME",$post_data["search_text"]);
		// 	$this->db->or_like("DM.DESCRIPTION",$post_data["search_text"]);
		// 	$this->db->group_end();
		// 	if($post_data["limit"] > 0)
		// 	{
		// 		$this->db->limit($post_data["limit"]);
		// 	}
		// }	
		// else
		// {
		// 	if($post_data["limit"] > 0)
		// 	{
		// 		$this->db->limit($post_data["limit"],$post_data["start"]);
		// 	}
		// }
		// $this->db->order_by("DM.LIST_ORDER","ASC");
		// $query = $this->db->get();
			// echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data);
		}
		
		return $result;
	}
	
	public function saveDiagnosis($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								"CODE" => trim($post_data["diagnosis_code"]),
								"NAME" => trim($post_data["diagnosis_name"]),
								"CLIENT_DATE" => format_date($post_data["client_date"])																								
							);							
			$order = array(
								"LIST_ORDER" => trim($post_data["diagnosis_order"]),																					
							);		
			
			$data_id = trim($post_data["diagnosis_id"]);
				
			if(trim($post_data["diagnosis_name"]) =='')
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Data Missing");
			}
																							
			if($this->utility->is_Duplicate("DIAGNOSIS_MASTER",array_keys($order), array_values($order),"DIAGNOSIS_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Diagnosis order duplicate data found");
			}
			if($this->utility->is_Duplicate("DIAGNOSIS_MASTER","LIST_ORDER",trim($post_data["diagnosis_order"]),"DIAGNOSIS_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
			$data["DESCRIPTION"] = trim($post_data["diagnosis_description"]);
			$data["LIST_ORDER"] = trim($post_data["diagnosis_order"]);
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("DIAGNOSIS_ID",$data_id);
				$this->db->update("DIAGNOSIS_MASTER",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("DIAGNOSIS_MASTER",$data))
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
	
	public function getDiagnosis($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["diagnosis_id"] > 0)
		{
				
			$data_id = $post_data["diagnosis_id"];
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("DIAGNOSIS_MASTER DM");
			$this->db->where("DM.DIAGNOSIS_ID",$data_id);			
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
	


} 
?>