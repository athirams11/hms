<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PatientImmunizationModel extends CI_Model 
{
	public function listPatientImmunization($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());		
		$this->db->start_cache();			
		$this->db->select("*");
		if($post_data["patient_immunization_id"] > 0)
		{
			$this->db->where("DM.PATIENT_IMMUNIZATION_ID", $post_data["patient_immunization_id"]);
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
		
		$this->db->from("PATIENT_IMMUNIZATION PA");		
		$this->db->where("STAT",1);
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
	
	public function savePatientImmunization($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());	
		
			$data = array(
							"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
							"PATIENT_ID" => trim($post_data["patient_id"]),
							"USER_ID" => trim($post_data["user_id"]),
							"VACCINE_OPTIONAL" => trim($post_data["vaccine_optional"]),
							"CLIENT_DATE" => format_date($post_data["client_date"]),	
							"DATE_TIME" => toUtc(trim($post_data["date"]),$post_data["timeZone"])
							 );
						 													
			$data_id = trim($post_data["patient_immunization_id"]);
														
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("PATIENT_IMMUNIZATION_ID",$data_id);
				$this->db->update("PATIENT_IMMUNIZATION",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();								
			}
			else
			{				
				$this->db->start_cache();			
				if($this->db->insert("PATIENT_IMMUNIZATION",$data))
				{
					$data_id = $this->db->insert_id();				
				}
				$this->db->stop_cache();
				$this->db->flush_cache();
			}		
			 
			if($data_id>0)
			{
				$ret = $this->savePatientImmunizationDetails($data_id, $post_data);
				if($ret){
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}
		
		return $result;
	}
	
	public function savePatientImmunizationDetails($patient_immunization_id, $req_array)
	{
		$flag = false;
		if($patient_immunization_id > 0)
		{		
			if(is_array($req_array['immunization_ids']) && count($req_array['immunization_ids'])>0)
			{
				$this->db->start_cache();			
				$this->db->where("PATIENT_IMMUNIZATION_ID", $patient_immunization_id);	
				$this->db->delete("PATIENT_IMMUNIZATION_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();	
				$data = array();
				foreach($req_array['immunization_ids'] as $key => $immunization_id)
				{
					$data[$key]['PATIENT_IMMUNIZATION_ID'] = $patient_immunization_id; 	
					$data[$key]['SERIAL_NO'] = $key;			
					$data[$key]['IMMUNIZATION_ID'] = $immunization_id;																															
					$data[$key]['IMMUNIZATION_DATE'] = date("Y-m-d H:i:s");
					$daat[$key]['CLIENT_DATE '] = $req_array['client_date'];
																																				
				}
				$this->db->insert_batch('PATIENT_IMMUNIZATION_DETAILS', $data);
				$flag = true;
			} 														
		}
		return $flag;						 
	}
	
	public function getPatientImmunization($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		
		if($post_data["patient_id"]!='')
		{	
			$this->db->start_cache();			
			$this->db->select("DM.*");
			if($post_data["patient_immunization_id"] > 0)
			{
				$this->db->where("DM.PATIENT_IMMUNIZATION_ID", $post_data["patient_immunization_id"]);
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
			$this->db->from("PATIENT_IMMUNIZATION DM");
			$this->db->order_by("PATIENT_IMMUNIZATION_ID",'DESC');
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();	
				$data['IMMUNIZATION_DETAILS'] = $this->getPatientImmunizationDetails($data['PATIENT_IMMUNIZATION_ID'],'PATIENT_IMMUNIZATION_DETAILS');						
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
		}		
		
		return $result;
		
	}
	
	public function getPatientImmunizationDetails($patient_immunization_id, $table)
	{
			$this->db->start_cache();			
			$this->db->select("*");
			$this->db->from($table);
			$this->db->where("PATIENT_IMMUNIZATION_ID", $patient_immunization_id);
			$this->db->order_by("SERIAL_NO","ASC");									
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			$data = array();
			if($query->num_rows() > 0)
			{
				$data = $query->result_array();							
			}
			return $data;
	}
	
	public function deletePatientImmunization($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["patient_immunization_id"];
		if($data_id > 0)
		{															
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("PATIENT_IMMUNIZATION");
			$this->db->where("PATIENT_IMMUNIZATION_ID", $data_id);			
			$this->db->where("STAT", 2);			
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
				$this->db->where("PATIENT_IMMUNIZATION_ID", $data_id);												
				$this->db->delete("PATIENT_IMMUNIZATION_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();
			
				$this->db->start_cache();
				$this->db->where("PATIENT_IMMUNIZATION_ID", $data_id);
				$ret = $this->db->delete("PATIENT_IMMUNIZATION");			
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