<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProcedureCodeModel extends CI_Model 
{
	public function listProcedureCode($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("PROCEDURE_CODE_MASTER DM");
		$this->db->where("STAT",1);
		$this->db->order_by("DM.LIST_ORDER","ASC");
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
	
	public function saveProcedureCode($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								"PROCEDURE_CODE_CATEGORY" => trim($post_data["procedure_code_category"]),
								"PROCEDURE_CODE_DESCRIPTION" => trim($post_data["procedure_code_description"]),	
								"CLIENT_DATE" => format_date($post_data["client_date"])																							
							);							
			
		   $data_id = trim($post_data["procedure_code_id"]);
		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('procedure_code_category','procedure_code_description'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }			
			 
			if($this->utility->is_Duplicate("PROCEDURE_CODE_MASTER",array_keys($data), array_values($data),"PROCEDURE_CODE_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
							
			$data["LIST_ORDER"] = trim($post_data["procedure_code_order"]);
			$data["USER_ID"] = trim($post_data["user_id"]);
						
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("PROCEDURE_CODE_ID",$data_id);
				$this->db->update("PROCEDURE_CODE_MASTER",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("PROCEDURE_CODE_MASTER",$data))
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
	
	public function getProcedureCode($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["procedure_code_id"] > 0)
		{
				
			$data_id = $post_data["procedure_code_id"];
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("PROCEDURE_CODE_MASTER DM");
			$this->db->where("DM.PROCEDURE_CODE_ID",$data_id);			
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
	
	public function deleteProcedureCode($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["procedure_code_id"];
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("OPERATIVE_PROCEDURE_CODE_MASTER");
			$this->db->where("PROCEDURE_CODE_ID",$data_id);			
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
				$this->db->where("PROCEDURE_CODE_ID", $data_id);
				$ret = $this->db->delete("PROCEDURE_CODE_MASTER");			
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