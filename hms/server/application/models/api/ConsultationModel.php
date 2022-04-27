<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ConsultationModel extends CI_Model 
{
	public function listConsultation($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		//$data['mode_of_arrivel'] = $this->MasterDataModel->getMasterDataList(1);				
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("CONSULTATION_DETAILS DM");
		//$this->db->where("DM.LIST_ORDER","ASC");
		$this->db->where("PATIENT_ID", $post_data["patient_id"]);
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
	
	public function saveConsultation($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
								"PRESENTING_COMPLAINTS" => trim($post_data["presenting_complaints"]),
								"EXAMINATION_NOTES" => trim($post_data["examination_notes"]),
								"PATIENT_ID" => trim($post_data["patient_id"]),
								"USER_ID" => trim($post_data["user_id"]),
								"CLIENT_DATE" => format_date($post_data["client_date"])	
							 );							
			
			$data_id = trim($post_data["consultation_id"]);
			if ($data_id > 0)
			{				
				$this->db->start_cache();					
				$this->db->where("CONSULTATION_ID",$data_id);
				$this->db->update("CONSULTATION_DETAILS",$data);					
				$this->db->stop_cache();
				$this->db->flush_cache();
				$this->saveConsultationParams($data_id,$post_data);				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{				
				if($this->db->insert("CONSULTATION_DETAILS",$data))
				{
					$this->db->start_cache();			
					$data_id = $this->db->insert_id();
					$this->db->stop_cache();
					$this->db->flush_cache();
					$this->saveConsultationParams($data_id,$post_data);				
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data saved")	;
				}
			}			
		
		return $result;
	}
	
	public function saveConsultationParams($consultation_id, $req_array)
	{
		if($consultation_id > 0)
		{		
			if(is_array($req_array['diagnosis_details_arr']))
			{
				$this->db->start_cache();			
				$this->db->where("CONSULTATION_ID", $consultation_id);												
				$this->db->delete("DIAGNOSIS_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();	
				$data = array();
				foreach($req_array['diagnosis_details_arr'] as $key => $diagnosis)
				{
					$data[$key]['CONSULTATION_ID'] = $consultation_id; 	
					$data[$key]['SERIAL_NO'] = $key;			
					$data[$key]['DIAGNOSIS'] = $diagnosis;																															
					$data[$key]['DETAILS'] = (isset($req_array["diagnosis_description_arr"][$key]) ? $req_array["diagnosis_description_arr"][$key] :'' );																					
				}
				$this->db->insert_batch('DIAGNOSIS_DETAILS', $data);
			} 
			
			if(is_array($req_array['treatment_plans_arr']))
			{
				$this->db->start_cache();			
				$this->db->where("CONSULTATION_ID", $consultation_id);												
				$this->db->delete("TREATMENT_PLAN_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();	
				$data = array();
				foreach($req_array['treatment_plans_arr'] as $key => $diagnosis)
				{
					$data[$key]['CONSULTATION_ID'] = $consultation_id; 	
					$data[$key]['SERIAL_NO'] = $key;			
					$data[$key]['DIAGNOSIS_ID'] = $diagnosis;																															
					$data[$key]['DETAILS'] = (isset($req_array["treatment_prescription_arr"][$key]) ? $req_array["treatment_prescription_arr"][$key] :'' );																				
				}
				$this->db->insert_batch('TREATMENT_PLAN_DETAILS', $data);
			}
			if(is_array($req_array['drug_details_arr']))
			{
				$this->db->start_cache();			
				$this->db->where("CONSULTATION_ID", $consultation_id);												
				$this->db->delete("DRUG_PRESCRIPTION_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();	
				$data = array();
				foreach($req_array['drug_details_arr'] as $key => $medicine)
				{
					$data[$key]['CONSULTATION_ID'] = $consultation_id; 	
					$data[$key]['SERIAL_NO'] = $key;			
					$data[$key]['MEDICINE_ID'] = $medicine;																															
					$data[$key]['PRESCRIPTION'] = (isset($req_array["drug_prescription_arr"][$key]) ? $req_array["drug_prescription_arr"][$key] :'' );																				
				}
				$this->db->insert_batch('DRUG_PRESCRIPTION_DETAILS', $data);
			}
			
			return 1;	
		}
		return false;						 
	}
	
	public function get_diagnosis_details($consultation_id)
	{
		$result = array();
		if($consultation_id > 0)
		{							
			$this->db->start_cache();			
			$this->db->select("*");
			$this->db->from("DIAGNOSIS_DETAILS");
			$this->db->where("CONSULTATION_ID",$data_id);			
			$this->db->order_by("SERIAL_NO","ASC");			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = $query->row_array();				
			}							
		}
		return $result;		
	}
	
	public function get_treatment_plans($consultation_id)
	{
		$result = array();
		if($consultation_id > 0)
		{							
			$this->db->start_cache();			
			$this->db->select("*");
			$this->db->from("TREATMENT_PLAN_DETAILS");
			$this->db->where("CONSULTATION_ID",$data_id);			
			$this->db->order_by("SERIAL_NO","ASC");			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = $query->row_array();				
			}							
		}
		return $result;		
	}
	
	public function get_drug_prescriptions($consultation_id)
	{
		$result = array();
		if($consultation_id > 0)
		{							
			$this->db->start_cache();			
			$this->db->select("*");
			$this->db->from("DRUG_PRESCRIPTION_DETAILS");
			$this->db->where("CONSULTATION_ID",$data_id);			
			$this->db->order_by("SERIAL_NO","ASC");			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = $query->row_array();				
			}							
		}
		return $result;		
	}
	
	public function getConsultation($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["consultation_id"] > 0)
		{				
			$data_id = $post_data["consultation_id"];
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("CONSULTATION_DETAILS DM");
			$this->db->where("DM.CONSULTATION_ID",$data_id);			
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
	
	public function getConsultationDetails($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["consultation_id"];
		if($data_id > 0)
		{				
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("CONSULTATION_DETAILS DM");
			$this->db->where("DM.CONSULTATION_ID",$data_id);			
			$this->db->order_by("DATE_TIME","DESC");			
			$this->db->limit_by("1");			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data['diagnosis_details'] = $this->get_diagnosis_details($data_id);
				$data['treatment_plans_details'] = $this->get_treatment_plans($data_id);
				$data['drug_prescription_details'] = $this->get_drug_prescriptions($data_id);
				$data = $query->row_array();				
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
								
		}
		return $result;
		
	}
	
	public function deleteConsultation($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["consultation_id"];
		if($data_id > 0)
		{									
			$this->db->start_cache();
			$this->db->where("CONSULTATION_ID", $data_id);
			$del = $this->db->delete("DIAGNOSIS_DETAILS");			
			$this->db->stop_cache();
			$this->db->flush_cache();
								
			$this->db->start_cache();
			$this->db->where("CONSULTATION_ID", $data_id);
			$del = $this->db->delete("TREATMENT_PLAN_DETAILS");			
			$this->db->stop_cache();
			$this->db->flush_cache();
						
			$this->db->start_cache();
			$this->db->where("CONSULTATION_ID", $data_id);
			$del = $this->db->delete("DRUG_PRESCRIPTION_DETAILS");			
			$this->db->stop_cache();
			$this->db->flush_cache();
				
			if($del)
			{
				$this->db->start_cache();
				$this->db->where("CONSULTATION_ID", $data_id);
				$ret = $this->db->delete("CONSULTATION_DETAILS");			
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