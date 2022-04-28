<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ReportModel extends CI_Model 
{
	public function listCashReport($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();	
		if($post_data["todate"]!= "" && $post_data["timeZone"] != "")
		{
			$this->db->start_cache();	
			$this->db->where("DATE(CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) <=",format_date($post_data["todate"],1));	
		}
		if($post_data["fromdate"]!= "" && $post_data["timeZone"] != "")
		{
			$this->db->start_cache();	
			$this->db->where("DATE(CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) >=",format_date($post_data["fromdate"],1));	
		}	
			
		$this->db->select("OP.OP_REGISTRATION_NUMBER,B.BILLING_ID,
			CONCAT(OP.FIRST_NAME,' ',OP.MIDDLE_NAME,' ',OP.LAST_NAME) AS PATIENT_NAME,
			DATE_FORMAT(DATE(CONVERT_TZ(B.BILLING_DATE,'+00:00','+05:30')), '%d-%m-%Y') AS INVOICE_DATE, 
			B.BILLING_INVOICE_NUMBER,
			D.DOCTORS_NAME,B.BILLED_AMOUNT,
			B.PAID_BY_PATIENT,B.PATIENT_DISCOUNT,B.PATIENT_TYPE,	
			(B.PAID_BY_PATIENT - B.PATIENT_DISCOUNT) AS PATIENT_AMOUNT,
			B.INSURED_AMOUNT,U.FIRSTNAME,U.LASTNAME,B.PAYMENT_MODE");
		$this->db->from("BILLING B");
		$this->db->join("BILLING_DETAILS BD","BD.BILLING_ID = B.BILLING_ID");
		$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = B.PATIENT_ID","left");
		$this->db->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","left");
		$this->db->join("PATIENT_VISIT_LIST V","V.PATIENT_VISIT_LIST_ID = N.VISIT_ID","left");
		$this->db->join("DOCTORS D","D.DOCTORS_ID = V.DOCTOR_ID","left");
		// $this->db->join("OP_INS_DETAILS IN","IN.OP_REGISTRATION_ID = B.PATIENT_ID AND IN.OP_INS_DETAILS_ID = B.PATIENT_TYPE_DETAIL_ID AND B.PATIENT_TYPE = 1","left");
		$this->db->join("USERS U","U.USER_SPK = B.CREATED_BY","left");
		// $this->db->join("TPA","TPA.TPA_ID = IN.OP_INS_TPA","left");
		// $this->db->join("OP_CORPORATE_DETAILS OC","OC.OP_REGISTRATION_ID = B.PATIENT_ID AND OC.OP_CORPORATE_DETAILS_ID = B.PATIENT_TYPE_DETAIL_ID AND B.PATIENT_TYPE = 3","left");
		// $this->db->join("CORPORATE_COMPANY C","C.CORPORATE_COMPANY_ID = OC.OP_CORPORATE_COMPANY_ID","left");
		$this->db->where("B.GENERATED",1);
		$this->db->where("B.PAID_BY_PATIENT > 0");
		$this->db->where("B.BILL_STATUS",1);
		$this->db->group_by("B.BILLING_ID");
		
		// if(isset($post_data["tpa_id"]) && $post_data["tpa_id"] > 0)
		// {
		// 	$this->db->start_cache();	
		// 	$this->db->where("TPA.TPA_ID",$post_data["tpa_id"]);	
		// }		
		if(isset($post_data["doctor_id"]) && $post_data["doctor_id"] > 0)
		{
			$this->db->start_cache();	
			$this->db->where("D.DOCTORS_ID",$post_data["doctor_id"]);	
		}	
		if(isset($post_data["cashier_id"]) && $post_data["cashier_id"] > 0)
		{
			$this->db->start_cache();	
			$this->db->where("B.CREATED_BY",$post_data["cashier_id"]);	
		}	
		// if(isset($post_data["company_id"]) && $post_data["company_id"] > 0)
		// {
		// 	$this->db->start_cache();	
		// 	$this->db->where("C.CORPORATE_COMPANY_ID",$post_data["company_id"]);	
		// }	
		// if(isset($post_data["pay_type"]) && $post_data["pay_type"] == 1 && isset($post_data["tpa_id"]) && $post_data["tpa_id"] == 0)
		// {
		// 	$this->db->start_cache();	
		// 	$this->db->where("B.PATIENT_TYPE",1);	
		// }
		// if(isset($post_data["pay_type"]) && $post_data["pay_type"] == 2 && isset($post_data["company_id"]) && $post_data["company_id"] == 0)
		// {
		// 	$this->db->start_cache();	
		// 	$this->db->where("B.PATIENT_TYPE",3);	
		// }		
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("B.BILLING_INVOICE_NUMBER",$post_data["search_text"]);
			$this->db->or_like("D.DOCTORS_NAME",$post_data["search_text"]);
			$this->db->or_like("OP.OP_REGISTRATION_NUMBER",$post_data["search_text"]);
			// $this->db->or_like("TPA.TPA_NAME",$post_data["search_text"]);
			// $this->db->or_like("TPA.TPA_ECLAIM_LINK_ID",$post_data["search_text"]);
			$this->db->or_like("U.FIRSTNAME",$post_data["search_text"]);
			$this->db->or_like("U.LASTNAME",$post_data["search_text"]);
			// $this->db->or_like("C.CORPORATE_COMPANY_NAME",$post_data["search_text"]);
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
		$query = $this->db->get();
		// echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$count = sizeof($data);	
			$result = array("status"=> "Success", "data"=> $data ,"total_count"=>$count);
		}
		
		return $result;
	}
	public function listCreditReport($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$this->db->start_cache();	
		if($post_data["todate"]!= "" && $post_data["timeZone"] != "")
		{
			$this->db->start_cache();	
			$this->db->where("DATE(CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) <=",format_date($post_data["todate"],1));	
		}
		if($post_data["fromdate"]!= "" && $post_data["timeZone"] != "")
		{
			$this->db->start_cache();	
			$this->db->where("DATE(CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) >=",format_date($post_data["fromdate"],1));	
		}		
		$this->db->select("OP.OP_REGISTRATION_NUMBER,B.BILLING_ID,
			CONCAT(OP.FIRST_NAME,' ',OP.MIDDLE_NAME,' ',OP.LAST_NAME) AS PATIENT_NAME,
			DATE_FORMAT(DATE(CONVERT_TZ(B.BILLING_DATE,'+00:00','+05:30')), '%d-%m-%Y') AS INVOICE_DATE, 
			B.BILLING_INVOICE_NUMBER,
			D.DOCTORS_NAME,TPA.TPA_NAME,
			CONCAT(TPA.TPA_ECLAIM_LINK_ID , ' - ', TPA.TPA_NAME) AS TPA,
			TPA.TPA_ECLAIM_LINK_ID,B.BILLED_AMOUNT,
			B.PAID_BY_PATIENT,B.PATIENT_DISCOUNT,	
			(B.PAID_BY_PATIENT - B.PATIENT_DISCOUNT) AS PATIENT_AMOUNT,
			B.INSURED_AMOUNT,U.FIRSTNAME,U.LASTNAME,B.PATIENT_TYPE,
			C.CORPORATE_COMPANY_NAME");
		$this->db->from("BILLING B");
		$this->db->join("BILLING_DETAILS BD","BD.BILLING_ID = B.BILLING_ID");
		$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = B.PATIENT_ID","left");
		$this->db->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","left");
		$this->db->join("PATIENT_VISIT_LIST V","V.PATIENT_VISIT_LIST_ID = N.VISIT_ID","left");
		$this->db->join("DOCTORS D","D.DOCTORS_ID = V.DOCTOR_ID","left");
		$this->db->join("OP_INS_DETAILS IN","IN.OP_REGISTRATION_ID = B.PATIENT_ID AND IN.OP_INS_DETAILS_ID = B.PATIENT_TYPE_DETAIL_ID","left");
		$this->db->join("USERS U","U.USER_SPK = B.CREATED_BY","left");
		$this->db->join("TPA","TPA.TPA_ID = IN.OP_INS_TPA","left");
		$this->db->join("OP_CORPORATE_DETAILS OC","OC.OP_REGISTRATION_ID = B.PATIENT_ID AND OC.OP_CORPORATE_DETAILS_ID = B.PATIENT_TYPE_DETAIL_ID AND B.PATIENT_TYPE = 3","left");
		$this->db->join("CORPORATE_COMPANY C","C.CORPORATE_COMPANY_ID = OC.OP_CORPORATE_COMPANY_ID","left");
		$this->db->where("B.BILL_STATUS",1);
		// $this->db->where("B.PATIENT_TYPE !=",2);
		// $this->db->where("B.BILL_TYPE",1);
		$this->db->where("B.BILL_TYPE",1);
		// $this->db->where("B.PATIENT_TYPE !=",4);
		//$this->db->or_where("B.PATIENT_TYPE",3);
		$this->db->where("B.GENERATED",1);
		//$this->db->where("B.BILL_TYPE",1);
		$this->db->group_by("B.BILLING_ID");
		if(isset($post_data["tpa_id"]) && $post_data["tpa_id"] > 0)
		{
			$this->db->start_cache();	
			$this->db->where("TPA.TPA_ID",$post_data["tpa_id"]);	
		}		
		if(isset($post_data["doctor_id"]) && $post_data["doctor_id"] > 0)
		{
			$this->db->start_cache();	
			$this->db->where("D.DOCTORS_ID",$post_data["doctor_id"]);	
		}	
		if(isset($post_data["cashier_id"]) && $post_data["cashier_id"] > 0)
		{
			$this->db->start_cache();	
			$this->db->where("B.CREATED_BY",$post_data["cashier_id"]);	
		}
		if(isset($post_data["company_id"]) && $post_data["company_id"] > 0)
		{
			$this->db->start_cache();	
			$this->db->where("C.CORPORATE_COMPANY_ID",$post_data["company_id"]);	
		}	
		if(isset($post_data["pay_type"]) && $post_data["pay_type"] == 1 && isset($post_data["tpa_id"]) && $post_data["tpa_id"] == 0)
		{
			$this->db->start_cache();	
			$this->db->where("B.PATIENT_TYPE",1);	
		}
		if(isset($post_data["pay_type"]) && $post_data["pay_type"] == 2 && isset($post_data["company_id"]) && $post_data["company_id"] == 0)
		{
			$this->db->start_cache();	
			$this->db->where("B.PATIENT_TYPE",3);	
		}		
		if($post_data["search_text"] != '')
		{
			$this->db->group_start();
			$this->db->like("B.BILLING_INVOICE_NUMBER",$post_data["search_text"]);
			$this->db->or_like("D.DOCTORS_NAME",$post_data["search_text"]);
			$this->db->or_like("OP.OP_REGISTRATION_NUMBER",$post_data["search_text"]);
			$this->db->or_like("TPA.TPA_NAME",$post_data["search_text"]);
			$this->db->or_like("TPA.TPA_ECLAIM_LINK_ID",$post_data["search_text"]);
			$this->db->or_like("U.FIRSTNAME",$post_data["search_text"]);
			$this->db->or_like("U.LASTNAME",$post_data["search_text"]);
			$this->db->or_like("C.CORPORATE_COMPANY_NAME",$post_data["search_text"]);
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
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();				
			$count = sizeof($data);	
			$result = array("status"=> "Success", "data"=> $data ,"total_count"=>$count);
		}
		
		return $result;
	}
	public function getBill($post_data = array())
	{
		$result = array('data'=>"",'status'=>'Failed');
		if(!empty($post_data))
		{
			if (isset($post_data["billing_id"]) &&  $post_data["billing_id"] > 0)
			{
				$this->db->start_cache();
				$this->db->select("B.*");
				$this->db->from("BILLING B");
				$this->db->where("BILLING_ID",$post_data["billing_id"]);
				$query = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$bill_data = $query->row_array();
					$bill_data["bill_details"] = $this->getBillDetails($bill_data["BILLING_ID"]);
					$bill_data["pateint_details"] = $this->getPatient($bill_data);	
					$result = array("status"=> "Success", "data"=> $bill_data);
					
				}
			}
		}
		return $result;
	}
	public function getPatient($bill_data)
	{

 		if (!empty($bill_data))
 		{
 			
	 		$this->db->where("OP_REGISTRATION_ID",$bill_data["PATIENT_ID"]);
			$this->db->select("OP.*,'".$bill_data["PATIENT_TYPE"]."' AS OP_REGISTRATION_TYPE, RT.OPTIONS_NAME as PAY_TYPE, C.COUNTRY_NAME as COUNTRY_NAME, C.COUNTRY_NAME as NATIONALITY_NAME,");
			$this->db->from("OP_REGISTRATION OP");
			$this->db->join("OPTIONS RT","RT.OPTIONS_ID = '".$bill_data["PATIENT_TYPE"]."'","left");
			$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.COUNTRY","left");
			$this->db->join("COUNTRY N","N.COUNTRY_ID = OP.NATIONALITY","left");
			
			$query = $this->db->get();
			// echo $this->db->last_query();
			if($query->num_rows() > 0)
			{
				$data["patient_data"] = $query->row();

				$data["ins_data"] = $this->billInsDetails($bill_data);
				$data["co_ins"] = array();
				if($data["ins_data"] != false)
				{
					if($data["ins_data"]->OP_INS_IS_ALL != 1)
					{
						$data["co_ins"] = $this->billCoinsDetails($data["ins_data"]->OP_INS_DETAILS_ID);
						if($data["co_ins"] == false)
						{
							$data["co_ins"] = array();
						}
					}
				}
				$data["corporate_data"] = $this->billCorporateDetails($bill_data);
				
				$result 	= array('data'=>$data,'status'=>'Success');
				return $result;		
		
			}
			else 
			{
				$result 	= array('data'=>"",'status'=>'Failed');
				return $result;
			}
 		}
 		else
 		{
 			$result = array('data'=>"",'status'=>'Failed');
				return $result;
 		}

 	}
	

	public function getBillDetails($billing_id)
	{
		$result = array();
		if ($billing_id > 0)
			{
				$this->db->start_cache();
				$this->db->select("B.*,LID.*");
				$this->db->select("CPT.PROCEDURE_CODE as PROCEDURE_CODE, CPT.PROCEDURE_CODE_NAME as PROCEDURE_CODE_NAME,CPT.PROCEDURE_CODE_CATEGORY as PROCEDURE_CODE_CATEGORY");
				$this->db->from("BILLING_DETAILS B");
				$this->db->join("LAB_INVESTIGATION_DETAILS LID","LID.LAB_INVESTIGATION_DETAILS_ID = B.LAB_INVESTIGATION_DETAILS_ID","left");
				$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CPT.CURRENT_PROCEDURAL_CODE_ID = LID.CURRENT_PROCEDURAL_CODE_ID","left");						
									
				$this->db->where("B.BILLING_ID",$billing_id);
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

 	public function billInsDetails($bill_data)
 	{
 		if($bill_data && $bill_data["PATIENT_TYPE"] == 1)
 		{
 			$this->db->where("IN.OP_REGISTRATION_ID",$bill_data["PATIENT_ID"]);	
	 		$this->db->where("IN.OP_INS_DETAILS_ID",$bill_data["PATIENT_TYPE_DETAIL_ID"]);	
			$this->db->select("IN.*,INP.INSURANCE_PAYERS_NAME,INN.INS_NETWORK_NAME");
			$this->db->from("OP_INS_DETAILS IN");
			$this->db->join("INSURANCE_PAYERS INP","INP.INSURANCE_PAYERS_ID = IN.OP_INS_PAYER","left");
			$this->db->join("INS_NETWORK INN","INN.INS_NETWORK_ID = IN.OP_INS_NETWORK","left");
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
 	public function billCoinsDetails($OP_INS_DETAILS_ID =0)
 	{
 		if($OP_INS_DETAILS_ID != 0)
 		{
 			$this->db->where("CI.OP_INS_DETAILS_ID",$OP_INS_DETAILS_ID);	
			$this->db->select("CI.*");
			$this->db->from("OP_COIN_DATA CI");
			$query = $this->db->get();
			// echo $this->db->last_query();exit;
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
	public function billCorporateDetails($bill_data)
 	{
 		if($bill_data && $bill_data["PATIENT_TYPE"] == 3)
 		{
 			$this->db->where("CD.OP_REGISTRATION_ID",$bill_data["PATIENT_ID"]);	
			$this->db->select("CD.*,CC.*");
			$this->db->from("OP_CORPORATE_DETAILS CD");
			$this->db->join("CORPORATE_COMPANY CC","CC.CORPORATE_COMPANY_ID = CD.OP_CORPORATE_COMPANY_ID",'left');
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


	public function getPatientDetails($post_data = array())
 	{
 			if($post_data["op_number"] != "")
 			{
 				if($post_data["op_number"])
	 			$this->db->where("OP_REGISTRATION_NUMBER",$post_data["op_number"]);	
		 		$this->db->where("OP_REGISTRATION_STATUS",1);	
				$this->db->select("OP.*");
				$this->db->from("OP_REGISTRATION OP");
				$query = $this->db->get();
				//echo $this->db->last_query();
				if($query->num_rows() > 0)
				{
					$data= $query->row();
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

 		public function getvisit($post_data)
 		{
		 		if($post_data["todate"]!= "" && $post_data["timeZone"] != "")
				{
					
					$this->db->where("DATE(CONVERT_TZ(PV.VISIT_TIME,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) <=",format_date($post_data["todate"],1));	
				}
				if($post_data["fromdate"]!= "" && $post_data["timeZone"] != "")
				{
					
					$this->db->where("DATE(CONVERT_TZ(PV.VISIT_TIME,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) >=",format_date($post_data["fromdate"],1));	
				}	
 				$this->db->where("PV.PATIENT_ID",$post_data["patient_uni_id"]);	
		 		$this->db->where("PV.VISIT_STATUS!=",2);	
				$this->db->select("NS.NURSING_ASSESSMENT_ID,PV.PATIENT_ID,PV.VISIT_TIME, PV.PATIENT_VISIT_LIST_ID, PV.PATIENT_NO");
				$this->db->from("PATIENT_VISIT_LIST PV");
				$this->db->join("NURSING_ASSESSMENT NS","NS.VISIT_ID = PV.PATIENT_VISIT_LIST_ID");
				$query = $this->db->get();
				
				if($query->num_rows() > 0)
				{
					$result = array();
					$data= $query->result();
					foreach ($data as $key => $value) 
					{
						
						$this->load->model('api/NursingAssesmentModel');  
						$this->load->model('api/NursingAssesmentNotesModel');  
						$this->load->model('api/PatientDiagnosisModel');  
						$this->load->model('api/DoctorPrescriptionModel');  
						$this->load->model('api/ChiefComplaintsModel');  
						$this->load->model('api/DentalComplaintsModel'); 
						$this->load->model('api/BillingModel'); 
						$this->load->model('api/PatientAllergiesModel'); 
						$this->load->model('api/PainAssessmentModel'); 
						$post_data["assessment_id"]=$value->NURSING_ASSESSMENT_ID;
						$post_data["patient_id"]=$value->PATIENT_ID;
						$post_data["visit_id"]=$value->PATIENT_VISIT_LIST_ID;
						$post_data["p_id"]=$value->PATIENT_NO;
						$post_data["patient_diagnosis_id"]=0;
						//$post_data["patient_allergies_id"]=0;
						$post_data["consultation_id"]='';
						$post_data["doctor_prescription_id"]='';
						$post_data["complaint_id"]='';
						$post_data["test_methode"]=1;
						$result[$key]["generalconsent"] = $this->getPatientConsent_id($post_data["p_id"],3);
						$result[$key]["Dentalconsent"] = $this->getPatientConsent($post_data["visit_id"],1);
						$result[$key]["covidconsent"] = $this->getPatientConsent_id($post_data["p_id"],2);
						$result[$key]["vitals"] = $this->NursingAssesmentModel->getAssesmentParameterValues($post_data);
						$result[$key]["vitals_params"] = $this->NursingAssesmentModel->getAssesmentParameters($post_data);
						$result[$key]["date"] = $value->VISIT_TIME;
						$result[$key]["diagonosis"] = $this->PatientDiagnosisModel->getPatientDiagnosis($post_data);
						$result[$key]["prescription"]= $this->DoctorPrescriptionModel->getPrescription($post_data);
						$result[$key]["nursingnotes"]= $this->NursingAssesmentNotesModel->editAssesmentValues($post_data);
						$result[$key]["pain"]= $this->PainAssessmentModel->getPainAssesmentpain($post_data);
						$result[$key]["complaints"] = $this->ChiefComplaintsModel->getComplaints($post_data);
						$result[$key]["dental"] = $this->DentalComplaintsModel->getDentalComplaints($post_data);
						$result[$key]["allergy"] = $this->PatientAllergiesModel->getPatientAllergiespdf($post_data["patient_id"]);
						$result[$key]["billing"]= $this->BillingModel->getBillByAssessment($post_data);
						$result[$key]["pain_array"][0]= Array(
								            "value" => 0,
								            "image" => "public/assets/image/PainAssessment0.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect0.jpeg",
								            );
						$result[$key]["pain_array"][1]= Array(
								            "value" => 2,
								            "image" => "public/assets/image/PainAssessment2.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect2.jpeg",
								            );
						$result[$key]["pain_array"][2]= Array(
								            "value" => 4,
								            "image" => "public/assets/image/PainAssessment4.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect4.jpeg",
								            );
						$result[$key]["pain_array"][3]= Array(
								            "value" => 6,
								            "image" => "public/assets/image/PainAssessment6.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect6.jpeg",
								            );
						$result[$key]["pain_array"][4]= Array(
								            "value" => 8,
								            "image" => "public/assets/image/PainAssessment8.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect8.jpeg",
								            );
						$result[$key]["pain_array"][5]= Array(
								            "value" => 10,
								            "image" => "public/assets/image/PainAssessment10.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect10.jpeg",
								            );
										
					}
					
					$result 	= array('data'=>$result,
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

 		public function getvisit_visitdate($post_data)
 		{
		 		if($post_data["visitdate"]!= "" && $post_data["timeZone"] != "")
				{
					
					$this->db->where("DATE(CONVERT_TZ(PV.VISIT_TIME,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) =",format_date($post_data["visitdate"],1));	
				}
 				$this->db->where("PV.PATIENT_ID",$post_data["patient_uni_id"]);	
		 		$this->db->where("PV.VISIT_STATUS!=",2);	
				$this->db->select("NS.NURSING_ASSESSMENT_ID,PV.PATIENT_ID,PV.VISIT_TIME");
				$this->db->from("PATIENT_VISIT_LIST PV");
				$this->db->join("NURSING_ASSESSMENT NS","NS.VISIT_ID = PV.PATIENT_VISIT_LIST_ID");
				$query = $this->db->get();
				//echo $this->db->last_query();
				//exit();
				if($query->num_rows() > 0)
				{
					$result = array();
					$data= $query->result();
					foreach ($data as $key => $value) 
					{
						
						$this->load->model('api/NursingAssesmentModel');  
						$this->load->model('api/NursingAssesmentNotesModel');  
						$this->load->model('api/PatientDiagnosisModel');  
						$this->load->model('api/DoctorPrescriptionModel');  
						$this->load->model('api/ChiefComplaintsModel');  
						$this->load->model('api/DentalComplaintsModel'); 
						$this->load->model('api/BillingModel'); 
						$this->load->model('api/PatientAllergiesModel'); 
						$this->load->model('api/PainAssessmentModel'); 
						$post_data["assessment_id"]=$value->NURSING_ASSESSMENT_ID;
						//$post_data["visit_id"]=$value->PATIENT_VISIT_LIST_ID;
						$post_data["patient_id"]=$value->PATIENT_ID;
						$post_data["patient_diagnosis_id"]=0;
						//$post_data["patient_allergies_id"]=0;
						$post_data["consultation_id"]='';
						$post_data["doctor_prescription_id"]='';
						$post_data["complaint_id"]='';
						$post_data["test_methode"]=1;
						// $result[$key]["generalconsent"] = $this->getPatientConsent($post_data["visit_id"],3);
						// $result[$key]["Dentalconsent"] = $this->getPatientConsent($post_data["visit_id"],1);
						// $result[$key]["covidconsent"] = $this->getPatientConsent($post_data["visit_id"],2);
						$result[$key]["vitals"] = $this->NursingAssesmentModel->getAssesmentParameterValues($post_data);
						$result[$key]["vitals_params"] = $this->NursingAssesmentModel->getAssesmentParameters($post_data);
						$result[$key]["date"] = $value->VISIT_TIME;
						$result[$key]["diagonosis"] = $this->PatientDiagnosisModel->getPatientDiagnosis($post_data);
						$result[$key]["prescription"]= $this->DoctorPrescriptionModel->getPrescription($post_data);
						$result[$key]["nursingnotes"]= $this->NursingAssesmentNotesModel->editAssesmentValues($post_data);
						$result[$key]["pain"]= $this->PainAssessmentModel->getPainAssesmentpain($post_data);
						$result[$key]["complaints"] = $this->ChiefComplaintsModel->getComplaints($post_data);
						$result[$key]["dental"] = $this->DentalComplaintsModel->getDentalComplaints($post_data);
						$result[$key]["allergy"] = $this->PatientAllergiesModel->getPatientAllergiespdf($post_data["patient_id"]);
						$result[$key]["billing"]= $this->BillingModel->getBillByAssessment($post_data);
						$result[$key]["pain_array"][0]= Array(
								            "value" => 0,
								            "image" => "public/assets/image/PainAssessment0.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect0.jpeg",
								            );
						$result[$key]["pain_array"][1]= Array(
								            "value" => 2,
								            "image" => "public/assets/image/PainAssessment2.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect2.jpeg",
								            );
						$result[$key]["pain_array"][2]= Array(
								            "value" => 4,
								            "image" => "public/assets/image/PainAssessment4.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect4.jpeg",
								            );
						$result[$key]["pain_array"][3]= Array(
								            "value" => 6,
								            "image" => "public/assets/image/PainAssessment6.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect6.jpeg",
								            );
						$result[$key]["pain_array"][4]= Array(
								            "value" => 8,
								            "image" => "public/assets/image/PainAssessment8.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect8.jpeg",
								            );
						$result[$key]["pain_array"][5]= Array(
								            "value" => 10,
								            "image" => "public/assets/image/PainAssessment10.jpeg",
								            "select" => "public/assets/image/PainAssessmentSelect10.jpeg",
								            );
										
					}
					
					$result 	= array('data'=>$result,
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
 	

 	public function downloadmedicalPdf($post_data)
	{

		/*if(file_exists(FCPATH . "public/uploads/MedicalReport.pdf"))
		{
			unlink(FCPATH . "public/uploads/MedicalReport.pdf");
		}*/
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '')	
		{		
			$this->load->model('api/InstitutionManagementModel');  
			$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
			$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
			$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : ''; 
			$post_data["op_number"] = $post_data["patient_id"]; 
			$institution      = $this->InstitutionManagementModel->listInstitution($post_data);
			$pateint_details	= $this->getPatientDetails($post_data);	
			if($pateint_details["status"]=="Success"){
			$post_data["patient_uni_id"]=$pateint_details["data"]->OP_REGISTRATION_ID;

			if($post_data["patient_uni_id"]>0){
				$visit_details	= $this->getvisit($post_data);	
			}
			$data["institution"] = $institution;
			$data["pateint_details"] = $pateint_details;
			$data["visit_details"] = $visit_details;
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->pdf;
			$pdf->autoScriptToLang = true;
			$pdf->autoLangToFont = true;
			$html = $this->load->view('export/medicalPdf.php', $data, true);
			$time=time();
			$pdfFilePath = FCPATH . "public/uploads/MedicalReport".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "F");
			
			 $result = array("status"=> "Success", "data"=> "public/uploads/MedicalReport".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf","filename" => "MedicalReport".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf" );
			}
			else{
				$result 	= array('data'=>"",
										'status'=>'Failed',
										'message'=>'Invalid Patient Number',

										);
			}
			
		}
		return $result;
	}


	public function downloadmedicalPdf_visitdate($post_data)
	{

		/*if(file_exists(FCPATH . "public/uploads/MedicalReport.pdf"))
		{
			unlink(FCPATH . "public/uploads/MedicalReport.pdf");
		}*/
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '')	
		{		
			$this->load->model('api/InstitutionManagementModel');  
			$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
			$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
			$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : ''; 
			$post_data["op_number"] = $post_data["patient_id"]; 
			$institution      = $this->InstitutionManagementModel->listInstitution($post_data);
			$pateint_details	= $this->getPatientDetails($post_data);	
			if($pateint_details["status"]=="Success"){
			$post_data["patient_uni_id"]=$pateint_details["data"]->OP_REGISTRATION_ID;

			if($post_data["patient_uni_id"]>0){
				$visit_details	= $this->getvisit_visitdate($post_data);	
			}
			$data["institution"] = $institution;
			$data["pateint_details"] = $pateint_details;
			$data["visit_details"] = $visit_details;
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->pdf;
			$html = $this->load->view('export/medicalPdf.php', $data, true);
			$time=time();
			$pdfFilePath = FCPATH . "public/uploads/MedicalReport".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "F");
			
			 $result = array("status"=> "Success", "data"=> "public/uploads/MedicalReport".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf","filename" => "MedicalReport".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf" );
			}
			else{
				$result 	= array('data'=>"",
										'status'=>'Failed',
										'message'=>'Invalid Patient Number',

										);
			}
			
		}
		return $result;
	}



	public function getPatientConsent($visit_id,$type)
 	{
 			
	 			$this->db->where("PV.VISIT_ID",$visit_id);
		 		$this->db->where("CONSENT_TYPE",$type);	
				$this->db->select("PV.*");
				$this->db->from("PATIENT_CONSENT PV");
				$this->db->order_by("PV.PATIENT_CONSENT_ID","desc");
				$query = $this->db->get();
				if($query->num_rows() > 0)
				{
					$data = $query->row_array();
					$result 	= array('data'=>$data,"sign_path"=>base_url().CONSENT_FILE_PATH,
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
					$data = $query->row_array();
					$result 	= array('data'=>$data,"sign_path"=>base_url().CONSENT_FILE_PATH,
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



} 
?>