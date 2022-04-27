<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NursingAssesmentNotesModel extends CI_Model 
{
	public function getAssesmentParameters($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data['mode_of_arrivel'] = $this->MasterDataModel->getMasterDataList(1);
		$data['accompanied_by'] = $this->MasterDataModel->getMasterDataList(2);		
			
		if(isset($post_data["test_methode"]) && (int)($post_data["test_methode"]) > 0)
		{
			
			
			$this->db->select("*");
			$this->db->from("TEST_METHODES TM");
			$this->db->join("TEST_UNITS TU","TU.METHODE_ID = TM.TEST_METHODES_ID","left","left");
			$this->db->join("TEST_PARAMETER TP","TP.TEST_PARAMETER_ID = TU.TEST_PARAMETER_ID","left","left");
			$this->db->join("UNITS U","U.UNITS_ID = TU.UNIT_ID","left","left");
			$this->db->where("TM.TEST_METHODES_ID",$post_data["test_methode"]);
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result();				
			}
		}
		$result = array("status"=> "Success", "data"=> $data);
		return $result;
	}
	
	public function saveAssesmentParameters($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());
		//if(isset($post_data["assessment_id"]) && $post_data["test_methode"])
		//{
			$data = array(
								"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
								"NEXT_OF_KIN" => trim($post_data["next_of_kin"]),  
								"NEXT_OF_KIN_MOBILE" => trim($post_data["next_of_kin_mobile"]),  
								"CHIEF_COMPLAINTS" => trim($post_data["chief_complaints"]),  
								"NURSING_NOTES" => trim($post_data["nursing_notes"]),  
								"PAST_HISTORY" => trim($post_data["past_history"]),  
								"FAMILY_HISTORY" => trim($post_data["family_history"]),  
								"MODE_ARRIVAL" => trim($post_data["mode_arrival"]),   
								"MODE_ARRIVAL_OTHER" => trim($post_data["mode_arrival_other_value"]),   
								"ACCOMPANIED_BY" => trim($post_data["accompanied_by"]),   
								"ACCOMPANIED_BY_OTHER" => trim($post_data["accompanied_other_value"]),   
								"PATIENT_WAITING_TIME_INFORMED"=> trim($post_data["patient_waiting_time_informed"]),   				    
								"EXPECTED_WAITING_TIME" => trim($post_data["expected_waiting_time"]),
								"ENTRY_STATUS" => 1,
								"CLIENT_DATE" => format_date($post_data["client_date"])	,	
								"DATE_TIME" => toUtc(trim($post_data["date"]),$post_data["timeZone"]),
							);							
			
			$data_id = trim($post_data["assessment_notes_id"]);
			if ($data_id > 0)
			{
				$data["UPDATED_BY"] = $post_data["user_id"];
				$data["UPDATED_ON"] = date("Y-m-d H:i:s");
				$this->db->where("NURSING_ASSESSMENT_NOTES_ID",$data_id);
				$this->db->update("NURSING_ASSESSMENT_NOTES",$data);				
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{
				$data["CREATED_BY"] = $post_data["user_id"];
				$data["CREATED_ON"] = date("Y-m-d H:i:s");
				if($this->db->insert("NURSING_ASSESSMENT_NOTES",$data))
				{
					$data_id = $this->db->insert_id();				
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}			
		//}
		return $result;
	}
	
	public function editAssesmentValues($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["assessment_id"] > 0)
		{
			$data_id = $post_data["assessment_id"];
			$this->db->select("NAE.*");
			$this->db->from("NURSING_ASSESSMENT_NOTES NAE");
			$this->db->where("NAE.ASSESSMENT_ID",$data_id);
			$this->db->order_by("NAE.DATE_TIME","DESC");
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
	
	public function deleteAssesmentValues($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["assessment_notes_id"] > 0)
		{
			$data_id = $post_data["assessment_notes_id"];			
			$this->db->where("NAE.NURSING_ASSESSMENT_NOTES_ID", $data_id);
			$ret = $this->db->delete("NURSING_ASSESSMENT_NOTES");			
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			 
			if(!empty($ret))
				$result = array("status"=> "Success", "data"=> $data);
				
		}
		return $result;
		
	}
	
	
	
	
	
	function getDrByDateDept($post_data = array())
	{
		
		//print_r($post_data);
		if ($post_data["dateVal"] != "" && $post_data["department"] != "")
		{
			$day = date('D', strtotime(format_date($post_data["dateVal"],1)));
			$date = format_date($post_data["dateVal"],1);
			$department = $post_data["department"];
			$this->db->start_cache();
			$this->db->select("T.DOCTORS_SCHEDULE_ID,T.DAY,T.SCHEDULE_NO,T.START_AT,T.END_AT,DS.DOCTORS_ID,DS.DOCTORS_NAME,DS.SPECIALIZED_IN,DS.AVG_CONS_TIME,SP.OPTIONS_NAME as SPECIALIZED_NAME");
			$this->db->from("DOCTORS_SCHEDULE_TIME_TABLE T");
			$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = T.DOCTORS_SCHEDULE_ID","left");
			$this->db->join("OPTIONS SP","SP.OPTIONS_ID = DS.SPECIALIZED_IN AND SP.OPTIONS_TYPE = 8","left");
			
			$this->db->where("UPPER(DAY)",strtoupper($day));
			$this->db->where("DS.SPECIALIZED_IN",$department);
			$this->db->where("DOCTORS_SCHEDULE_TIME_TABLE_STATUS",1);
			$this->db->order_by("DS.DOCTORS_NAME","asc");
			$this->db->group_by("T.DOCTORS_SCHEDULE_ID");
			$query = $this->db->get();
				//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$re_array = $query->result();
				
				$result 	= array('data'=>$re_array,
									'status'=>'Success',
									);

				return $result;				
			}
			else 
			{
				$result 	= array('data'=>"",
									'status'=>'Failed',
									);
				return $result;
			}
		}
		else 
		{
			$result 	= array('data'=>"",
								'status'=>'Failed',
								);
			return $result;
		}
			
		//return $query->row();		
	}
	
	public function getPatientDetails($post_data = array())
 	{
 		if (!empty($post_data) && (isset($post_data["op_number"]) || isset($post_data["op_reg_id"])) )
 		{
 			if($post_data["op_number"])
	 			$this->db->where("OP_REGISTRATION_NUMBER",$post_data["op_number"]);	
	 		if($post_data["op_reg_id"])
	 			$this->db->where("OP_REGISTRATION_ID",$post_data["op_reg_id"]);	
	 		$this->db->where("OP_REGISTRATION_STATUS",1);	
			$this->db->select("OP.*, RT.OPTIONS_NAME as PAY_TYPE, C.COUNTRY_NAME as COUNTRY_NAME, C.COUNTRY_NAME as NATIONALITY_NAME, EM.OPTIONS_NAME as EMIRATES_NAME, SI.OPTIONS_NAME as SOURCE_OF_INFO_NAME");
			$this->db->from("OP_REGISTRATION OP");
			$this->db->join("OPTIONS RT","RT.OPTIONS_ID = OP.OP_REGISTRATION_TYPE","left");
			$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.COUNTRY","left");
			$this->db->join("COUNTRY N","N.COUNTRY_ID = OP.NATIONALITY","left");
			$this->db->join("OPTIONS EM","EM.OPTIONS_ID = OP.EMIRATES","left");
			$this->db->join("OPTIONS SI","SI.OPTIONS_ID = OP.SOURCE_OF_INFO","left");
			$query = $this->db->get();

			if($query->num_rows() > 0)
			{
				$data["patient_data"] = $query->row();

				$data["ins_data"] = $this->getInsDetails($data["patient_data"]->OP_REGISTRATION_ID);
				$data["co_ins"] = array();
				if($data["ins_data"] != false)
				{
					if($data["ins_data"]->OP_INS_IS_ALL != 1)
					{
						$data["co_ins"] = $this->getCoinsDetails($data["ins_data"]->OP_INS_DETAILS_ID);
						if($data["co_ins"] == false)
						{
							$data["co_ins"] = array();
						}
					}
				}
				//print_r($data);
				$result 	= array('data'=>$data,
									'status'=>'Success'
									);
				return $result;		
		
			}
			else 
			{
				$result 	= array('data'=>"",
									'status'=>'Failed',
									);
				return $result;
			}
 		}
 		else
 		{
 			$result 	= array('data'=>"",
									'status'=>'Failed',
									);
				return $result;
 		}

 	}
 	
	function startAssesment($post_data = array())
	{
		//print_r($post_data);exit;
		$result = array("status"=> "Failed", "message"=> "Failed to add visit");
		if(!empty($post_data))
		{
			$data=array(
			"START_TIME" 		=> trim($post_data["time"]),
			//"END_TIME" 		=> trim($post_data["time"]),
			"VISIT_ID" 		=> trim($post_data["visit_id"]),
			"PATIENT_ID" 		=> trim($post_data["p_id"])
			
			);

			$data_id = 0;
			
			
			if ($data_id> 0)
			{
				$this->db->where("NURSING_ASSESSMENT_ID",$data_id);
				$this->db->update("NURSING_ASSESSMENT",$data);
				$result = array("status"=> "Success", "reg_id"=>$data_id)	;
			}
			else
			{
				
				//$gen_no=$this->GenerateOpNo($post_data);
				
				$this->db->insert("NURSING_ASSESSMENT",$data);
				$data_id = $this->db->insert_id();
				 //$this->addScheduleTable($data_id,$post_data["time_table"]);
				$result = array("status"=> "Success", "message"=>"Visit added successfully");
			}
		}
		
		return $result;
	}
	
	public function getAssesmentListByDate($post_data = array())
	{

		$result = array("status"=> "Failed", "data"=> array());
		if(!empty($post_data))
		{
			if ($post_data["dateVal"] != "")
			{
				$this->db->start_cache();
		
				$this->db->where("V.VISIT_DATE",format_date($post_data["dateVal"],1));	
				$this->db->select("P.*,OP.*,OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,C.COUNTRY_ISO3,C.COUNTRY_NAME,SP.OPTIONS_NAME as DEPARTMENT_NAME");
				$this->db->from("NURSING_ASSESSMENT P");
				 $this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = P.PATIENT_ID","left");	
				 $this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");
				 $this->db->join("PATIENT_VISIT_LIST V","P.VISIT_ID = V.PATIENT_VISIT_LIST_ID","left");	
				 $this->db->join("OPTIONS SP","SP.OPTIONS_ID = V.DEPARTMENT_ID AND SP.OPTIONS_TYPE = 8","left");	
				 
				$query = $this->db->get();
				//echo $this->db->last_query();exit;
			
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$result = array("status"=> "Success", "data"=> $query->result());
					return $result;
				}
				
			}
		}
		return $result;
		
	}
	
			
	
	public function saveAssesmentParameterValues($parameter_array,$assesment_id=0,$entry_id=0)
	{
		//print_r($post_data);
		if(!empty($parameter_array) && $assesment_id > 0 && $entry_id > 0)
		{
			$this->db->start_cache();
			$this->db->where("ENTRY_ID",$entry_id);
			$this->db->delete("NURSING_ASSESSMENT_DETAILS");
			$this->db->stop_cache();
			$this->db->flush_cache();
			foreach($parameter_array as $key => $value)
			{
				$data_arr = array(
					"ASSESSSMENT_ID" => $assesment_id,
					"ENTRY_ID" => $entry_id,
					"PARAMETER_ID" => $key,
					"PARAMETER_VALUE" => $value
				);
				$this->db->insert("NURSING_ASSESSMENT_DETAILS",$data_arr);
			}
			
		}
	}
	
	public function getAssesmentParameterValues($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());
		if(isset($post_data["patient_id"]) && (int)($post_data["patient_id"]) > 0)
		{
			$this->db->select("*");
			$this->db->from("NURSING_ASSESSMENT N");
			$this->db->join("NURSING_ASSESSMENT_ENTRY NE","NE.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID","left");
			//$this->db->join("NURSING_ASSESSMENT_DETAILS ND","ND.ENTRY_ID = NE.NURSING_ASSESSMENT_ENTRY_ID","left");
			//$this->db->join("TEST_UNITS TU","TU.METHODE_ID = NE.ENTRY_TYPE AND TU.TEST_PARAMETER_ID = ND.PARAMETER_ID","left");
			//$this->db->join("TEST_PARAMETER TP","TP.TEST_PARAMETER_ID = TU.TEST_PARAMETER_ID","left");
			//$this->db->join("UNITS U","U.UNITS_ID = TU.UNIT_ID","left");
			$this->db->where("N.PATIENT_ID",$post_data["patient_id"]);
			$this->db->where("NE.NURSING_ASSESSMENT_ENTRY_ID != ",NULL);
			$this->db->order_by("NE.DATE_TIME","DESC");
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result_array();
				foreach($data as $key => $value)
				{
					$data[$key]["param_values"] = $this->getAssesmentParameterValuesOnly($value["NURSING_ASSESSMENT_ENTRY_ID"],$value["ENTRY_TYPE"]);
				}
				
				$result = array("status"=> "Success", "data"=> $data);
				
			}

		}
		return $result;
	}
	
	
	
	public function getAssesmentParameterValuesOnly($entry_id,$entry_type)
	{
		//print_r($post_data);
		if((int)($entry_id) > 0 && (int)($entry_type) > 0)
		{
			$this->db->select("ND.*,TP.SHORT_FORM,U.SYMBOL");
			$this->db->from("NURSING_ASSESSMENT_DETAILS ND");
			//$this->db->join("NURSING_ASSESSMENT_ENTRY NE","NE.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID","left");
			//$this->db->join("NURSING_ASSESSMENT_DETAILS ND","ND.ENTRY_ID = NE.NURSING_ASSESSMENT_ENTRY_ID","left");
			$this->db->join("TEST_UNITS TU","TU.TEST_PARAMETER_ID = ND.PARAMETER_ID AND TU.METHODE_ID = $entry_type","left");
			$this->db->join("TEST_PARAMETER TP","TP.TEST_PARAMETER_ID = TU.TEST_PARAMETER_ID","left");
			$this->db->join("UNITS U","U.UNITS_ID = TU.UNIT_ID","left");
			$this->db->where("ND.ENTRY_ID",$entry_id);
			//$this->db->where("NE.NURSING_ASSESSMENT_ENTRY_ID != ",NULL);
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}

		}
		else{
			return array();
		}
	}


} 
?>