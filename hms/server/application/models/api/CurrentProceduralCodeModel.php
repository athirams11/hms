<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CurrentProceduralCodeModel extends CI_Model 
{
	public function listCurrentProceduralCode($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());

		$this->db->start_cache();	
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("DM.PROCEDURE_CODE",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_NAME",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_ALIAS_NAME",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_DESCRIPTION",trim($post_data["search_text"]));
			$this->db->group_end();
			if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"]);
			}
		}
		$this->db->where("DM.STAT",1);				
		$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS DM");				
		$count = $this->db->count_all_results();	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();	
		$this->db->select("DM.*,CC.OPTIONS_ID,CC.OPTIONS_NAME,CONCAT(DM.PROCEDURE_CODE,' - ',DM.PROCEDURE_CODE_NAME) AS CPT");
		$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS DM");
		$this->db->join("OPTIONS CC","CC.OPTIONS_ID = DM.PROCEDURE_CODE_CATEGORY AND OPTIONS_TYPE = 9","left");
		if($post_data["current_procedural_code_id"] > 0)
		{
			$this->db->where("DM.CURRENT_PROCEDURAL_CODE_ID",$post_data["current_procedural_code_id"]);
		}	
		if(isset($post_data["procedure_code_category"]) && $post_data["procedure_code_category"] > 0)
		{
			$this->db->where("DM.PROCEDURE_CODE_CATEGORY",$post_data["procedure_code_category"]);
		}
		if(isset($post_data["dental_procedure_id"]) && $post_data["dental_procedure_id"] > 0)
		{
			$this->db->where("DM.DENTAL_PROCEDURE_ID",$post_data["dental_procedure_id"]);
		}
		$this->db->where("DM.STAT",1);
		$this->db->order_by("DM.PROCEDURE_CODE, DM.PROCEDURE_CODE_NAME","ASC");
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("DM.PROCEDURE_CODE",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_NAME",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_ALIAS_NAME",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_DESCRIPTION",trim($post_data["search_text"]));
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
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data);
		}
		
		return $result;
	}
	
	public function listCurrentProceduralCodeforTreatment($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());

		$this->db->start_cache();	
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("DM.PROCEDURE_CODE",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_NAME",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_ALIAS_NAME",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_DESCRIPTION",trim($post_data["search_text"]));
			$this->db->group_end();
			if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"]);
			}
		}
		$this->db->where("DM.STAT",1);				
		$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS DM");				
		$count = $this->db->count_all_results();	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();	
		$this->db->select("DM.*,CC.OPTIONS_ID,CC.OPTIONS_NAME,CONCAT(DM.PROCEDURE_CODE,' - ',DM.PROCEDURE_CODE_NAME) AS CPT");
		$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS DM");
		$this->db->join("OPTIONS CC","CC.OPTIONS_ID = DM.PROCEDURE_CODE_CATEGORY AND OPTIONS_TYPE = 9","left");
		if($post_data["current_procedural_code_id"] > 0)
		{
			$this->db->where("DM.CURRENT_PROCEDURAL_CODE_ID",$post_data["current_procedural_code_id"]);
		}	
		if(isset($post_data["procedure_code_category"]) && $post_data["procedure_code_category"] > 0)
		{
			$this->db->where("DM.PROCEDURE_CODE_CATEGORY",$post_data["procedure_code_category"]);
		}
		if(isset($post_data["dental_procedure_id"]) && $post_data["dental_procedure_id"] > 0)
		{
			$this->db->where("DM.DENTAL_PROCEDURE_ID",$post_data["dental_procedure_id"]);
		}
		$this->db->where("DM.STAT",1);
		$this->db->where("DM.DISCOUNT_SITE_ID",0);
		$this->db->order_by("DM.PROCEDURE_CODE, DM.PROCEDURE_CODE_NAME","ASC");
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("DM.PROCEDURE_CODE",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_NAME",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_ALIAS_NAME",trim($post_data["search_text"]));
			$this->db->or_like("DM.PROCEDURE_CODE_DESCRIPTION",trim($post_data["search_text"]));
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
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$result = array("status"=> "Success","total_count"=>$count, "data"=> $data);
		}
		
		return $result;
	}

	public function saveCurrentProceduralCode($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());		
		$data = array(
				"PROCEDURE_CODE_CATEGORY" => trim($post_data["current_procedural_code_type"]),
				"CPT_GROUP_ID" => trim($post_data["current_procedural_code_group"]),
				"PROCEDURE_CODE" => trim($post_data["current_procedural_code"]),
				"PROCEDURE_CODE_NAME" => trim($post_data["current_procedural_code_name"]),	
				"PROCEDURE_CODE_ALIAS_NAME" => trim($post_data["current_procedural_code_alias_name"]),	
				"PROCEDURE_CODE_DESCRIPTION" => trim($post_data["current_procedural_code_description"]),
				"DENTAL_PROCEDURE_ID" => trim($post_data["current_dental_procedure"]),
				"DISCOUNT_SITE_ID" => trim($post_data["current_procedure_discount_site"]),
			);							
			
		$data_id = trim($post_data["current_procedural_code_id"]);

		$ret = $this->ApiModel->mandatory_check( $post_data , array('current_procedural_code','current_procedural_code_name','current_procedural_code_alias_name'));		  
		if($ret!='')
		{		  	 		  		                         	 		  
    	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
     	}			
			 
		if($this->utility->is_Duplicate("CURRENT_PROCEDURAL_TERMINOLOGY_MS",array_keys($data), array_values($data),"CURRENT_PROCEDURAL_CODE_ID",$data_id))
		{								
			return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
		}
		$data["CLIENT_DATE"] = date('Y-m-d H:i:s',strtotime($post_data["client_date"]));
		$data["LIST_ORDER"] = trim($post_data["current_procedural_code_order"]);
		$data["USER_ID"] = trim($post_data["user_id"]);
			
		if ($data_id > 0)
		{				
			$this->db->start_cache();			

			$this->db->where("CURRENT_PROCEDURAL_CODE_ID",$data_id);
			$this->db->update("CURRENT_PROCEDURAL_TERMINOLOGY_MS",$data);
				
			$this->db->stop_cache();
			$this->db->flush_cache();
			$this->saveCPTRate($data_id,$post_data["current_procedural_code_rate"]);				
			return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
		}
		else
		{				
			if($this->db->insert("CURRENT_PROCEDURAL_TERMINOLOGY_MS",$data))
			{
				$this->db->start_cache();			
					$data_id = $this->db->insert_id();				
				$this->db->stop_cache();
				$this->db->flush_cache();
				$this->saveCPTRate($data_id,$post_data["current_procedural_code_rate"]);
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
		}			
		
		return $result;
	}
	public function saveCPTRate($cpt_id=0,$rate=0)
	{
		if($cpt_id > 0)
		{

			if($this->utility->is_Duplicate("CPT_RATE",array("0"=>"TPA_ID","1"=>"NETWORK_ID","2"=>"CURRENT_PROCEDURAL_CODE_ID"), array("0"=>"0","1"=>"0","2"=>"$cpt_id")))
			{								
				$this->db->where("TPA_ID",0);
				$this->db->where("NETWORK_ID",0);
				$this->db->where("CURRENT_PROCEDURAL_CODE_ID",$cpt_id);
				$this->db->update("CPT_RATE",array("CPT_RATE"=>$rate));
			}
			else
			{
				$this->db->insert("CPT_RATE",array("TPA_ID"=>0,"NETWORK_ID"=>0,"CURRENT_PROCEDURAL_CODE_ID"=>$cpt_id,"CPT_RATE"=>$rate));
			}
		}
	}
	public function getCurrentProceduralCode($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["current_procedural_code_id"] > 0)
		{
				
			$data_id = $post_data["current_procedural_code_id"];
			
			$this->db->start_cache();			
			$this->db->select("DM.*,CC.OPTIONS_NAME as CPT_CATEGORY_NAME,CC.OPTIONS_ID as CPT_CATEGORY_CODE,CR.CPT_RATE");
			$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS DM");
			$this->db->join("OPTIONS CC","CC.OPTIONS_ID = DM.PROCEDURE_CODE_CATEGORY AND OPTIONS_TYPE = 9","left");
			$this->db->join("CPT_RATE CR","CR.CURRENT_PROCEDURAL_CODE_ID = DM.CURRENT_PROCEDURAL_CODE_ID AND CR.TPA_ID = 0 AND CR.NETWORK_ID = 0","left");			
			$this->db->where("DM.CURRENT_PROCEDURAL_CODE_ID",$data_id);
			$this->db->where("DM.STAT",1);			
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
	public function getCPTByCode($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["current_procedure_code"] != '')
		{
				
			$code = $post_data["current_procedure_code"];
			
			$this->db->start_cache();			
			$this->db->select("DM.*,CC.OPTIONS_NAME as CPT_CATEGORY_NAME,CC.OPTIONS_ID as CPT_CATEGORY_CODE,CR.CPT_RATE");
			$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS DM");
			$this->db->join("OPTIONS CC","CC.OPTIONS_ID = DM.PROCEDURE_CODE_CATEGORY AND OPTIONS_TYPE = 9","left");
			$this->db->join("CPT_RATE CR","CR.CURRENT_PROCEDURAL_CODE_ID = DM.CURRENT_PROCEDURAL_CODE_ID AND CR.TPA_ID = 0 AND CR.NETWORK_ID = 0","left");	
			$this->db->where("DM.PROCEDURE_CODE",trim($code));
			$this->db->where("DM.STAT",1);	
			$this->db->where("DM.DISCOUNT_SITE_ID",0);		
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
	public function getCurrentDentalByDentalCode($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["dental_code"] != '')
		{
				
			$code = $post_data["dental_code"];
			
			$this->db->start_cache();			
			$this->db->select("DM.*,CC.OPTIONS_NAME as CPT_CATEGORY_NAME,CC.OPTIONS_ID as CPT_CATEGORY_CODE,CR.CPT_RATE");
			$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS DM");
			$this->db->join("OPTIONS CC","CC.OPTIONS_ID = DM.PROCEDURE_CODE_CATEGORY AND OPTIONS_TYPE = 9","left");
			$this->db->join("CPT_RATE CR","CR.CURRENT_PROCEDURAL_CODE_ID = DM.CURRENT_PROCEDURAL_CODE_ID AND CR.TPA_ID = 0 AND CR.NETWORK_ID = 0","left");			
			$this->db->where("DM.PROCEDURE_CODE_CATEGORY",37);
			$this->db->where("DM.PROCEDURE_CODE",trim($code));
			$this->db->where("DM.STAT",1);	
			$this->db->where("DM.DISCOUNT_SITE_ID",0);		
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
	public function deleteCurrentProceduralCode($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["current_procedural_code_id"];
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS");
			$this->db->where("CURRENT_PROCEDURAL_CODE_ID",$data_id);			
			$this->db->where("STAT",2);			
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{				
				$result = array("status"=> "Failed","msg"=>"Record Can't be deleted, Reference data available", "data"=> array());
				return $result;	
			}else
			{
				$this->db->start_cache();
				$this->db->where("CURRENT_PROCEDURAL_CODE_ID", $data_id);
				$ret = $this->db->delete("CURRENT_PROCEDURAL_TERMINOLOGY_MS");		
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