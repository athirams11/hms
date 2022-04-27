<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OpVisitModel extends CI_Model 
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
				$data["corporate_data"] = $this->getCorporateDetails($data["patient_data"]->OP_REGISTRATION_ID);
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
 	public function getInsDetailsForlist($OP_REGISTRATION_ID =0)
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
	
	public function getCoinsDetailsForlist($OP_INS_DETAILS_ID =0)
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
 	public function getInsDetails($OP_REGISTRATION_ID =0)
 	{
 		if($OP_REGISTRATION_ID != 0)
 		{
 			$this->db->where("IN.OP_REGISTRATION_ID",$OP_REGISTRATION_ID);	
 			$this->db->where("IN.OP_INS_STATUS",1);	
			$this->db->select("IN.*");
			$this->db->from("OP_INS_DETAILS IN");
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row();
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
			$this->db->select("CI.COIN_ID,CI.COIN_NAME,CI.COIN_VALUE,CI.COIN_VALUE_TYPE");
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
 	public function getCorporateDetails($OP_REGISTRATION_ID =0)
 	{
 		if($OP_REGISTRATION_ID != 0)
 		{
 			$this->db->where("CD.OP_REGISTRATION_ID",$OP_REGISTRATION_ID);	
 			$this->db->where("CD.OP_COMPANY_STATUS",1);	
			$this->db->select("CD.*");
			$this->db->from("OP_CORPORATE_DETAILS CD");
			$query = $this->db->get();
			// echo $this->db->last_query();	
			if($query->num_rows() > 0)
			{
				return $query->row();
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
	public function updateInsuranceDetails($post_data = array())
	{
		$OP_REGISTRATION_ID = trim($post_data["OP_REGISTRATION_ID"]);
		$INS_DATA = $post_data["INS_DATA"];
		$CO_IN_DATA = $post_data["CO_IN_DATA"];
		
		// print_r($post_data);
		// exit;
		if($INS_DATA['OP_INS_TPA'] == 0 || $INS_DATA['OP_INS_TPA'] == '')
		{
			return array("status" => "warning" ,"msg"=> "Please select TPA reciever");
		}
		if($INS_DATA['OP_INS_PAYER'] == 0 || $INS_DATA['OP_INS_PAYER'] == '' )
		{
			return array("status" => "warning" ,"msg"=> "Please select Insurance co payer");
		}
		if($INS_DATA['OP_INS_NETWORK'] == 0 || $INS_DATA['OP_INS_NETWORK'] == '')
		{
			return array("status" => "warning" ,"msg"=> "Please select IInsurance nerwork");
		}
		if($INS_DATA['OP_INS_MEMBER_ID'] == '')
		{
			return array("status" => "warning" ,"msg"=> "Please select Insurance member id !");
		}
		if($INS_DATA['OP_INS_POLICY_NO'] == '')
		{
			return array("status" => "warning" ,"msg"=> "Please select Insurance policy number");
		}
		if($INS_DATA['OP_INS_VALID_FROM'] == '' || $INS_DATA['OP_INS_VALID_FROM'] == '0000-00-00')
		{
			return array("status" => "warning" ,"msg"=> "Please select Insurance validity");
		}
		if($INS_DATA['OP_INS_VALID_TO'] == '' || $INS_DATA['OP_INS_VALID_TO'] == '0000-00-00')
		{
			return array("status" => "warning" ,"msg"=> "Please select Insurance validity");
		}
		
		
		$result = array("status"=> "Failed", "message"=> "Insurance details update failed");
		if ($OP_REGISTRATION_ID> 0)
		{
			unset($INS_DATA["OP_INS_STATUS"]);
			unset($INS_DATA["CREATED_DATE"]);
			unset($INS_DATA["UPDATED_DATE"]);

			$this->db->start_cache();
				$this->db->where("OP_REGISTRATION_ID",$OP_REGISTRATION_ID);
				$this->db->where("OP_INS_DETAILS_ID",$INS_DATA["OP_INS_DETAILS_ID"]);
				$this->db->update("OP_INS_DETAILS",$INS_DATA);
			$this->db->stop_cache();
			$this->db->flush_cache();
			
			// $this->db->insert("OP_INS_DETAILS",$INS_DATA);
			// $ins_id = $this->db->insert_id();
			$ins_id = $INS_DATA["OP_INS_DETAILS_ID"];

			
			// $this->db->start_cache();
			// 	$this->db->where("OP_REGISTRATION_ID",$OP_REGISTRATION_ID);
			// 	$this->db->update("OP_COIN_DATA",array("OP_COIN_DATA_STATUS"=>0));
			// $this->db->stop_cache();
			// $this->db->flush_cache();


			if($post_data['OP_INS_IS_ALL'] == false && !empty($CO_IN_DATA))
			{
				$coin_arr = array();
				$this->db->start_cache();
				$this->db->where("OP_REGISTRATION_ID",$OP_REGISTRATION_ID);
				$this->db->where("OP_INS_DETAILS_ID",$ins_id);
				$this->db->delete("OP_COIN_DATA");
				$this->db->stop_cache();
				$this->db->flush_cache();
				$coin_arr = array();
				foreach ($CO_IN_DATA as $key => $value) {

					unset($value["OP_COIN_DATA_ID"]);
					unset($value["OP_COIN_DATA_STATUS"]);
					//unset($value["OP_REGISTRATION_ID"]);
					//unset($value["OP_INS_DETAILS_ID"]);
					$value["OP_REGISTRATION_ID"] = $OP_REGISTRATION_ID;
					$value["OP_INS_DETAILS_ID"] = $ins_id;
					$coin_arr[$key] = $value;
				}
			}
			if(!empty($coin_arr))
				$this->db->insert_batch('OP_COIN_DATA', $coin_arr); 

			$result = array("status"=> "Success", "message"=> "Insurance details updated")	;
		}
		return $result;
	}
	public function updateCompanyDetails($post_data = array())
	{
		$OP_REGISTRATION_ID = trim($post_data["OP_REGISTRATION_ID"]);
		$CORPORATE_DATA = $post_data["CORPORATE_DATA"];
		
		$result = array("status"=> "Failed", "message"=> "Company details update failed");
		if ($OP_REGISTRATION_ID> 0)
		{
			$this->db->start_cache();
				$this->db->where("OP_REGISTRATION_ID",$OP_REGISTRATION_ID);
				$this->db->update("OP_CORPORATE_DETAILS",array("OP_COMPANY_STATUS"=>0));
			$this->db->stop_cache();
			$this->db->flush_cache();


			
			unset($CORPORATE_DATA["OP_CORPORATE_DETAILS_ID"]);
			unset($CORPORATE_DATA["OP_COMPANY_STATUS"]);
			unset($CORPORATE_DATA["CREATED_DATE"]);
			$CORPORATE_DATA["UPDATED_DATE"] =  date("Y-m-d H:i:s");

			$this->db->insert("OP_CORPORATE_DETAILS",$CORPORATE_DATA);
			

			$result = array("status"=> "Success", "message"=> "Insurance details updated")	;
		}
		return $result;
	}
	function addVisit($post_data = array())
	{
		//print_r($post_data);exit;
		$result = array("status"=> "Failed", "message"=> "Failed to add visit");
		if(!empty($post_data))
		{
			$data = array(
				"VISIT_DATE" 		=> toUtc(trim($post_data["date"]),$post_data["timeZone"]),//date("Y-m-d"),
				"VISIT_TIME" 		=> date(toUtc(trim($post_data["date"]),$post_data["timeZone"])." H:i:s"),
				"PATIENT_NO" 		=> trim($post_data["op_number"]),
				"PATIENT_ID" 		=> trim($post_data["p_id"]),
				"APPOINTMENT_ID" 	=> trim($post_data["appointment_id"]),
				"DEPARTMENT_ID" 	=> trim($post_data["department"]),
				"DOCTOR_ID" 		=> trim($post_data["visit_doctor"]),
				"DOCTOR_NAME" 		=> trim($post_data["visit_doctor_name"]),
				"DISCOUNT_SITE_ID" 	=> trim($post_data["discount_site"]),
				"DISCOUNT_GIFT" 	=> trim($post_data["giftcard"]),
				"REFERENCE_CODE" 	=> trim($post_data["reference_code"]),
				"CREATED_BY" 		=> trim($post_data["user_id"]),
				"CREATED_TIME" 		=> date("Y-m-d H:i:s"),
				"VISIT_REASON" 		=> trim($post_data["visit_reason"]),
				"CLIENT_DATE" 		=> format_date($post_data["client_date"])	
				
			);

			$data_id = 0;
			
			if($this->utility->is_Duplicate("PATIENT_VISIT_LIST","APPOINTMENT_ID",$post_data["appointment_id"]))
			{								
				$result= array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Visit already added");
				return $result;
			}	
			if($post_data["appointment_id"] > 0)
			{
				$this->db->where("APPOINTMENT_ID",$post_data["appointment_id"]);	
				$query =  $this->db->update("APPOINTMENT",array("APPOINTMENT_STATUS"=>1));
			}
			if($data_id> 0)
			{
				$this->db->where("PATIENT_VISIT_LIST_ID",$data_id);
				$this->db->update("PATIENT_VISIT_LIST",$data);
				$result = array("status"=> "Success", "reg_id"=>$data_id)	;
			}
			else
			{
				
				$this->db->insert("PATIENT_VISIT_LIST",$data);
				$data_id = $this->db->insert_id();
				if($data_id > 0 && !empty($post_data['dicountedCPTdata']) && $post_data['discount_site'] > 0)
				{
					$rep = $this->saveDiscountTreatmentDetails($data_id,$post_data);
				}
				$result = array("status"=> "Success", "message"=>"Visit added successfully");
			}
		}
		
		return $result;
	}
	public function saveDiscountTreatmentDetails($data_id,$post_data = array())
	{
		$flag = false;
		if(!empty($post_data['dicountedCPTdata']))
		{

			foreach($post_data['dicountedCPTdata'] as $key => $discount)
			{

				$data["PATIENT_VISIT_ID"] = $data_id;
				$data["CPT_ID"] = trim($discount["current_procedural_code_id"]);
				$data["CPT_CODE"] = trim($discount["cptcode"]);
				$data["CPT_NAME"] = trim($discount["cptname"]);
				$data["CPT_RATE"] = trim($discount["rate"]);
				$data["CREATED_BY"] = trim($post_data["user_id"]);
				$data["CREATED_DATE"] = date("Y-m-d H:i:s");
				
				$this->db->start_cache();		
				$this->db->insert("PATIENT_DISCOUNT_DETAILS",$data);
				$this->db->stop_cache();
				$this->db->flush_cache();	
				$flag = true;
			}	
		}
		
		return $flag;
	}
	public function getVisitListByDate($post_data = array())
	{

		$result = array("status"=> "Failed", "data"=> array());
		if(!empty($post_data))
		{
			if ($post_data["dateVal"] && $post_data["timeZone"] != "")
			{
				$this->db->start_cache();
				if(isset($post_data["doctor_id"]) && $post_data["doctor_id"] != '')
				{
					$this->db->where("D.DOCTORS_ID",$post_data["doctor_id"]);
				}
				if(isset($post_data["dateVal"]) && $post_data["dateVal"] != '')
				{
					$this->db->where("DATE(CONVERT_TZ(P.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) >=",format_date($post_data["dateVal"],1));	

				}
				if(isset($post_data["search_by_opnumber"]) && $post_data["search_by_opnumber"] != '')
				{
					$this->db->where("OP.OP_REGISTRATION_NUMBER",$post_data["search_by_opnumber"]);
				}
				if(isset($post_data["todate"]) && $post_data["todate"] != '')
				{
					$this->db->where("DATE(CONVERT_TZ(P.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) <=",format_date($post_data["todate"],1));	
				}	

				//$this->db->where("DATE(CONVERT_TZ(P.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateVal"],1));	
				if($post_data["user_group"] == 4){

					$this->db->where("D.LOGIN_ID",$post_data["user_id"]);	
				}	

				$this->db->select("P.*,OP.*,NA.START_TIME,OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,C.COUNTRY_ISO3,C.COUNTRY_NAME,SP.OPTIONS_NAME as DEPARTMENT_NAME,NA.STAT,L.STAT AS DOCTOR_STAT,(SELECT BILL_STATUS FROM BILLING WHERE BILLING.ASSESSMENT_ID = NA.NURSING_ASSESSMENT_ID LIMIT 1) AS BILL_STATUS");
				$this->db->from("PATIENT_VISIT_LIST P");
				$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = P.PATIENT_ID","left");	
				$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");	
				$this->db->join("OPTIONS SP","SP.OPTIONS_ID = P.DEPARTMENT_ID AND SP.OPTIONS_TYPE = 8","left");
				$this->db->join("DOCTORS D","D.DOCTORS_ID = P.DOCTOR_ID","left");		
				$this->db->join("NURSING_ASSESSMENT NA","NA.VISIT_ID = P.PATIENT_VISIT_LIST_ID","left");
				$this->db->join("LAB_INVESTIGATION L","NA.NURSING_ASSESSMENT_ID = L.ASSESSMENT_ID","left");
				$this->db->order_by("P.PATIENT_VISIT_LIST_ID","DESC");
				$query = $this->db->get();
				 //echo $this->db->last_query();exit;
			
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$data=$query->result_array();
					foreach ($data as $key => $value) 
					{
						 $data[$key]["general"] =$this->getPatientConsent_id($value["PATIENT_NO"],3);
						 $data[$key]["dental"] =$this->getPatientConsent($value["PATIENT_VISIT_LIST_ID"],1);
						 $data[$key]["covid"]=$this->getPatientConsent_id($value["PATIENT_NO"],2);
						 // print_r($value);exit();
						if($value["OP_REGISTRATION_TYPE"] == 1 &&  $value["PATIENT_TYPE"] == null || $value["PATIENT_TYPE"] != null &&  $value["PATIENT_TYPE"] == 1)
						{
							if($value["PATIENT_TYPE_DETAIL_ID"] == null)
							{
								$data[$key]["insurance"] = $this->getInsDetailsForlist($value["PATIENT_ID"]);
								if($data[$key]["insurance"] != false)
								{
									if($data[$key]["insurance"]["OP_INS_IS_ALL"] != 1)
									{
										$data[$key]["insurance"]["co_ins"] = $this->getCoinsDetailsForlist($data[$key]["insurance"]["OP_INS_DETAILS_ID"]);
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
	
	public function getCPTBySites($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["discount_site_id"] > 0)
		{
				
			$discount = $post_data["discount_site_id"];
			
			$this->db->start_cache();			
			$this->db->select("DM.*,CC.OPTIONS_NAME as CPT_CATEGORY_NAME,CC.OPTIONS_ID as CPT_CATEGORY_CODE,CR.CPT_RATE,CONCAT(DM.PROCEDURE_CODE,' - ',DM.PROCEDURE_CODE_NAME) AS CPT");
			$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS DM");
			$this->db->join("OPTIONS CC","CC.OPTIONS_ID = DM.PROCEDURE_CODE_CATEGORY AND OPTIONS_TYPE = 9");
			$this->db->join("CPT_RATE CR","CR.CURRENT_PROCEDURAL_CODE_ID = DM.CURRENT_PROCEDURAL_CODE_ID");	
			$this->db->where("DM.DISCOUNT_SITE_ID",trim($discount));
			$this->db->where("DM.STAT",1);			
			$this->db->where("CR.TPA_ID ",0);			
			$this->db->where("CR.NETWORK_ID ",0);				
			$this->db->group_by("CR.CURRENT_PROCEDURAL_CODE_ID"	);			
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

	public function getPatientConsent($visitis,$type)
	{
			
			$this->db->where("PV.VISIT_ID",$visitis);
			$this->db->where("CONSENT_TYPE",$type);	
			$this->db->select("PV.*");
			$this->db->from("PATIENT_CONSENT PV");
			$this->db->order_by("PV.PATIENT_CONSENT_ID","desc");
			$query = $this->db->get();
			   if($query->num_rows() > 0)
			   {

				   return 1;		
		   
			   }
			   else 
			   {
				
				   return 2;
			   }
	}

	public function getPatientConsent_id($p_id,$type)
	{
			
				$this->db->where("PV.OP_NUMBER",$p_id);
				$this->db->where("CONSENT_TYPE",$type);	
			   $this->db->select("PV.*");
			   $this->db->from("PATIENT_CONSENT PV");
			   $this->db->order_by("PV.PATIENT_CONSENT_ID","desc");
			   $query = $this->db->get();
			   if($query->num_rows() > 0)
			   {
				return 1;		
		   
			   }
			   else 
			   {
				return 2;
			   }
			
	}
} 
?>