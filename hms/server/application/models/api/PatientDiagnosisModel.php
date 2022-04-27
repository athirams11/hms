<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PatientDiagnosisModel extends CI_Model 
{
	public function listPatientDiagnosis($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());		
		$this->db->start_cache();			
		$this->db->select("PA.*");
		$this->db->select("D.DOCTORS_NAME");
		$this->db->from("PATIENT_DIAGNOSIS PA");														
		$this->db->join("DOCTORS D","D.DOCTORS_ID = PA.USER_ID","left");
		if($post_data["patient_diagnosis_id"] > 0)
		{
			$this->db->where("PA.PATIENT_DIAGNOSIS_ID", $post_data["patient_diagnosis_id"]);
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
		
		$this->db->where("STAT",1);
		$this->db->limit($post_data['record_limit']);
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
	
	public function savePatientDiagnosis($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());	
		
			$data = array(
							"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
							"PATIENT_ID" => trim($post_data["patient_id"]),
							"CONSULTATION_ID" => trim($post_data["consultation_id"]),
							"CHIEF_COMPLAINT_ID" => trim($post_data["chief_complaint_id"]),
							"DIAGNOSIS_DATE" => toUtc(trim($post_data["date"]),$post_data["timeZone"]) //date('Y-m-d H:i:s')
							 );
						 										
			
			$data_id = trim($post_data["patient_diagnosis_id"]);
																		
			if($this->utility->is_Duplicate("PATIENT_DIAGNOSIS",array_keys($data), array_values($data),"PATIENT_DIAGNOSIS_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}
			
			$data["USER_ID"] = trim($post_data["user_id"]);
			$data["PATIENT_OTHER_DIAGNOSIS"] = trim($post_data["patient_other_diagnosis"]);
													
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("PATIENT_DIAGNOSIS_ID",$data_id);
				$this->db->update("PATIENT_DIAGNOSIS",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();								
			}
			else
			{				
				if($this->db->insert("PATIENT_DIAGNOSIS",$data))
				{
					$this->db->start_cache();			
					$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
				}
			}		
			 
			if($data_id>0)
			{
				$ret = $this->savePatientDiagnosisDetails($data_id, $post_data);
				if($ret)
				{
					$result =  array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
				if($post_data["patient_other_diagnosis"] != '')
				{
					$result =  array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}
		
		return $result;
	}

	
	public function savePatientDiagnosisDetails($patient_diagnosis_id, $req_array)
	{
		$flag = false;
		if($patient_diagnosis_id > 0)
		{		
				if(is_array($req_array['diagnosis_id_arr']) && !empty($req_array['diagnosis_id_arr']))
				{
					$this->db->start_cache();			
					$this->db->where("PATIENT_DIAGNOSIS_ID", $patient_diagnosis_id);												
					$this->db->delete("PATIENT_DIAGNOSIS_DETAILS");
					$this->db->stop_cache();
					$this->db->flush_cache();	
					$data = array();
					foreach($req_array['diagnosis_id_arr'] as $key => $diagnosis_id)
					{
						$data[$key]['PATIENT_DIAGNOSIS_ID'] = $patient_diagnosis_id; 	
						$data[$key]['SERIAL_NO'] = $key;			
						$data[$key]['DIAGNOSIS_ID'] = $diagnosis_id;																															
						$data[$key]['DIAGNOSIS_LEVEL_ID'] = (isset($req_array["diagnosis_level_arr"][$key]) ? $req_array["diagnosis_level_arr"][$key] :'' );																															
						$data[$key]['DIAGNOSIS_TYPE_ID'] = (isset($req_array["diagnosis_type_arr"][$key]) ? $req_array["diagnosis_type_arr"][$key] :'' );																																					
					}
					$this->db->insert_batch('PATIENT_DIAGNOSIS_DETAILS', $data);
					$flag = true;
				} 
															
		}
		return $flag;						 
	}
	
	public function getPatientDiagnosis($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{				
			$this->db->start_cache();			
			$this->db->select("DM.*");
			if($post_data["patient_diagnosis_id"] > 0)
			{
				$this->db->where("DM.PATIENT_DIAGNOSIS_ID", $post_data["patient_diagnosis_id"]);
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

			$this->db->from("PATIENT_DIAGNOSIS DM");
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();	
				$data['PATIENT_DIAGNOSIS_DETAILS'] = $this->getPatientDiagnosisDetails($data['PATIENT_DIAGNOSIS_ID'],'PATIENT_DIAGNOSIS_DETAILS PDD');			
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
					
		}
		return $result;
		
	}
	
	public function getPreviousDiagnosis($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{				
			$this->db->start_cache();			
			$this->db->select("DM.*,DATE_FORMAT(V.VISIT_DATE,'%d-%m-%Y') AS VISIT_DATE,DR.DOCTORS_NAME");
			if($post_data["patient_diagnosis_id"] > 0)
			{
				$this->db->where("DM.PATIENT_DIAGNOSIS_ID", $post_data["patient_diagnosis_id"]);
			}
			if($post_data["patient_id"]!=''){
				$this->db->where("DM.PATIENT_ID",$post_data["patient_id"]);
			}
			if($post_data["consultation_id"]!=''){
				$this->db->where("DM.CONSULTATION_ID", $post_data["consultation_id"]);
			}
			if($post_data["assessment_id"] > 0){
				$this->db->where("DM.ASSESSMENT_ID < ", $post_data["assessment_id"]);
			}
			$this->db->where('V.VISIT_DATE BETWEEN DATE_SUB(NOW(), INTERVAL 365 DAY) AND NOW()');
			$this->db->from("NURSING_ASSESSMENT P");
			$this->db->join("PATIENT_DIAGNOSIS DM","DM.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID",'left');
			$this->db->join("PATIENT_VISIT_LIST V","V.PATIENT_VISIT_LIST_ID = P.VISIT_ID",'left');
			$this->db->join("DOCTORS DR","DR.DOCTORS_ID = V.DOCTOR_ID",'left');
			$this->db->order_by("V.VISIT_DATE","DESC");
			// $this->db->limit(1);
			if($post_data["limit"] > 0)
			{
				$this->db->limit($post_data["limit"],$post_data["start"]);
			}
			$query = $this->db->get();
			// echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$count = $this->CountPreviousDiagnosis($post_data);
				$data = $query->result_array();	
				foreach ($data as $key => $value) {
					$data[$key]['PATIENT_DIAGNOSIS_DETAILS'] = $this->getPatientDiagnosisDetails($value['PATIENT_DIAGNOSIS_ID']);	
				}
						
			}
			if(!empty($data))
				
				$result = array("status"=> "Success", "total_count" => $count , "data"=> $data);
		}		
		
		return $result;
		
	}
	public function CountPreviousDiagnosis($post_data)
	{
		$result = 0;
		if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{	

			$this->db->start_cache();			
			$this->db->select("DM.*,DATE_FORMAT(V.VISIT_DATE,'%d-%m-%Y') AS VISIT_DATE,DR.DOCTORS_NAME");
			$this->db->where("DM.PATIENT_ID",$post_data["patient_id"]);
			$this->db->where("DM.ASSESSMENT_ID < ", $post_data["assessment_id"]);
			$this->db->where('V.VISIT_DATE BETWEEN DATE_SUB(NOW(), INTERVAL 365 DAY) AND NOW()');
			$this->db->from("NURSING_ASSESSMENT P");
			$this->db->join("PATIENT_DIAGNOSIS DM","DM.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID",'left');
			$this->db->join("PATIENT_VISIT_LIST V","V.PATIENT_VISIT_LIST_ID = P.VISIT_ID",'left');
			$this->db->join("DOCTORS DR","DR.DOCTORS_ID = V.DOCTOR_ID",'left');
			$this->db->order_by("V.VISIT_DATE","DESC");
			/*$query = $this->db->get();	
			if($query->num_rows() > 0)
			{
				$data = $query->result_array();	
			}*/
			$result = $this->db->count_all_results();
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		return $result;
	}
	public function getPatientDiagnosisDetails($patient_diagnosis_id)
	{
			$this->db->start_cache();			
			$this->db->select("PDD.*,DATE_FORMAT(V.VISIT_DATE,'Y-m-d')");			
			$this->db->select("D.CODE as DIAGNOSIS_CODE,D.NAME as DIAGNOSIS_NAME");		
			$this->db->select("MD1.DATA as DIAGNOSIS_LEVEL_NAME,MD2.DATA as DIAGNOSIS_TYPE_NAME");				

			$this->db->from("PATIENT_DIAGNOSIS_DETAILS PDD");
			$this->db->join("PATIENT_DIAGNOSIS PD","PD.PATIENT_DIAGNOSIS_ID = PDD.PATIENT_DIAGNOSIS_ID","left");
			$this->db->join("DIAGNOSIS_MASTER D","D.DIAGNOSIS_ID = PDD.DIAGNOSIS_ID","left");
			$this->db->join("NURSING_ASSESSMENT N","PD.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID",'left');
			$this->db->join("PATIENT_VISIT_LIST V","V.PATIENT_VISIT_LIST_ID = N.VISIT_ID",'left');
			$this->db->join("MASTER_DATA MD1","MD1.MASTER_DATA_ID = PDD.DIAGNOSIS_LEVEL_ID  ","left");
			$this->db->join("MASTER_DATA MD2","MD2.MASTER_DATA_ID = PDD.DIAGNOSIS_TYPE_ID  ","left");
			$this->db->where("PDD.PATIENT_DIAGNOSIS_ID", $patient_diagnosis_id);
			$this->db->order_by("V.VISIT_DATE","DESC");			
			$this->db->order_by("PDD.PATIENT_DIAGNOSIS_DETAILS_ID","ASC");			
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
	/*$this->db->start_cache();			
			$this->db->select("PDD.*,DATE_FORMAT(V.VISIT_DATE,'%d-%m-%Y') AS VISIT_DATE,DR.DOCTORS_NAME");	
			$this->db->select("D.CODE as DIAGNOSIS_CODE,D.NAME as DIAGNOSIS_NAME");		
			$this->db->select("MD1.DATA as DIAGNOSIS_LEVEL_NAME,MD2.DATA as DIAGNOSIS_TYPE_NAME,PD.ASSESSMENT_ID,PD.PATIENT_ID");				
			if($post_data["patient_diagnosis_id"] > 0)
			{
				$this->db->where("PD.PATIENT_DIAGNOSIS_ID", $post_data["patient_diagnosis_id"]);
			}
			if($post_data["patient_id"]!=''){
				$this->db->where("PD.PATIENT_ID",84);
			}
			if($post_data["consultation_id"]!=''){
				$this->db->where("PD.CONSULTATION_ID", $post_data["consultation_id"]);
			}
			if($post_data["assessment_id"] > 0){
				$this->db->where("PD.ASSESSMENT_ID < ", $post_data["assessment_id"]);
			}	
			$this->db->where('V.VISIT_DATE BETWEEN DATE_SUB(NOW(), INTERVAL 365 DAY) AND NOW()');				
			$this->db->from("PATIENT_DIAGNOSIS_DETAILS PDD");
			$this->db->join("PATIENT_DIAGNOSIS PD","PD.PATIENT_DIAGNOSIS_ID = PDD.PATIENT_DIAGNOSIS_ID","left");
			$this->db->join("DIAGNOSIS_MASTER D","D.DIAGNOSIS_ID = PDD.DIAGNOSIS_ID","left");
			$this->db->join("NURSING_ASSESSMENT N","PD.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID",'left');
			$this->db->join("PATIENT_VISIT_LIST V","V.PATIENT_VISIT_LIST_ID = N.VISIT_ID",'left');
			$this->db->join("DOCTORS DR","DR.DOCTORS_ID = V.DOCTOR_ID",'left');
			$this->db->join("MASTER_DATA MD1","MD1.MASTER_DATA_ID = PDD.DIAGNOSIS_LEVEL_ID  ","left");
			$this->db->join("MASTER_DATA MD2","MD2.MASTER_DATA_ID = PDD.DIAGNOSIS_TYPE_ID  ","left");
			// $this->db->where("PDD.PATIENT_DIAGNOSIS_ID", $patient_diagnosis_id);
			$this->db->order_by("V.VISIT_DATE","DESC");			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			$data = array();
			if($query->num_rows() > 0)
			{
				$data = $query->result_array();							
			}*/
	}
	public function deletePatientDiagnosis($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["patient_diagnosis_id"];
		if($data_id > 0)
		{															
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("PATIENT_DIAGNOSIS");
			$this->db->where("PATIENT_DIAGNOSIS_ID", $data_id);			
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
				$this->db->where("PATIENT_DIAGNOSIS_ID", $data_id);												
				$this->db->delete("PATIENT_DIAGNOSIS_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();
							
				$this->db->start_cache();
				$this->db->where("PATIENT_DIAGNOSIS_ID", $data_id);
				$ret = $this->db->delete("PATIENT_DIAGNOSIS");			
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