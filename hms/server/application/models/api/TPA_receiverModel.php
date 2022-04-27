<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TPA_receiverModel extends CI_Model 
{
	public function listTPA($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();					
		$this->db->where("TPA_STATUS",1);
		$count = $this->db->count_all('TPA');	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("TPA TA");
		$this->db->where("TPA_STATUS",1);
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("TA.TPA_ECLAIM_LINK_ID",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_ECLAIM_LINK_ID",$post_data["search_text"]);
			$this->db->or_like("TA.TPA_NAME",$post_data["search_text"]);
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
		$this->db->order_by("TA.TPA_ID","ASC");
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



	public function saveTPA($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								
								"TPA_ECLAIM_LINK_ID" => trim($post_data["tpa_eclaim_link_id"]),
								"TPA_NAME" => trim($post_data["tpa_name"]),
								"TPA_CLASSIFICAION" => trim($post_data["tpa_classification"]),
								"TPA_STATUS" => trim($post_data["tpa_status"]),
								"CREATED_USER" 			=> (int) $post_data['user_id'],
								"CREATED_DATE" => date('Y-m-d H:i:s'),
								"CLIENT_DATE" => format_date($post_data["client_date"])		

							);	
													
			
		   $data_id = trim($post_data["tpa_id"]);
		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('tpa_eclaim_link_id','tpa_name','tpa_classification','tpa_status','user_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }			
			 
			if($this->utility->is_Duplicate("TPA",array_keys($data), array_values($data),"TPA_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
				
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("TPA_ID",$data_id);
				$this->db->update("TPA",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("TPA",$data))
				{
					$this->db->start_cache();			
						$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data inserted")	;
				}
			}			
		
		return $result;
	}
	
	public function getTPA($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["tpa_id"] > 0)
		{
				
			$data_id = $post_data["tpa_id"];
			$this->db->start_cache();			
			$this->db->select("TA.*");
			$this->db->from("TPA TA");
			$this->db->where("TA.TPA_ID",$data_id);			
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
	
	public function deleteTPA($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["tpa_id"];
		if($data_id > 0)
		{				
			
				$this->db->start_cache();
				$this->db->where("TPA_ID", $data_id);
				$ret = $this->db->delete("TPA");			
				//echo $this->db->last_query();exit;		
				$this->db->stop_cache();
				$this->db->flush_cache();				 
				if(!empty($ret))
					$result = array("status"=> "Success", "data"=> $data);
														
		}
		return $result;
		
	}
	

}