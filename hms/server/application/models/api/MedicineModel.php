<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MedicineModel extends CI_Model 
{
	public function listMedicine($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		//$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();					
		$this->db->where("MEDICINE_STATUS",1);
		$count = $this->db->count_all('MEDICINE');	
		$this->db->stop_cache();
		$this->db->flush_cache();
		$this->db->start_cache();	

		$sql = "SELECT *,CONCAT(`DM`.`TRADE_NAME`,' - ',`DM`.`SCIENTIFIC_NAME`) AS TRADE_NAMES
		 FROM `MEDICINE` `DM`
				WHERE `MEDICINE_STATUS` = 1
					AND `DM`.`DDC_CODE` LIKE '%".trim($post_data["search_text"])."%'
					OR  `DM`.`TRADE_NAME` LIKE '%".trim($post_data["search_text"])."%' 
					OR  `DM`.`SCIENTIFIC_CODE` LIKE '%".trim($post_data["search_text"])."%'
					OR  `DM`.`SCIENTIFIC_NAME` LIKE '%".trim($post_data["search_text"])."%'
                ORDER BY
                  	CASE
						WHEN `DM`.`DDC_CODE` LIKE '".trim($post_data["search_text"])."%' THEN 1
						WHEN `DM`.`TRADE_NAME` LIKE '".trim($post_data["search_text"])."%' THEN 2
						WHEN `DM`.`SCIENTIFIC_CODE` LIKE '".trim($post_data["search_text"])."%' THEN 3
						WHEN `DM`.`SCIENTIFIC_NAME` LIKE '".trim($post_data["search_text"])."%' THEN 4
						WHEN `DM`.`DDC_CODE` LIKE '%".trim($post_data["search_text"])."' THEN 6
						WHEN `DM`.`TRADE_NAME` LIKE '%".trim($post_data["search_text"])."' THEN 7
						WHEN `DM`.`SCIENTIFIC_CODE` LIKE '%".trim($post_data["search_text"])."' THEN 8
						WHEN `DM`.`SCIENTIFIC_NAME` LIKE '%".trim($post_data["search_text"])."' THEN 10
						ELSE 9
                  	END
			LIMIT ".$post_data["start"].",".$post_data["limit"]."";	
		$query = $this->db->query($sql);
		

			// SELECT *,CONCAT(`DM`.`TRADE_NAME`,' - ',`DM`.`SCIENTIFIC_NAME`) AS TRADE_NAMES
			// FROM `MEDICINE` `DM` 
			// WHERE `MEDICINE_STATUS` = 1 
			// 	AND (`DM`.`DDC_CODE` LIKE '".$post_data["search_text"]."%' ESCAPE '!' 
			// 		OR `DM`.`TRADE_NAME` LIKE '".$post_data["search_text"]."%' ESCAPE '!' 
			// 		OR `DM`.`SCIENTIFIC_CODE` LIKE '".$post_data["search_text"]."%' ESCAPE '!' 
			// 		OR `DM`.`SCIENTIFIC_NAME` LIKE '".$post_data["search_text"]."%' ESCAPE '!' 
			// 		OR `DM`.`DDC_CODE` LIKE '%".$post_data["search_text"]."%' ESCAPE '!' 
			// 		OR `DM`.`TRADE_NAME` LIKE '%".$post_data["search_text"]."%' ESCAPE '!' 
			// 		OR `DM`.`SCIENTIFIC_CODE` LIKE '%".$post_data["search_text"]."%' ESCAPE '!' 
			// 		OR `DM`.`SCIENTIFIC_NAME` LIKE '%".$post_data["search_text"]."%' ESCAPE '!')
			// LIMIT ".$post_data["start"].",".$post_data["limit"]."
		// $this->db->select("*,CONCAT(DM.TRADE_NAME,' - ',DM.SCIENTIFIC_NAME) AS TRADE_NAMES");
		// $this->db->from("MEDICINE DM");
		// $this->db->where("MEDICINE_STATUS",1);
		// if($post_data["search_text"] != '')
		// {
		// 	$this->db->group_start();
		// 	$this->db->like("DM.DDC_CODE",trim($post_data["search_text"]));
		// 	$this->db->or_like("DM.TRADE_NAME",trim($post_data["search_text"]));
		// 	$this->db->or_like("DM.SCIENTIFIC_CODE",trim($post_data["search_text"]));
		// 	$this->db->or_like("DM.SCIENTIFIC_NAME",trim($post_data["search_text"]));
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
		// $this->db->order_by("DM.MEDICINE_ID","ASC");
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
	
	public function saveMedicine($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());	

			$medicine_data = array(
				"DDC_CODE" => trim($post_data["ddc_code"]),
				"TRADE_NAME" => trim($post_data["trade_name"]),
				"SCIENTIFIC_CODE" => trim($post_data["scientific_code"]),								
				"SCIENTIFIC_NAME" => trim($post_data["scientific_name"]),					
							);							
			$data = array(
				"DDC_CODE" => trim($post_data["ddc_code"]),
				"TRADE_NAME" => trim($post_data["trade_name"]),
				"SCIENTIFIC_CODE" => trim($post_data["scientific_code"]),								
				"SCIENTIFIC_NAME" => trim($post_data["scientific_name"]),
				"INGREDIENT_STRENGTH" => trim($post_data["ingredient_strength"]),
				"DOSAGE_FORM_PACKAGE" => trim($post_data["dosage_from_package"]),
				"ROUTE_OF_ADMIN" => trim($post_data["route_of_admin"]),
				"PACKAGE_PRICE" => trim($post_data["package_price"]),
				"GRANULAR_UNIT" => trim($post_data["granular_unit"]),
				"MANUFACTURER" => trim($post_data["manufacturer"]),
				"REGISTERED_OWNER" => trim($post_data["registered_owner"]),
				"SOURCE" => trim($post_data["source"]),
				"UPDATED_DATE" =>  date('Y-m-d H:i:s'),
				"MEDICINE_STATUS" => 1,
				"CLIENT_DATE" => format_date($post_data["client_date"])								
							);							
			
		   $data_id = trim($post_data["medicine_id"]);
		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('ddc_code','scientific_code'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }			
			 
			if($this->utility->is_Duplicate("MEDICINE",array_keys($medicine_data), array_values($medicine_data),"MEDICINE_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
				

			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("MEDICINE_ID",$data_id);
				$this->db->update("MEDICINE",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("MEDICINE",$data))
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
	
	public function getMedicine($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["medicine_id"] > 0)
		{
				
			$data_id = $post_data["medicine_id"];
			$this->db->start_cache();			
			$this->db->select("DM.*,CONCAT(DM.TRADE_NAME,' - ',DM.SCIENTIFIC_NAME) AS TRADE_NAMES");
			$this->db->from("MEDICINE DM");
			$this->db->where("DM.MEDICINE_ID",$data_id);			
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
	
	public function deleteMedicine($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["medicine_id"];
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("PRESCRIPTION_DETAILS");
			$this->db->where("MEDICINE_ID",$data_id);			
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
				$this->db->where("MEDICINE_ID", $data_id);
				$ret = $this->db->delete("MEDICINE_MASTER");			
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