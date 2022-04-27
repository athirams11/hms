<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NursingAssesmentModel extends CI_Model 
{
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
			"START_TIME" 	=> date(toUtc(trim($post_data["date"]),$post_data["timeZone"])." H:i:s"), //date("Y-m-d H:i:s"),
			//"END_TIME" 		=> trim($post_data["time"]),
			"VISIT_ID" 		=> trim($post_data["visit_id"]),
			"PATIENT_ID" 	=> trim($post_data["p_id"]),
			"CREATED_TIME"	=> date(toUtc(trim($post_data["date"]),$post_data["timeZone"])." H:i:s"), //date("Y-m-d H:i:s")
			
			);
			if($this->utility->is_Duplicate("NURSING_ASSESSMENT","VISIT_ID",$post_data["visit_id"]))
			{								
				$result= array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Assessment already started");
				return $result;
			}	

				$this->db->where("PATIENT_VISIT_LIST_ID	",$post_data["visit_id"]);
				$this->db->update("PATIENT_VISIT_LIST",array("VISIT_STATUS"=>1));

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
	public function cancelVisit($post_data = array())
  	{
	    if ($post_data["visit_id"] != "" && $post_data["cancel_reason"] !="")
	    {
	      $this->db->where("PATIENT_VISIT_LIST_ID",$post_data["visit_id"]);  
	      $query =  $this->db->update("PATIENT_VISIT_LIST",array("VISIT_STATUS"=>2,"CANCEL_REASON"=>$post_data["cancel_reason"],"CANCELED_BY"=> $post_data["user_id"]));
	      //echo $this->db->last_query();
	      if ($this->db->affected_rows() > 0)
	      {     
	        
	        $result   = array('message'=> "Visit cancelled successfully",
	                  'status'=>'Success',
	                  );

	        return $result; 
	      }
	      else 
	      {
	        $result   = array('message'=>"Failed to cancel visit",
	                  'status'=>'Failed',
	                  );
	        return $result;
	      }
	    }
	    else 
	    {
	      $result   = array('message'=>"Invalid  Visit",
	                  'status'=>'Failed',
	                  );
	      return $result;
	    }
	    
  	}
	public function getAssesmentListByDate($post_data = array())
	{

		$result = array("status"=> "Failed", "data"=> array());
		if(!empty($post_data))
		{
			if ($post_data["dateVal"] && $post_data["timeZone"] != "")
			{
				$this->db->start_cache();	
				$this->db->where("DATE(CONVERT_TZ(V.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateVal"],1));	
				if($post_data["user_group"] == 4){

					$this->db->where("D.LOGIN_ID",$post_data["user_id"]);	
				}	
				$this->db->select("P.*,OP.*,
					IFNULL(V.DISCOUNT_SITE_ID,0) AS DISCOUNT_SITE_ID,
					OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,
					C.COUNTRY_ISO3,C.COUNTRY_NAME,
					SP.OPTIONS_NAME as DEPARTMENT_NAME,
					V.DOCTOR_NAME,V.DOCTOR_ID,
					(SELECT BILL_STATUS FROM BILLING WHERE BILLING.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID LIMIT 1) AS BILL_STATUS");
				$this->db->from("NURSING_ASSESSMENT P");
				$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = P.PATIENT_ID","left");	
				$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");
				$this->db->join("PATIENT_VISIT_LIST V","P.VISIT_ID = V.PATIENT_VISIT_LIST_ID","left");	
				$this->db->join("DOCTORS D","D.DOCTORS_ID = V.DOCTOR_ID","left");	
				$this->db->join("OPTIONS SP","SP.OPTIONS_ID = V.DEPARTMENT_ID AND SP.OPTIONS_TYPE = 8","left");	
				// $this->db->join("BILLING B","P.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","left");
				$this->db->order_by("P.NURSING_ASSESSMENT_ID","DESC");
				// $this->db->group_by("B.ASSESSMENT_ID,B.PATIENT_ID");
				$query = $this->db->get();
				//echo $this->db->last_query();exit;
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$data = $query->result_array();
					foreach ($data as $key => $value) {
						if($value["OP_REGISTRATION_TYPE"] == 1 &&  $value["PATIENT_TYPE"] == null || $value["PATIENT_TYPE"] != null &&  $value["PATIENT_TYPE"] == 1)
						{
							if($value["PATIENT_TYPE_DETAIL_ID"] == null)
							{
								$data[$key]["insurance"] = $this->getInsDetails($value["PATIENT_ID"]);
								if($data[$key]["insurance"] != false)
								{
									if($data[$key]["insurance"]["OP_INS_IS_ALL"] != 1)
									{
										$data[$key]["insurance"]["co_ins"] = $this->getCoinsDetails($data[$key]["insurance"]["OP_INS_DETAILS_ID"]);
										if($data[$key]["insurance"]["co_ins"] == false)
										{
											$data[$key]["insurance"]["co_ins"] = array();
										}
									}
								}
							}
							else
							{
								$data[$key]["insurance"] = $this->InsDetails($value["PATIENT_ID"],$value["PATIENT_TYPE_DETAIL_ID"]);
								
										
							}
						}
						else
						{
							$data[$key]["insurance"] = false;
						}
					}
					$result = array("status"=> "Success", "data"=> $data);
					return $result;
				}
				
			}
		}
		return $result;
		
	}

	public function getDoctorAssesmentListByDate($post_data = array())
	{

		$result = array("status"=> "Failed", "data"=> array());
		if(!empty($post_data))
		{
			if ($post_data["dateVal"] && $post_data["timeZone"] != "")
			{
				$this->db->start_cache();
				$this->db->where("DATE(CONVERT_TZ(V.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateVal"],1));	
				if($post_data["user_group"] == 4){

					$this->db->where("D.LOGIN_ID",$post_data["user_id"]);	
				}	
				$this->db->select("V.*,P.*,OP.*,
					OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,
					C.COUNTRY_ISO3,C.COUNTRY_NAME,SP.OPTIONS_NAME as DEPARTMENT_NAME,
					V.DOCTOR_NAME,V.DOCTOR_ID,P.DOCTOR_STAT,V.VISIT_STATUS,
					(SELECT BILL_STATUS FROM BILLING WHERE BILLING.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID LIMIT 1) AS BILL_STATUS,
					(SELECT PATIENT_TYPE_DETAIL_ID FROM BILLING WHERE BILLING.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID LIMIT 1) AS PATIENT_TYPE_DETAIL_ID,
					(SELECT PATIENT_TYPE FROM BILLING WHERE BILLING.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID LIMIT 1) AS PATIENT_TYPE");
				$this->db->from("PATIENT_VISIT_LIST V");
				$this->db->join("NURSING_ASSESSMENT P","P.VISIT_ID = V.PATIENT_VISIT_LIST_ID","left");	
				$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = V.PATIENT_ID","left");	
				$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");
				$this->db->join("DOCTORS D","D.DOCTORS_ID = V.DOCTOR_ID","left");	
				$this->db->join("OPTIONS SP","SP.OPTIONS_ID = V.DEPARTMENT_ID AND SP.OPTIONS_TYPE = 8","left");	
				// 	$this->db->join("BILLING B","B.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID","left");	
				//$this->db->join("OP_INS_DETAILS INS","INS.OP_REGISTRATION_ID = OP.OP_REGISTRATION_ID","left");
				$this->db->join("LAB_INVESTIGATION L","P.NURSING_ASSESSMENT_ID = L.ASSESSMENT_ID","left");
				$this->db->order_by("P.START_TIME","DESC");
				// $this->db->group_by("B.ASSESSMENT_ID,B.PATIENT_ID");
				$query = $this->db->get();
				//echo $this->db->last_query();exit;
			
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$data = $query->result_array();
					foreach ($data as $key => $value) {
						if($value["OP_REGISTRATION_TYPE"] == 1 &&  $value["PATIENT_TYPE"] == null || $value["PATIENT_TYPE"] != null &&  $value["PATIENT_TYPE"] == 1)
						{
							if($value["PATIENT_TYPE_DETAIL_ID"] == null)
							{
								$data[$key]["insurance"] = $this->getInsDetails($value["PATIENT_ID"]);
								if($data[$key]["insurance"] != false)
								{
									if($data[$key]["insurance"]["OP_INS_IS_ALL"] != 1)
									{
										$data[$key]["insurance"]["co_ins"] = $this->getCoinsDetails($data[$key]["insurance"]["OP_INS_DETAILS_ID"]);
										if($data[$key]["insurance"]["co_ins"] == false)
										{
											$data[$key]["insurance"]["co_ins"] = array();
										}
									}
								}
							}
							else
							{
								$data[$key]["insurance"] = $this->InsDetails($value["PATIENT_ID"],$value["PATIENT_TYPE_DETAIL_ID"]);
								
										
							}
						}
						else
						{
							$data[$key]["insurance"] = false;
						}
					}
					/*foreach ($data as $key => $value) {
						$data[$key]["insurance"] = $this->getInsDetails($value["OP_REGISTRATION_ID"]);
						if($data[$key]["insurance"] != false)
						{
							if($data[$key]["insurance"]["OP_INS_IS_ALL"] != 1)
							{
								$data[$key]["insurance"]["co_ins"] = $this->getCoinsDetails($data[$key]["insurance"]["OP_INS_DETAILS_ID"]);
								if($data[$key]["insurance"]["co_ins"] == false)
								{
									$data[$key]["insurance"]["co_ins"] = array();
								}
							}
						}
					}*/
					$result = array("status"=> "Success", "data"=> $data);

					return $result;
				}
				
			}
		}
		return $result;
		
	}
	public function InsDetails($OP_REGISTRATION_ID =0,$OP_INS_DETAILS_ID=0)
 	{
 		if($OP_REGISTRATION_ID != 0 && $OP_INS_DETAILS_ID != 0)
 		{
 			$this->db->where("IN.OP_REGISTRATION_ID",$OP_REGISTRATION_ID);	
 			$this->db->where("IN.OP_INS_DETAILS_ID",$OP_INS_DETAILS_ID);	
			$this->db->select("IN.*,INP.INSURANCE_PAYERS_ECLAIM_LINK_ID,
				T.TPA_ECLAIM_LINK_ID,T.TPA_NAME,
				INP.INSURANCE_PAYERS_NAME,INN.INS_NETWORK_NAME");
			$this->db->from("OP_INS_DETAILS IN");
			$this->db->join("INSURANCE_PAYERS INP","INP.INSURANCE_PAYERS_ID = IN.OP_INS_PAYER","left");
			$this->db->join("TPA T","T.TPA_ID = IN.OP_INS_TPA","left");
			$this->db->join("INS_NETWORK INN","INN.INS_NETWORK_ID = IN.OP_INS_NETWORK","left");
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();
				if($data["OP_INS_IS_ALL"] != 1)
				{
					$data["co_ins"] = $this->CoinsDetails($data["OP_INS_DETAILS_ID"]);
					if($data["co_ins"] == false)
					{
						$data["co_ins"] = array();
					}
				}
				return $data;
			}
			else
			{
				return false;
			}
 		}
 		else
 		{
 			return false;
 		}
 	}
 	public function CoinsDetails($OP_INS_DETAILS_ID =0)
 	{
 		if($OP_INS_DETAILS_ID != 0)
 		{
 			$this->db->where("CI.OP_INS_DETAILS_ID",$OP_INS_DETAILS_ID);	
 			//$this->db->where("CI.OP_COIN_DATA_STATUS",1);	
			$this->db->select("CI.*");
			$this->db->from("OP_COIN_DATA CI");
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
			else
			{
				return false;
			}
 		}
 		else
 		{
 			return false;
 		}
 	}
	public function getAssesmentParameters($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		if(isset($post_data["test_methode"]) && (int)($post_data["test_methode"]) > 0)
		{
			$this->db->select("*");
			$this->db->from("TEST_METHODES TM");
			$this->db->join("TEST_UNITS TU","TU.METHODE_ID = TM.TEST_METHODES_ID","left","left");
			$this->db->join("TEST_PARAMETER TP","TP.TEST_PARAMETER_ID = TU.TEST_PARAMETER_ID","left","left");
			$this->db->join("UNITS U","U.UNITS_ID = TU.UNIT_ID","left","left");
			$this->db->where("TM.TEST_METHODES_ID",$post_data["test_methode"]);
			$this->db->order_by("TEST_UNIT_ORDER","ASC");
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = array("status"=> "Success", "data"=> $query->result());
				
			}

		}
		return $result;
	}
	public function saveAssesmentParameters($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());
		if(isset($post_data["assessment_id"]) && $post_data["test_methode"])
		{
			$data = array(
				"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
				
				"ENTRY_TYPE" => trim($post_data["test_methode"]),
				"ENTRY_STATUS" => 1
			);
			$data_id = trim($post_data["assessment_entry_id"]);
			if ($data_id > 0)
			{
				$data["UPDATED_TIME"] = date("Y-m-d H:i:s");
				$data["UPDATED_BY"] = $post_data["user_id"];
				$this->db->where("NURSING_ASSESSMENT_ENTRY_ID",$data_id);
				$this->db->update("NURSING_ASSESSMENT_ENTRY",$data);
				$this->saveAssesmentParameterValues($post_data["vitals_details"],trim($post_data["assessment_id"]),$data_id);
					return array("status"=> "Success", "reg_id"=>$data_id, "msg"=> "Data updated")	;
			}
			else
			{
				$data["DATE_TIME"] = toUtc(trim($post_data["date"]),$post_data["timeZone"]); //date("Y-m-d H:i:s");
				$data["CREATED_BY"] = $post_data["user_id"];
				$data["CREATED_ON"] = date("Y-m-d H:i:s");
				if($this->db->insert("NURSING_ASSESSMENT_ENTRY",$data))
				{
					$data_id = $this->db->insert_id();
					$this->saveAssesmentParameterValues($post_data["vitals_details"],trim($post_data["assessment_id"]),$data_id);
					return array("status"=> "Success", "reg_id"=>$data_id, "msg"=> "Data updated")	;
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
				if($data_arr ['PARAMETER_VALUE'])
					$this->db->insert("NURSING_ASSESSMENT_DETAILS",$data_arr);
			}
			
		}
	}
	public function getAssesmentParameterValues($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());
		if(isset($post_data["assessment_id"]) && (int)($post_data["assessment_id"]) > 0)
		{
			$this->db->select("*");
			$this->db->from("NURSING_ASSESSMENT N");
			$this->db->join("NURSING_ASSESSMENT_ENTRY NE","NE.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID","left");
			
			//$this->db->join("NURSING_ASSESSMENT_DETAILS ND","ND.ENTRY_ID = NE.NURSING_ASSESSMENT_ENTRY_ID","left");
			//$this->db->join("TEST_UNITS TU","TU.METHODE_ID = NE.ENTRY_TYPE AND TU.TEST_PARAMETER_ID = ND.PARAMETER_ID","left");
			//$this->db->join("TEST_PARAMETER TP","TP.TEST_PARAMETER_ID = TU.TEST_PARAMETER_ID","left");
			//$this->db->join("UNITS U","U.UNITS_ID = TU.UNIT_ID","left");
			//$this->db->where("N.PATIENT_ID",$post_data["patient_id"]);
			$this->db->where("N.NURSING_ASSESSMENT_ID",$post_data["assessment_id"]);
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
	public function editAssesmentValues($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["entry_id"] > 0 && $post_data["entry_type"] > 0)
		{
			$entry_id = $post_data["entry_id"];
			$entry_type = $post_data["entry_type"];
			$this->db->select("*");
			$this->db->from("NURSING_ASSESSMENT_ENTRY NE");
			//$this->db->join("NURSING_ASSESSMENT_ENTRY NE","NE.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID","left");
			//$this->db->join("NURSING_ASSESSMENT_DETAILS ND","ND.ENTRY_ID = NE.NURSING_ASSESSMENT_ENTRY_ID","left");
			//$this->db->join("TEST_UNITS TU","TU.METHODE_ID = NE.ENTRY_TYPE AND TU.TEST_PARAMETER_ID = ND.PARAMETER_ID","left");
			//$this->db->join("TEST_PARAMETER TP","TP.TEST_PARAMETER_ID = TU.TEST_PARAMETER_ID","left");
			//$this->db->join("UNITS U","U.UNITS_ID = TU.UNIT_ID","left");
			$this->db->where("NE.NURSING_ASSESSMENT_ENTRY_ID",$entry_id);
			$this->db->order_by("NE.DATE_TIME","DESC");
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();

				$data["parameters"] = $this->getAssesmentParameterValuesOnly($entry_id,$entry_type);
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
				
		}
		return $result;
		
	}
	public function getAssesmentParameterValuesOnly($entry_id,$entry_type)
	{
		//print_r($post_data);
		if((int)($entry_id) > 0 && (int)($entry_type) > 0)
		{
			$this->db->select("ND.*,TP.SHORT_FORM,U.SYMBOL,TP.TEST_PARAMETER_ID,TU.TEST_UNIT_ORDER");
			$this->db->from("TEST_UNITS TU");
			//$this->db->from("NURSING_ASSESSMENT_DETAILS ND");
			//$this->db->join("NURSING_ASSESSMENT_ENTRY NE","NE.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID","left");
			//$this->db->join("NURSING_ASSESSMENT_DETAILS ND","ND.ENTRY_ID = NE.NURSING_ASSESSMENT_ENTRY_ID","left");
			$this->db->join("NURSING_ASSESSMENT_DETAILS ND","ND.PARAMETER_ID = TU.TEST_PARAMETER_ID AND ND.ENTRY_ID = $entry_id","left");
			$this->db->join("TEST_PARAMETER TP","TP.TEST_PARAMETER_ID = TU.TEST_PARAMETER_ID","left");
			$this->db->join("UNITS U","U.UNITS_ID = TU.UNIT_ID","left");
			//$this->db->where("ND.ENTRY_ID",$entry_id);
			$this->db->where("TU.METHODE_ID",$entry_type);
			$this->db->order_by("TU.TEST_UNIT_ORDER","ASC");
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
	
	public function getAssesmentSummary($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		if($post_data["assessment_id"] > 0 && $post_data["entry_type"] >0 )
		{
			$entry_type = $post_data["entry_type"];
			$this->db->select("ND.*,TP.SHORT_FORM,U.SYMBOL,TP.TEST_PARAMETER_ID");
			$this->db->from("NURSING_ASSESSMENT_DETAILS ND");			
			$this->db->join("TEST_UNITS TU","TU.TEST_PARAMETER_ID = ND.PARAMETER_ID AND TU.METHODE_ID = $entry_type","left");
			$this->db->join("TEST_PARAMETER TP","TP.TEST_PARAMETER_ID = TU.TEST_PARAMETER_ID","left");
			$this->db->join("UNITS U","U.UNITS_ID = TU.UNIT_ID","left");
			$this->db->where("ND.ASSESSSMENT_ID", $post_data["assessment_id"]);
			$this->db->order_by("TU.TEST_UNIT_ORDER","ASC");
			//$this->db->where("NE.NURSING_ASSESSMENT_ENTRY_ID != ",NULL);
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{				
				$result = array("status"=> "Success", "data"=> $query->result_array());
			}
		}		
		return $result;		
	}
	
	
	public function completeAssesment($post_data = array())
	{		
		$result = array("status"=> "Failed", "message"=> "Failed to change status");
		if(!empty($post_data))
		{
			$data_id = $post_data["assessment_id"];
			 
			$data = array(
						"END_TIME"	=> toUtc(trim($post_data["date"]),$post_data["timeZone"]), //date("Y-m-d H:i:s"),
						"STAT" => 1,
						"USER_ID" => trim($post_data["user_id"])			
							);
				
			$this->db->where("NURSING_ASSESSMENT_ID",$data_id);
			$this->db->update("NURSING_ASSESSMENT",$data);
			$result = array("status"=> "Success", "reg_id"=>$data_id);
		 
		}
		
		return $result;
	}

	public function completeDoctorAssesment($post_data = array())
	{		
		$result = array("status"=> "Failed", "message"=> "Failed to change status");
		if(!empty($post_data))
		{
			$data_id = $post_data["assessment_id"];
			 
			$data = array(
						"END_TIME"	=> toUtc(trim($post_data["date"]),$post_data["timeZone"]), //date("Y-m-d H:i:s"),
						"DOCTOR_STAT" => 1,
						"USER_ID" => trim($post_data["user_id"])			
							);
				
			$this->db->where("NURSING_ASSESSMENT_ID",$data_id);
			$this->db->update("NURSING_ASSESSMENT",$data);
			$result = array("status"=> "Success", "reg_id"=>$data_id);
		 
		}
		
		return $result;
	}
	public function getAssessmentDetails($post_data = array())
	{

		$result = array("status"=> "Failed", "data"=> array());
		if(!empty($post_data))
		{
			if ($post_data["patient_id"] != "" && $post_data["assessment_id"] != "")
			{
				$this->db->start_cache();
		
				$this->db->where("P.NURSING_ASSESSMENT_ID",$post_data["assessment_id"]);	
				$this->db->where("P.PATIENT_ID",$post_data["patient_id"]);	
				$this->db->select("P.*,OP.*,OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,
					C.COUNTRY_ISO3,C.COUNTRY_NAME,
					SP.OPTIONS_NAME as DEPARTMENT_NAME,V.DOCTOR_NAME,V.DOCTOR_ID,
					(SELECT PATIENT_TYPE_DETAIL_ID FROM BILLING WHERE BILLING.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID LIMIT 1) AS PATIENT_TYPE_DETAIL_ID,
					(SELECT PATIENT_TYPE FROM BILLING WHERE BILLING.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID LIMIT 1) AS PATIENT_TYPE");
				$this->db->from("NURSING_ASSESSMENT P");
				$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = P.PATIENT_ID","left");	
				$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");
				$this->db->join("PATIENT_VISIT_LIST V","P.VISIT_ID = V.PATIENT_VISIT_LIST_ID","left");	
				$this->db->join("OPTIONS SP","SP.OPTIONS_ID = V.DEPARTMENT_ID AND SP.OPTIONS_TYPE = 8","left");	
				$this->db->order_by("NURSING_ASSESSMENT_ID","ASC");
				$query = $this->db->get();
				//echo $this->db->last_query();exit;
			
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$data = $query->row_array();
					if($data["OP_REGISTRATION_TYPE"] == 1 &&  $data["PATIENT_TYPE"] == null || $data["PATIENT_TYPE"] != null &&  $data["PATIENT_TYPE"] == 1)
					{
						if($data["PATIENT_TYPE_DETAIL_ID"] == null)
						{
							$data["insurance"] = $this->getInsDetails($data["PATIENT_ID"]);
							if($data["insurance"] != false)
							{
								if($data["insurance"]["OP_INS_IS_ALL"] != 1)
								{
									$data["insurance"]["co_ins"] = $this->getCoinsDetails($data["insurance"]["OP_INS_DETAILS_ID"]);
									if($data["insurance"]["co_ins"] == false)
									{
										$data["insurance"]["co_ins"] = array();
									}
								}
							}
						}
						else
						{
							$data["insurance"] = $this->InsDetails($data["PATIENT_ID"],$data["PATIENT_TYPE_DETAIL_ID"]);
						}
					}
					else
					{
						$data["insurance"] = false;
					}
					$result = array("status"=> "Success", "data"=> $data);
					return $result;
				}
				
			}
		}
		return $result;
		
	}
	public function getPreviousAssessmentDetails($post_data = array())
	{

		$result = array("status"=> "Failed", "data"=> array());
		if(!empty($post_data))
		{
			if ($post_data["patient_id"] != "" && $post_data["assessment_id"] != "")
			{
				$this->db->start_cache();
		
				$this->db->where("P.NURSING_ASSESSMENT_ID < ",$post_data["assessment_id"]);	
				$this->db->where("P.PATIENT_ID",$post_data["patient_id"]);	
				$this->db->select("P.*,OP.*,OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,C.COUNTRY_ISO3,C.COUNTRY_NAME,SP.OPTIONS_NAME as DEPARTMENT_NAME,V.DOCTOR_NAME,V.DOCTOR_ID,
					(SELECT PATIENT_TYPE_DETAIL_ID FROM BILLING WHERE BILLING.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID LIMIT 1) AS PATIENT_TYPE_DETAIL_ID,
					(SELECT PATIENT_TYPE FROM BILLING WHERE BILLING.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID LIMIT 1) AS PATIENT_TYPE");
				$this->db->from("NURSING_ASSESSMENT P");
				 $this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = P.PATIENT_ID","left");	
				 $this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");
				 $this->db->join("PATIENT_VISIT_LIST V","P.VISIT_ID = V.PATIENT_VISIT_LIST_ID","left");	
				 $this->db->join("OPTIONS SP","SP.OPTIONS_ID = V.DEPARTMENT_ID AND SP.OPTIONS_TYPE = 8","left");	
				 $this->db->order_by("NURSING_ASSESSMENT_ID","DESC");
				$query = $this->db->get();
				//echo $this->db->last_query();exit;
			
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$data = $query->row_array();
					if($data["OP_REGISTRATION_TYPE"] == 1 &&  $data["PATIENT_TYPE"] == null || $data["PATIENT_TYPE"] != null &&  $data["PATIENT_TYPE"] == 1)
					{
						if($data["PATIENT_TYPE_DETAIL_ID"] == null)
						{
							$data["insurance"] = $this->getInsDetails($data["PATIENT_ID"]);
							if($data["insurance"] != false)
							{
								if($data["insurance"]["OP_INS_IS_ALL"] != 1)
								{
									$data["insurance"]["co_ins"] = $this->getCoinsDetails($data["insurance"]["OP_INS_DETAILS_ID"]);
									if($data["insurance"]["co_ins"] == false)
									{
										$data["insurance"]["co_ins"] = array();
									}
								}
							}
						}
						else
						{
							$data["insurance"] = $this->InsDetails($data["PATIENT_ID"],$data["PATIENT_TYPE_DETAIL_ID"]);
						}
					}
					else
					{
						$data["insurance"] = false;
					}
					$result = array("status"=> "Success", "data"=> $data);
					return $result;
				}
				
			}
		}
		return $result;
		
	}
	public function getNextAssessmentDetails($post_data = array())
	{

		$result = array("status"=> "Failed", "data"=> array());
		if(!empty($post_data))
		{
			if ($post_data["patient_id"] != "" && $post_data["assessment_id"] != "")
			{
				$this->db->start_cache();
		
				$this->db->where("P.NURSING_ASSESSMENT_ID > ",$post_data["assessment_id"]);	
				$this->db->where("P.PATIENT_ID",$post_data["patient_id"]);	
				$this->db->select("P.*,OP.*,OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,C.COUNTRY_ISO3,C.COUNTRY_NAME,SP.OPTIONS_NAME as DEPARTMENT_NAME,V.DOCTOR_NAME,V.DOCTOR_ID,
					(SELECT PATIENT_TYPE_DETAIL_ID FROM BILLING WHERE BILLING.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID LIMIT 1) AS PATIENT_TYPE_DETAIL_ID,
					(SELECT PATIENT_TYPE FROM BILLING WHERE BILLING.ASSESSMENT_ID = P.NURSING_ASSESSMENT_ID LIMIT 1) AS PATIENT_TYPE");
				$this->db->from("NURSING_ASSESSMENT P");
				 $this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = P.PATIENT_ID","left");	
				 $this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");
				 $this->db->join("PATIENT_VISIT_LIST V","P.VISIT_ID = V.PATIENT_VISIT_LIST_ID","left");	
				 $this->db->join("OPTIONS SP","SP.OPTIONS_ID = V.DEPARTMENT_ID AND SP.OPTIONS_TYPE = 8","left");	
				 $this->db->order_by("NURSING_ASSESSMENT_ID","ASC");
				$query = $this->db->get();
				//echo $this->db->last_query();exit;
			
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$data = $query->row_array();
					if($data["OP_REGISTRATION_TYPE"] == 1 &&  $data["PATIENT_TYPE"] == null || $data["PATIENT_TYPE"] != null &&  $data["PATIENT_TYPE"] == 1)
					{
						if($data["PATIENT_TYPE_DETAIL_ID"] == null)
						{
							$data["insurance"] = $this->getInsDetails($data["PATIENT_ID"]);
							if($data["insurance"] != false)
							{
								if($data["insurance"]["OP_INS_IS_ALL"] != 1)
								{
									$data["insurance"]["co_ins"] = $this->getCoinsDetails($data["insurance"]["OP_INS_DETAILS_ID"]);
									if($data["insurance"]["co_ins"] == false)
									{
										$data["insurance"]["co_ins"] = array();
									}
								}
							}
						}
						else
						{
							$data["insurance"] = $this->InsDetails($data["PATIENT_ID"],$data["PATIENT_TYPE_DETAIL_ID"]);
						}
					}
					else
					{
						$data["insurance"] = false;
					}
					$result = array("status"=> "Success", "data"=> $data);
					return $result;
				}
				
			}
		}
		return $result;
		
	}
	public function getallVisitDetails($post_data = array())
	{

		$result = array("status"=> "Failed", "data"=> array());
		if(!empty($post_data))
		{
			if ($post_data["patient_id"] != "")
			{
				$this->db->start_cache();
		
				$this->db->where("P.PATIENT_ID",$post_data["patient_id"]);	
				$this->db->select("P.*,OP.*,OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,C.COUNTRY_ISO3,C.COUNTRY_NAME,SP.OPTIONS_NAME as DEPARTMENT_NAME,V.DOCTOR_NAME,V.DOCTOR_ID");
				$this->db->from("NURSING_ASSESSMENT P");
				$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = P.PATIENT_ID","left");	
				$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");
				$this->db->join("PATIENT_VISIT_LIST V","P.VISIT_ID = V.PATIENT_VISIT_LIST_ID","left");	
				$this->db->join("OPTIONS SP","SP.OPTIONS_ID = V.DEPARTMENT_ID AND SP.OPTIONS_TYPE = 8","left");
				$this->db->join("DOCTORS D","D.DOCTORS_ID = V.DOCTOR_ID","left");	
				$this->db->order_by("V.CREATED_TIME","DESC");
				$query = $this->db->get();
				//echo $this->db->last_query();exit;
			
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$result = array("status"=> "Success", "data"=> $query->result_array());
					return $result;
				}
				
			}
		}
		return $result;
		
	}
	public function getInsDetails($OP_REGISTRATION_ID =0)
 	{
 		if($OP_REGISTRATION_ID != 0)
 		{
 			$this->db->where("IN.OP_REGISTRATION_ID",$OP_REGISTRATION_ID);	
 			$this->db->where("IN.OP_INS_STATUS",1);	
			$this->db->select("IN.*,INP.INSURANCE_PAYERS_ECLAIM_LINK_ID,
				T.TPA_ECLAIM_LINK_ID,T.TPA_NAME,
				INP.INSURANCE_PAYERS_NAME,INN.INS_NETWORK_NAME");
			$this->db->from("OP_INS_DETAILS IN");
			$this->db->join("INSURANCE_PAYERS INP","INP.INSURANCE_PAYERS_ID = IN.OP_INS_PAYER","left");
			$this->db->join("TPA T","T.TPA_ID = IN.OP_INS_TPA","left");
			$this->db->join("INS_NETWORK INN","INN.INS_NETWORK_ID = IN.OP_INS_NETWORK","left");
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row_array();
			}
			else
			{
				return false;
			}
 		}
 		else
 		{
 			return false;
 		}
 	}
 	
 	public function getCoinsDetails($OP_INS_DETAILS_ID =0)
 	{
 		if($OP_INS_DETAILS_ID != 0)
 		{
 			$this->db->where("CI.OP_INS_DETAILS_ID",$OP_INS_DETAILS_ID);	
 			$this->db->where("CI.OP_COIN_DATA_STATUS",1);	
			$this->db->select("CI.*");
			$this->db->from("OP_COIN_DATA CI");
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
			else
			{
				return false;
			}
 		}
 		else
 		{
 			return false;
 		}
 	}
 	public function saveBloodSugarReport($post_data)
	{
		//print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());
		if(isset($post_data["assessment_id"]) && $post_data["patient_id"])
		{
			$data = array(
				"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
				"PATIENT_ID" => trim($post_data["patient_id"]),
				"BLOOD_SUGAR_REPORT" => trim($post_data["blood_sugar_report"]),
				"CLIENT_DATE" => date("Y-m-d H:i:s")
			);
			$data_id = trim($post_data["blood_sugar_report_id"]);
			if ($data_id > 0)
			{
				$data["UPDATED_TIME"] = date("Y-m-d H:i:s");
				$data["DATE_TIME"] = toUtc(trim($post_data["date"]),$post_data["timeZone"]);
				$data["UPDATED_BY"] = $post_data["user_id"];
				$this->db->where("BLOOD_SUGAR_REPORT_ID",$data_id);
				$this->db->update("BLOOD_SUGAR_REPORT",$data);
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Blood sugar report details saved successfully")	;
			}
			else
			{

				$data["CREATED_BY"] = $post_data["user_id"];
				$data["CREATED_TIME"] = date("Y-m-d H:i:s");
				if($this->db->insert("BLOOD_SUGAR_REPORT",$data))
				{
					$data_id = $this->db->insert_id();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Blood sugar report details saved successfully")	;
				}
			}
			

		}
		return $result;
	}
	public function getBloodSugarReport($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '' && $post_data["assessment_id"] != '')
		{
			$patient_id = $post_data["patient_id"];
			$assessment_id = $post_data["assessment_id"];
			$blood_sugar_report_id = $post_data["blood_sugar_report_id"];
			$this->db->select("*");
			$this->db->from("BLOOD_SUGAR_REPORT B");
			if($post_data["blood_sugar_report_id"] > 0)
				$this->db->where("B.BLOOD_SUGAR_REPORT_ID",$blood_sugar_report_id);
			if($post_data["patient_id"] !='')
				$this->db->where("B.PATIENT_ID",$patient_id);
			if($post_data["assessment_id"] !='')
				$this->db->where("B.ASSESSMENT_ID",$assessment_id);
			$query = $this->db->get();
		
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

	public function getDiscountTreatmentDetails($post_data)
	{	
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["visit_id"] != '')
		{
			$visit_id = $post_data["visit_id"];
			$this->db->select("*");
			$this->db->from("PATIENT_DISCOUNT_DETAILS PD");
			$this->db->where("PD.PATIENT_VISIT_ID",$visit_id);
			$query = $this->db->get();
		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result_array();
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
				
		}
		return $result;
		
	}
	
} 
?>
