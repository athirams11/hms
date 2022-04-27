<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DentalComplaintsModel extends CI_Model 
{
	
	
	public function listDentalProcedure()
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();	
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("MASTER_DENTAL_PROCEDURE D");
		$this->db->where("D.STATUS", 1);
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $key => $value) 
			{
				$data[$key]["id"] = $value->MASTER_DENTAL_PROCEDURE_ID;
				$data[$key]["procedures"] = $value->PROCEDURE_NAME;
				$data[$key]["color"] = $value->PROCEDURE_COLOR;
				$data[$key]["status"] = $value->STATUS;
			}	
		}
		if(!empty($data))
			$result = array("status"=> "Success", "data"=> $data);
		return $result;
		
	}
	public function getCDTByProcedureId($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		if(isset($post_data["dental_procedure_id"]) && $post_data["dental_procedure_id"] > 0)
		{
			$this->db->start_cache();	
			$this->db->select("DM.*,CONCAT(DM.PROCEDURE_CODE,' - ',DM.PROCEDURE_CODE_NAME) AS CPT,DENTAL_PROCEDURE_ID");
			$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS DM");
			$this->db->where("DM.PROCEDURE_CODE_CATEGORY",37);
			$this->db->where("DM.DENTAL_PROCEDURE_ID",$post_data["dental_procedure_id"]);
			$this->db->where("DM.STAT",1);
			$this->db->order_by("DM.PROCEDURE_CODE, DM.PROCEDURE_CODE_NAME","ASC");
			$query = $this->db->get();
			// echo $this->db->last_query();
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
							$data["PROCEDURE_ID"] = $complaint["procedure_id"];
							$data["PROCEDURE"] = $complaint["procedure"];
							$data["PROCEDURE_COLOR"] = $complaint["color"];
							$data["DESCRIPTION"] = $complaint["description"];
							$data["TOOTH_INDEX"] = $complaint["tooth_index"];
							$data["CREATED_DATE"] = format_date($complaint["date"]);
							
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
							$data["PROCEDURE_ID"] = $complaint["procedure_id"];
							$data["PROCEDURE"] = $complaint["procedure"];
							$data["PROCEDURE_COLOR"] = $complaint["color"];
							$data["DESCRIPTION"] = $complaint["description"];
							$data["TOOTH_INDEX"] = $complaint["tooth_index"];
							$data["CREATED_DATE"] = format_date($complaint["date"]);
							
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
				// $data["alerdyExsits_Extraction_child"] = $this->findNotallowedProcedure($data["PATIENT_ID"],5,2);
				// $data["alerdyExsits_Implant_child"] = $this->findNotallowedProcedure($data["PATIENT_ID"],10,2);		
				// $data["alerdyExsits_Extraction"] = $this->findNotallowedProcedure($data["PATIENT_ID"],5,1);
				// $data["alerdyExsits_Implant"] = $this->findNotallowedProcedure($data["PATIENT_ID"],10,1);			
				// $data["alerdyExsits"] = $this->findNotallowedProcedure($data["PATIENT_ID"],$data["ASSESSMENT_ID"],0,1);			
				// $data["alerdyExsits_child"] = $this->findNotallowedProcedure($data["PATIENT_ID"],$data["ASSESSMENT_ID"],0,2);			
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
		}		
		
		return $result;
		
	}
	public function listNotAllowedProcedure($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '')	
		{							
				
			$data["alerdyExsits"] = $this->findNotallowedProcedure($post_data["patient_id"],$post_data["patient_id"],0,1);
			$data["alerdyExsits_child"] = $this->findNotallowedProcedure($post_data["patient_id"],$post_data["patient_id"],0,2);	
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
			$this->db->select("D.*,DATE_FORMAT(D.CREATED_DATE,'%d-%m-%Y') AS CREATEDATE");
			$this->db->from("PATIENT_DENTAL_PROCEDURE D");
			$this->db->where("D.DENTAL_COMPLAINT_ID", $data["DENTAL_COMPLAINT_ID"]);
			$this->db->order_by("D.CREATED_DATE","asc");
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
	public function findNotallowedProcedure($patient_id,$assessment_id,$procedure_id,$patient_type)
	{
		$result = array();
		if($patient_id > 0)
		{
			$this->db->start_cache();			
			$this->db->select("N.*");
			$this->db->from("NURSING_ASSESSMENT N");
			$this->db->where("N.PATIENT_ID", $patient_id);
			$this->db->order_by("N.NURSING_ASSESSMENT_ID","desc");
			$this->db->limit("1");
			$ass = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($ass->num_rows() > 0)
			{
				$assessment = $ass->row_array();
				$this->db->start_cache();			
				$this->db->select("D.*");
				$this->db->from("DENTAL_COMPLAINTS D");
				// $this->db->where("D.PROCEDURE_ID", $procedure_id);
				$this->db->where("D.PATIENT_ID", $patient_id);
				$this->db->where("D.ASSESSMENT_ID <" , $assessment["NURSING_ASSESSMENT_ID"]);
				$this->db->where("D.PATIENT_TYPE", $patient_type);
				$query = $this->db->get();	
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{

					$data =  $query->result_array();	
					foreach ($data as $key => $value) 
					{
						$array[$key] = $value["DENTAL_COMPLAINT_ID"];
					}
					$this->db->start_cache();			
					$this->db->select("PD.*");
					$this->db->from("PATIENT_DENTAL_PROCEDURE PD");
					// $this->db->join("DENTAL_COMPLAINTS D","D.DENTAL_COMPLAINT_ID = PD.DENTAL_COMPLAINT_ID");
					// $this->db->where("PD.PROCEDURE_ID", $procedure_id);
					$this->db->group_start();
					$this->db->where("PD.PROCEDURE_ID",5);
					$this->db->or_where("PD.PROCEDURE_ID",10);
					$this->db->group_end();
					$this->db->where_in("PD.DENTAL_COMPLAINT_ID", $array);
					$sql = $this->db->get();	
					 // echo $this->db->last_query();
					$this->db->stop_cache();
					$this->db->flush_cache();
					if($sql->num_rows() > 0)
					{
						$result =  $sql->result_array();	
					}
				
				}
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
		$post_data["instruction_to_cashier"] = isset($post["instruction_to_cashier"]) ? $post["instruction_to_cashier"] : '';	
			$dp_data = array(
				"PATIENT_ID" => trim($post_data["patient_id"]),
				"ASSESSMENT_ID" => trim($post_data["assessment_id"])							
			);
							 
			$data = array(
				"PATIENT_ID" => trim($post_data["patient_id"]),
				"USER_ID" => trim($post_data["user_id"]),
				"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
				"INSTRUCTION_TO_CASHIER" => trim($post_data["instruction_to_cashier"]),
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
						$data[$key]['TOOTH_PROCEDURE'] = (isset($req_array["tooth_procedure"][$key]) ? $req_array["tooth_procedure"][$key] :NULL );		
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

	public function saveDentalHistory($post_data)
	{
		$result = array("status"=> "Failed", "msg"=> 'Failed');	
		if(is_array($post_data))	
		{
			
			// if(is_array($post_data['complients']) || is_array($post_data['child_complients']))
			// {
				
				$data = array(
					"ASSESSMENT_ID"  => 0,
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
						$result = array("status"=> "Success", "dental_complaint_id"=>$data_id ,"msg"=> 'Patient dental history details saved successfully...!');
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
							$result = array("status"=> "Success", "dental_complaint_id"=>$data_id , "msg"=> 'Patient dental history details saved successfully...!');
						}
					}
				}
		}
		return $result;
	}
	public function getDentalHistory($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '')	
		{							
			$this->db->start_cache();			
			$this->db->select("D.*,OP_REGISTRATION_NUMBER,
				OP.FIRST_NAME,
				OP.MIDDLE_NAME,OP.LAST_NAME,
				OP.GENDER,OP.MOBILE_NO,
				OP.AGE,OP.MONTHS,OP.DAYS,
				DATE_FORMAT(OP.DOB,'%d-%m-%Y') AS DOB");
			$this->db->from("DENTAL_COMPLAINTS D");
			$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = '".$post_data["patient_id"]."'","LEFT");
			$this->db->where("D.PATIENT_ID", $post_data["patient_id"]);
			$this->db->where("D.ASSESSMENT_ID",0);
			$query = $this->db->get();	
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
	public function downloadDentalHistoryPdf($post_data)
	{

		if(file_exists(FCPATH . "public/uploads/DentalHistory.pdf"))
		{
			unlink(FCPATH . "public/uploads/DentalHistory.pdf");
		}
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '')	
		{							
			$this->load->model('api/InstitutionManagementModel'); ''; 
			$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
			$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
			$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : ''; 
			$institution      = $this->InstitutionManagementModel->listInstitution($post_data);
			$data =  array();
			$dentaldata = $this->getDentalHistory($post_data);
			$data["dentaldata"] = $dentaldata;
			$data["institution"] = $institution;
			// print_r($data["dentaldata"]);exit;
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->pdf;
			$html = $this->load->view('export/exportPdf.php', $data, true);
			 // print_r($dentaldata);
			$pdfFilePath = FCPATH . "public/uploads/DentalHistory.pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "F");
			
			 $result = array("status"=> "Success", "data"=> "public/uploads/DentalHistory.pdf","filename" => "DentalHistory".$dentaldata["data"]["OP_REGISTRATION_NUMBER"].".pdf" );
		}		
		
		return $result;
		
	}

} 
?>