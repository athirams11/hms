<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class INS_networkModel extends CI_Model 
{
	public function listinsnetwork($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();					
		// $this->db->where("INS_NETWORK_STATUS",1);
		$count = $this->db->count_all('INS_NETWORK');	
		$this->db->stop_cache();
		$this->db->flush_cache();

		$this->db->start_cache();			
		$this->db->select("*","T.TPA_NAME");
		$this->db->from("INS_NETWORK IN");
		$this->db->join("TPA T","T.TPA_ID = IN.TPA_ID");
		// $this->db->where("INS_NETWORK_STATUS",1);
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();

			$this->db->like("IN.TPA_ID",$post_data["search_text"]);
			//$this->db->or_like("TA.TPA_ECLAIM_LINK_ID",$post_data["search_text"]);
			$this->db->or_like("IN.INS_NETWORK_CODE",$post_data["search_text"]);
			$this->db->or_like("IN.INS_NETWORK_NAME",$post_data["search_text"]);
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
		$this->db->order_by("IN.INS_NETWORK_ID","ASC");
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



	public function saveinsnetwork($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$net = array(
				
				"TPA_ID" => trim($post_data["tpa_id"]),
				"INS_NETWORK_CODE" => trim($post_data["ins_network_code"]),
				"INS_NETWORK_NAME" => trim($post_data["ins_network_name"]),
				);
			$data = array(
								
								"TPA_ID" => trim($post_data["tpa_id"]),
								"INS_NETWORK_CODE" => trim($post_data["ins_network_code"]),
								"INS_NETWORK_NAME" => trim($post_data["ins_network_name"]),
								"INS_NETWORK_CLASSIFICATION" => trim($post_data["ins_network_classification"]),
								"INS_NETWORK_STATUS" => trim($post_data["ins_network_status"]),
								"CREATED_USER" 			=> (int) $post_data['user_id']	,
								"CREATED_DATE" => date('Y-m-d H:i:s'),					
								"CLIENT_DATE" => format_date($post_data["client_date"])					
							);							
			

		   $data_id = trim($post_data["ins_network_id"]);

		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('tpa_id','ins_network_code','ins_network_name','ins_network_classification','user_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }			
			 
			// if($this->utility->is_Duplicate("INS_NETWORK",array_keys($net), array_values($net),"INS_NETWORK_ID",$data_id))
			// {								
			// 	return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			// }
				
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("INS_NETWORK_ID",$data_id);
				$this->db->update("INS_NETWORK",$data);
				if($post_data["copy_from_network"] != '')
				{
					$result = $this->copyFromNetwork($post_data["tpa_id"],$post_data["copy_from_network"],$data_id,$post_data["client_date"]);	
				}
				else{			
					$result= array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}		
				$this->db->stop_cache();
				$this->db->flush_cache();	
				
				return $result;
			}
			else
			{				
				if($this->db->insert("INS_NETWORK",$data))
				{
					$this->db->start_cache();			
					$data_id = $this->db->insert_id();	
					if($post_data["copy_from_network"] != '')
					{
						$result = $this->copyFromNetwork($post_data["tpa_id"],$post_data["copy_from_network"],$data_id,$post_data["client_date"]);	
					}	
					else{
						$result= array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data inserted")	;
					}
						//print_r($result);	
					$this->db->stop_cache();
					$this->db->flush_cache();
					return $result;
				}
			}			
		
		return $result;
	}
	public function copyFromNetwork($tpa_id,$network_id,$data_id,$client_date)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($tpa_id > 0 && $network_id > 0)
		{
						
			$this->db->select("C.*");
			$this->db->from("CPT_RATE C");
			$this->db->where("C.TPA_ID",$tpa_id);			
			$this->db->where("C.NETWORK_ID",$network_id);			
			$query = $this->db->get();	
			//echo $this->db->last_query();exit;		
			$data = array();
			if($query->num_rows() > 0)
			{
				foreach ($query->result() as $key =>  $row)
	            {

	                $data[$key]['TPA_ID'] = $tpa_id; 
	                $data[$key]['NETWORK_ID'] = $data_id;
	                $data[$key]['CURRENT_PROCEDURAL_CODE'] = $row->CURRENT_PROCEDURAL_CODE;
	                $data[$key]['CPT_RATE'] = $row->CPT_RATE;
	                $data[$key]['CURRENT_PROCEDURAL_CODE_ID'] = $row->CURRENT_PROCEDURAL_CODE_ID;
	                $data[$key]['LAST_UPDATED_ON'] = date('Y-m-d H:i:s');
	                $data[$key]['CLIENT_DATE'] = format_date($client_date);
	            }
	            $this->db->insert_batch('CPT_RATE', $data);	
	            $result = array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data inserted");
	        }
	       	else
	        {
	        	$result = array("status"=> "Failed","msg"=> "Can not copy Insurance price");
	        }
			
        }
        
	
			
		return $result;
		
	}
	public function getinsnetwork($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["ins_network_id"] > 0)
		{
				
			$data_id = $post_data["ins_network_id"];
			$this->db->start_cache();			
			$this->db->select("IN.*,T.TPA_NAME");
			$this->db->from("INS_NETWORK IN");
			$this->db->join("TPA T","IN.TPA_ID = T.TPA_ID");
			$this->db->where("IN.INS_NETWORK_ID",$data_id);			
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
	
	public function deleteinsnetwork($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["ins_network_id"];
		if($data_id > 0)
		{				
			
				$this->db->start_cache();
				$this->db->where("INS_NETWORK_ID", $data_id);
				$ret = $this->db->delete("INS_NETWORK");			
				//echo $this->db->last_query();exit;		
				$this->db->stop_cache();
				$this->db->flush_cache();				 
				if(!empty($ret))
					$result = array("status"=> "Success", "data"=> $data);
														
		}
		return $result;
		
	}
	

}