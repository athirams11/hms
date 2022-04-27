<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CorporateCompanyModel extends CI_Model 
{
	public function listCorporateCompany($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();	
		$count = $this->db->count_all('CORPORATE_COMPANY');	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("CORPORATE_COMPANY CC");
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("CC.CORPORATE_COMPANY_NAME",trim($post_data["search_text"]));
			$this->db->or_like("CC.CORPORATE_COMPANY_ADDRESS",trim($post_data["search_text"]));
			// $this->db->or_like("CC.CORPORATE_COMPANY_NAME",trim($post_data["search_text"]));
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
		if($post_data['company_status'] == 1)
		{
			$this->db->where("CC.CORPORATE_STATUS",1);
		}
		$this->db->order_by("CC.CORPORATE_COMPANY_ID","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data );
		}
		
		return $result;
	}

	public function saveCorporateCompany($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
		$data = array(	
					"CORPORATE_COMPANY_NAME" 			=> trim($post_data["company_name"]),
					"CORPORATE_COMPANY_ADDRESS" 		=> trim($post_data["company_address"]),
					"CORPORATE_COMPANY_CODE" 			=> trim($post_data["company_code"]),
					"CORPORATE_STATUS" 					=> trim($post_data["company_status"]),
					"CREATED_DATE" 						=> date('Y-m-d H:i:s'),	
					"CREATED_USER" 						=> (int) $post_data['user_id'],
					"CLIENT_DATE"						=> format_date($post_data["client_date"])					
				);							
	   $data_id = trim($post_data["company_id"]);
		  
			 
		if($this->utility->is_Duplicate("CORPORATE_COMPANY",array_keys(array("CORPORATE_COMPANY_NAME" => trim($post_data["company_name"]))), array_values(array("CORPORATE_COMPANY_NAME" => trim($post_data["company_name"]))),"CORPORATE_COMPANY_ID",$data_id))
		{								
			return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
		}
				
		if ($data_id > 0)
		{				
			$this->db->start_cache();			

			$this->db->where("CORPORATE_COMPANY_ID",$data_id);
			$this->db->update("CORPORATE_COMPANY",$data);
				
			$this->db->stop_cache();
			$this->db->flush_cache();				
			$result = array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Corporate company details saved succesfully...!")	;
		}
		else
		{				
			if($this->db->insert("CORPORATE_COMPANY",$data))
			{
				$this->db->start_cache();			
					$data_id = $this->db->insert_id();				
				$this->db->stop_cache();
				$this->db->flush_cache();
				$result = array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Corporate company details saved succesfully...!")	;
			}
		}			
		
		return $result;
	}
	
	public function getCorporateCompany($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["company_id"] > 0)
		{
				
			$data_id = $post_data["company_id"];
			$this->db->start_cache();			
			$this->db->select("CC.*");
			$this->db->from("CORPORATE_COMPANY CC");
			$this->db->where("CC.CORPORATE_COMPANY_ID",$data_id);			
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
	

}