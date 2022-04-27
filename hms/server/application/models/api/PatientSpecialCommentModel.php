<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PatientSpecialCommentModel extends CI_Model 
{
	public function listPatientSpecialComment($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();			
		$this->db->select("*");
		
		if($post_data["patient_special_comment_id"] > 0)
		{
			$this->db->where("DM.PATIENT_SPECIAL_COMMENT_ID", $post_data["patient_special_comment_id"]);
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
			
		$this->db->from("PATIENT_SPECIAL_COMMENT DM");
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
	
	public function savePatientSpecialComment($post_data)
	{
		//print_r($post_data);
			$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								"PATIENT_ID" => trim($post_data["patient_id"]),
								"CONSULTATION_ID" => trim($post_data["consultation_id"]),
								"ASSESSMENT_ID" => trim($post_data["assessment_id"])																							
							 );							
			
		   $data_id = trim($post_data["patient_special_comment_id"]);
		  
		   $ret = $this->ApiModel->mandatory_check( $post_data , array('special_comment','patient_id','assessment_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         	}			
			 
			if($this->utility->is_Duplicate("PATIENT_SPECIAL_COMMENT",array_keys($data), array_values($data),"PATIENT_SPECIAL_COMMENT_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
			
			$data['DATE_TIME'] = toUtc(trim($post_data["date"]),$post_data["timeZone"]);				
			$data['SPECIAL_COMMENT'] = trim($post_data["special_comment"]);				
			$data['USER_ID'] = $post_data["user_id"];				
									
			if ($data_id > 0)
			{				
				$this->db->start_cache();					
				$this->db->where("PATIENT_SPECIAL_COMMENT_ID",$data_id);
				$this->db->update("PATIENT_SPECIAL_COMMENT",$data);					
				$this->db->stop_cache();
				$this->db->flush_cache();				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				$this->db->start_cache();			
				if($this->db->insert("PATIENT_SPECIAL_COMMENT",$data))
				{
					$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}			
		
		return $result;
	}
	
	
	public function getPatientSpecialComment($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		
			if($post_data["patient_special_comment_id"] > 0)
			{
				$this->db->where("DM.PATIENT_SPECIAL_COMMENT_ID", $post_data["patient_special_comment_id"]);
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
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("PATIENT_SPECIAL_COMMENT DM");						
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
	
	public function getPreviousPatientSpecialComment($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
					
			if($post_data["patient_id"]!=''){
				$this->db->where("PATIENT_ID", $post_data["patient_id"]);
			}
			if($post_data["consultation_id"]!=''){
				$this->db->where("CONSULTATION_ID", $post_data["consultation_id"]);
			}								
			if($post_data["assessment_id"] > 0){
				$this->db->where("ASSESSMENT_ID < ", $post_data["assessment_id"]);
			}		
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("PATIENT_SPECIAL_COMMENT DM");		
			$this->db->order_by("DM.PATIENT_SPECIAL_COMMENT_ID","DESC");
			$this->db->limit(1);				
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
	
	
	public function deletePatientSpecialComment($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["patient_special_comment_id"];
		if($data_id > 0)
		{				
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("PATIENT_SPECIAL_COMMENT");
			$this->db->where("PATIENT_SPECIAL_COMMENT_ID",$data_id);			
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
				$this->db->where("PATIENT_SPECIAL_COMMENT_ID", $data_id);
				$ret = $this->db->delete("PATIENT_SPECIAL_COMMENT");			
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