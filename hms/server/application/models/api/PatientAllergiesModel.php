<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PatientAllergiesModel extends CI_Model 
{
	public function listPatientAllergies($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());		
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("PATIENT_ALLERGIES DM");
		if($post_data["patient_allergies_id"] !='')
		{
			$this->db->where("DM.PATIENT_ALLERGIES_ID", $post_data["patient_allergies_id"]);			
		}						
		if($post_data["assessment_id"] !='')
		{
			$this->db->where("DM.ASSESSMENT_ID", $post_data["assessment_id"]);			
		}
		if($post_data["patient_id"] !='')
		{
			$this->db->where("DM.PATIENT_ID", $post_data["patient_id"]);			
		}
					
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
	
	public function savePatientAllergies($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());	
		
			$data = array(
							"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
							"PATIENT_ID" => trim($post_data["patient_id"]),	
							"CLIENT_DATE" => format_date($post_data["client_date"])				
							 );
						 										
			
			$data_id = trim($post_data["patient_allergies_id"]);
			
			$ret = $this->ApiModel->mandatory_check( $post_data , array('patient_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }	
         
			if($this->utility->is_Duplicate("PATIENT_ALLERGIES",array_keys($data), array_values($data),"PATIENT_ALLERGIES_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
			
			$data["CONSULTATION_ID"] = trim($post_data["consultation_id"]);
			$data["USER_ID"] = trim($post_data["user_id"]);
			$data["NO_KNOWN_ALLERGIES"] = trim($post_data["patient_no_known_allergies"]);
													
			if ($data_id > 0)
			{				
				$this->db->start_cache();
				$this->db->where("PATIENT_ID", $post_data["patient_id"]);	
				$this->db->where("PATIENT_ALLERGIES_ID",$data_id);
				$this->db->update("PATIENT_ALLERGIES",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();								
			}
			else
			{				
				if($this->db->insert("PATIENT_ALLERGIES",$data))
				{
					$this->db->start_cache();			
					$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
				}
			}		
			 
			if($data_id>0)
			{
				$ret = $this->savePatientAllergiesDetails($data_id, $post_data);
				if($ret){
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}
		
		return $result;
	}
	
	public function savePatientAllergiesDetails($patient_allergies_id, $req_array)
	{
		$flag = false;
		if($patient_allergies_id > 0)
		{		
				if(is_array($req_array['patient_drug_allergies_generic_name']))
				{
					$this->db->start_cache();			
					$this->db->where("PATIENT_ALLERGIES_ID", $patient_allergies_id);	
					$this->db->delete("PATIENT_ALLERGIES_DETAILS");
					$this->db->stop_cache();
					$this->db->flush_cache();	
					$data = array();
					foreach($req_array['patient_drug_allergies_generic_name'] as $key => $generic_name)
					{
						if($generic_name != '')
						{
							$data[$key]['PATIENT_ALLERGIES_ID'] = $patient_allergies_id; 	
							$data[$key]['SERIAL_NO'] = $key;			
							$data[$key]['GENERIC_NAME'] = $generic_name;																															
							$data[$key]['BRAND_NAME'] = (isset($req_array["patient_drug_allergies_brand_name"][$key]) ? $req_array["patient_drug_allergies_brand_name"][$key] :'' );		
						}
																									
					}
					if(!empty($data))
						$this->db->insert_batch('PATIENT_ALLERGIES_DETAILS', $data);
					$flag = true;
				} 
				
				if(is_array($req_array['patient_other_allergies_detail_id']))
				{
					$this->db->start_cache();			
					$this->db->where("PATIENT_ALLERGIES_ID", $patient_allergies_id);	
					$this->db->delete("PATIENT_ALLERGIES_OTHER_DETAILS");
					$this->db->stop_cache();
					$this->db->flush_cache();	
					$data = array();
					foreach($req_array['patient_other_allergies_detail_id'] as $key => $detail_id)
					{
						if($detail_id > 0)
						{
							$data[$key]['PATIENT_ALLERGIES_ID'] = $patient_allergies_id; 	
							$data[$key]['SERIAL_NO'] = $key;			
							$data[$key]['ALLERGIES_OTHER_ID'] = $detail_id;																															
							$data[$key]['ALLERGIES_ITEM'] = (isset($req_array["patient_other_allergies_item"][$key]) ? $req_array["patient_other_allergies_item"][$key] :'' );																				
						}
					}
					if(!empty($data))
						$this->db->insert_batch('PATIENT_ALLERGIES_OTHER_DETAILS', $data);
					$flag = true;
				}							
		}
		return $flag;						 
	}
	
	public function getPatientAllergies($post_data)
	{
		$result = array("status"=> "Failed", "data"=> $post_data);
		$data = array();
		if($post_data["patient_id"] > 0 )
		{
				
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("PATIENT_ALLERGIES DM");
			if($post_data["patient_allergies_id"] > 0)
			{
				$this->db->where("DM.PATIENT_ALLERGIES_ID", $post_data["patient_allergies_id"]);			
			}						
			if($post_data["assessment_id"] > 0)
			{
				$this->db->where("DM.ASSESSMENT_ID", $post_data["assessment_id"]);			
			}
			if($post_data["patient_id"] > 0)
			{
				$this->db->where("DM.PATIENT_ID", $post_data["patient_id"]);			
			}
			$query = $this->db->get();
			//return $this->db->last_query();		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();	
				$data['DRUG_ALLERGIES'] = $this->getPatientAllergiesDetails($data["PATIENT_ALLERGIES_ID"],'PATIENT_ALLERGIES_DETAILS');			
				$data['OTHER_ALLERGIES'] = $this->getPatientOtherAllergiesDetails($data["PATIENT_ALLERGIES_ID"],'PATIENT_ALLERGIES_OTHER_DETAILS');			
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
				
		}
		return $result;
		
	}
	
	public function getPatientAllergiesDetails($patient_allergies_id, $table)
	{
			$this->db->start_cache();			
			$this->db->select("*");
			$this->db->from($table);
			$this->db->where("PATIENT_ALLERGIES_ID", $patient_allergies_id);
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
	
	
	public function getPatientOtherAllergiesDetails($patient_allergies_id, $table)
	{
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->select("MD1.NAME as ALLERGIES_OTHER_NAME,MD1.DESCRIPTION as ALLERGIES_OTHER_DESCRIPTION ");
			$this->db->from($table.' DM ');
			$this->db->where("PATIENT_ALLERGIES_ID", $patient_allergies_id);
			$this->db->join("ALLERGIES_OTHER_MASTER MD1","MD1.ALLERGIES_OTHER_ID = DM.ALLERGIES_OTHER_ID  ","left");
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
	
	public function deletePatientAllergies($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["patient_allergies_id"];
		if($data_id > 0)
		{															
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("PATIENT_ALLERGIES");
			$this->db->where("PATIENT_ALLERGIES_ID", $data_id);			
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
				$this->db->where("PATIENT_ALLERGIES_ID", $data_id);												
				$this->db->delete("PATIENT_ALLERGIES_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();
				
				$this->db->start_cache();			
				$this->db->where("PATIENT_ALLERGIES_ID", $data_id);												
				$this->db->delete("PATIENT_ALLERGIES_OTHER_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();
						
			
				$this->db->start_cache();
				$this->db->where("PATIENT_ALLERGIES_ID", $data_id);
				$ret = $this->db->delete("PATIENT_ALLERGIES");			
				//echo $this->db->last_query();exit;		
				$this->db->stop_cache();
				$this->db->flush_cache();				 
				if(!empty($ret))
					$result = array("status"=> "Success", "data"=> $data);
				
			}										
		}
		return $result;
		
	}
	


	public function getPatientAllergiespdf($id)
	{
		$result = array("status"=> "Failed", "data"=> $id);
		$data = array();
		if($id > 0 )
		{
				
			$this->db->start_cache();			
			$this->db->select("DM.*");
			$this->db->from("PATIENT_ALLERGIES DM");
			if($id > 0)
			{
				$this->db->where("DM.PATIENT_ID", $id);			
			}
			$query = $this->db->get();
			//return $this->db->last_query();		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();	
				$data['DRUG_ALLERGIES'] = $this->getPatientAllergiesDetails($data["PATIENT_ALLERGIES_ID"],'PATIENT_ALLERGIES_DETAILS');			
				$data['OTHER_ALLERGIES'] = $this->getPatientOtherAllergiesDetails($data["PATIENT_ALLERGIES_ID"],'PATIENT_ALLERGIES_OTHER_DETAILS');			
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
				
		}
		return $result;
		
	}


} 
?>