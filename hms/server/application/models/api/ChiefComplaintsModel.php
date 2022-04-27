<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ChiefComplaintsModel extends CI_Model 
{
	public function listComplaints($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());						
		if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{
			$this->db->start_cache();			
			$this->db->select("CC.*");
			$this->db->from("CHIEF_COMPLAINTS CC");
			if($post_data["complaint_id"]!=''){
			$this->db->where("CC.CHIEF_COMPLAINT_ID", $post_data["complaint_id"]);
			}
			if($post_data["patient_id"]!=''){
			$this->db->where("PATIENT_ID", $post_data["patient_id"]);
			}
			if($post_data["CONSULTATION_ID"]!=''){
			$this->db->where("PATIENT_ID", $post_data["consultation_id"]);
			}				
			if($post_data["appointment_date"]!=''){
			//$this->db->where("CREATED_DATE", $post_data["appointment_date"]);
			}		
			$query = $this->db->get();
			//echo $this->db->last_query();exit;	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result_array();				
				$result = array("status"=> "Success", "data"=> $data);
			}
		}
		return $result;
	}
	
	public function saveComplaints($post_data)
	{
		// print_r($post_data);
		// exit();
		$result = array("status"=> "Failed", "data"=> array());		
			$data = array(
					"ASSESSMENT_ID"  => trim($post_data["assessment_id"]),
					"CONSULTATION_ID"=> trim($post_data["consultation_id"]),
					"PATIENT_ID" 	 => trim($post_data["patient_id"]),
					"USER_ID" 		 => trim($post_data["user_id"]),											
					"COMPLAINTS"	 => (isset($complaint_data['complaints']) ? $complaint_data['complaints'] : '') ,
					"CREATED_DATE"	 => date('Y-m-d H:i:s'),
					"CLIENT_DATE" 	 => format_date($post_data["client_date"])	
						 );
			
			$complaint_col_arr = array(
												"complaints" => "COMPLAINTS",
												"procedure_result" => "PROCEDURE_RESULT", 
												"summary_of_case" => "SUMMARY_OF_CASE",
												"instruction_to_nurse" => "INSTRUCTION_TO_NURSE",
												"past_medical_history" => "PAST_MEDICAL_HISTORY",
												"drug_history" => "DRUG_HISTORY",
												"social_history" => "SOCIAL_HISTORY",
												"clinical_examination" => "CLINICAL_EXAMINATION",
												"umbilicus" => "UMBILICUS",
												"chest" =>  "CHEST",
												"abdomen" => "ABDOMEN",
												"history_of_present_illness" => "HISTORY_OF_PRESENT_ILLNESS",
												"notes" => "NOTES",
												"CNS" => "CNS",
												"EYE" => "EYE",
												"fever" => "FEVER",
												"CVS" => "CVS"
												);
												 			 
			$complaint_data = $post_data["complaint_data"];			
			if($complaint_data && is_array($complaint_data) && count($complaint_data) > 0)
			{
				foreach($complaint_data as $key => $value)
				{
					if(isset($complaint_col_arr[$key]))
					{
						if($value != '')
						{
							$data[$complaint_col_arr[$key]] = $value;
						}
						else
						{
							$data[$complaint_col_arr[$key]] = NULL;
						}
					}
				}
			} 
					
			$dp_data = array(
							"ASSESSMENT_ID" => trim($post_data["assessment_id"]),							
							"PATIENT_ID" => trim($post_data["patient_id"])
						 			);
						 
			$data_id = trim($post_data["complaint_id"]);
			
			$ret = $this->ApiModel->mandatory_check( $post_data , array('patient_id','assessment_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }		
         									
			if($this->utility->is_Duplicate("CHIEF_COMPLAINTS",array_keys($dp_data), array_values($dp_data),"CHIEF_COMPLAINT_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}									
			  
			  
			if ($data_id > 0)
			{				
						$this->db->start_cache();					
						$this->db->where("CHIEF_COMPLAINT_ID",$data_id);
						$this->db->update("CHIEF_COMPLAINTS",$data);					
						$this->db->stop_cache();
						$this->db->flush_cache();						
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{			
				$data["DATE_TIME"] = toUtc(trim($post_data["date"]),$post_data["timeZone"]);	
				
				if($this->db->insert("CHIEF_COMPLAINTS",$data))
				{
						$this->db->start_cache();			
						$data_id = $this->db->insert_id();
						$this->db->stop_cache();
						$this->db->flush_cache();									
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data saved")	;
				}
			}			
		
		return $result;
	}
	

	
	public function getComplaints($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		
		if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{					
			$this->db->start_cache();			
			$this->db->select("CC.*");
			$this->db->from("CHIEF_COMPLAINTS CC");
			if($post_data["complaint_id"]!=''){
				$this->db->where("CC.CHIEF_COMPLAINT_ID", $post_data["complaint_id"]);
			}
			
			if($post_data["patient_id"]!=''){
				$this->db->where("CC.PATIENT_ID", $post_data["patient_id"]);
			}
			
			if($post_data["assessment_id"]!=''){
				$this->db->where("CC.ASSESSMENT_ID", $post_data["assessment_id"]);
			}
			
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
	
	public function getPreviousComplaints($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();

		if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{	

			$this->db->start_cache();			
			$this->db->select("CC.CHIEF_COMPLAINT_ID,CC.CREATED_DATE,D.DOCTORS_NAME,V.VISIT_DATE,CC.ASSESSMENT_ID");	
			$this->db->from("CHIEF_COMPLAINTS CC");		
			$this->db->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = CC.ASSESSMENT_ID","left");		
			$this->db->join("PATIENT_VISIT_LIST V","V.PATIENT_VISIT_LIST_ID = N.VISIT_ID","left");		
			$this->db->join("DOCTORS D","CC.USER_ID = D.LOGIN_ID","left");		
			$this->db->where("CC.PATIENT_ID", $post_data["patient_id"]);
			$this->db->where("CC.ASSESSMENT_ID < ",$post_data["assessment_id"]);	
			$this->db->order_by("N.NURSING_ASSESSMENT_ID","DESC");
			if($post_data["search_text"] != '' && $post_data["timeZone"] != '')
			{
				$this->db->like("DATE(CONVERT_TZ(V.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["search_text"],1));	
				// $this->db->like("DATE(V.VISIT_DATE)",format_date($post_data["search_text"]);
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
			// echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$counts = $this->CountPreviousComplaints($post_data);
				$data = $query->result_array();			
				foreach ($data as $key =>  $value) {
					$data[$key]["COMPLAINTS"] = $this->getPreviousComplaintsDetails($value['CHIEF_COMPLAINT_ID']);		
				}
				$result = array("status"=> "Success", "total_count" => $counts , "data"=> $data);
			}
		}			
		
		return $result;

		/*if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{						
			$this->db->start_cache();			
			$this->db->select("P.NURSING_ASSESSMENT_ID");
			$this->db->from("NURSING_ASSESSMENT P");
			$this->db->where("P.PATIENT_ID", $post_data["patient_id"]);
			$this->db->where("P.NURSING_ASSESSMENT_ID < ",$post_data["assessment_id"]);	
			$this->db->order_by("P.NURSING_ASSESSMENT_ID","DESC");
			$this->db->limit(1);
			$query = $this->db->get();
			//echo $this->db->last_query();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$value = $query->row_array();	
				$this->db->start_cache();			
				$this->db->select("CC.*");	
				$this->db->from("CHIEF_COMPLAINTS CC");		
				$this->db->where("CC.PATIENT_ID", $post_data["patient_id"]);
				$this->db->where("CC.ASSESSMENT_ID",$value["NURSING_ASSESSMENT_ID"]);	
				$quy = $this->db->get();	
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($quy->num_rows() > 0)
				{
					$data = $quy->row_array();				
				}
				if(!empty($data))
					$result = array("status"=> "Success", "data"=> $data);
			}
		}			
		
		return $result;*/
		

		/*$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{						
			$this->db->start_cache();			
			$this->db->select("CC.*");
			$this->db->from("NURSING_ASSESSMENT P");
			$this->db->join("CHIEF_COMPLAINTS CC","CC.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID",'left');
			if($post_data["consultation_id"]!=''){
				$this->db->where("CC.CONSULTATION_ID", $post_data["consultation_id"]);
			}
			
			if($post_data["patient_id"]!=''){
				$this->db->where("CC.PATIENT_ID", $post_data["patient_id"]);
			}
					
			if($post_data["assessment_id"] > 0){
				$this->db->where("P.NURSING_ASSESSMENT_ID < ",$post_data["assessment_id"]);	
			}
			
			$this->db->order_by("CC.ASSESSMENT_ID","DESC");
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
		}			
		
		return $result;*/
		
	}
	
	public function CountPreviousComplaints($post_data)
	{
		$result = 0;
		if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{	

			$this->db->start_cache();			
			$this->db->select("CC.CHIEF_COMPLAINT_ID,CC.CREATED_DATE,D.DOCTORS_NAME");	
			$this->db->from("CHIEF_COMPLAINTS CC");		
			$this->db->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = CC.ASSESSMENT_ID","left");		
			$this->db->join("PATIENT_VISIT_LIST V","V.PATIENT_VISIT_LIST_ID = N.VISIT_ID","left");		
			$this->db->join("DOCTORS D","V.DOCTOR_ID = D.DOCTORS_ID","left");		
			$this->db->where("CC.PATIENT_ID", $post_data["patient_id"]);
			$this->db->where("CC.ASSESSMENT_ID < ",$post_data["assessment_id"]);	
			$this->db->order_by("N.NURSING_ASSESSMENT_ID","DESC");
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
	public function getPreviousComplaintsDetails($complaint_id)
	{
		$this->db->start_cache();	
		// $SQL = "SELECT COMPLAINTS, SUMMARY_OF_CASE, INSTRUCTION_TO_NURSE, PAST_MEDICAL_HISTORY,DRUG_HISTORY,SOCIAL_HISTORY,
		// 	CLINICAL_EXAMINATION,UMBILICUS,CHEST,ABDOMEN,
		// 	HISTORY_OF_PRESENT_ILLNESS,NOTES,CNS,
		// 	EYE,FEVER,CVS from CHIEF_COMPLAINTS
 	// 	 where CHIEF_COMPLAINT_ID = '".$complaint_id."'";
			
		$this->db->select("COMPLAINTS, SUMMARY_OF_CASE, 
			INSTRUCTION_TO_NURSE, PAST_MEDICAL_HISTORY,
			DRUG_HISTORY,SOCIAL_HISTORY,
			CLINICAL_EXAMINATION,UMBILICUS,CHEST,
			ABDOMEN,HISTORY_OF_PRESENT_ILLNESS,NOTES,
			CNS, EYE,FEVER,CVS,PROCEDURE_RESULT");	
		$this->db->from("CHIEF_COMPLAINTS");		
		$this->db->where("CHIEF_COMPLAINT_ID", $complaint_id);
		$query = $this->db->get();	
		// $query = $this->db->query($SQL);	
		//echo $this->db->last_query();
		$this->db->stop_cache();
		$this->db->flush_cache();
		$data = array();
		if($query->num_rows() > 0)
		{
			$data = $query->row_array();							
		}
		return $data;
	}
	public function deleteComplaints($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["complaint_id"];
		if($data_id > 0)
		{									
			$this->db->start_cache();
			$this->db->where("CHIEF_COMPLAINT_ID", $data_id);
			$ret = $this->db->delete("CHIEF_COMPLAINTS");			
			$this->db->stop_cache();
			$this->db->flush_cache();
				 
			if(!empty($ret))
				$result = array("status"=> "Success", "data"=> $data);
						
		}
		return $result;
		
	}
	

	public function changeComplaints($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["complaint_id"];
		$stat = $post_data["complaint_status"];
		if($data_id > 0)
		{									
			$this->db->start_cache();
			$this->db->where("CHIEF_COMPLAINT_ID", $data_id);
			$ret = $this->db->update("CHIEF_COMPLAINTS",array("COMPLAINTS_STATUS" => $stat) );			
			$this->db->stop_cache();
			$this->db->flush_cache();
				 
			if(!empty($ret))
				$result = array("status"=> "Success", "data"=> $data);
						
		}
		return $result;
		
	}
	public function saveDentalComplaints($postData)
	{
		$result = array("status"=> "Failed", "msg"=> 'Failed');	
		if(is_array($postData['dental_data']))	
		{
			$post = $postData['dental_data'];
			$post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
			$post_data["date"] = isset($post["date"]) ? $post["date"] : '';
			$post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';
			$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : 0;
			$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
			$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : 0;
			$post_data["dental_id"] = isset($post["dental_id"]) ? $post["dental_id"] : '';       
			$post_data["FDI"] = isset($post["FDI"]) ? $post["FDI"] : ''; 
			$post_data["palmer"] = isset($post["palmer"]) ? $post["palmer"] : ''; 
			$post_data["tooth_complaint"] = isset($post["tooth_complaint"]) ? $post["tooth_complaint"] : ''; 
			$post_data["tooth_issue"] = isset($post["tooth_issue"]) ? $post["tooth_issue"] : ''; 
			$post_data["universal"] = isset($post["universal"]) ? $post["universal"] : ''; 
			$post_data["procedure"] = isset($post["procedure"]) ? $post["procedure"] : ''; 
			$post_data["color"] = isset($post["color"]) ? $post["color"] : ''; 
			$post_data["child_tooth_value"] = isset($post["child_tooth_value"]) ? $post["child_tooth_value"] : ''; 
			$post_data["child_tooth_number"] = isset($post["child_tooth_number"]) ? $post["child_tooth_number"] : ''; 
			$post_data["dental_complaint_id"] = isset($post["dental_complaint_id"]) ? $post["dental_complaint_id"] : '';
			$post_data["patient_type"] = isset($post["patient_type"]) ? $post["patient_type"] : ''; 
			$post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : ''; 
			$post_data["complients"] = isset($post["complients"]) ? $post["complients"] : ''; 
			$post_data["child_complients"] = isset($post["child_complients"]) ? $post["child_complients"] : ''; 
			$post_data["dental_id"] = isset($post["dental_id"]) ? $post["dental_id"] : ''; 
		
			// if(is_array($post_data['complients']) || is_array($post_data['child_complients']))
			// {
				
				$data = array(
					"ASSESSMENT_ID"  => trim($post_data["assessment_id"]),
					"CONSULTATION_ID"=> trim($post_data["consultation_id"]),
					"PATIENT_ID" 	 => trim($post_data["patient_id"]),
					"USER_ID" 		 => trim($post_data["user_id"]),											
					"PATIENT_TYPE" 	 => trim($post_data["patient_type"]),	
					"CREATED_DATE"	 => date('Y-m-d H:i:s'),
					"CLIENT_DATE" 	 => format_date($post_data["client_date"]),
						 );
				$data_id = $post_data["dental_complaint_id"];
				if($data_id > 0)
				{				
					$this->db->start_cache();					
					$this->db->where("DENTAL_COMPLAINT_ID",$data_id);
					$this->db->update("DENTAL_COMPLAINTS",$data);					
					$this->db->stop_cache();
					$this->db->flush_cache();						
					$val = $this->saveDental($post_data,$data_id);
					if($val)
					{
						$rel = $this->saveDentalInvestigation($postData["dental_investigative"]);
						$result = array("status"=> "Success", "dental_complaint_id"=>$data_id ,"msg"=> 'Dental complaint details saved successfully!');
					}
						
				}
				else
				{			
					$data["DATE_TIME"] = toUtc(trim($post_data["client_date"]),$post_data["timeZone"]);	
					
					if($this->db->insert("DENTAL_COMPLAINTS",$data))
					{
						$this->db->start_cache();			
						$data_id = $this->db->insert_id();
						$this->db->stop_cache();
						$this->db->flush_cache();	
						$val = $this->saveDental($post_data,$data_id);
						if($val)
						{
							$rel = $this->saveDentalInvestigation($postData["dental_investigative"]);
							$result = array("status"=> "Success", "dental_complaint_id"=>$data_id , "msg"=> 'Dental complaint details saved successfully!');
						}
					}
				}	
			// }

		}
		return $result;
	}
	public function saveDental($post_data,$dental_complaint_id)
	{
		//print_r($post_data);
		$flag = false;	
		if($dental_complaint_id > 0)
		{
			$this->db->start_cache();			
			$this->db->where("DENTAL_COMPLAINT_ID", $dental_complaint_id);	
			$this->db->delete("PATIENT_DENTAL_PROCEDURE");
			$this->db->stop_cache();
			$this->db->flush_cache();	
			if($post_data["patient_type"] == 1 && is_array($post_data['complients']))
			{
				foreach($post_data['complients'] as $key => $complaints)
				{
					 
					if(is_array($complaints) && !empty($complaints))
					{
						foreach($complaints as  $complaint)
						{

							$data["DENTAL_COMPLAINT_ID"] = $dental_complaint_id;
							$data["TOOTH_NUMBER"] = $complaint["tooth_number"];
							$data["PROCEDURE"] = $complaint["procedure"];
							$data["PROCEDURE_COLOR"] = $complaint["color"];
							$data["DESCRIPTION"] = $complaint["description"];
							$data["TOOTH_INDEX"] = $complaint["tooth_index"];
							
							$this->db->start_cache();		
							$this->db->insert("PATIENT_DENTAL_PROCEDURE",$data);
							$this->db->stop_cache();
							$this->db->flush_cache();	
							$flag = true;
						}
					}
				}																	
			}
			if($post_data["patient_type"] == 2 && is_array($post_data['child_complients']))
			{
				foreach($post_data['child_complients'] as $key => $complaints)
				{
					 
					if(is_array($complaints) && !empty($complaints))
					{
						foreach($complaints as  $complaint)
						{

							$data["DENTAL_COMPLAINT_ID"] = $dental_complaint_id;
							$data["TOOTH_NUMBER"] = $complaint["tooth_number"];
							$data["PROCEDURE"] = $complaint["procedure"];
							$data["PROCEDURE_COLOR"] = $complaint["color"];
							$data["DESCRIPTION"] = $complaint["description"];
							$data["TOOTH_INDEX"] = $complaint["tooth_index"];
							
							$this->db->start_cache();		
							$this->db->insert("PATIENT_DENTAL_PROCEDURE",$data);
							$this->db->stop_cache();
							$this->db->flush_cache();	
							$flag = true;
						}
					}
				}																	
			}
			$flag = true;										
		}
		return $flag;
	}
	public function getDentalComplaints($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{							
			$this->db->start_cache();			
			$this->db->select("D.*");
			$this->db->from("DENTAL_COMPLAINTS D");
			if($post_data["patient_id"]!=''){
				$this->db->where("D.PATIENT_ID", $post_data["patient_id"]);
			}
			
			if($post_data["assessment_id"]!=''){
				$this->db->where("D.ASSESSMENT_ID", $post_data["assessment_id"]);
			}
			// $this->db->group_by("D.PATIENT_ID,D.ASSESSMENT_ID");
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();	
				$data["DENTAL_COMPLAINT"] = $this->getDentalComplaintsDetails($data);					
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
		}		
		
		return $result;
		
	}
	public function getDentalComplaintsDetails($data)
	{
		$result = array();
		if($data["DENTAL_COMPLAINT_ID"] > 0)
		{
			$this->db->start_cache();			
			$this->db->select("D.*");
			$this->db->from("PATIENT_DENTAL_PROCEDURE D");
			$this->db->where("D.DENTAL_COMPLAINT_ID", $data["DENTAL_COMPLAINT_ID"]);
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = $query->result_array();	
			}
		}
		
		return $result;
		
	}
	// FOr dental investigation

	public function saveDentalInvestigation($post)
	{
		
		// $result = array("status"=> "Failed", "data"=> array());	
		$result = false;	

		$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
		$post_data["doctor_id"] = isset($post["doctor_id"]) ? $post["doctor_id"] : "";
		$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
		$post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
		$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
		$post_data["lab_investigation_id"] = isset($post["lab_investigation_id"]) ? $post["lab_investigation_id"] : 0;
		$post_data["current_procedural_code_id_arr"] = isset($post["current_procedural_code_id_arr"]) ? $post["current_procedural_code_id_arr"] : "";
		$post_data["description_arr"] = isset($post["description_arr"]) ? $post["description_arr"] : "";            
		$post_data["quantity_arr"] = isset($post["quantity_arr"]) ? $post["quantity_arr"] : "";
		$post_data["rate_arr"] = isset($post["rate_arr"]) ? $post["rate_arr"] : "";
		$post_data["remarks_arr"] = isset($post["remarks_arr"]) ? $post["remarks_arr"] : "";
		$post_data["tooth_number"] = isset($post["tooth_number"]) ? $post["tooth_number"] : 0;
		$post_data["tooth_index"] = isset($post["tooth_index"]) ? $post["tooth_index"] : NULL;
		$post_data["tooth_procedure"] = isset($post["tooth_procedure"]) ? $post["tooth_procedure"] : NULL;
		$post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';  
		$post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';
		$post_data["date"] = isset($post["date"]) ? $post["date"] : '';	
			$dp_data = array(
				"PATIENT_ID" => trim($post_data["patient_id"]),
				"ASSESSMENT_ID" => trim($post_data["assessment_id"])							
			);
							 
			$data = array(
				"PATIENT_ID" => trim($post_data["patient_id"]),
				"USER_ID" => trim($post_data["user_id"]),
				"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
				"IS_DENTAL" => 1,	
				"DATE_LOG" => toUtc(trim($post_data["date"]),$post_data["timeZone"]),//date('Y-m-d H:i:s'),	
				"CLIENT_DATE" => format_date($post_data["client_date"])
			);
						 										
			
			$data_id = trim($post_data["lab_investigation_id"]);
									
			// $ret = $this->ApiModel->mandatory_check( $post_data , array('user_id','patient_id','assessment_id','current_procedural_code_id_arr'));		  
		 //   if($ret!='')
		 //   {		  	 		  		                         	 		  
   //      	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
   //       	}	
                       									
			if($this->utility->is_Duplicate("LAB_INVESTIGATION",array_keys($dp_data), array_values($dp_data),"LAB_INVESTIGATION_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}	
												
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("LAB_INVESTIGATION_ID",$data_id);
				$this->db->update("LAB_INVESTIGATION",$data);
				$this->db->stop_cache();
				$this->db->flush_cache();								
			}
			else
			{				
				if($this->db->insert("LAB_INVESTIGATION",$data))
				{
					$this->db->start_cache();			
					$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
				}
			}		
			 
			if($data_id>0)
			{
				$ret = $this->saveDentalInvestigationDetails($data_id, $post_data);
				if($ret){
				// return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
					$result = true;
				}
			}
		
		return $result;
	}
	public function saveDentalInvestigationDetails($lab_investigation_id, $req_array)
	{
		$flag = false;
		if($lab_investigation_id > 0)
		{	
			$this->db->start_cache();			
			$this->db->where("LAB_INVESTIGATION_ID", $lab_investigation_id);	
			$this->db->delete("LAB_INVESTIGATION_DETAILS");
			$this->db->stop_cache();
			$this->db->flush_cache();	
			if(is_array($req_array['current_procedural_code_id_arr']))
			{
					
				$data = array();
				foreach($req_array['current_procedural_code_id_arr'] as $key => $cpt_code_id)
				{
					if($cpt_code_id > 0)
					{
						$data[$key]['SERIAL_NO'] = $key;			
						$data[$key]['LAB_INVESTIGATION_ID'] = $lab_investigation_id; 	
						$data[$key]['CURRENT_PROCEDURAL_CODE_ID'] = $cpt_code_id;																											
						$data[$key]['DESCRIPTION'] = (isset($req_array["description_arr"][$key]) ? $req_array["description_arr"][$key] :'' );	
							
						$data[$key]['QUANTITY'] = (isset($req_array["quantity_arr"][$key]) ? $req_array["quantity_arr"][$key] :'' );		
						$data[$key]['RATE'] = (isset($req_array["rate_arr"][$key]) ? $req_array["rate_arr"][$key] :'' );		
						$data[$key]['TOOTH_NUMBER'] = (isset($req_array["tooth_number"][$key]) ? $req_array["tooth_number"][$key] :'' );		
						$data[$key]['TOOTH_INDEX'] = (isset($req_array["tooth_index"][$key]) ? $req_array["tooth_index"][$key] :'' );		
						$data[$key]['TOOTH_PROCEDURE'] = (isset($req_array["tooth_procedure"][$key]) ? $req_array["tooth_procedure"][$key] :'' );		
						$data[$key]['REMARKS'] = (isset($req_array["remarks_arr"][$key]) ? $req_array["remarks_arr"][$key] :'' );		
					}
					
																	
				}
				if(!empty($data))
				$this->db->insert_batch('LAB_INVESTIGATION_DETAILS', $data);
			} 															
			$flag = true;
		}
		return $flag;						 
	}

	public function getDentalInvestigation($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"]!='' && $post_data["assessment_id"]!=''){ 				
			$this->db->start_cache();			
			$this->db->select("DM.*");
			
			if($post_data["lab_investigation_id"]!='')
			{
				$this->db->where("LAB_INVESTIGATION_ID", $post_data["lab_investigation_id"]);
			}			
			if($post_data["patient_id"]!='')
			{
				$this->db->where("PATIENT_ID", $post_data["patient_id"]);
			}
			if($post_data["consultation_id"]!='')
			{
				$this->db->where("CONSULTATION_ID", $post_data["consultation_id"]);
			}
			if($post_data["assessment_id"]!='')
			{
				$this->db->where("ASSESSMENT_ID", $post_data["assessment_id"]);
			}
			$this->db->where("IS_DENTAL", 1);
			$this->db->from("LAB_INVESTIGATION DM");			
							
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();	
				$data['LAB_INVESTIGATION_DETAILS'] = $this->getDentalInvestigationDetails($data['LAB_INVESTIGATION_ID'],'LAB_INVESTIGATION_DETAILS LID');										
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
					
		}	
		return $result;
		
	}
	
	public function getDentalInvestigationDetails($lab_investigation_id, $table)
	{
			$this->db->start_cache();			
			$this->db->select("LID.*");	
			$this->db->select("CPT.PROCEDURE_CODE as PROCEDURE_CODE, CPT.PROCEDURE_CODE_NAME as PROCEDURE_CODE_NAME,CPT.PROCEDURE_CODE_CATEGORY as PROCEDURE_CODE_CATEGORY");																			
			$this->db->from($table);
			$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CPT.CURRENT_PROCEDURAL_CODE_ID = LID.CURRENT_PROCEDURAL_CODE_ID","left");						
			$this->db->where("LAB_INVESTIGATION_ID", $lab_investigation_id);
			// $this->db->order_by("LID.SERIAL_NO","ASC");			
			$this->db->order_by("LID.LAB_INVESTIGATION_DETAILS_ID","ASC");			
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

} 
?>