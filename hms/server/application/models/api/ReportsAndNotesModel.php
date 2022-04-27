<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ReportsAndNotesModel extends CI_Model 
{
	public function listReportsAndNotes($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("REPORTS_NOTES DM");
		$this->db->where("STAT",1);
		// $this->db->order_by("DM.LIST_ORDER","ASC");
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
	
	public function saveReportsAndNotes($post_data)
	{
		//print_r($post_data);
			$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								"PATIENT_ID" => trim($post_data["patient_id"]),
								"CONSULTATION_ID" => trim($post_data["consultation_id"]),
								"ASSESSMENT_ID" => trim($post_data["assessment_id"]),																							
								"INVESTIGATION_REQUESTED" => trim($post_data["investigation_requested"]),
								"CLIENT_DATE" => format_date($post_data["client_date"])																									
							 );							
			
		   $data_id = trim($post_data["reports_notes_id"]);
		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('investigation_requested','patient_id','assessment_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }			
			 
			if($this->utility->is_Duplicate("REPORTS_NOTES",array_keys($data), array_values($data),"REPORTS_NOTES_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
						
									
			if ($data_id > 0)
			{				
				$this->db->start_cache();					
				$this->db->where("REPORTS_NOTES_ID",$data_id);
				$this->db->update("REPORTS_NOTES",$data);					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				$this->db->start_cache();			
				if($this->db->insert("REPORTS_NOTES",$data))
				{
					$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}			
		
		return $result;
	}
	
	
	public function getReportsAndNotes($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
			

		$this->db->start_cache();			
		$this->db->select("DM.*");
		if($post_data["reports_notes_id"] > 0)
		{
			$this->db->where("DM.REPORTS_NOTES_ID", $post_data["reports_notes_id"]);
		}
		if($post_data["patient_id"]!=''){
			$this->db->where("PATIENT_ID", $post_data["patient_id"]);
		}
		if($post_data["consultation_id"]!=''){
			$this->db->where("CONSULTATION_ID", $post_data["consultation_id"]);
		}
		if($post_data["assessment_id"]!=''){
			$this->db->where("ASSESSMENT_ID", $post_data["assessment_id"]);
		}	
		$this->db->from("REPORTS_NOTES DM");
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
				
		
		return $result;
		
	}
	
	public function deleteReportsAndNotes($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["reports_notes_id"];
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("REPORTS_NOTES");
			$this->db->where("REPORTS_NOTES_ID",$data_id);			
			$this->db->where("STAT",2);
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
				$this->db->where("REPORTS_NOTES_ID", $data_id);
				$ret = $this->db->delete("REPORTS_NOTES");			
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