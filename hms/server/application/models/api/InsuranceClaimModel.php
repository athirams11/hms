<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InsuranceClaimModel extends CI_Model 
{


	function getPatientCptRate($post_data = array())
	{
		
		//print_r($post_data);
		if ($post_data["cpt_code_ids"])
		{
			
			$cpt_code_ids = $post_data["cpt_code_ids"];
			$insurance_tpa_id = (int)$post_data["insurance_tpa_id"];
			$insurance_network_id = (int)$post_data["insurance_network_id"];
			$this->db->start_cache();
			$this->db->select("C.CPT_RATE as rate,C.CURRENT_PROCEDURAL_CODE_ID as cpt_code_id");
			$this->db->from("CPT_RATE C");
			
			
			$this->db->where_in("CURRENT_PROCEDURAL_CODE_ID",$cpt_code_ids);
			$this->db->where("TPA_ID",$insurance_tpa_id);
			$this->db->where("NETWORK_ID",$insurance_network_id);
			$this->db->where("STATUS",1);
			
			$this->db->order_by("CURRENT_PROCEDURAL_CODE_ID","asc");
			
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
	public function GetBillverificationDetails($post_data = array())
	{
		$result 	= array('data'=>"",'status'=>'Failed');
		if(!empty($post_data))
		{
			if (isset($post_data["billing_id"]) &&  $post_data["billing_id"] > 0)
			{
				$this->db->start_cache();
				$this->db->select("B.BILLING_ID AS BILLING_ID,
								OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,
								IFNULL(OP.FIRST_NAME,BV.NAME) AS NAME,
								OP.MIDDLE_NAME AS MIDDLE_NAME,
								OP.LAST_NAME AS LAST_NAME,
								IP.INSURANCE_PAYERS_NAME AS INSURANCE_PAYERS_NAME,
								IP.INSURANCE_PAYERS_ECLAIM_LINK_ID AS INSURANCE_PAYERS_CODE,
								IN.INS_NETWORK_CODE AS INSURANCE_NETWORK,
								IN.INS_NETWORK_NAME AS INS_NETWORK_NAME,
								TPA.TPA_NAME as TPA_NAME,
								IFNULL(INS.OP_INS_MEMBER_ID,BV.MEMBER_ID) AS MEMBER_ID,
								IFNULL(INS.OP_INS_POLICY_NO,BV.POLICY_NO) AS POLICY_NO,
								B.INSURED_AMOUNT AS INSURED_AMOUNT,
								BV.VERIFICATION_STATUS AS VERIFICATION_STATUS");
				$this->db->from("BILLING B");
				$this->db->join("OP_REGISTRATION OP","B.PATIENT_ID = OP.OP_REGISTRATION_ID ","left");	
				$this->db->join("OP_INS_DETAILS INS","INS.OP_REGISTRATION_ID = OP.OP_REGISTRATION_ID ","left");	
				$this->db->join("INSURANCE_PAYERS IP","INS.OP_INS_PAYER= IP.INSURANCE_PAYERS_ID ","left");	
				$this->db->join("TPA TPA","TPA.TPA_ID = INS.OP_INS_TPA","left");
				$this->db->join("INS_NETWORK IN","IN.INS_NETWORK_ID = INS.OP_INS_NETWORK","left");	
				$this->db->join("BILL_VERIFICATION BV","BV.BILLING_ID = B.BILLING_ID","left");
				$this->db->join("BILLING_DETAILS BD","BV.BILLING_ID = BD.BILLING_ID","left");
				$this->db->where("B.BILLING_ID",$post_data["billing_id"]);
				$query = $this->db->get();
				//print_r($query);
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{

					$bill_data = $query->row_array();
					$result = array("status"=> "Success", "data"=> $bill_data);
				}
				
			}
		}
		return $result;
	}
	public function getPatientDetails($post_data = array())
 	{
 		if (!empty($post_data) && (isset($post_data["op_number"]) || isset($post_data["patient_id"])) )
 		{
 			if($post_data["op_number"])
	 			$this->db->where("OP_REGISTRATION_NUMBER",$post_data["op_number"]);	
	 		if($post_data["patient_id"])
	 			$this->db->where("OP_REGISTRATION_ID",$post_data["patient_id"]);	
	 		$this->db->where("OP_REGISTRATION_STATUS",1);	
			$this->db->select("OP.*, RT.OPTIONS_NAME as PAY_TYPE, C.COUNTRY_NAME as COUNTRY_NAME, C.COUNTRY_NAME as NATIONALITY_NAME,");
			$this->db->from("OP_REGISTRATION OP");
			$this->db->join("OPTIONS RT","RT.OPTIONS_ID = OP.OP_REGISTRATION_TYPE","left");
			$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.COUNTRY","left");
			$this->db->join("COUNTRY N","N.COUNTRY_ID = OP.NATIONALITY","left");
			//$this->db->join("OP_INS_DETAILS INS","INS.OP_REGISTRATION_ID = OP.OP_REGISTRATION_ID AND OP_REGISTRATION_TYPE = 1","left");
			//$this->db->join("OPTIONS SI","SI.OPTIONS_ID = OP.SOURCE_OF_INFO","left");
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
				$result 	= $data;
				return $result;		
		
			}
			else 
			{
				$result 	= array();
				return $result;
			}
 		}
 		else
 		{
 			$result 	= array();
				return $result;
 		}

 	}
 	public function getInsDetails($OP_REGISTRATION_ID =0)
 	{
 		if($OP_REGISTRATION_ID != 0)
 		{
 			$this->db->where("IN.OP_REGISTRATION_ID",$OP_REGISTRATION_ID);	
 			$this->db->where("IN.OP_INS_STATUS",1);	
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

	public function updateInsuranceDetails($post_data = array())
	{
		$OP_REGISTRATION_ID = trim($post_data["OP_REGISTRATION_ID"]);
		$INS_DATA = $post_data["INS_DATA"];
		$CO_IN_DATA = $post_data["CO_IN_DATA"];
		
		//print_r($post_data);
		//exit;
		
		
		
		$result = array("status"=> "Failed", "message"=> "Insurance details update failed");
		if ($OP_REGISTRATION_ID> 0)
		{
			$this->db->start_cache();
				$this->db->where("OP_REGISTRATION_ID",$OP_REGISTRATION_ID);
				$this->db->update("OP_INS_DETAILS",array("OP_INS_STATUS"=>0));
			$this->db->stop_cache();
			$this->db->flush_cache();
			unset($INS_DATA["OP_INS_DETAILS_ID"]);
			unset($INS_DATA["OP_INS_STATUS"]);
			unset($INS_DATA["CREATED_DATE"]);
			unset($INS_DATA["UPDATED_DATE"]);

			$this->db->insert("OP_INS_DETAILS",$INS_DATA);
			$ins_id = $this->db->insert_id();

			
			$this->db->start_cache();
				$this->db->where("OP_REGISTRATION_ID",$OP_REGISTRATION_ID);
				$this->db->update("OP_COIN_DATA",array("OP_COIN_DATA_STATUS"=>0));
			$this->db->stop_cache();
			$this->db->flush_cache();


			if($post_data['OP_INS_IS_ALL'] == false)
			{
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
			$this->db->insert_batch('OP_COIN_DATA', $coin_arr); 

			$result = array("status"=> "Success", "message"=> "Insurance details updated")	;
		}
		return $result;
	}
	function generateSubmissionFile($post_data = array())
	{
		//print_r($post_data);exit;
		$result = array("status"=> "Failed", "message"=> "Failed to generate submission file");
		if(!empty($post_data))
		{
			$post_data["file_name"] = isset($post_data["file_name"]) ? $post_data["file_name"] : '';
           	$post_data["bill_ids"] = isset($post_data["bill_ids"]) ? $post_data["bill_ids"] : array();
           	$post_data["user_id"] = isset($post_data["user_id"]) ? $post_data["user_id"] : 0;
           	$post_data["tpa_id"] = isset($post_data["tpa_id"]) ? $post_data["tpa_id"] : 0;
           	$post_data["tpa_code"] = isset($post_data["tpa_code"]) ? $post_data["tpa_code"] : 0;
			$bill_ids = array_filter($post_data["bill_ids"]);
			$file_name =  $post_data["file_name"];
			/*if($file_name == "")
			{
				$result = array("status"=> "Failed", "message"=> "Please enter a valid file name");
				return $result;
			}
			if(empty($post_data["bill_ids"]))
			{
				$result = array("status"=> "Failed", "message"=> "Please select Bills to generate Submission File");
				return $result;
			}
			if($post_data["tpa_id"] == 0)
			{
				$result = array("status"=> "Failed", "message"=> "Please select bills with provider for submission file generation");
				return $result;
			}*/
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			//print_r($ext);
			if($ext!='xml')
			{
				$file_name = $file_name.'.xml';
			}
		

			//exit;
			$data=array(
			//"VISIT_DATE" 		=> trim($post_data["date"]),
			//"VISIT_TIME" 		=> trim($post_data["time"]),
			"XML_TYPE" 				=> CLAIM_XML_SUBMISSION,
			//"DESCRIPTION" 			=> trim($post_data["payment_type"]),
			"FILE_NAME"				=> $file_name,
			"SUBMISSION_STATUS" 	=> CLAIM_CREATED,
			"SENDER_ID" 			=> getInstitutionSettings()->INSTITUTION_DHPO_ID,
			"TPA_ID" 				=> trim($post_data["tpa_id"]),
			"RECEIVER_ID" 			=> trim($post_data["tpa_code"]),
			"TRANSACTION_DATE" 		=> date('Y-m-d H:i:s'),
			"RECORD_COUNT" 			=> count($bill_ids),
			"DISPOSITION_FLAG" 		=> CLAIM_DISPOSITION_FLAG,
			"CREATED_BY" 			=> trim($post_data["user_id"]),
			"CREATED_DATE" 			=> date("Y-m-d H:i:s"),
			"CLIENT_DATE" 			=> format_date($post_data["client_date"])	
			
			);
		
				//$gen_no=$this->GenerateOpNo($post_data);
				//$gen_no=$this->GenerateBillNo($post_data);
				//$data["BILLING_INVOICE_NUMBER"]=$gen_no["data"];
				$this->db->insert("SUBMISSION_FILE",$data);
				$data_id = $this->db->insert_id();
				if((int) $data_id > 0)
				{	

					$this->addClaimDetials($bill_ids,$data_id);
					$file_name = $this->generateSubmissionXml($data_id);
					//$filename =  $post_data["file_name"];
					$result = array("status"=> "Success","file_name"=>$file_name,"submission_file_id"=>$data_id, "message"=>"Submission File generated successfully");
				}
			}
			return $result;
	}
	function reGenerateSubmissionXml($post_data=array())
	{
		$result = array("status"=> "Failed", "message"=> "Failed to generate file");
		if(!empty($post_data))
		{
			$file_name = $this->generateSubmissionXml($post_data["submissin_file_id"]);
			$result = array("status"=> "Success","file_name"=>$file_name, "message"=>"Submission File generated successfully");
		}
		return $result;

	}
	function generateSubmissionXml($submission_file_id=0)
	{
		
		$file_name = "";
		$cpt_code_ids = $post_data["cpt_code_ids"];
		$price_list = $post_data["price"];
		$co_payments = $post_data["co_payment"];
		$co_payments_type = $post_data["co_payment_type"];
		$patient_pay_list = $post_data["patient_pay"];
		$ins_amount_list = $post_data["total"];
		if($submission_file_id > 0)
		{
			$xml_array = array();
			$FileName = $this->getFileName($submission_file_id);
			$Header = $this->getXmlHeader($submission_file_id);
			$Claims = $this->getXmlClaims($submission_file_id);
			$xml_array["Header"] = $Header;
			$xml_array["Claim"] = $Claims;
			$xml_array_con = mapSubmissionArray($xml_array);
			//creating object of SimpleXMLElement
			//print_r($xml_array_con);
			$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><Claim.Submission xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"></Claim.Submission>");

			//function call to convert array to xml
			array_to_xml($xml_array_con,$xml_user_info);

			//saving generated xml file
			$xml_file = $xml_user_info->asXML();//$xml_user_info->asXML('users.xml');
			$xml_file = str_replace("<Claim><Claim>", "<Claim>", $xml_file);
			$xml_file = str_replace("</Claim></Claim>", "</Claim>", $xml_file);
			
			$xml_file = str_replace("<Diagnosis><Diagnosis>", "<Diagnosis>", $xml_file);
			$xml_file = str_replace("</Diagnosis></Diagnosis>", "</Diagnosis>", $xml_file);

			$xml_file = str_replace("<Activity><Activity>", "<Activity>", $xml_file);
			$xml_file = str_replace("</Activity></Activity>", "</Activity>", $xml_file);

			$xml_file = str_replace("<Observation><Observation>", "<Observation>", $xml_file);
			$xml_file = str_replace("</Observation></Observation>", "</Observation>", $xml_file);
			//echo $xml_file;
			//exit;
			//success and error message based on xml creation
			$tpa = $this->getTpaDetails($submission_file_id);
			if($xml_file){
				$path = SUBMISSION_FILE_PATH;
				if(!file_exists($path))
		        {        	   
		            mkdir(FCPATH.$path);
		            if (!is_dir($path)) mkdir($path, 0777, true);                           
		            chmod($path,0777);                    
		        }
		        if(is_writable($path))
		        {
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
	
	
	public function getFileName($submissin_file_id = 0)
	{

		$this->db->select("FILE_NAME")
			->from("SUBMISSION_FILE")
			->where("SUBMISSION_FILE_ID",$submissin_file_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getXmlHeader($submissin_file_id = 0)
	{
		$this->db->select("SENDER_ID,RECEIVER_ID,DATE_FORMAT(TRANSACTION_DATE,'%d/%m/%Y %H:%i') as TRANSACTION_DATE,RECORD_COUNT,DISPOSITION_FLAG")
			->from("SUBMISSION_FILE")
			->where("SUBMISSION_FILE_ID",$submissin_file_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	// public function getXmlHeader($submissin_file_id = 0)
	// {
	// 	$this->db->select("SENDER_ID,RECEIVER_ID,DATE_FORMAT(now(),'%d/%c/%Y %H:%i') as TRANSACTION_DATE,RECORD_COUNT,DISPOSITION_FLAG")
	// 		->from("SUBMISSION_FILE")
	// 		->where("SUBMISSION_FILE_ID",$submissin_file_id);
	// 	$query = $this->db->get();
	// 	return $query->row_array();
	// }
	public function getXmlClaims($submissin_file_id = 0)
	{
		$this->db->select("BILL_ID, CLAIM_ID, PATIENT_INS_NO, PAYER_ID, PROVIDER_ID, EMIRITES_ID_NUMBER, GROSS_AMOUNT, PATIENT_SHARE, NET_AMOUNT")
			->from("SUBMISSION_CLAIM")
			->where("SUBMISSION_ID",$submissin_file_id);
		$query = $this->db->get();
		$result = array();
		foreach ($query->result_array() as $key => $value) {
			$BILL_ID = $value["BILL_ID"];
			unset($value['BILL_ID']);
			$result[$key] = $value;
			$result[$key]["Encounter"] = $this->getXmlAssessmentFromBill($BILL_ID);
			//$result[$key]["Encounter"]["END_TIME"] = "";
			$result[$key]["Encounter"]["TRANSFER_SOURCE"] = "";
			$result[$key]["Encounter"]["TRANSFER_DESTINATION"] = "";
			$result[$key]["Diagnosis"] = $this->getXmlDiagnosisFromBill($BILL_ID);
			$result[$key]["Activity"] = $this->getXmlProceduresFromBill($BILL_ID);
		}
		return $result;
	}
	public function getXmlAssessmentFromBill($bill_id = 0)
	{

		//$this->db->select(" '".CLAIM_SENDER_ID."' as FACILITY_ID, 1 as TYPE , OP_REGISTRATION_NUMBER, DATE_FORMAT(CREATED_TIME,'%d/%c/%Y %H:%i') as CREATED_TIME, DATE_FORMAT(B.BILLING_DATE,'%d/%c/%Y %H:%i') as END_TIME, 1 as START_TYPE ")
	/*	$this->db->select(" '".CLAIM_SENDER_ID."' as FACILITY_ID, 1 as TYPE , OP_REGISTRATION_NUMBER, DATE_FORMAT(CREATED_TIME,'%d/%c/%Y %H:%i') as CREATED_TIME, DATE_FORMAT(B.BILLING_DATE,'%d/%c/%Y %H:%i') as END_TIME, 1 as START_TYPE ")*/
		$this->db->select(" '".getInstitutionSettings()->INSTITUTION_DHPO_ID."' as FACILITY_ID, 1 as TYPE , OP_REGISTRATION_NUMBER, DATE_FORMAT(CREATED_TIME,'%d/%c/%Y %H:%i') as CREATED_TIME, DATE_FORMAT(B.BILLING_DATE,'%d/%c/%Y %H:%i') as END_TIME, 1 as START_TYPE ")
			->from("BILLING B")
			->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","LEFT")
			->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = N.PATIENT_ID","LEFT")
			->where("BILLING_ID",$bill_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getXmlDiagnosisFromBill($bill_id = 0)
	{
		$this->db->select(" M.DATA as TYPE, D.CODE ")
			->from("BILLING B")
			->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","LEFT")
			->join("PATIENT_DIAGNOSIS P","P.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID","LEFT")
			->join("PATIENT_DIAGNOSIS_DETAILS PD","PD.PATIENT_DIAGNOSIS_ID = P.PATIENT_DIAGNOSIS_ID","LEFT")
			->join("DIAGNOSIS_MASTER D","D.DIAGNOSIS_ID = PD.DIAGNOSIS_ID","LEFT")
			->join("MASTER_DATA M","M.MASTER_DATA_ID = PD.DIAGNOSIS_LEVEL_ID","LEFT")
			->where("BILLING_ID",$bill_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getXmlProceduresFromBill($bill_id = 0)
	{

		$this->db->select("LD.LAB_INVESTIGATION_DETAILS_ID, DATE_FORMAT(L.DATE_LOG,'%d/%c/%Y %H:%i') as DATE_LOG,   CPTG.CPT_GROUP_CODE as TYPE, PROCEDURE_CODE, LD.QUANTITY, BD.INSURED_AMOUNT as NET, D.DOCTORS_LISCENCE_NO as DOCTOR, BD.PRIOR_AUTHORIZATION")
			->from("BILLING B")
			->join("NURSING_ASSESSMENT N","N.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","LEFT")
			->join("LAB_INVESTIGATION L","L.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID","LEFT")
			->join("BILLING_DETAILS BD","BD.BILLING_ID = B.BILLING_ID","LEFT")
			->join("LAB_INVESTIGATION_DETAILS LD","LD.LAB_INVESTIGATION_DETAILS_ID = BD.LAB_INVESTIGATION_DETAILS_ID","LEFT")
			->join("DOCTORS D","D.LOGIN_ID = L.USER_ID","LEFT")
			->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CPT.CURRENT_PROCEDURAL_CODE_ID = LD.CURRENT_PROCEDURAL_CODE_ID","LEFT")
			->join("CPT_GROUP CPTG","CPTG.CPT_GROUP_ID = CPT.CPT_GROUP_ID","LEFT")
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
					if($ck["Code"]){
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
	public function addClaimDetials($bill_ids = array(),$submissin_file_id = 0)
	{
		
		$bill_data = $this->getBilldetialsFromIds($bill_ids);
		if(!empty($bill_data) && $submissin_file_id > 0)
		{	$this->db->start_cache();
				$this->db->where("SUBMISSION_ID",$submissin_file_id);
				$this->db->delete("SUBMISSION_CLAIM");
			$this->db->stop_cache();
			$this->db->flush_cache();

			foreach ($bill_data as $key => $value) {
				$patient = $this->getPatientDetails(array("patient_id"=>$value["PATIENT_ID"]));
				$payer = $this->getPayerDetails($patient["ins_data"]->OP_INS_PAYER);
				//$claim_id = $patient["ins_data"]->OP_INS_PAYER);
				$data = array(
						"SUBMISSION_ID" => $submissin_file_id,
						
						"CLAIM_ID_PAYER" => "",
						"PATIENT_ID" => $value["PATIENT_ID"],
						"PATIENT_INS_NO" => $patient["ins_data"]->OP_INS_MEMBER_ID,
						"INS_PAYER_ID" => $patient["ins_data"]->OP_INS_PAYER,
						"PAYER_ID" => $payer["INSURANCE_PAYERS_ECLAIM_LINK_ID"],
						"PROVIDER_ID" => CLAIM_SENDER_ID,
						"EMIRITES_ID_NUMBER" => $patient["patient_data"]->NATIONAL_ID,
						"GROSS_AMOUNT" => $value["BILLED_AMOUNT"],
						"PATIENT_SHARE" => $value["PAID_BY_PATIENT"],
						"NET_AMOUNT" => $value["INSURED_AMOUNT"],
						"BILL_ID" => $value["BILLING_ID"],
				);

				$this->db->insert("SUBMISSION_CLAIM",$data);
				$id = $this->db->insert_id();
				$this->db->where(array("SUBMISSION_CLAIM_ID" => $id));
				$this->db->update("SUBMISSION_CLAIM",array("CLAIM_ID" => $payer["INSURANCE_PAYERS_ECLAIM_LINK_ID"].date('YmdH').sprintf('%04d', $id)));
			}
		}
	}
	public function getBilldetialsFromIds($ids = array())
	{
		$result 	= array();
		$bill_data 	= array();
		if(!empty($ids))
		{
			$ids = array_filter($ids);
			//if (isset($post_data["billing_id"]) &&  $post_data["billing_id"] > 0)
			//{
				$this->db->start_cache();
				$this->db->select("B.*");
				$this->db->from("BILLING B");
				$this->db->where_in("BILLING_ID",$ids);
				$query = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					foreach ($query->result_array() as $key => $value) {
						# code...
						$bill_data[$value["BILLING_ID"]] = $value;
						$bill_data[$value["BILLING_ID"]]["bill_details"] = $this->getBillDetails($value["BILLING_ID"]);
					}
					$result = $bill_data;
					
				}
			//}
		}
		return $result;
	}
		
	public function getPayerDetails($payer_id=0)
	{
		$result = array();
		if($payer_id > 0)
		{
			
			$this->db->start_cache();
			$this->db->select("B.*");
			$this->db->from("INSURANCE_PAYERS B");
			$this->db->where("INSURANCE_PAYERS_ID",$payer_id);
			$query = $this->db->get();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = $query->row_array();				
			}
			
		}
		return $result;
	}
		
	public function getBill($post_data = array())
	{
		$result 	= array('data'=>"",
									'status'=>'Failed',
									);
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
					$bill_data["bill_details"] = $this->getBillDetails($post_data["billing_id"]);
					$result = array("status"=> "Success", "data"=> $bill_data);
					
				}
			}
		}
		return $result;
	}
	public function getBillByAssessment($post_data = array())
	{
		$result 	= array('data'=>"",
									'status'=>'Failed',
									);
		if(!empty($post_data))
		{
			if (isset($post_data["assessment_id"]) &&  $post_data["assessment_id"] > 0)
			{
				$this->db->start_cache();
				$this->db->select("B.*");
				$this->db->from("BILLING B");
				$this->db->where("ASSESSMENT_ID",$post_data["assessment_id"]);
				$query = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$bill_data = $query->row_array();
					$bill_data["bill_details"] = $this->getBillDetails($bill_data["BILLING_ID"]);
					$result = array("status"=> "Success", "data"=> $bill_data);
					
				}
			}
		}
		return $result;
	}
	public function getBillDetails($billing_id)
	{
		$result = array();
		if ($billing_id > 0)
			{
				$this->db->start_cache();
				$this->db->select("B.*,LID.*");
				$this->db->from("BILLING_DETAILS B");
				$this->db->join("LAB_INVESTIGATION_DETAILS LID","LID.LAB_INVESTIGATION_DETAILS_ID = B.LAB_INVESTIGATION_DETAILS_ID","left");
				$this->db->where("BILLING_ID",$billing_id);
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
	public function assessmentListByDate($post_data = array())
	{

		$result = array("status"=> "Failed", "data"=> array());
		if(!empty($post_data))
		{
			if ($post_data["dateVal"] != "")
			{
				$this->db->start_cache();
		
				$this->db->where("P.VISIT_DATE",format_date($post_data["dateVal"],1));	
				$this->db->where("NA.STAT",1);	
				$this->db->select("B.*,NA.*,OP.*,NA.START_TIME,P.VISIT_DATE,OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,C.COUNTRY_ISO3,C.COUNTRY_NAME,SP.OPTIONS_NAME as DEPARTMENT_NAME");
				$this->db->from("NURSING_ASSESSMENT NA");
				$this->db->join("PATIENT_VISIT_LIST P","NA.VISIT_ID = P.PATIENT_VISIT_LIST_ID","left");
				 $this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = NA.PATIENT_ID","left");	
				 $this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");	
				 $this->db->join("OPTIONS SP","SP.OPTIONS_ID = P.DEPARTMENT_ID AND SP.OPTIONS_TYPE = 8","left");	
				 
				 $this->db->join("BILLING B","NA.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","left");
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
	public function getTpaDetails($submission_file_id=0)
	{
		$result = array();
		if($submission_file_id > 0)
		{
			
			$this->db->start_cache();
			$this->db->select("B.*");
			$this->db->from("SUBMISSION_FILE A");
			$this->db->join("TPA B","B.TPA_ID = A.TPA_ID","left");
			$this->db->where("SUBMISSION_FILE_ID",$submission_file_id);
			$query = $this->db->get();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = $query->row_array();				
			}
			
		}
		return $result;
	}

	public function submissionFileList($post_data = array())
	{
		$result = array("status"=> "Failed", "data"=> array());		 
		$this->db->start_cache();
			if($post_data["search_text"] != '')
			{
				$this->db->group_start();
					$this->db->like("B.FILE_NAME",$post_data["search_text"]);
					$this->db->or_like("B.RECEIVER_ID",$post_data["search_text"]);
					$this->db->or_like("B.RECORD_COUNT",$post_data["search_text"]);
					$this->db->or_like("B.DISPOSITION_FLAG",$post_data["search_text"]);			
				$this->db->group_end();
				
			}	
		
			if ($post_data["created_date_to"] != "")
			{
				$this->db->where("CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."') <= ",format_date($post_data["created_date_to"],1));	
			}
			
			if ($post_data["created_date_from"] != "")
			{
				$this->db->where("CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."') >= ",format_date($post_data["created_date_from"],2));	
			}
			$this->db->from('SUBMISSION_FILE B');
			$count =  $this->db->count_all_results();
		$this->db->stop_cache();
		$this->db->flush_cache();

			$this->db->start_cache();
			if($post_data["search_text"] != '')
			{
				$this->db->group_start();
					$this->db->like("B.FILE_NAME",$post_data["search_text"]);
					$this->db->or_like("B.RECEIVER_ID",$post_data["search_text"]);
					$this->db->or_like("B.RECORD_COUNT",$post_data["search_text"]);
					$this->db->or_like("B.DISPOSITION_FLAG",$post_data["search_text"]);			
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
		
			if ($post_data["created_date_to"] != "")
			{
				$this->db->where("CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."') <= ",format_date($post_data["created_date_to"],1));	
			}
			
			if ($post_data["created_date_from"] != "")
			{
				$this->db->where("CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."') >= ",format_date($post_data["created_date_from"],2));	
			}
			
			//$this->db->select("NA.NURSING_ASSESSMENT_ID,OP.*,B.*,NA.*,INS.*");
			$this->db->select("B.*,IP.TPA_NAME,U.FIRSTNAME,U.LASTNAME,SU.SUBMISSION_UPLOAD_STATUS_CODE");
			$this->db->from("SUBMISSION_FILE B");						
			$this->db->join("TPA IP","IP.TPA_ID = B.TPA_ID","left");
			$this->db->join("USERS U","U.USER_SPK = B.CREATED_BY","left");
			$this->db->join("SUBMISSION_UPLOAD SU","B.SUBMISSION_FILE_ID = SU.SUBMISSION_FILE_ID AND SU.SUBMISSION_UPLOAD_ID = (SELECT MAX(SUBMISSION_UPLOAD_ID) FROM SUBMISSION_UPLOAD WHERE SUBMISSION_FILE_ID = SU.SUBMISSION_FILE_ID)","left");
			//$this->db->where("");
			$this->db->order_by("B.CREATED_DATE","DESC");
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = array("status"=> "Success", "data"=> $query->result());
				$result["count"] = $count;
				$result["path"] = base_url().SUBMISSION_FILE_PATH;
				return $result;
			}				
		return $result;		
	}
	public function nonClaimedinvoiceList_count($post_data = array())
	{
		$result = 0;		 
			$this->db->start_cache();
			if($post_data["search_text"] != '')
			{
				$this->db->group_start();
					$this->db->like("NA.NURSING_ASSESSMENT_ID",$post_data["search_text"]);
					$this->db->or_like("OP.FIRST_NAME",$post_data["search_text"]);
					$this->db->or_like("OP.MIDDLE_NAME",$post_data["search_text"]);
					$this->db->or_like("OP.LAST_NAME",$post_data["search_text"]);			
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
		
			if ($post_data["invoice_date_to"] != "")
			{
				$this->db->where("CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."') <= ",format_date($post_data["invoice_date_to"],2));	
			}
			
			if ($post_data["invoice_date_from"] != "")
			{
				$this->db->where("CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."') >= ",format_date($post_data["invoice_date_from"],1));	
			}
			if($post_data["insurance_payer_id"]>0){
				$this->db->where("INS.OP_INS_PAYER",$post_data["insurance_payer_id"]);
			}
				
			if($post_data["insurance_network_id"]>0){
				$this->db->where("INS.OP_INS_NETWORK",$post_data["insurance_network_id"]);
			}
									
			
			//$this->db->select("NA.NURSING_ASSESSMENT_ID,OP.*,B.*,NA.*,INS.*");
			$this->db->select(" NA.NURSING_ASSESSMENT_ID,
							    B.BILLING_ID,
							    INS.OP_INS_DETAILS_ID,
							    OP_REGISTRATION_NUMBER,
							    FIRST_NAME,
							    MIDDLE_NAME,
							    LAST_NAME,
							    DOCTOR_NAME,
							    BILLING_INVOICE_NUMBER,
							    BILLING_DATE,
							    INSURANCE_PAYERS_NAME,
							    INS_NETWORK_NAME,
							    OP_INS_MEMBER_ID,
							    STAT,
							    BILLED_AMOUNT,
							    PAID_BY_PATIENT,
							    INSURED_AMOUNT,
							    OP_INS_PAYER,
							    INS_NETWORK_CODE,
							    OP_INS_NETWORK,
							    OP_INS_MEMBER_ID,
							    OP_INS_POLICY_NO,
							    INSURANCE_PAYERS_ECLAIM_LINK_ID,
							    INSURANCE_PAYERS_ID,
							    INS.OP_INS_TPA,
							    TPA.TPA_ECLAIM_LINK_ID as TPA_CODE,
							    B.PATIENT_ID,
							    BV.VERIFICATION_STATUS,
							    TPA.TPA_NAME
							    ");
			$this->db->from("BILLING B");						
			$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = B.PATIENT_ID","left");
			//$this->db->join("OP_INS_DETAILS INS","INS.OP_REGISTRATION_ID = B.PATIENT_ID","left");							
			// $this->db->join("(
			// 		              SELECT    MAX(OP_INS_DETAILS.OP_INS_DETAILS_ID) OP_LATEST_INS_DETAILS_ID, OP_INS_DETAILS.*,T.TPA_ECLAIM_LINK_ID 
			// 		              FROM      OP_INS_DETAILS left join TPA T on  T.TPA_ID = OP_INS_DETAILS.OP_INS_TPA WHERE OP_INS_DETAILS.OP_INS_STATUS = 1
			// 		              GROUP BY  OP_REGISTRATION_ID
			// 		          )  INS","INS.OP_REGISTRATION_ID = B.PATIENT_ID","left");							
			$this->db->join("OP_INS_DETAILS INS","INS.OP_INS_DETAILS_ID = B.PATIENT_TYPE_DETAIL_ID AND B.PATIENT_ID = INS.OP_REGISTRATION_ID","left");	
			$this->db->join("INSURANCE_PAYERS IP","IP.INSURANCE_PAYERS_ID = INS.OP_INS_PAYER","left");
			$this->db->join("TPA TPA","TPA.TPA_ID = INS.OP_INS_TPA","left");
			$this->db->join("INS_NETWORK IN","IN.INS_NETWORK_ID = INS.OP_INS_NETWORK","left");
			$this->db->join("NURSING_ASSESSMENT NA","NA.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","left");
			$this->db->join("PATIENT_VISIT_LIST V","NA.VISIT_ID = V.PATIENT_VISIT_LIST_ID","left");	
			$this->db->join("SUBMISSION_CLAIM C","C.BILL_ID = B.BILLING_ID","left");	
			$this->db->join("BILL_VERIFICATION BV","BV.BILLING_ID = B.BILLING_ID","left");	
			$this->db->where("NA.STAT",1);	
			$this->db->where("C.SUBMISSION_CLAIM_ID is null");	
			$this->db->where("B.INSURED_AMOUNT> 0");	
			$this->db->where("B.PATIENT_TYPE",INSURANCE_ID_CONSTANT);
			$this->db->where("B.BILL_TYPE",1);
			$this->db->where("B.GENERATED",1);
			$this->db->order_by("B.BILLING_ID","DESC");	
			$result = $this->db->count_all_results();
			//echo $this->db->last_query();exit;
			
			$this->db->stop_cache();
			$this->db->flush_cache();
						
		return $result;	
	}
	public function nonClaimedinvoiceList($post_data = array())
	{
		$result = array("status"=> "Failed", "data"=> array());		 
			$this->db->start_cache();
			if($post_data["search_text"] != '')
			{
				$this->db->group_start();
					$this->db->like("NA.NURSING_ASSESSMENT_ID",$post_data["search_text"]);
					$this->db->or_like("OP.FIRST_NAME",$post_data["search_text"]);
					$this->db->or_like("OP.MIDDLE_NAME",$post_data["search_text"]);
					$this->db->or_like("OP.LAST_NAME",$post_data["search_text"]);			
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
		
			if ($post_data["invoice_date_to"] != "")
			{
				$this->db->where("CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."') <= ",format_date($post_data["invoice_date_to"],2));	
			}
			
			if ($post_data["invoice_date_from"] != "")
			{
				$this->db->where("CONVERT_TZ(B.BILLING_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."') >= ",format_date($post_data["invoice_date_from"],1));	
			}
			
			if($post_data["insurance_payer_id"]>0){
				$this->db->where("INS.OP_INS_PAYER",$post_data["insurance_payer_id"]);
			}
			if($post_data["insurance_tpa_id"]>0){
				$this->db->where("TPA.TPA_ID",$post_data["insurance_tpa_id"]);
			}
				
			if($post_data["insurance_network_id"]>0){
				$this->db->where("INS.OP_INS_NETWORK",$post_data["insurance_network_id"]);
			}
									
			
			//$this->db->select("NA.NURSING_ASSESSMENT_ID,OP.*,B.*,NA.*,INS.*");
			$this->db->select(" NA.NURSING_ASSESSMENT_ID,
							    B.BILLING_ID,
							    INS.OP_INS_DETAILS_ID,
							    OP_REGISTRATION_NUMBER,
							    FIRST_NAME,
							    MIDDLE_NAME,
							    LAST_NAME,
							    DOCTOR_NAME,
							    BILLING_INVOICE_NUMBER,
							    BILLING_DATE,
							    INSURANCE_PAYERS_NAME,
							    INS_NETWORK_NAME,
							    OP_INS_MEMBER_ID,
							    STAT,
							    BILLED_AMOUNT,
							    PAID_BY_PATIENT,
							    INSURED_AMOUNT,
							    OP_INS_PAYER,
							    INS_NETWORK_CODE,
							    OP_INS_NETWORK,
							    OP_INS_MEMBER_ID,
							    OP_INS_POLICY_NO,
							    INSURANCE_PAYERS_ECLAIM_LINK_ID,
							    INSURANCE_PAYERS_ID,
							    INS.OP_INS_TPA,
							    TPA.TPA_ECLAIM_LINK_ID as TPA_CODE,
							    B.PATIENT_ID,
							    BV.VERIFICATION_STATUS,
							    TPA.TPA_NAME,
								TPA.TPA_ID
							    ");
			$this->db->from("BILLING B");						
			$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = B.PATIENT_ID","left");
			//$this->db->join("OP_INS_DETAILS INS","INS.OP_REGISTRATION_ID = B.PATIENT_ID","left");							
			// $this->db->join("(
			// 		              SELECT    MAX(OP_INS_DETAILS.OP_INS_DETAILS_ID) OP_LATEST_INS_DETAILS_ID, OP_INS_DETAILS.*,T.TPA_ECLAIM_LINK_ID 
			// 		              FROM      OP_INS_DETAILS left join TPA T on  T.TPA_ID = OP_INS_DETAILS.OP_INS_TPA WHERE OP_INS_DETAILS.OP_INS_STATUS = 1
			// 		              GROUP BY  OP_REGISTRATION_ID
			// 		          )  INS","INS.OP_REGISTRATION_ID = B.PATIENT_ID","left");							
			$this->db->join("OP_INS_DETAILS INS","INS.OP_INS_DETAILS_ID = B.PATIENT_TYPE_DETAIL_ID AND B.PATIENT_ID = INS.OP_REGISTRATION_ID","left");	
			$this->db->join("INSURANCE_PAYERS IP","IP.INSURANCE_PAYERS_ID = INS.OP_INS_PAYER","left");
			$this->db->join("TPA TPA","TPA.TPA_ID = INS.OP_INS_TPA","left");
			$this->db->join("INS_NETWORK IN","IN.INS_NETWORK_ID = INS.OP_INS_NETWORK","left");
			$this->db->join("NURSING_ASSESSMENT NA","NA.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","left");
			$this->db->join("PATIENT_VISIT_LIST V","NA.VISIT_ID = V.PATIENT_VISIT_LIST_ID","left");	
			$this->db->join("SUBMISSION_CLAIM C","C.BILL_ID = B.BILLING_ID","left");	
			$this->db->join("BILL_VERIFICATION BV","BV.BILLING_ID = B.BILLING_ID","left");	
			$this->db->where("NA.STAT",1);	
			$this->db->where("C.SUBMISSION_CLAIM_ID is null");	
			$this->db->where("B.INSURED_AMOUNT> 0");	
			$this->db->where("B.PATIENT_TYPE",INSURANCE_ID_CONSTANT);
			$this->db->where("B.BILL_TYPE",1);
			$this->db->where("B.GENERATED",1);
			$this->db->order_by("B.BILLING_ID","DESC");	
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$count = $this->nonClaimedinvoiceList_count();
				$result = array("status"=> "Success", "count" =>$count,  "data"=> $query->result());
				return $result;
			}				
		return $result;		
	}


	public function updateBillverificationData($post_data)
	{
		//	print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
		$data=array(
			"BILLING_ID" 		=> trim($post_data["billing_id"]),
			"NAME" 				=> trim($post_data["name"]),
			"MEMBER_ID" 		=> trim($post_data["member_id"]),
			"POLICY_NO"			=> trim($post_data["policy_no"])
				);
		$this->db->where('BILLING_ID',$post_data["billing_id"]);
		$row = $this->db->get('BILL_VERIFICATION');
		if ( $row->num_rows() > 0 ) 
		{
			$this->db->start_cache();			
			$this->db->where("BILLING_ID",$post_data["billing_id"]);
			$this->db->update("BILL_VERIFICATION",$data);
			$this->db->stop_cache();
			$this->db->flush_cache();	
			$result = array("status"=> "Success", "bill_id"=>$post_data["billing_id"], "message"=> "Data inserted");
		}
		else
		{
			$this->db->start_cache();	
			$this->db->insert("BILL_VERIFICATION",$data);				
			$this->db->stop_cache();
			$this->db->flush_cache();
			//$data_id = $this->db->insert_id();
			$result = array("status"=> "Success", "bill_id"=>$post_data["billing_id"], "message"=> "Data updated");
			
		}

		return $result;
	}		
	public function confirmBillverificationData($post_data)
	{
		//	print_r($post_data);
		$result = array("status"=> "Failed", "data"=> array());		
		$data=array(
			"BILLING_ID" 			=> trim($post_data["billing_id"]),
			"NAME" 					=> trim($post_data["name"]),
			"MEMBER_ID" 			=> trim($post_data["member_id"]),
			"POLICY_NO"				=> trim($post_data["policy_no"]),
			"VERIFICATION_STATUS"	=> 1
				);
		$this->db->where('BILLING_ID',$post_data["billing_id"]);
   		$row = $this->db->get('BILL_VERIFICATION');
		if ($row->num_rows() > 0 ) 
		{
			$this->db->start_cache();			
			$this->db->where("BILLING_ID",$post_data["billing_id"]);
			$this->db->update("BILL_VERIFICATION",$data);
			$this->db->stop_cache();
			$this->db->flush_cache();
			$result = array("status"=> "Success", "bill_id"=>$post_data["billing_id"], "message"=> "Data inserted successfully");
		}
		else
		{
			$this->db->start_cache();	
			$this->db->insert("BILL_VERIFICATION",$data);				
			$this->db->stop_cache();
			$this->db->flush_cache();
			//$data_id = $this->db->insert_id();
			$result = array("status"=> "Success", "bill_id"=>$post_data["billing_id"], "message"=> "Data updated successfully");
			
		}
		return $result;
	}
	public function getFileContent($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());	
		if(!empty($post_data))
   		{	
			$this->db->where('SUBMISSION_FILE_ID',$post_data["file_id"]);
			$this->db->select('FILE_NAME');
			$this->db->from('SUBMISSION_FILE');
			$query = $this->db->get();
			$filename = $query->row()->FILE_NAME;
			$path =FCPATH.SUBMISSION_FILE_PATH.$filename;
			if(file_exists($path))
			{ 
				$file_content = read_file($path);
				$result = array("status"=> "Success","file_id"=>$post_data["file_id"],"file_name"=>$filename,       
				"file_content"=> $file_content);	
			}
			else
			{
				$result = array( "status"=> "Failed" ,'message' => 'No such a file or directory');
			}
		}
		return $result;
	}
	public function saveFileContent($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());	
		if(!empty($post_data))
   		{	
   			$file_content=$post_data["file_content"];
			$this->db->where('SUBMISSION_FILE_ID',$post_data["file_id"]);
			$this->db->select('FILE_NAME');
			$this->db->from('SUBMISSION_FILE');
			$query = $this->db->get();
			$file_name = $query->row()->FILE_NAME;
			$path =FCPATH.SUBMISSION_FILE_PATH.$file_name;
			if(write_file($path,$file_content))
			{
				$result= array("status"=> "Success", "file_id"=>$post_data["file_id"],'message' => 'File content saved successfully');
			}
		}
		return $result;
	}
	public function submittedFileList($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());		 	
			$this->db->start_cache();
			if($post_data["search_text"] != '')
			{
				$this->db->group_start();
					$this->db->like("SF.FILE_NAME",$post_data["search_text"]);
					$this->db->or_like("OP.FIRST_NAME",$post_data["search_text"]);
					$this->db->or_like("OP.MIDDLE_NAME",$post_data["search_text"]);
					$this->db->or_like("OP.LAST_NAME",$post_data["search_text"]);
					$this->db->or_like("SC.PAYER_ID",$post_data["search_text"]);			
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
		
			if ($post_data["submission_upload_from"] != "")
			{
				$this->db->where("CONVERT_TZ(DATE(SU.SUBMISSION_UPLOAD_DATE),'+00:00','".timezoneToOffset($post_data["timeZone"])."') >= ",format_date($post_data["submission_upload_from"],2));	
			}
			if ($post_data["submission_upload_to"] != "")
			{
				$this->db->where("CONVERT_TZ(DATE(SU.SUBMISSION_UPLOAD_DATE),'+00:00','".timezoneToOffset($post_data["timeZone"])."') <= ",format_date($post_data["submission_upload_to"],1));	
			}
			$this->db->select("SU.SUBMISSION_UPLOAD_DATE,
								SF.CREATED_DATE,
								SF.FILE_NAME,
								IP.INSURANCE_PAYERS_NAME,
								U.FIRSTNAME AS USERNAME,
								SU.SUBMISSION_ERROR_MESSAGE,
								SU.SUBMISSION_ERROR_DETAILS,
								SU.SUBMISSION_FILE_ID,
								SU.SUBMISSION_UPLOAD_ID");
			$this->db->from("SUBMISSION_UPLOAD SU");						
			$this->db->join("SUBMISSION_CLAIM SC","SC.SUBMISSION_ID = SU.SUBMISSION_FILE_ID","left");
			$this->db->join("SUBMISSION_FILE SF","SU.SUBMISSION_FILE_ID = SF.SUBMISSION_FILE_ID","left");
			$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = SC.PATIENT_ID","left");
			$this->db->join("INSURANCE_PAYERS IP","IP.INSURANCE_PAYERS_ID = SC.INS_PAYER_ID","left");
			$this->db->join("USERS U","U.USER_SPK = SU.SUBMISSION_UPLOAD_USER_ID","left");
			$this->db->where("SU.SUBMISSION_UPLOAD_ID = (SELECT MAX(SUBMISSION_UPLOAD_ID) FROM SUBMISSION_UPLOAD WHERE SUBMISSION_FILE_ID = SU.SUBMISSION_FILE_ID)");	
			$this->db->where("SU.SUBMISSION_UPLOAD_STATUS",1);
			$this->db->order_by("SU.SUBMISSION_UPLOAD_DATE","DESC");	
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = array("status"=> "Success", "data"=> $query->result());
				return $result;
			}				
		return $result;		
	}
	public function testUploadSubmissionFile($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array(), "message"=>"Invalid Parameters");	
		if(!empty($post_data))
   		{
			$this->load->model('soap/RequestModel');
			//print_r($post_data);
			$result = array("status"=> "Failed", "data"=> array());		
			$this->db->where('SUBMISSION_FILE_ID',$post_data["file_id"]);
			$this->db->select('FILE_NAME');
			$this->db->from('SUBMISSION_FILE');
			$query = $this->db->get();
			$filename = $query->row()->FILE_NAME;
			$user_id = trim($post_data["user_id"]);
			$data=array(
				"SUBMISSION_FILE_ID" 				=> trim($post_data["file_id"]),
				"SUBMISSION_FILENAME" 				=> $filename,
				"SUBMISSION_UPLOAD_TYPE"			=> "TEST",
				"SUBMISSION_UPLOAD_DATE" 			=> date('Y-m-d H:i:s'),
				"SUBMISSION_UPLOAD_USER_ID" 		=> trim($post_data["user_id"]),
				"SUBMISSION_UPLOAD_STATUS"			=> 1,
				//"SUBMISSION_ERROR_MESSAGE"			=> "error"
					);	
			$path =FCPATH.SUBMISSION_FILE_PATH.$filename;
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
			$response_data   = $this->RequestModel->UploadTransaction($user_id,$filename,$file_content);  
			//print_r($response_data);
	        $result_content =  get_string_between($response_data,"<soap:Body>","</soap:Body>");
	        //print_r($result_content);
			if($result_content != '')
			{
		        $xml = simplexml_load_string($result_content, 'SimpleXMLElement', LIBXML_NOCDATA);
		       // echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
		        $response = $this->xml2json->xmlToArray($xml,"root");
		        // print_r($response);exit();
		        $data["SUBMISSION_UPLOAD_STATUS_CODE"] = $response["UploadTransactionResponse"]["UploadTransactionResult"];
				$data["SUBMISSION_ERROR_MESSAGE"]      = $response["UploadTransactionResponse"]["errorMessage"];
				$data["SUBMISSION_ERROR_DETAILS"]      =  base64_decode($response["UploadTransactionResponse"]["errorReport"]);	
					
				if( !empty($filename) && !empty($file_content))	
				{
					$this->db->start_cache();	
					$this->db->insert("SUBMISSION_UPLOAD",$data);				
					$this->db->stop_cache();
					$this->db->flush_cache();
				}
				else
				{
					$result = array( "status"=> "Failed" ,'message' => 'No such a file or directory');
					return $result;
				}
				if($response["UploadTransactionResponse"]["UploadTransactionResult"] == 0)
				{

					$result =array("status"=> "Success","message"=>$response["UploadTransactionResponse"]["errorMessage"]);
				}
				else
				{
					$result = array("status"=> "Failed","message"=> $response["UploadTransactionResponse"]["errorMessage"],
					 "errorReport" =>  base64_decode($response["UploadTransactionResponse"]["errorReport"]));
				}
			
			}
			else
			{
				$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data);	
			}
			
		}
		return $result;
	}
	public function UploadSubmissionFile($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array(), "message"=>"Invalid Parameters");	
		if(!empty($post_data))
   		{
			$this->load->model('soap/RequestModel');
			//print_r($post_data);
			$result = array("status"=> "Failed", "data"=> array());		
			$this->db->where('SUBMISSION_FILE_ID',$post_data["file_id"]);
			$this->db->select('FILE_NAME');
			$this->db->from('SUBMISSION_FILE');
			$query = $this->db->get();
			$filename = $query->row()->FILE_NAME;
			$user_id = trim($post_data["user_id"]);
			$data=array(
				"SUBMISSION_FILE_ID" 				=> trim($post_data["file_id"]),
				"SUBMISSION_FILENAME" 				=> $filename,
				"SUBMISSION_UPLOAD_TYPE"			=> "PRODUCTION",
				"SUBMISSION_UPLOAD_DATE" 			=> date('Y-m-d H:i:s'),
				"SUBMISSION_UPLOAD_USER_ID" 		=> trim($post_data["user_id"]),
				"SUBMISSION_UPLOAD_STATUS"			=> 1,
				//"SUBMISSION_ERROR_MESSAGE"			=> "error"
					);	
			$path =FCPATH.SUBMISSION_FILE_PATH.$filename;

			if(file_exists($path))
			{ 
				$file_path = file_get_contents($path);
				$get_disposition = get_string_between($file_path,"<DispositionFlag>","</DispositionFlag>");
 				$production_file=str_ireplace($get_disposition,"PRODUCTION",$file_path);
				$file_content = base64_encode($production_file); 

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
				$response_data   = $this->RequestModel->UploadTransaction($user_id,$filename,$file_content); 
			}
			else
			{
				$this->db->where('SUBMISSION_FILE_ID',$post_data["file_id"]);
					//$this->db->set('SUBMISSION_STATUS',CLAIM_SUBMITTED);
					$this->db->update('SUBMISSION_FILE',array("SUBMISSION_STATUS"=>CLAIM_SUBMITTED));
				$result = array( "status"=> "success");
					return $result;
			}
			//print_r($response_data);
	        $result_content =  get_string_between($response_data,"<soap:Body>","</soap:Body>");
	        //print_r($result_content);
			if($result_content != '')
			{
		        $xml = simplexml_load_string($result_content, 'SimpleXMLElement', LIBXML_NOCDATA);
		       // echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
		        $response = $this->xml2json->xmlToArray($xml,"root");
		       // print_r($response);
		        $data["SUBMISSION_UPLOAD_STATUS_CODE"] = $response["UploadTransactionResponse"]["UploadTransactionResult"];
				$data["SUBMISSION_ERROR_MESSAGE"]      = $response["UploadTransactionResponse"]["errorMessage"];
				$data["SUBMISSION_ERROR_DETAILS"]      = base64_decode($response["UploadTransactionResponse"]["errorReport"]);
				if( !empty($filename) && !empty($file_content))	
				{
					$this->db->start_cache();	
					$this->db->insert("SUBMISSION_UPLOAD",$data);				
					$this->db->stop_cache();
					$this->db->flush_cache();
				}
				else
				{
					$result = array( "status"=> "Failed" ,'message' => 'No such a file or directory');
					return $result;
				}
				if($response["UploadTransactionResponse"]["UploadTransactionResult"] == 0)
				{
					$this->db->where('SUBMISSION_FILE_ID',$post_data["file_id"]);
					$this->db->set('SUBMISSION_STATUS',CLAIM_SUBMITTED);
					$this->db->update('SUBMISSION_FILE');
					//$this->db->Update('SUBMISSION_FILE',array('SUBMISSION_STATUS' =>CLAIM_SUBMITTED);
					$result =array("status"=> "Success","message"=>$response["UploadTransactionResponse"]["errorMessage"]);
				}
				else
				{
					$result = array("status"=> "Failed","message"=> $response["UploadTransactionResponse"]["errorMessage"],
					 "errorReport" =>  base64_decode($response["UploadTransactionResponse"]["errorReport"]));
				}
			
			}
			else
			{
				$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data);	
			}
			
		}
		return $result;
	}
	public function searchTransactions($post_data)
	{
		$result = array("status"=> "Failed",  "message"=>"Invalid Parameters");	

		if(!empty($post_data))
   		{
			$this->load->model('soap/RequestModel');	
			$response_data   = $this->RequestModel->SearchTransactions($post_data);  
        	$result_content =  get_string_between($response_data,"<foundTransactions>","</foundTransactions>");
        	//print_r($response_data);
			if($result_content != '')
			{
		        $xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement',LIBXML_NOCDATA);
		        $response = $this->xml2json->xmlToArray($xml,"root");
		       $result =array("status"=> "Success","message"=>$response["Files"]);

		    }
		    else
			{
				$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data);	
			 
			}
			
		}
		return $result;
		
	}
	public function searchTransactionDirection()
	{
		//$result = array("status"=> "Failed", "data"=> array());
		$data = array();
			$this->db->start_cache();			
			$this->db->select("SP.SEARCH_TRANSACTION_PARAM,SP.SEARCH_TRANSACTION_DESCRIPTION");
			$this->db->from("SEARCH_TRANSACTION_MASTER SM");
			$this->db->join("SEARCH_TRANSACTION_PARAMS SP","SM.SEARCH_TRANSACTION_MASTER_ID = SP.SEARCH_TRANSACTION_TYPE","left");	
			$this->db->where("SP.SEARCH_TRANSACTION_TYPE",1);	
			$this->db->where("SP.SEARCH_TRANSACTION_STATUS",1);	
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result();				
			}
			if(!empty($data))
			{
				$result = $data;
			}
				
		return $result;
	}
	public function searchTransactioncallerLicense()
	{
		//$result = array("status"=> "Failed", "data"=> array());
		$data = array();
			$this->db->start_cache();			
			$this->db->select("SP.SEARCH_TRANSACTION_PARAM,SP.SEARCH_TRANSACTION_DESCRIPTION");
			$this->db->from("SEARCH_TRANSACTION_MASTER SM");
			$this->db->join("SEARCH_TRANSACTION_PARAMS SP","SM.SEARCH_TRANSACTION_MASTER_ID = SP.SEARCH_TRANSACTION_TYPE","left");	
			$this->db->where("SP.SEARCH_TRANSACTION_TYPE",2);	
			$this->db->where("SP.SEARCH_TRANSACTION_STATUS",1);		
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result();				
			}
			if(!empty($data))
			{
				$result =  $data;
			}
				
		return $result;
	}
	public function searchTransactionePartner()
	{
		//$result = array("status"=> "Failed", "data"=> array());
		$data = array();
			$this->db->start_cache();			
			$this->db->select("SP.SEARCH_TRANSACTION_PARAM,SP.SEARCH_TRANSACTION_DESCRIPTION");
			$this->db->from("SEARCH_TRANSACTION_MASTER SM");
			$this->db->join("SEARCH_TRANSACTION_PARAMS SP","SM.SEARCH_TRANSACTION_MASTER_ID = SP.SEARCH_TRANSACTION_TYPE","left");	
			$this->db->where("SP.SEARCH_TRANSACTION_TYPE",3);	
			$this->db->where("SP.SEARCH_TRANSACTION_STATUS",1);		
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result();				
			}
			if(!empty($data))
			{
				$result =  $data;
			}
				
		return $result;
	}

	public function searchTransactiontransactionID()
	{
		//$result = array("status"=> "Failed", "data"=> array());
		$data = array();
			$this->db->start_cache();			
			$this->db->select("SP.SEARCH_TRANSACTION_PARAM,SP.SEARCH_TRANSACTION_DESCRIPTION");
			$this->db->from("SEARCH_TRANSACTION_MASTER SM");
			$this->db->join("SEARCH_TRANSACTION_PARAMS SP","SM.SEARCH_TRANSACTION_MASTER_ID = SP.SEARCH_TRANSACTION_TYPE","left");	
			$this->db->where("SP.SEARCH_TRANSACTION_TYPE",4);
			$this->db->where("SP.SEARCH_TRANSACTION_STATUS",1);			
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result();				
			}
			if(!empty($data))
			{
				$result = $data;
			}
				
		return $result;
	}
	public function searchTransactiontransactionStatus()
	{
		//$result = array("status"=> "Failed", "data"=> array());
		$data = array();
			$this->db->start_cache();			
			$this->db->select("SP.SEARCH_TRANSACTION_PARAM,SP.SEARCH_TRANSACTION_DESCRIPTION");
			$this->db->from("SEARCH_TRANSACTION_MASTER SM");
			$this->db->join("SEARCH_TRANSACTION_PARAMS SP","SM.SEARCH_TRANSACTION_MASTER_ID = SP.SEARCH_TRANSACTION_TYPE","left");	
			$this->db->where("SP.SEARCH_TRANSACTION_TYPE",5);
			$this->db->where("SP.SEARCH_TRANSACTION_STATUS",1);			
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result();				
			}
			if(!empty($data))
			{
				$result =  $data;
			}
				
		return $result;
	}
	public function GetNewTransactions($post_data)
	{
		$result = array("status"=> "Failed",  "message"=>"Invalid Parameters");	
		$this->load->model('soap/RequestModel');	
		$response_data   = $this->RequestModel->GetNewTransactions();  
		//print_r($response_data);
    	$result_content =  get_string_between($response_data,"<xmlTransaction>","</xmlTransaction>");
		//print_r($response_data);
		if($result_content != '')
		{
			//print_r($result_content	);
	       	$xml =simplexml_load_string(htmlspecialchars_decode($result_content),'SimpleXMLElement',LIBXML_NOCDATA);
	      	$response = $this->xml2json->xmlToArray($xml,"root");
	      	//print_r($response);
	      	$result =array("status"=> "Success","message"=>$response["Files"]);
	      	//echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
	        // echo "<textarea>".$body;
	        //print_r($response);
		}
	    else
		{
			$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data);	
		 
		}
		return $result;
	}
	public function getNewRemittance($post_data)
	{
		$result = array("status"=> "Failed",  "message"=>"Invalid Parameters");	

		if(!empty($post_data))
   		{
			$this->load->model('soap/RequestModel');	
			$post_data["minRecordCount"] = 1;
			$post_data["maxRecordCount"] = 1000;
			$post_data["direction"] = '2';
			$post_data["callerLicense"] = '';
			$post_data["ePartner"] = '';
			$post_data["transactionID"] = '8';
			$post_data["transactionStatus"] = '1';
			$post_data["transactionFileName"] = '';


			$response_data   = $this->RequestModel->SearchTransactions($post_data);  
        	$result_content =  get_string_between($response_data,"<foundTransactions>","</foundTransactions>");
        	//print_r($response_data);
			if($result_content != '')
			{
		        $xml = simplexml_load_string(htmlspecialchars_decode($result_content), 'SimpleXMLElement',LIBXML_NOCDATA);
		        $response = $this->xml2json->xmlToArray($xml,"root");
		       $result =array("status"=> "Success","message"=>$response["Files"]);

		    }
		    else
			{
				$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data);	
			 
			}
			
		}
		return $result;
		
	}
	public function DownloadTransactionFile($post_data=array())
  	{  
  		$result = array("status"=> "Failed",  "message"=>"Invalid Parameters");	
		$file_id= $post_data["file_id"];
		if($file_id && $post_data['user_id'] && $post_data['client_date'])
   		{
			$this->load->model('soap/RequestModel');	
			$response_data   = $this->RequestModel->DownloadTransactionFile($file_id);  
	    	$result_content =  get_string_between($response_data,"<soap:Body>","</soap:Body>");
			//print_r($response_data);
			if($result_content != '')
			{
				$xml = simplexml_load_string($result_content, 'SimpleXMLElement', LIBXML_NOCDATA);
		        $response = $this->xml2json->xmlToArray($xml,"root");
		       //	print_r($response);		
				if($response["DownloadTransactionFileResponse"]["DownloadTransactionFileResult"] == 0)
				{
					$file_details =array("filename" =>$response["DownloadTransactionFileResponse"]["fileName"] , "file_content" => base64_decode($response["DownloadTransactionFileResponse"]["file"]));
					$path =FCPATH.REMITTANCE_FILE_PATH.$file_details["filename"];
					$file_content = simplexml_load_string($file_details["file_content"],'SimpleXMLElement', LIBXML_NOCDATA);
					//print_r($file_content);
					$file_content_data = $this->xml2json->xmlToArray($file_content,"root");
					$file_content_array = $file_content_data["Remittance.Advice"];
					//exit;
					//$file_content_json  = json_encode($file_content);
					//$file_content_array = json_decode($file_content_json, true);
					
					$data=array(
						"REMITTANCE_FILE_NAME" 		=> $file_details["filename"],
						"REMITTANCE_XML_FILE_ID" 	=> $file_id,
						"REMITTANCE_TYPE" 			=> "REMITTANCE",
						//"DESCRIPTION"				=> ,
						"REMITTANCE_STATUS"			=> 1,
						//"TPA_ID"					=> $file_content_array["Claim"]["IDPayer"],
						"SENDER_ID"					=> $file_content_array["Header"]["SenderID"],
						"RECEIVER_ID"				=> $file_content_array["Header"]["ReceiverID"],
						"TRANSACTION_DATE"			=> format_date($file_content_array["Header"]["TransactionDate"]),
						"RECORD_COUNT"				=> $file_content_array["Header"]["RecordCount"],
						"DISPOSITION_FLAG"			=> $file_content_array["Header"]["DispositionFlag"],
						"DOWNLOADED_DATE"			=> date('Y-m-d H:i:s'),
						"DOWNLOADED_BY"				=> $post_data['user_id'],
						"CLIENT_DATE"				=> format_date($post_data['client_date'])
							);
					
					$this->db->where('REMITTANCE_XML_FILE_ID',$post_data["file_id"]);
					$row = $this->db->get('REMITTANCE_FILE');
					if ( $row->num_rows() > 0 ) 
					{
						$this->db->start_cache();
						$this->db->where('REMITTANCE_XML_FILE_ID',$post_data["file_id"]);	
						$this->db->update("REMITTANCE_FILE",$data);	
						$rem_id = $row->row()->REMITTANCE_FILE_ID;
						$this->db->stop_cache();
						$this->db->flush_cache();
						$this->insert_remittance_claim($file_content_array["Claim"],$rem_id);		

					}
					else 
					{
						$this->db->start_cache();
						$this->db->insert("REMITTANCE_FILE",$data);		
						$rem_id = $this->db->insert_id();		
						$this->db->stop_cache();
						$this->db->flush_cache();
						$this->insert_remittance_claim($file_content_array["Claim"],$rem_id);
					}
					if(write_file($path,$file_details["file_content"]))
					{
						$result= array("status"=> "Success", "file_name"=>$file_details["filename"],'message' => 'File downloaded successfully!');
					}
					else
					{
						$result= array("status"=> "Failed",'message' => 'Unable to download the file');
					}
					
				}
				else
				{
					$result = array("status"=> "Failed","message"=> $response["DownloadTransactionFileResponse"]["errorMessage"]);
				}
		    }
		    else
			{
				$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data);	
			 
			}
		}
		//return $result; 
	
	/*	$date='1-10-2019'	;
	echo $result = toConfigTimezone($date);
	echo "<br>";
	echo $result = toConfigTimezoneDate($date);
	//echo "<br>";*/
	//echo $result = toUtc($result,'Asia/Kolkata');
	}

	public function insert_remittance_claim($claim_arr,$rem_id)
	{
		
		if($rem_id > 0)
		{
			$this->db->start_cache();			
					$this->db->where("REMITTANCE_FILE_ID", $rem_id);												
					$this->db->delete("REMITTANCE_CLAIM");
					$this->db->stop_cache();
					$this->db->flush_cache();	
			foreach($claim_arr as $claim)
			{

				$data = array(
					"REMITTANCE_FILE_ID" 		=> $rem_id,
					"CLAIM_ID" 					=> $claim["ID"],
					"ID_PAYER" 					=> $claim["IDPayer"],
					"PROVIDER_ID" 				=> $claim["ProviderID"],
					"PAYMENT_REFERENCE" 		=> $claim["PaymentReference"],
					"DATE_SETTILEMENT" 			=> format_date($claim["DateSettlement"]),
					"SETTILEMENT_DATE" 			=> format_date($claim["DateSettlement"]),
				);

				$this->db->start_cache();
				$this->db->insert("REMITTANCE_CLAIM",$data);		
				$claim_id = $this->db->insert_id();
				$this->insert_remitance_claim_activity($claim['Activity'],$claim_id);	
				$this->db->stop_cache();
				$this->db->flush_cache();
			}
		}
		else
		{
			foreach($claim_arr as $claim)
			{

				$data = array(
					"REMITTANCE_FILE_ID" 		=> $rem_id,
					"CLAIM_ID" 					=> $claim["ID"],
					"ID_PAYER" 					=> $claim["IDPayer"],
					"PROVIDER_ID" 				=> $claim["ProviderID"],
					"PAYMENT_REFERENCE" 		=> $claim["PaymentReference"],
					"DATE_SETTILEMENT" 			=> format_date($claim["DateSettlement"]),
					"SETTILEMENT_DATE" 			=> format_date($claim["DateSettlement"]),
				);

				$this->db->start_cache();
				$this->db->insert("REMITTANCE_CLAIM",$data);		
				$claim_id = $this->db->insert_id();
				$this->insert_remitance_claim_activity($claim['Activity'],$claim_id);	
				$this->db->stop_cache();
				$this->db->flush_cache();
			}

		}
	}
	public function insert_remitance_claim_activity($activity_arr,$claim_id)
	{
		if($claim_id > 0)
		{
			$this->db->start_cache();			
					$this->db->where("CLAIM_ID", $claim_id);												
					$this->db->delete("REMITTANCE_ACTIVITY");
					$this->db->stop_cache();
					$this->db->flush_cache();
			
			//print_r($activity_arr);
			if(isset($activity_arr[0]))
			{

				foreach($activity_arr as $activity)
				{
					$data = array(
						"CLAIM_ID" 					=> $claim_id,
						"START" 					=> $activity["Start"],
						"START_DATE" 				=> format_date($activity["Start"]),
						"TYPE" 						=> $activity["Type"],
						"CODE" 						=> $activity["Code"],
						"QUANTITY" 					=> $activity["Quantity"],
						"NET" 						=> $activity["Net"],
					//	"LIST" 			=> $activity["IDPayer"],
						"CLINICIAN" 				=> $activity["Clinician"],
						"GROSS" 					=> $activity["Gross"],
						"PATIENT_SHARE" 			=> $activity["PatientShare"],
						"PAYMENT_AMOUNT" 			=> $activity["PatientShare"],
						"DENIAL_CODE" 				=> $activity["DenialCode"],
					);
					$this->db->start_cache();
					$this->db->insert("REMITTANCE_ACTIVITY",$data);		
					$this->db->stop_cache();
					$this->db->flush_cache();
				}
			}
			else
			{
				$activity = $activity_arr;
				$data = array(
						"CLAIM_ID" 					=> $claim_id,
						"START" 					=> $activity["Start"],
						"START_DATE" 				=> format_date($activity["Start"]),
						"TYPE" 						=> $activity["Type"],
						"CODE" 						=> $activity["Code"],
						"QUANTITY" 					=> $activity["Quantity"],
						"NET" 						=> $activity["Net"],
						"CLINICIAN" 				=> $activity["Clinician"],
						"GROSS" 					=> $activity["Gross"],
						"PATIENT_SHARE" 			=> $activity["PatientShare"],
						"PAYMENT_AMOUNT" 			=> $activity["PatientShare"],
						"DENIAL_CODE" 				=> $activity["DenialCode"],
					);
					$this->db->start_cache();
					$this->db->insert("REMITTANCE_ACTIVITY",$data);		
					$this->db->stop_cache();
					$this->db->flush_cache();
			}
		}
		else
		{
			//print_r($activity);
			if(isset($activity_arr[0]))
			{

				foreach($activity_arr as $activity)
				{
					$data = array(
						"CLAIM_ID" 					=> $claim_id,
						"START" 					=> $activity["Start"],
						"START_DATE" 				=> format_date($activity["Start"]),
						"TYPE" 						=> $activity["Type"],
						"CODE" 						=> $activity["Code"],
						"QUANTITY" 					=> $activity["Quantity"],
						"NET" 						=> $activity["Net"],
					//	"LIST" 			=> $activity["IDPayer"],
						"CLINICIAN" 				=> $activity["Clinician"],
						"GROSS" 					=> $activity["Gross"],
						"PATIENT_SHARE" 			=> $activity["PatientShare"],
						"PAYMENT_AMOUNT" 			=> $activity["PatientShare"],
						"DENIAL_CODE" 				=> $activity["DenialCode"],
					);
					$this->db->start_cache();
					$this->db->insert("REMITTANCE_ACTIVITY",$data);		
					$this->db->stop_cache();
					$this->db->flush_cache();
				}
			}
			else
			{
				$activity = $activity_arr;
				$data = array(
						"CLAIM_ID" 					=> $claim_id,
						"START" 					=> $activity["Start"],
						"START_DATE" 				=> format_date($activity["Start"]),
						"TYPE" 						=> $activity["Type"],
						"CODE" 						=> $activity["Code"],
						"QUANTITY" 					=> $activity["Quantity"],
						"NET" 						=> $activity["Net"],
					//	"LIST" 			=> $activity["IDPayer"],
						"CLINICIAN" 				=> $activity["Clinician"],
						"GROSS" 					=> $activity["Gross"],
						"PATIENT_SHARE" 			=> $activity["PatientShare"],
						"PAYMENT_AMOUNT" 			=> $activity["PatientShare"],
						"DENIAL_CODE" 				=> $activity["DenialCode"],
					);
					$this->db->start_cache();
					$this->db->insert("REMITTANCE_ACTIVITY",$data);		
					$this->db->stop_cache();
					$this->db->flush_cache();
			}
		}
	}
	public function SetTransactionDownloaded($post_data)
	{
		$result = array("status"=> "Failed",  "message"=>"Invalid Parameters");	
		$file_id = trim($post_data["file_id"]);
		if(!empty($post_data))
   		{
			print_r($post_data);
			$this->load->model('soap/RequestModel');	
			$response_data   = $this->RequestModel->SetTransactionDownloaded($file_id);  
	    	$result_content =  get_string_between($response_data,"<SetTransactionDownloadedResponse>","</SetTransactionDownloadedResponse>");
			//print_r($result_content	);
			if($result_content != '')
			{
		       	$xml =simplexml_load_string(htmlspecialchars_decode($result_content),'SimpleXMLElement',LIBXML_NOCDATA);
		      	$response = $this->xml2json->xmlToArray($xml,"root");
		      	print_r($response);
		      	$result =array("status"=> "Success","message"=>$response);
		      	//echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
		        // echo "<textarea>".$body;
		        //print_r($response);
		    }
		    else
			{
				$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data,
					'msg' => get_string_between($response_data,"<errorMessage>","</errorMessage>"));	
			 
			}
		}
		return $result;
	}
	public function GetNewPriorAuthorizationTransactions()
	{
		$result = array("status"=> "Failed",  "message"=>"Invalid Parameters");	
		//print_r($post_data);
		$this->load->model('soap/RequestModel');	
		$response_data   = $this->RequestModel->GetNewPriorAuthorizationTransactions();  
    	$result_content =  get_string_between($response_data,"<xmlTransaction>","</xmlTransaction>");
		//print_r($response_data);
		if($result_content != '')
		{
	       	$xml =simplexml_load_string(htmlspecialchars_decode($result_content),'SimpleXMLElement',LIBXML_NOCDATA);
	      	$response = $this->xml2json->xmlToArray($xml,"root");
	      	//print_r($response);
	      	$result =array("status"=> "Success","message"=>$response);
	      	//echo "<textarea>".htmlspecialchars_decode($result_content)."</textarea>";
	        // echo "<textarea>".$body;
	        //print_r($response);
	    }
	    else
		{
			$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data);	
		 
		}
		return $result;
	}
	public function CheckForNewPriorAuthorizationTransactions()
	{
		$result = array("status"=> "Failed",  "message"=>"Invalid Parameters");	
		//print_r($post_data);
		$this->load->model('soap/RequestModel');	

		$response_data   = $this->RequestModel->CheckForNewPriorAuthorizationTransactions();  
		//print_r($response_data);
    	$result_content =  get_string_between($response_data,"<xmlTransaction>","</xmlTransaction>");
		//print_r($result_content);
		//exit;	
		if($response_data != '')
		{
			//print_r($result_content);
        	$xml = simplexml_load_string(htmlspecialchars_decode($response_data), 'SimpleXMLElement', LIBXML_NOCDATA);  
	      	$response = $this->xml2json->xmlToArray($xml,"root");
	     	$result =array("status"=> "Success","message"=>$response["Envelope"]["soap:Body"]["CheckForNewPriorAuthorizationTransactionsResponse"]);
	    }
	    else
		{
			$result = array("status"=> "Failed", "data"=> array(), "message"=>$response_data);	
		 
		}
		return $result;
	}
	
	
}
?>
