<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DoctorPrescriptionModel extends CI_Model 
{
	
	public function listRouteOfAdmin($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());		
		$this->db->start_cache();								
		$this->db->where("STATUS",1);
		$count = $this->db->count_all('MASTER_ROUTE_OF_ADMIN');	
		$this->db->select("*");
		$this->db->from("MASTER_ROUTE_OF_ADMIN");		
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("DESCRIPTION",$post_data["search_text"]);
			$this->db->or_like("CODE",$post_data["search_text"]);
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
		
		$this->db->order_by("MASTER_ROUTE_OF_ADMIN_ID","ASC");
		if($post_data["routeofadmin_id"] > 0)
		{
			$this->db->start_cache();								
			$this->db->where("MASTER_ROUTE_OF_ADMIN_ID",$post_data["routeofadmin_id"]);
			$this->db->select("*");
			$this->db->from("MASTER_ROUTE_OF_ADMIN");	
		}
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
	public function getRouteOfAdmin($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());		
		$this->db->start_cache();			
		$this->db->select("M.*");	
		$this->db->from("MASTER_ROUTE_OF_ADMIN M");		
		$this->db->where("M.MASTER_ROUTE_OF_ADMIN_ID",$post_data['route_arr_id']);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{			
			$data = $query->row_array();											
			$result = array("status"=> "Success", "data"=> $data);
		}
		
		return $result;
	}
	public function listPrescription($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());		
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("DOCTOR_PRESCRIPTION PA");		
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
	
	public function savePrescription($post)
	{
		$result = array("status"=> "Failed", "data"=> array());	
	    $post_data =[];
		foreach ($post['prescription_array'] as $key => $data_row) {  
	       $post_data["generic_id_arr"][] = isset($data_row["generic_id_arr"]) ? $data_row["generic_id_arr"] : "";            
	       $post_data["medicine_id_arr"][]= isset($data_row["medicine_id_arr"]) ? $data_row["medicine_id_arr"] : "";            
	       $post_data["strength_arr"][] = isset($data_row["strength_arr"]) ? $data_row["strength_arr"] : "";            
	       $post_data["dosage_arr"][] = isset($data_row["dosage_arr"]) ? $data_row["dosage_arr"] : "";            
	       $post_data["uom_arr"][] = isset($data_row["uom_arr"]) ? $data_row["uom_arr"] : "";            
	       $post_data["multiple_frequency_arr"][] = isset($data_row["multiple_frequency_arr"]) ? $data_row["multiple_frequency_arr"] : "";            
	       $post_data["frequency_arr"][] = isset($data_row["frequency_arr"]) ? $data_row["frequency_arr"] : "";    
	       $post_data["frequency"][] = isset($data_row["frequency"]) ? $data_row["frequency"] : "";            
	       $post_data["instructions"][] = isset($data_row["instructions"]) ? $data_row["instructions"] : "";            
	       $post_data["route_arr"][] = isset($data_row["route_arr"]) ? $data_row["route_arr"] : "";            
	       $post_data["route_arr_id"][] = isset($data_row["route_arr_id"]) ? $data_row["route_arr_id"] : "";            
	       $post_data["remarks_arr"][]= isset($data_row["remarks_arr"]) ? $data_row["remarks_arr"] : "";            
	       $post_data["period_arr"][] = isset($data_row["period_arr"]) ? $data_row["period_arr"] : "";            
	       $post_data["total_quantity_arr"][] = isset($data_row["total_quantity_arr"]) ? $data_row["total_quantity_arr"] : "";            
	       
	      $post_data["other_advice"] = isset($data_row["other_advice"]) ? $data_row["other_advice"] : '';
	      $post_data["review_after"] = isset($data_row["review_after"]) ? $data_row["review_after"] : '';
	      $post_data["review_on"] = isset($data_row["review_on"]) ? $data_row["review_on"] : '';
	      
 	    }

		$post_data["patient_id"] = isset($post["patient_id"]) ? $post["patient_id"] : "";
		$post_data["doctor_id"] = isset($post["doctor_id"]) ? $post["doctor_id"] : "";
		$post_data["assessment_id"] = isset($post["assessment_id"]) ? $post["assessment_id"] : "";
		$post_data["user_id"] = isset($post["user_id"]) ? $post["user_id"] : 0;
		$post_data["consultation_id"] = isset($post["consultation_id"]) ? $post["consultation_id"] : 0;
		$post_data["doctor_prescription_id"] = isset($post["doctor_prescription_id"]) ? $post["doctor_prescription_id"] : 0;  
		$post_data["client_date"] = isset($post["client_date"]) ? $post["client_date"] : '';

		$post_data["date"] = isset($post["date"]) ? $post["date"] : '';
		$post_data["timeZone"] = isset($post["timeZone"]) ? $post["timeZone"] : '';
			$dp_data = array(
							"PATIENT_ID" => $post_data["patient_id"],
							"DOCTOR_ID" => $post_data["doctor_id"],							
							"ASSESSMENT_ID" => $post_data["assessment_id"]
						);
				//print_r($dp_data);		 
			$data = array(
							"PATIENT_ID" => $post_data["patient_id"],
							"DOCTOR_ID" => $post_data["doctor_id"],
							"USER_ID" => $post_data["user_id"],
							"CONSULTATION_ID" => $post_data["consultation_id"],
							"ASSESSMENT_ID" => $post_data["assessment_id"],
							"OTHER_ADVICE" => $post_data["other_advice"],
							"REVIEW_AFTER" => $post_data["review_after"],
							"REVIEW_ON" => toUtc(trim($post_data["date"]),$post_data["timeZone"]), //$post_data["review_on"],
							"DATE_LOG" => toUtc(trim($post_data["date"]),$post_data["timeZone"]),	
							"CLIENT_DATE" => format_date($post_data["client_date"]),	
							 );
							 										
			//print_r($post_data["doctor_prescription_id"]);
			$data_id = $post_data["doctor_prescription_id"];				
			$ret = $this->ApiModel->mandatory_check( $post_data , array('doctor_id','patient_id','assessment_id','medicine_id_arr'));	
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         	}	
                       									
			/*if($this->utility->is_Duplicate("DOCTOR_PRESCRIPTION",array_keys($dp_data), array_values($dp_data),"DOCTOR_PRESCRIPTION_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}	*/
												
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
	
				$this->db->where("DOCTOR_PRESCRIPTION_ID",$data_id);
				$this->db->update("DOCTOR_PRESCRIPTION",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();								
			}
			else
			{				
				if($this->db->insert("DOCTOR_PRESCRIPTION",$data))
				{
					$this->db->start_cache();			
					$data_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
				}
			}		
			 
			if($data_id>0)
			{
				$ret = $this->savePrescriptionDetails($data_id, $post_data);
				if($ret){
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}
		return $result;

	}
	public function savePrescriptionDetails($doctor_prescription_id, $req_array)
	{
		$flag = false;
		if($doctor_prescription_id > 0)
		{		
			
				if(is_array($req_array['medicine_id_arr']))
				{
					$this->db->start_cache();			
					$this->db->where("DOCTOR_PRESCRIPTION_ID", $doctor_prescription_id);												
					$this->db->delete("DOCTOR_PRESCRIPTION_DETAILS");
					$this->db->stop_cache();
					$this->db->flush_cache();	
					$data = array();
					foreach($req_array['medicine_id_arr'] as $key => $medicine_id)
					{
						$data[$key]['SERIAL_NO'] = $key;			
						$data[$key]['DOCTOR_PRESCRIPTION_ID'] = $doctor_prescription_id; 	
						$data[$key]['MEDICINE_ID'] = $medicine_id;
						// $data[$key]['GENERIC_ID'] = (isset($req_array["generic_id_arr"][$key]) ? $req_array["generic_id_arr"][$key] :'' );
																																					
						$data[$key]['STRENGTH'] = (isset($req_array["strength_arr"][$key]) ? $req_array["strength_arr"][$key] :'' );																					
						$data[$key]['DOSAGE'] = (isset($req_array["dosage_arr"][$key]) ? $req_array["dosage_arr"][$key] :'' );																					
						$data[$key]['UOM'] = (isset($req_array["uom_arr"][$key]) ? $req_array["uom_arr"][$key] :'' );																					
						$data[$key]['MULTIPLE_FREQUENCY'] = (isset($req_array["multiple_frequency_arr"][$key]) ? $req_array["multiple_frequency_arr"][$key] :'' );
																											
						$data[$key]['FREQUENCY'] = (isset($req_array["frequency_arr"][$key]) ? $req_array["frequency_arr"][$key] :'' );																					
						$data[$key]['FREQUENCY_TYPE'] = (isset($req_array["frequency"][$key]) ? $req_array["frequency"][$key] :'' );																					
						$data[$key]['INSTRUCTION'] = (isset($req_array["instructions"][$key]) ? $req_array["instructions"][$key] :'' );																					
						$data[$key]['ROUTE'] = (isset($req_array["route_arr_id"][$key]) ? $req_array["route_arr_id"][$key] :'' );																					
						$data[$key]['REMARKS'] = (isset($req_array["remarks_arr"][$key]) ? $req_array["remarks_arr"][$key] :0 );																											
						$data[$key]['PERIOD'] = (isset($req_array["period_arr"][$key]) ? $req_array["period_arr"][$key] :'' );		
																									
						$data[$key]['TOTAL_QUANTITY'] = (isset($req_array["total_quantity_arr"][$key]) ? $req_array["total_quantity_arr"][$key] :'' );																					
																											
					}
					$this->db->insert_batch('DOCTOR_PRESCRIPTION_DETAILS', $data);
					$flag = true;
				} 															
		}
		return $flag;						 
	}
	
	public function getPrescription($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"]!='' && $post_data["assessment_id"]!='')
		{ 				
			$this->db->start_cache();			
			$this->db->select("DM.*");
			
			if($post_data["doctor_prescription_id"]!='')
			{
				$this->db->where("DOCTOR_PRESCRIPTION_ID", $post_data["doctor_prescription_id"]);
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
			$this->db->from("DOCTOR_PRESCRIPTION DM");			
			$this->db->where("STATUS", 0);				
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();	
				$data['PRESCRIPTION_DETAILS'] = $this->getPrescriptionDetails($data['DOCTOR_PRESCRIPTION_ID'],'DOCTOR_PRESCRIPTION_DETAILS DPD');										
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
		}		
		
		return $result;
		
	}
	
	public function getPrescriptionDetails($doctor_prescription_id, $table)
	{
			$this->db->start_cache();			
			$this->db->select("DPD.*");	
			$this->db->select("M.DDC_CODE ,M.TRADE_NAME ,M.SCIENTIFIC_CODE,M.SCIENTIFIC_NAME,
				CONCAT(M.TRADE_NAME,' - ',M.SCIENTIFIC_NAME) AS TRADE_NAMES");								
			//$this->db->select("GM.NAME as GENERIC_NAME");								
			$this->db->from($table);
			$this->db->join("MEDICINE M","M.MEDICINE_ID = DPD.MEDICINE_ID","left");
			// $this->db->join("MASTER_ROUTE_OF_ADMIN R","R.MASTER_ROUTE_OF_ADMIN_ID = DPD.ROUTE","left");
			//$this->db->join("MEDICINE_GENERIC_MASTER GM","GM.GENERIC_ID = DPD.GENERIC_ID","left");
			
			$this->db->where("DOCTOR_PRESCRIPTION_ID", $doctor_prescription_id);
			$this->db->order_by("DPD.SERIAL_NO","ASC");			
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
	
	public function deletePrescription($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["doctor_prescription_id"];
		if($data_id > 0)
		{															
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("DOCTOR_PRESCRIPTION");
			$this->db->where("DOCTOR_PRESCRIPTION_ID", $data_id);			
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
				$this->db->where("DOCTOR_PRESCRIPTION_ID", $data_id);												
				$this->db->delete("DOCTOR_PRESCRIPTION_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();														
			
				$this->db->start_cache();
				$this->db->where("DOCTOR_PRESCRIPTION_ID", $data_id);
				$ret = $this->db->delete("DOCTOR_PRESCRIPTION");			
				//echo $this->db->last_query();exit;		
				$this->db->stop_cache();
				$this->db->flush_cache();				 
				if(!empty($ret))
					$result = array("status"=> "Success", "data"=> $data);
				
			}										
		}
		return $result;
		
	}


	public function getPreviousPrescription($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"]!='' && $post_data["assessment_id"]!='')
		{ 				
			$this->db->start_cache();			
			$this->db->select("DM.*,D.DOCTORS_NAME,V.VISIT_DATE,DM.ASSESSMENT_ID");
			$this->db->where("DM.PATIENT_ID", $post_data["patient_id"]);
			$this->db->where("DM.ASSESSMENT_ID < ", $post_data["assessment_id"]);
			$this->db->where("DM.STATUS", 0);
			$this->db->from("DOCTOR_PRESCRIPTION DM");			
			$this->db->join("DOCTORS D","DM.DOCTOR_ID = D.LOGIN_ID","left");	
			$this->db->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = DM.ASSESSMENT_ID","left");		
			$this->db->join("PATIENT_VISIT_LIST V","V.PATIENT_VISIT_LIST_ID = N.VISIT_ID","left");					
			$this->db->order_by("DM.DOCTOR_PRESCRIPTION_ID","DESC");	
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
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$count = $this->CountPreviousPrescription($post_data);
				$data = $query->result_array();	
				foreach ($data as $key => $value) {
					$data[$key]['PRESCRIPTION'] = $this->getPreviousDetails($value['DOCTOR_PRESCRIPTION_ID']);										
				}
			}
			if(!empty($data))
				$result = array("status"=> "Success", "total_count"=> $count ,"data"=> $data);
		}		
		
		return $result;
		
	}


	public function getCancelPrescription($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"]!='' && $post_data["assessment_id"]!='')
		{ 				
			$this->db->start_cache();			
			$this->db->select("DM.*,D.DOCTORS_NAME,V.VISIT_DATE,DM.ASSESSMENT_ID");
			$this->db->where("DM.PATIENT_ID", $post_data["patient_id"]);
			$this->db->where("DM.ASSESSMENT_ID", $post_data["assessment_id"]);
			$this->db->where("DM.STATUS", 1);
			$this->db->from("DOCTOR_PRESCRIPTION DM");			
			$this->db->join("DOCTORS D","DM.DOCTOR_ID = D.LOGIN_ID","left");	
			$this->db->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = DM.ASSESSMENT_ID","left");		
			$this->db->join("PATIENT_VISIT_LIST V","V.PATIENT_VISIT_LIST_ID = N.VISIT_ID","left");					
			$this->db->order_by("DM.DOCTOR_PRESCRIPTION_ID","DESC");	
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
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$count = $this->CountPreviousPrescription($post_data);
				$data = $query->result_array();	
				foreach ($data as $key => $value) {
					$data[$key]['PRESCRIPTION'] = $this->getPreviousDetails($value['DOCTOR_PRESCRIPTION_ID']);										
				}
			}
			if(!empty($data))
				$result = array("status"=> "Success", "total_count"=> $count ,"data"=> $data);
		}		
		
		return $result;
		
	}
	
	public function getPreviousDetails($doctor_prescription_id)
	{
		$this->db->start_cache();			
		$this->db->select("DPD.*,R.DESCRIPTION");	
		$this->db->select("M.DDC_CODE ,M.TRADE_NAME ,M.SCIENTIFIC_CODE,M.SCIENTIFIC_NAME,
			CONCAT(M.TRADE_NAME,' - ',M.SCIENTIFIC_NAME) AS TRADE_NAMES");		
		$this->db->from("DOCTOR_PRESCRIPTION_DETAILS DPD");
		$this->db->join("MEDICINE M","M.MEDICINE_ID = DPD.MEDICINE_ID","left");
		$this->db->join("MASTER_ROUTE_OF_ADMIN R","R.MASTER_ROUTE_OF_ADMIN_ID = DPD.ROUTE","left");
		$this->db->where("DOCTOR_PRESCRIPTION_ID", $doctor_prescription_id);
		$this->db->order_by("DPD.SERIAL_NO","ASC");			
		$query = $this->db->get();	
		$this->db->stop_cache();
		$this->db->flush_cache();
		$data = array();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();							
		}
		return $data;
	}
	public function CountPreviousPrescription($post_data)
	{
		$result = 0;
		if($post_data["patient_id"] != '' && $post_data["assessment_id"]!='')	
		{	
			$this->db->start_cache();			
			$this->db->select("DM.*,D.DOCTORS_NAME");
			$this->db->where("PATIENT_ID", $post_data["patient_id"]);
			$this->db->where("ASSESSMENT_ID < ", $post_data["assessment_id"]);
			$this->db->from("DOCTOR_PRESCRIPTION DM");			
			$this->db->join("DOCTORS D","DM.DOCTOR_ID = D.DOCTORS_ID","left");					
			$this->db->order_by("DOCTOR_PRESCRIPTION_ID","DESC");				
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
			CNS, EYE,FEVER,CVS");	
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
	
	function generateRxFile($post_data = array())
	{
		
		//$post_data["file_name"] = isset($post_data["file_name"]) ? $post_data["file_name"] : '';
       	$post_data["assessment_id"] = isset($post_data["assessment_id"]) ? $post_data["assessment_id"] : array();
       	$post_data["patient_id"] = isset($post_data["patient_id"]) ? $post_data["patient_id"] : 0;
       	$post_data["user_id"] = isset($post_data["user_id"]) ? $post_data["user_id"] : 0;
       	$post_data["client_date"] = isset($post_data["client_date"]) ? $post_data["client_date"] : "";
       	$post_data["eRxtype"] = isset($post_data["eRxtype"]) ? $post_data["eRxtype"] : 0;
       	$post_data["eRxprescription_uniqueid"] = isset($post_data["eRxprescription_uniqueid"]) ? $post_data["eRxprescription_uniqueid"] : '';

		$file_name =  "";
		$assessment_id = $post_data["assessment_id"] ;
		$patient_id = $post_data["patient_id"] ;
		$user_id = $post_data["user_id"] ;
		$client_date = $post_data["client_date"];
		$prefix= 'pre';
		$prescription_id = uniqid($prefix);
		$eRxType = "";
		$type = $post_data["eRxtype"];
		if($post_data["eRxtype"] == 1){ 
			$eRxType = ERX_REQUEST; 
		}
		if($post_data["eRxtype"] == 2){ 
			$eRxType = ERX_REQUEST_CANCELLATION ; 
			$prescription_id = $post_data["eRxprescription_uniqueid"];
		}
		if(!isClinicain($user_id))
		{
			return array( "status"=> "Failed" ,'message' => 'Only doctors can submit erx request');
		}
		//$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			//print_r($ext);
			/*if($ext!='xml')
			{*/
			$file_name = uniqid('erx-REQ').'.xml';
			//}
			$row = array();
			$this->db->select("'".getInstitutionSettings()->INSTITUTION_DHPO_ID."' as SENDER_ID,T.TPA_ECLAIM_LINK_ID as RECEIVER_ID,T.TPA_ID")
			->from("OP_INS_DETAILS O")
			->join("TPA T","T.TPA_ID = O.OP_INS_TPA",'LEFT')
			->where("O.OP_REGISTRATION_ID",$patient_id);
			$query = $this->db->get();
			$row = $query->row();
			$data =array(
		
			"XML_TYPE" 				=> CLAIM_XML_SUBMISSION,
			"FILE_NAME"				=> $file_name,
			"SUBMISSION_STATUS" 	=> CLAIM_CREATED,
			"SENDER_ID" 			=> getInstitutionSettings()->INSTITUTION_DHPO_ID,
			//"TPA_ID" 				=> ($row->TPA_ID != '') ? $row->TPA_ID : '',
			//"RECEIVER_ID" 			=> ($row->RECEIVER_ID != '') ? $row->RECEIVER_ID : 'SelfPay',
			"TRANSACTION_DATE" 		=> date('Y-m-d H:i:s'),
			"RECORD_COUNT" 			=> count($assessment_id),
			"DISPOSITION_FLAG" 		=> ERX_DISPOSITION_PRODUCTION_FLAG,
			"CREATED_BY" 			=> trim($post_data["user_id"]),
			"CREATED_DATE" 			=> date("Y-m-d H:i:s"),
			"CLIENT_DATE" 			=> format_date($post_data["client_date"])	
			
			);


			// if($post_data["eRxtype"] == 1){ 
			// $data["XML_TYPE"]=CLAIM_XML_SUBMISSION
			// }
			// if($post_data["eRxtype"] == 2){ 
			// 	$data["XML_TYPE"]= CLAIM_XML_CANCELLATION ; 
			// }

			if(isset($row->TPA_ID) && ($row->TPA_ID != '')){
				$data["TPA_ID"]=$row->TPA_ID ;		
			}
			else{
				$data["TPA_ID"]='' ;		
			}
			if(isset($row->RECEIVER_ID) && ($row->RECEIVER_ID != '')){
				$data["RECEIVER_ID"]=$row->RECEIVER_ID ;		
			}
			else{
				$data["RECEIVER_ID"]='SelfPay';
			}
		
				$this->db->insert("ERX_SUBMISSION_FILE",$data);
				$data_id = $this->db->insert_id();
				if((int) $data_id > 0)
				{	
					$file_name = $this->generateRxFileXml($data_id,$assessment_id,$patient_id,$user_id,$eRxType,$prescription_id);
					// $result = array("status"=> "Success","file_name"=>$file_name,"submission_file_id"=>$data_id, "message"=>"Submission File generated successfully");
					$result = $this->uploadeRxFile($data_id,$file_name,$user_id,$assessment_id,$prescription_id,$type);
				}
			return $result;
		}
		function generateRxFileXml($erx_file_id = 0,$assessment_id = 0,$patient_id = 0,$user_id = 0,$eRxType = '',$prescription_id)
		{
	
		$file_name = "";
		if($assessment_id > 0)
		{
			$xml_array = array();
			//$FileName = $file_name;
			$FileName = $this->getFileName($erx_file_id);
			$Header = $this->getXmlHeader($erx_file_id);
				
			$Prescription = $this->getXmlPrescription($erx_file_id,$assessment_id,$user_id,$patient_id,$eRxType,$prescription_id);
			
			$xml_array["Header"] = $Header;
			$xml_array["Prescription"] = $Prescription;
			$xml_array_con = mapingSubmissionArray($xml_array);
			//creating object of SimpleXMLElement
			//print_r($xml_array_con);
			$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><eRx.Request xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"></eRx.Request>");

			//function call to convert array to xml
			array_to_xml($xml_array_con,$xml_user_info);

			//saving generated xml file
			$xml_file = $xml_user_info->asXML();//$xml_user_info->asXML('users.xml');
			$xml_file = str_replace("<Prescription><Prescription>", "<Prescription>", $xml_file);
			$xml_file = str_replace("</Prescription></Prescription>", "</Prescription>", $xml_file);
			
			$xml_file = str_replace("<Diagnosis><Diagnosis>", "<Diagnosis>", $xml_file);
			$xml_file = str_replace("</Diagnosis></Diagnosis>", "</Diagnosis>", $xml_file);

			$xml_file = str_replace("<Activity><Activity>", "<Activity>", $xml_file);
			$xml_file = str_replace("</Activity></Activity>", "</Activity>", $xml_file);

			$xml_file = str_replace("<Frequency><Frequency>", "<Frequency>", $xml_file);
			$xml_file = str_replace("</Frequency></Frequency>", "</Frequency>", $xml_file);

			$xml_file = str_replace("<Observation><Observation>", "<Observation>", $xml_file);
			$xml_file = str_replace("</Observation></Observation>", "</Observation>", $xml_file);
			// echo $xml_file;
			// exit;
			//success and error message based on xml creation
			//$tpa = $this->getTpaDetails($submission_file_id);
			if($xml_file){
				$path = EXR_REQUEST_FILE_PATH;
				if(!file_exists($path))
		        {        	   
		            mkdir(FCPATH.$path);
		            if (!is_dir($path)) mkdir($path, 0777, true);                           
		            chmod($path,0777);                    
		        }
		        if(is_writable($path))
		        {
		        	//$file_name = $FileName;
		        	$file_name = $FileName["FILE_NAME"];
					file_put_contents($path.$file_name, $xml_file);
					//$this->db->where("SUBMISSION_FILE_ID",$submission_file_id);
					//$this->db->update("SUBMISSION_FILE",array("FILE_NAME"=>$file_name));
				}
			    //echo 'XML file have been generated successfully.';
			}else{
			    //echo 'XML file generation error.';
			}
		}
		return $file_name;
	}
	
	public function getFileName($erx_file_id = 0)
	{

		$this->db->select("FILE_NAME")
			->from("ERX_SUBMISSION_FILE")
			->where("ERX_SUBMISSION_FILE_ID",$erx_file_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getXmlHeader($erx_file_id = 0)
	{
		$this->db->select("SENDER_ID,RECEIVER_ID,DATE_FORMAT(TRANSACTION_DATE,'%d/%m/%Y %H:%i') as TRANSACTION_DATE,RECORD_COUNT,DISPOSITION_FLAG")
			->from("ERX_SUBMISSION_FILE")
			->where("ERX_SUBMISSION_FILE_ID",$erx_file_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getXmlPrescription($erx_file_id=O,$assessment_id=0,$user_id=0,$patient_id=0,$eRxType = '',$prescription_id)
	{
		
		$this->db->select("'".$prescription_id."' as ID,'".$eRxType."'as TYPE,IFNULL(T.TPA_ECLAIM_LINK_ID, 'SelfPay') as PAYER_ID, DR.DOCTORS_LISCENCE_NO as CLINICIAN")
			->from("DOCTOR_PRESCRIPTION D")
			->join("OP_INS_DETAILS O","O.OP_REGISTRATION_ID = D.PATIENT_ID AND OP_INS_STATUS = 1" ,"left")
			->join("DOCTORS DR","DR.LOGIN_ID = D.DOCTOR_ID","left")
			->join("TPA T","T.TPA_ID = O.OP_INS_TPA","left")
			->where("D.ASSESSMENT_ID",$assessment_id)
			->where("D.PATIENT_ID",$patient_id)
			->where("D.STATUS",0);
		$query = $this->db->get();
	// echo $this->db->last_query();
		$result = array();
		// print_r($query->result_array());
		// 	exit;
		foreach ($query->result_array() as $key => $value) {
			
			$result[$key] = $value;
			$result[$key]["Patient"] = $this->getXmlPatient($patient_id,$assessment_id);
			$result[$key]["Encounter"] = $this->getXmlEncounter($assessment_id);
			$result[$key]["Diagnosis"] = $this->getXmlDiagnosis($assessment_id);
			$result[$key]["Activity"] = $this->getXmlProcedures($assessment_id);
			
			
		}
		return $result;
	}
	public function getXmlPatient($patient_id = 0,$assessment_id = 0)
	{
		$weight = select_data("NURSING_ASSESSMENT_DETAILS","PARAMETER_VALUE","ASSESSSMENT_ID = ".$assessment_id." AND PARAMETER_ID = 4");
		$this->db->select("IFNULL(IN.OP_INS_MEMBER_ID,OP.OP_REGISTRATION_NUMBER) as MEMBER_ID,OP.NATIONAL_ID as EMIRITES_ID_NUMBER,DATE_FORMAT(OP.DOB,'%d/%m/%Y')as DATE_OF_BIRTH, '".$weight['PARAMETER_VALUE']."' as PATIENT_WEIGHT")
			->from("OP_REGISTRATION OP")
			->join("OP_INS_DETAILS IN","IN.OP_REGISTRATION_ID = OP.OP_REGISTRATION_ID","LEFT")
			->where("OP.OP_REGISTRATION_ID",$patient_id);
		$query = $this->db->get();
		return $query->row_array();
		//print_r($query->row_array());
	}
	public function getXmlEncounter($assessment_id = 0)
	{
		$this->db->select("'".getInstitutionSettings()->INSTITUTION_DHPO_ID."' as FACILITY_ID, 1 as TYPE")
			->from("DOCTOR_PRESCRIPTION D")
			->join("DOCTORS DR","DR.LOGIN_ID = D.DOCTOR_ID")
			->where("D.ASSESSMENT_ID",$assessment_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getXmlDiagnosis($assessment_id = 0)
	{
		$this->db->select(" M.DATA as TYPE, D.CODE ")
			->from("DOCTOR_PRESCRIPTION B")
			->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","LEFT")
			->join("PATIENT_DIAGNOSIS P","P.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID","LEFT")
			->join("PATIENT_DIAGNOSIS_DETAILS PD","PD.PATIENT_DIAGNOSIS_ID = P.PATIENT_DIAGNOSIS_ID","LEFT")
			->join("DIAGNOSIS_MASTER D","D.DIAGNOSIS_ID = PD.DIAGNOSIS_ID","LEFT")
			->join("MASTER_DATA M","M.MASTER_DATA_ID = PD.DIAGNOSIS_LEVEL_ID","LEFT")
			->where("B.STATUS",0)
			->where("B.ASSESSMENT_ID",$assessment_id);

		$query = $this->db->get();
		return $query->result_array();
	}
	public function getXmlProcedures($assessment_id = 0)
	{

		$this->db->select("DR.DOCTOR_PRESCRIPTION_DETAILS_ID AS ID, DATE_FORMAT(N.START_TIME,'%d/%m/%Y %H:%i') as START, 5 as TYPE, M.DDC_CODE as CODE, DR.UOM AS QUANTITY, DR.STRENGTH as DURATION, DR.REMARKS as REFILLS,R.CODE as ROUTE_OF_ADMIN,DR.INSTRUCTION as INSTRUCTIONS,DR.MEDICINE_ID")
			->from("DOCTOR_PRESCRIPTION D")
			->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = D.ASSESSMENT_ID","LEFT")
			->join("DOCTOR_PRESCRIPTION_DETAILS DR","DR.DOCTOR_PRESCRIPTION_ID = D.DOCTOR_PRESCRIPTION_ID","LEFT")
			->join("DOCTORS DS","DS.LOGIN_ID = D.DOCTOR_ID","LEFT")
			// ->join("MASTER_ROUTE_OF_ADMIN R","R.MASTER_ROUTE_OF_ADMIN_ID = DR.ROUTE","LEFT")
			->join("MEDICINE M","M.MEDICINE_ID = DR.MEDICINE_ID","LEFT")
			//->join("CPT_GROUP C","WHERE CPT_GROUP_ID = 5","LEFT")
			->where("D.STATUS",0)
			->where("D.ASSESSMENT_ID",$assessment_id);
		$query = $this->db->get();
		
		//exit();
		$result = $query->result_array();
		
		foreach ($result as $key => $value) {
			$result[$key]["Frequency"] = $this->getXmlProceduresFrequency($value['MEDICINE_ID'],$assessment_id);
			
			unset($result[$key]['MEDICINE_ID']);
			//$result[$key]["Observation"] = $this->getXmlProceduresObservation($assessment_id);
		}

		return $result;
	}
	public function getXmlProceduresFrequency($medicine_id = 0,$assessment_id = 0)
	{
		$this->db->select("M.GRANULAR_UNIT as UNIT_PER_FREQUENCY,D.FREQUENCY as FREQUENCY_VALUE,MS.DATA as FREQUENCY_TYPE")
			->from("DOCTOR_PRESCRIPTION_DETAILS D")
			->join("DOCTOR_PRESCRIPTION DP","DP.DOCTOR_PRESCRIPTION_ID = D.DOCTOR_PRESCRIPTION_ID","LEFT")
			->join("MEDICINE M","M.MEDICINE_ID = D.MEDICINE_ID","LEFT")
			->join("MASTER_DATA MS","MS.MASTER_DATA_ID = D.FREQUENCY_TYPE","LEFT")
			->where("D.MEDICINE_ID",$medicine_id)
			->where("DP.STATUS",0)
			->where("DP.ASSESSMENT_ID",$assessment_id);
		$query = $this->db->get();
		//echo $this->db->last_query();
		//$this->db->last_query();
		
		return $query->result_array();
	}
	public function getXmlProceduresObservation($assessment_id = 0)
	{
		
		$this->db->select("LD.LAB_INVESTIGATION_DETAILS_ID, DATE_FORMAT(L.DATE_LOG,'%d/%c/%Y %H:%i') as DATE_LOG,   CPT.CPT_GROUP_ID as TYPE, PROCEDURE_CODE, LD.QUANTITY, BD.INSURED_AMOUNT as NET, D.DOCTORS_LISCENCE_NO as DOCTOR, BD.PRIOR_AUTHORIZATION")
			->from("NURSING_ASSESSMENT N")
			->join("DOCTORS D","D.LOGIN_ID = L.USER_ID","LEFT")
			->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CPT.CURRENT_PROCEDURAL_CODE_ID = LD.CURRENT_PROCEDURAL_CODE_ID","LEFT")
			//->join("CPT_NEW_TABLE CN","CN.CPT_CODE = CPT.PROCEDURE_CODE","LEFT")
			->where("B.BILLING_ID",$bill_id);
		$query = $this->db->get();
		//echo $this->db->last_query();
		foreach ($query->result_array() as $key => $value) {
			
			$ck_cpt = check_cpt_req($value["PROCEDURE_CODE"],$value["TYPE"]);
			if(count($ck_cpt) > 0){

				foreach($ck_cpt as $k => $ck)
				{
					//print_r($ck);
					//$ck = check_cpt_req($value["PROCEDURE_CODE"]);
					if($ck["CODE"]){
						switch ($ck["CODE"]) {
							case 'value':
								# code...
								break;
							
							
							case  "PresentingComplaint":
									$bill_data = select_data("BILLING","ASSESSMENT_ID","BILLING_ID = ".$bill_id);
									$data = select_data("CHIEF_COMPLAINTS","COMPLAINTS ","ASSESSMENT_ID = ".$bill_data["ASSESSMENT_ID"]);
									//echo "data:".$bill_id;
									//print_r($bill_data);
									//print_r($data);
									$value["Observation"][$k]["TYPE"] = 	$ck["TYPE"];
									$value["Observation"][$k]["CODE"] = $ck["CODE"];
									$value["Observation"][$k]["VALUE"] = $data["COMPLAINTS"];
									$value["Observation"][$k]["VALUE_TYPE"] = ($ck["VALUE_TYPE"] == 'DD/MM/YYYY')? date('d/m/Y'): $ck["VALUE_TYPE"];
								# code...
								break;
							case "BPS":
									$bill_data = select_data("BILLING","ASSESSMENT_ID","BILLING_ID = ".$bill_id);
									$data = select_data("NURSING_ASSESSMENT_DETAILS","PARAMETER_VALUE","ASSESSSMENT_ID = ".$bill_data["ASSESSMENT_ID"]." AND PARAMETER_ID = 8");
									//print_r($data);exit;
									$value["Observation"][$k]["TYPE"] = 	$ck["TYPE"];
									$value["Observation"][$k]["CODE"] = $ck["CODE"];
									$value["Observation"][$k]["VALUE"] = $data["PARAMETER_VALUE"];
									$value["Observation"][$k]["VALUE_TYPE"] = ($ck["VALUE_TYPE"] == 'DD/MM/YYYY')? date('d/m/Y'): $ck["VALUE_TYPE"];
								# code...
								break;
							case "BPD":
									$bill_data = select_data("BILLING","ASSESSMENT_ID","BILLING_ID = ".$bill_id);
									$data = select_data("NURSING_ASSESSMENT_DETAILS","PARAMETER_VALUE","ASSESSSMENT_ID = ".$bill_data["ASSESSMENT_ID"]." AND PARAMETER_ID = 9");
									//print_r($data);exit;
									$value["Observation"][$k]["TYPE"] = 	$ck["TYPE"];
									$value["Observation"][$k]["CODE"] = $ck["CODE"];
									$value["Observation"][$k]["VALUE"] = $data["PARAMETER_VALUE"];
									$value["Observation"][$k]["VALUE_TYPE"] = ($ck["VALUE_TYPE"] == 'DD/MM/YYYY')? date('d/m/Y'): $ck["VALUE_TYPE"];
								# code...
								break;
							default:
									$lab_result = select_data("LAB_INVESTIGATION_RESULT","RESULT_VALUE","LAB_INVESTIGATION_DETAILS_ID = ".$value["LAB_INVESTIGATION_DETAILS_ID"]." AND REQ_ID = ".$ck["CPT_REQUIRED_ID"]);
										//print_r($lab_result);exit;
									if($lab_result)
									{
										
										$value["Observation"][$k]["TYPE"] = 	$ck["TYPE"];
										$value["Observation"][$k]["CODE"] = $ck["CODE"];
										$value["Observation"][$k]["VALUE"] = $lab_result["RESULT_VALUE"];
										$value["Observation"][$k]["VALUE_TYPE"] = ($ck["VALUE_TYPE"] == 'DD/MM/YYYY')? date('d/m/Y'): $ck["VALUE_TYPE"];
									}
								# code...
								break;
						}
					}
				}
			}
			$result[$key] = $value;
		}
		return $result;
		
	}
		
		
/*	public function uploadeRxFile($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array(), "message"=>"Invalid Parameters");	
		if(!empty($post_data))
   		{
			$this->load->model('soap/RequestModel');
			//print_r($post_data);
			$result = array("status"=> "Failed", "data"=> array());		
			// $this->db->where('SUBMISSION_FILE_ID',$post_data["file_id"]);
			// $this->db->select('FILE_NAME');
			// $this->db->from('SUBMISSION_FILE');
			// $query = $this->db->get();
			// $filename = $query->row()->FILE_NAME;
			// 
			// $data=array(
			// 	"SUBMISSION_FILE_ID" 				=> trim($post_data["file_id"]),
			// 	"SUBMISSION_FILENAME" 				=> $filename,
			// 	"SUBMISSION_UPLOAD_TYPE"			=> "PRODUCTION",
			// 	"SUBMISSION_UPLOAD_DATE" 			=> date('Y-m-d H:i:s'),
			// 	"SUBMISSION_UPLOAD_USER_ID" 		=> trim($post_data["user_id"]),
			// 	"SUBMISSION_UPLOAD_STATUS"			=> 1,
			// 	//"SUBMISSION_ERROR_MESSAGE"			=> "error"
			// 		);	
			$user_id = trim($post_data["user_id"]);
			$filename = $post_data["file_name"];
			$path =FCPATH.EXR_REQUEST_FILE_PATH.$filename;

			if(file_exists($path))
			{ 
				// $file_path = file_get_contents($path);
				// $get_disposition = get_string_between($file_path,"<DispositionFlag>","</DispositionFlag>");
 			// 	$production_file=str_ireplace($get_disposition,"PRODUCTION",$file_path);
				// $file_content = base64_encode($production_file); 


				if($production_file)
				{
					$production_path = PRODUCTION_FILE_PATH;
					if(!file_exists($path))
			        {        	   
			            mkdir(FCPATH.$production_path);
			            if (!is_dir($production_path)) mkdir($production_path, 0777, true);                                      
			        }
			        if(is_writable($production_path))
			        {
						file_put_contents($production_path.$filename, $production_file);
						$result = array('message' => 'Upload successfully!!!');  
					}
					else 
					{
					       $result = array('status' => 'Failed' ,'message' => 'Unable to write the file');   
					       return $result;  

					}
				}
				//return $result;
			}
			else
			{
				$result = array('message' => 'No such a file or directory');
				return $result;
			}
			if(PROD_ENV == 1)
			{
				
			}
			$response_data   = $this->RequestModel->UploadTransaction($user_id,$filename,$file_content); 
			print_r($response_data);
			exit;
	       // $result_content =  get_string_between($response_data,"<soap:Body>","</soap:Body>");
	        //print_r($result_content);
			if($result_content != '')
			{
		        $xml = simplexml_load_string($result_content, 'SimpleXMLElement', LIBXML_NOCDATA);
		       // echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
		        $response = $this->xml2json->xmlToArray($xml,"root");
		       // print_r($response);
				if( empty($filename) && empty($file_content))	
				{
					$result = array( "status"=> "Failed" ,'message' => 'No such a file or directory');
					return $result;
				}
				else
				{
					
				}
				if($response["UploadERxRequestResult"]["UploadERxRequestResult"] == 0)
				{
					
					$result =array("status"=> "Success","message"=>$response["UploadERxRequestResult"]["errorMessage"] ,"eRxReferenceNo" =>$response["UploadERxRequestResult"]["eRxReferenceNo"] );
				}
				else
				{
					$result = array("status"=> "Failed","message"=> $response["UploadERxRequestResult"]["errorMessage"],
					 "error report" =>  base64_decode($response["UploadERxRequestResult"]["errorReport"]));
				}
			
			}
			else
			{
				$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data);	
			}
			
		}
		return $result;
	}*/
	public function uploadeRxFile($file_id,$filename,$user_id,$assessment_id,$prescription_id,$type)
	{
		
		if($file_id > 0)
   		{
			$this->load->model('soap/RequestModel');
			//print_r($post_data);
			//$result = array("status"=> "Failed", "data"=> array());			
			$this->db->where("ERX_SUBMISSION_FILE_ID",$file_id);
			$this->db->select("FILE_NAME");
			$this->db->from("ERX_SUBMISSION_FILE");
			$query = $this->db->get();
			$filename = $query->row()->FILE_NAME;
			$data=array(
				"ERX_SUBMISSION_FILE_ID" 				=> $file_id,
				"ERX_SUBMISSION_FILENAME" 				=> $filename,
				"ERX_SUBMISSION_UPLOAD_TYPE"			=> "TEST",
				"ERX_SUBMISSION_UPLOAD_DATE" 			=> date('Y-m-d H:i:s'),
				"ERX_SUBMISSION_UPLOAD_USER_ID" 		=> $user_id,
				/*"ERX_SUBMISSION_UPLOAD_STATUS_CODE"			=> 1,
				"ERX_SUBMISSION_ERROR_MESSAGE"			=> 1,*/
				"ERX_SUBMISSION_UPLOAD_STATUS"			=> 1,
		
					);	
			$path =FCPATH.EXR_REQUEST_FILE_PATH.$filename;
			if(file_exists($path))
			{ 
				$file_path = file_get_contents($path);
				$file_content = base64_encode($file_path); 
			}
			else
			{
				$result = array('message' => 'No such a file or directory');
				return $result;
			}
			$response_data   = $this->RequestModel->UploadERxRequest($user_id,$filename,$file_content); 
			
			// print_r($response_data);
			// exit;
	        $result_content =  get_string_between($response_data,"<soap:Body>","</soap:Body>");
	        // print_r($result_content);
			if($result_content != '')
			{
		        $xml = simplexml_load_string($result_content, 'SimpleXMLElement', LIBXML_NOCDATA);
		      //  echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
		        $response = $this->xml2json->xmlToArray($xml,"root");
		      
				$data["ERX_SUBMISSION_UPLOAD_STATUS_CODE"] = $response["UploadERxRequestResponse"]["UploadERxRequestResult"];
				$data["ERX_SUBMISSION_ERROR_MESSAGE"]      = $response["UploadERxRequestResponse"]["errorMessage"];		
					
				if( !empty($filename) && !empty($file_content))	
				{
					$this->db->start_cache();	
					$this->db->insert("ERX_SUBMISSION_UPLOAD",$data);				
					$this->db->stop_cache();
					$this->db->flush_cache();
				}	
				else
				{
					$result = array( "status"=> "Failed" ,'message' => 'No such a file or directory');
					return $result;
				}
				if($response["UploadERxRequestResponse"]["UploadERxRequestResult"] == 0)
				{
					
					$result =array("status"=> "Success","message"=>$response["UploadERxRequestResponse"]["errorMessage"] ,"eRxReferenceNo" =>$response["UploadERxRequestResponse"]["eRxReferenceNo"] ,"eRxprescription_uniqueid" =>$prescription_id);
					//$eRxNo["ERX_REFERENCE_NUMBER"] = array($response["UploadERxRequestResponse"]["eRxReferenceNo"]);
					$eRxNo = $response["UploadERxRequestResponse"]["eRxReferenceNo"];
					$erx = array('ERX_REFERENCE_NUMBER' => $eRxNo,'ERX_PRESCRIPTION_UNIQUE_ID' => $prescription_id,'ERX_GENERATED_DATE' =>date('Y-m-d H:i:s'));
					$this->db->start_cache();	
					$this->db->where("ASSESSMENT_ID",$assessment_id);	
					$this->db->where("STATUS",0);
					$this->db->update("DOCTOR_PRESCRIPTION",$erx);		
					$this->db->stop_cache();
					$this->db->flush_cache();

					if($type==2 && $type!=""){
						$erx1 = array('STATUS' => 1,'ERX_CANCELLED_DATE' =>date('Y-m-d H:i:s'));
						$this->db->start_cache();	
						$this->db->where("ASSESSMENT_ID",$assessment_id);
						$this->db->where("STATUS",0);	
						$this->db->update("DOCTOR_PRESCRIPTION",$erx1);		
						$this->db->stop_cache();
						$this->db->flush_cache();
					}
				}
				else
				{
					if(isset($response["UploadERxRequestResponse"]["errorReport"]))
					{
						$result = array("status"=> "Failed","message"=>$response["UploadERxRequestResponse"]["errorMessage"],"eRxReferenceNo" =>$response["UploadERxRequestResponse"]["eRxReferenceNo"],"error" =>base64_decode($response["UploadERxRequestResponse"]["errorReport"]));
					}
					else
					{
						$result = array("status"=> "Failed","message"=>$response["UploadERxRequestResponse"]["errorMessage"],"eRxReferenceNo" =>$response["UploadERxRequestResponse"]["eRxReferenceNo"]);
					}
					
				}
			
			}
			else
			{
				$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data);	
			}
			
		}
		// print_r($response["UploadERxRequestResponse"]);
		return $result;
	}
}
?>