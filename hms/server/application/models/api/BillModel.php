<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BillModel extends CI_Model 
{

	function getPatientCptRate($post_data = array())
	{
		
		if ($post_data["cpt_code_ids"])
		{
			
			$cpt_code_ids = $post_data["cpt_code_ids"];
			$insurance_tpa_id = (int)$post_data["insurance_tpa_id"];
			$insurance_network_id = (int)$post_data["insurance_network_id"];
			$this->db->start_cache();

			$this->db->select("IFNULL(C.CPT_RATE,0) as rate,CPT.CURRENT_PROCEDURAL_CODE_ID as cpt_code_id");
			$this->db->from("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT  ");
			$this->db->join("CPT_RATE C","C.CURRENT_PROCEDURAL_CODE_ID = CPT.CURRENT_PROCEDURAL_CODE_ID AND C.TPA_ID ='".$insurance_tpa_id."' AND C.NETWORK_ID = '".$insurance_network_id."' AND C.STATUS = 1",'left');
			$this->db->where_in("CPT.CURRENT_PROCEDURAL_CODE_ID",$cpt_code_ids);
			$this->db->order_by("C.CURRENT_PROCEDURAL_CODE_ID","asc");
			
			$query = $this->db->get();

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
			
			$query = $this->db->get();

			if($query->num_rows() > 0)
			{
				$data["patient_data"] = $query->row();

				$data["ins_data"] = $this->getInsDetails($data["patient_data"]->OP_REGISTRATION_ID);
				$data["co_ins"] = array();
				if($data["ins_data"] != false)
				{
					if($data["ins_data"]["OP_INS_IS_ALL"] != 1)
					{
						$data["co_ins"] = $this->getCoinsDetails($data["ins_data"]["OP_INS_DETAILS_ID"]);
						if($data["co_ins"] == false)
						{
							$data["co_ins"] = array();
						}
					}
				}
				$data["corporate_data"] = $this->getCorporateDetails($data["patient_data"]->OP_REGISTRATION_ID);
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
			$this->db->select("CI.*");
			$this->db->from("OP_COIN_DATA CI");
			$query = $this->db->get();
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
			$this->db->select("CD.*,CC.*");
			$this->db->from("OP_CORPORATE_DETAILS CD");
			$this->db->join("CORPORATE_COMPANY CC","CC.CORPORATE_COMPANY_ID = CD.OP_CORPORATE_COMPANY_ID",'left');
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
	public function updateInsuranceDetails($post_data = array())
	{
		$OP_REGISTRATION_ID = trim($post_data["OP_REGISTRATION_ID"]);
		$INS_DATA = $post_data["INS_DATA"];
		$CO_IN_DATA = $post_data["CO_IN_DATA"];
		
		
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
	function updatebillstatus($post_data = array())
	{
		$result = array("status"=> "Failed", "message"=> "Failed to generate bill");
		if($post_data["assessment_id"] > 0)
		{
				
			$data_id = $post_data["assessment_id"];
			//$billing_id = $post_data["billing_id"];
			$this->db->start_cache();			
			$this->db->select("B.*");
			$this->db->from("BILLING B");
			$this->db->where("B.ASSESSMENT_ID",$data_id);			
			$query = $this->db->get();		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				//$data = $query->row_array();
				$data=array("GENERATED"			=> 1);
				$this->db->where("ASSESSMENT_ID",$data_id);
				$this->db->update("BILLING",$data);
				$result = array("status"=> "Success","message"=>"Bill generated successfully");
			}
			else
				$result = array("status"=> "Failed", "message"=> "Please add atleast one item of any invoice..!");
				
		}
		return $result;
		
	}
	function generatePatientBill($post_data = array())
	{
		
		//exit();
		$result = array("status"=> "Failed", "message"=> "Failed to generate bill");
		if(!empty($post_data))
		{
			$cpt_code_ids = $post_data["cpt_code_ids"];
			if(!empty($cpt_code_ids)){
				$data=array(
				"PATIENT_ID" 		=> trim($post_data["patient_id"]),
				"ASSESSMENT_ID" 	=> trim($post_data["assessment_id"]),
				"PAYMENT_TYPE" 		=> trim($post_data["payment_type"]),
				"PAYMENT_MODE" 		=> trim($post_data["payment_mode"]),
				"BILLED_AMOUNT" 	=> trim($post_data["billed_amount"]),
				"PAID_BY_PATIENT" 	=> trim($post_data["paid_by_patient"]),
				"INSURED_AMOUNT" 	=> trim($post_data["insured_amount"]),
				"CREATED_BY" 		=> trim($post_data["user_id"]),
				"CREATED_ON" 		=> date("Y-m-d H:i:s"),
				"CLIENT_DATE" 	 	=> format_date($post_data["client_date"]),
				"GENERATED"			=> 1,
				"BILL_TYPE"			=>  trim($post_data["bill_type"]),
				"PATIENT_TYPE"		=>  trim($post_data["patient_type"]),
				"PATIENT_TYPE_DETAIL_ID" =>  trim($post_data["patient_type_detail_id"]),
				
				);
				if($post_data["patient_type"] == 3 && $post_data["bill_type"] == 0 || $post_data["patient_type"] == 1 && $post_data["bill_type"] == 1)
				{
					$data["BILLING_TYPE"] = 1;
				}
				$data_id = $post_data["billing_id"];
				if(isset($post_data["department_id"]) && $post_data["department_id"] > 0)
				{
					$data['DEPARTMENT_ID'] = trim($post_data["department_id"]);
				}
				
				if ($data_id> 0)
				{
					$this->db->where("BILLING_ID",$data_id);
					$this->db->update("BILLING",$data);
					$this->addBillStatusofInvestigation($data_id,$post_data);
					$this->addBillDetails($data_id,$post_data);
					$this->updatebillstatus($post_data);
					$result = array("status"=> "Success","billing_no"=>$gen_no,"billing_id"=>$data_id, "message"=>"Bill updated successfully");
				}
				else
				{
					
					if(count($post_data["cpt_code_ids"])>0)
					{
						$gen_no=$this->GenerateBillNo($post_data);
						$data["BILLING_INVOICE_NUMBER"]=$gen_no["data"];
						$data["DUPLICATE_INVOICE_NUMBER"]=$gen_no["duplicate"];
						$this->db->insert("BILLING",$data);
						$data_id = $this->db->insert_id();
						$this->addBillStatusofInvestigation($data_id,$post_data);
						$this->saveBillDetails($data_id,$post_data);
						$this->updatebillstatus($post_data);
						$result = array("status"=> "Success","billing_no"=>$gen_no,"billing_id"=>$data_id, "message"=>"Bill generated successfully");
					}
						
				}
			}
			else{
				return $result = $this->updatebillstatus($post_data);
				
			}
		}
		
		return $result;
	}
	function saveBillDetails($data_id=0,$post_data=array())
	{	

		$lab_details_ids = $post_data["lab_details_ids"];
		$cpt_code_ids = $post_data["cpt_code_ids"];
		$price_list = $post_data["price"];
		$co_payments = $post_data["co_payment"];
		$co_payments_type = $post_data["co_payment_type"];
		$patient_pay_list = $post_data["patient_pay"];
		$ins_amount_list = $post_data["total"];
		$patient_payable_total = $post_data["patient_payable_total"];
		$insurance_payable_total = $post_data["insurance_payable_total"];
		$prior_authorization = $post_data["prior_authorization"];
		$insurance_status =  $post_data["insurance_status"];
		$client_date = format_date($post_data["client_date"]);
		if($data_id> 0)
		{
			$this->db->start_cache();
			$this->db->where("BILLING_ID",$data_id);
			$this->db->delete("BILLING_DETAILS");
			$this->db->stop_cache();
			$this->db->flush_cache();

			if(!empty($cpt_code_ids))
			{
				foreach ($cpt_code_ids as $key => $value) {
				$data = array(
					"BILLING_ID"=>$data_id,
					"LAB_INVESTIGATION_DETAILS_ID"=>$lab_details_ids[$key],
					"CPT_ID"=>$cpt_code_ids[$key],
					"PRIOR_AUTHORIZATION"=>$prior_authorization[$key],
					"TOTAL_PATIENT_PAYABLE"=>$patient_payable_total[$key],
					"TOTAL_INSURED_AMOUNT"=>$insurance_payable_total[$key],
					"INSURANCE_STATUS"=>$insurance_status[$key],
					"TOTAL_AMOUNT"=>$price_list[$key],
					"PATIENT_PAYABLE"=>$patient_pay_list[$key],
					"INSURED_AMOUNT"=>$ins_amount_list[$key],
					"COINS"=>$co_payments[$key],
					"COINS_TYPE"=>$co_payments_type[$key],
					"CLIENT_DATE"=>$client_date[$key]

					);
					if ($value > 0)
					{
						$this->db->insert("BILLING_DETAILS",$data);
					}
				}
			}
		}
	}
	function savePatientBill($post_data = array())
	{
		$result = array("status"=> "success", "message"=> "Failed to save bill");
		if(!empty($post_data))
		{
			if(isset($post_data["claim_invoice"]) && count($post_data["claim_invoice"]) > 0)
			{
				$claim_invoice = $post_data["claim_invoice"];
				$data = array(
				"PATIENT_ID" 		=> trim($claim_invoice["patient_id"]),
				"ASSESSMENT_ID" 	=> trim($claim_invoice["assessment_id"]),
				"PAYMENT_TYPE" 		=> trim($claim_invoice["payment_type"]),
				"PAYMENT_MODE" 		=> trim($claim_invoice["payment_mode"]),
				"BILLING_DATE" 		=> toUtc(trim($claim_invoice["date"]),$claim_invoice["timeZone"]), 
				"BILLED_AMOUNT" 	=> trim($claim_invoice["billed_amount"]),
				"PAID_BY_PATIENT" 	=> trim($claim_invoice["paid_by_patient"]),
				"PATIENT_PAYABLE" 	=> trim($claim_invoice["paid_by_patient"]),
				"INSURED_AMOUNT" 	=> trim($claim_invoice["insured_amount"]),
				"BILL_TYPE" 		=> trim($claim_invoice["bill_type"]),
				"PATIENT_TYPE"		=>  trim($claim_invoice["patient_type"]),
				"PATIENT_TYPE_DETAIL_ID" =>  trim($claim_invoice["patient_type_detail_id"]),
				"CREATED_BY" 		=> trim($claim_invoice["user_id"]),
				"CREATED_ON" 		=> date("Y-m-d H:i:s"),
				"CLIENT_DATE" 	 	=> format_date($claim_invoice["client_date"])
				
				);

				$data_id = $claim_invoice["billing_id"];
				if(isset($claim_invoice["discount"]))
				{
					$data['PATIENT_DISCOUNT'] = trim($claim_invoice["discount"]);
				}
				if(isset($claim_invoice["balance_payment"]) && $claim_invoice["balance_payment"]>0)
				{
					$data['PENDING_PAYMENT'] = trim($claim_invoice["balance_payment"]);
					$data['IS_AVAILABLE_PENDING'] = 1;
				}
				if(isset($claim_invoice["department_id"]) && $claim_invoice["department_id"] > 0)
				{
					$data['DEPARTMENT_ID'] = trim($claim_invoice["department_id"]);
				}
				
				if ($data_id> 0)
				{
					$this->db->where("BILLING_ID",$data_id);
					$this->db->update("BILLING",$data);
					$this->addBillStatusofInvestigation($data_id,$claim_invoice);
					$this->addBillDetails($data_id,$claim_invoice);
					$result = array("status"=> "Success","billing_no"=>$gen_no,"claim_billing_id"=>$data_id, "message"=>"Bill updated successfully")	;
				}
				else
				{
					
					if($claim_invoice["lab_details_ids"] && count($claim_invoice["lab_details_ids"]) > 0)
					{
						$gen_no=$this->GenerateBillNo($claim_invoice);
						$data["BILLING_INVOICE_NUMBER"]=$gen_no["data"];
						$data["DUPLICATE_INVOICE_NUMBER"]=$gen_no["duplicate"];	
						$this->db->insert("BILLING",$data);
						$data_id = $this->db->insert_id();
						$this->addBillStatusofInvestigation($data_id,$claim_invoice);
						$this->addBillDetails($data_id,$claim_invoice);
						$result = array("status"=> "Success","billing_no"=>$gen_no,"claim_billing_id"=>$data_id, "message"=>"Bill saved successfully");
					}
						
				}
			}
			if(isset($post_data["cash_invoice"]) && count($post_data["cash_invoice"]) > 0)
			{
				$cash_invoice = $post_data["cash_invoice"];
				$data = array(
				"PATIENT_ID" 		=> trim($cash_invoice["patient_id"]),
				"ASSESSMENT_ID" 	=> trim($cash_invoice["assessment_id"]),
				"PAYMENT_TYPE" 		=> trim($cash_invoice["payment_type"]),
				"PAYMENT_MODE" 		=> trim($cash_invoice["payment_mode"]),
				"BILLING_DATE" 		=> toUtc(trim($cash_invoice["date"]),$cash_invoice["timeZone"]), 
				"BALANCE_PENDING" 	=> trim($cash_invoice["balance_payment"]),
				"BILLED_AMOUNT" 	=> trim($cash_invoice["billed_amount"]),
				"PATIENT_PAYABLE" 	=> trim($cash_invoice["patient_payable"]),
				"PAID_BY_PATIENT" 	=> trim($cash_invoice["paid_by_patient"]),
				"INSURED_AMOUNT" 	=> trim($cash_invoice["insured_amount"]),
				"BILL_TYPE" 		=> trim($cash_invoice["bill_type"]),
				"PATIENT_TYPE"		=>  trim($cash_invoice["patient_type"]),
				"PATIENT_TYPE_DETAIL_ID" =>  trim($cash_invoice["patient_type_detail_id"]),
				"CREATED_BY" 		=> trim($cash_invoice["user_id"]),
				"CREATED_ON" 		=> date("Y-m-d H:i:s"),
				"CLIENT_DATE" 	 	=> format_date($cash_invoice["client_date"])
				
				);
				$data_id = $cash_invoice["billing_id"];
				if(isset($cash_invoice["balance_payment"]) && $cash_invoice["balance_payment"]>0)
				{
					$data['PENDING_PAYMENT'] = trim($cash_invoice["pending_amount"]);
					$data['IS_AVAILABLE_PENDING'] = 1;
				}
				if(isset($cash_invoice["discount"]))
				{
					$data['PATIENT_DISCOUNT'] = trim($cash_invoice["discount"]);
				}
				if(isset($cash_invoice["department_id"]) && $cash_invoice["department_id"] > 0)
				{
					$data['DEPARTMENT_ID'] = trim($cash_invoice["department_id"]);
				}
				if ($data_id> 0)
				{
					$this->updatependingpayment($cash_invoice["patient_id"]);
					$this->db->where("BILLING_ID",$data_id);
					$this->db->update("BILLING",$data);
					$this->addBillStatusofInvestigation($data_id,$cash_invoice);
					$this->addBillDetails($data_id,$cash_invoice);
					$result = array("status"=> "Success","billing_no"=>$gen_no,"cash_billing_id"=>$data_id, "message"=>"Bill updated successfully")	;
				}
				else
				{
					
					if($cash_invoice["lab_details_ids"] && count($cash_invoice["lab_details_ids"]) > 0)
					{
						$gen_no=$this->GenerateBillNo($cash_invoice);
						$data["BILLING_INVOICE_NUMBER"]=$gen_no["data"];
						$data["DUPLICATE_INVOICE_NUMBER"]=$gen_no["duplicate"];	
						$this->db->insert("BILLING",$data);
						$data_id = $this->db->insert_id();
						$this->addBillStatusofInvestigation($data_id,$cash_invoice);
						$this->addBillDetails($data_id,$cash_invoice);

						$result = array("status"=> "Success","billing_no"=>$gen_no,"cash_billing_id"=>$data_id, "message"=>"Bill saved successfully");
					}
						
				}
			}
		}
		
		return $result;
	}


	function savediscount($post_data = array())
	{
		$result = array("status"=> "success", "message"=> "Failed to save bill");
		if(!empty($post_data))
		{
			if(isset($post_data["claim_invoice"]) && count($post_data["claim_invoice"]) > 0)
			{
				$claim_invoice = $post_data["claim_invoice"];
				$data = array(
				"PATIENT_ID" 		=> trim($claim_invoice["patient_id"]),
				"ASSESSMENT_ID" 	=> trim($claim_invoice["assessment_id"]),
				"PAYMENT_TYPE" 		=> trim($claim_invoice["payment_type"]),
				"PAYMENT_MODE" 		=> trim($claim_invoice["payment_mode"]),
				"BILLING_DATE" 		=> toUtc(trim($claim_invoice["date"]),$claim_invoice["timeZone"]), 
				"BILLED_AMOUNT" 	=> trim($claim_invoice["billed_amount"]),
				//"PAID_BY_PATIENT" 	=> trim($claim_invoice["paid_by_patient"]),
				"INSURED_AMOUNT" 	=> trim($claim_invoice["insured_amount"]),
				"BILL_TYPE" 		=> trim($claim_invoice["bill_type"]),
				"PATIENT_TYPE"		=>  trim($claim_invoice["patient_type"]),
				"PATIENT_TYPE_DETAIL_ID" =>  trim($claim_invoice["patient_type_detail_id"]),
				"CREATED_BY" 		=> trim($claim_invoice["user_id"]),
				"CREATED_ON" 		=> date("Y-m-d H:i:s"),
				"CLIENT_DATE" 	 	=> format_date($claim_invoice["client_date"])
				
				);

				$data_id = $claim_invoice["billing_id"];
				if(isset($claim_invoice["discount"]))
				{
					$data['PATIENT_DISCOUNT'] = trim($claim_invoice["discount"]);
				}
				if(isset($claim_invoice["balance_payment"]) && $claim_invoice["balance_payment"]>0)
				{
					$data['PENDING_PAYMENT'] = trim($claim_invoice["balance_payment"]);
					$data['IS_AVAILABLE_PENDING'] = 1;
				}
				if(isset($claim_invoice["department_id"]) && $claim_invoice["department_id"] > 0)
				{
					$data['DEPARTMENT_ID'] = trim($claim_invoice["department_id"]);
				}
				
				if ($data_id> 0)
				{
					$this->db->where("BILLING_ID",$data_id);
					$this->db->update("BILLING",$data);
					$this->addBillStatusofInvestigation($data_id,$claim_invoice);
					$this->addBillDetails($data_id,$claim_invoice);
					$result = array("status"=> "Success","billing_no"=>$gen_no,"claim_billing_id"=>$data_id, "message"=>"Bill updated successfully")	;
				}
				else
				{
					
					if($claim_invoice["lab_details_ids"] && count($claim_invoice["lab_details_ids"]) > 0)
					{
						$gen_no=$this->GenerateBillNo($claim_invoice);
						$data["BILLING_INVOICE_NUMBER"]=$gen_no["data"];
						$data["DUPLICATE_INVOICE_NUMBER"]=$gen_no["duplicate"];	
						$this->db->insert("BILLING",$data);
						$data_id = $this->db->insert_id();
						$this->addBillStatusofInvestigation($data_id,$claim_invoice);
						$this->addBillDetails($data_id,$claim_invoice);
						$result = array("status"=> "Success","billing_no"=>$gen_no,"claim_billing_id"=>$data_id, "message"=>"Bill saved successfully");
					}
						
				}
			}
			if(isset($post_data["cash_invoice"]) && count($post_data["cash_invoice"]) > 0)
			{
				$cash_invoice = $post_data["cash_invoice"];
				$data = array(
				"PATIENT_ID" 		=> trim($cash_invoice["patient_id"]),
				"ASSESSMENT_ID" 	=> trim($cash_invoice["assessment_id"]),
				"PAYMENT_TYPE" 		=> trim($cash_invoice["payment_type"]),
				"PAYMENT_MODE" 		=> trim($cash_invoice["payment_mode"]),
				"BILLING_DATE" 		=> toUtc(trim($cash_invoice["date"]),$cash_invoice["timeZone"]), 
				"BALANCE_PENDING" 	=> trim($cash_invoice["balance_payment"]),
				"BILLED_AMOUNT" 	=> trim($cash_invoice["billed_amount"]),
				//"PATIENT_PAYABLE" 	=> trim($cash_invoice["patient_payable"]),
				//"PAID_BY_PATIENT" 	=> trim($cash_invoice["paid_by_patient"]),
				"INSURED_AMOUNT" 	=> trim($cash_invoice["insured_amount"]),
				"BILL_TYPE" 		=> trim($cash_invoice["bill_type"]),
				"PATIENT_TYPE"		=>  trim($cash_invoice["patient_type"]),
				"PATIENT_TYPE_DETAIL_ID" =>  trim($cash_invoice["patient_type_detail_id"]),
				"CREATED_BY" 		=> trim($cash_invoice["user_id"]),
				"CREATED_ON" 		=> date("Y-m-d H:i:s"),
				"CLIENT_DATE" 	 	=> format_date($cash_invoice["client_date"])
				
				);
				$data_id = $cash_invoice["billing_id"];
				if(isset($cash_invoice["balance_payment"]) && $cash_invoice["balance_payment"]>0)
				{
					$data['PENDING_PAYMENT'] = trim($cash_invoice["pending_amount"]);
					$data['IS_AVAILABLE_PENDING'] = 1;
				}
				if(isset($cash_invoice["discount"]))
				{
					$data['PATIENT_DISCOUNT'] = trim($cash_invoice["discount"]);
				}
				if(isset($cash_invoice["department_id"]) && $cash_invoice["department_id"] > 0)
				{
					$data['DEPARTMENT_ID'] = trim($cash_invoice["department_id"]);
				}
				if ($data_id> 0)
				{
					$this->updatependingpayment($cash_invoice["patient_id"]);
					$this->db->where("BILLING_ID",$data_id);
					$this->db->update("BILLING",$data);
					$this->addBillStatusofInvestigation($data_id,$cash_invoice);
					$this->addBillDetails($data_id,$cash_invoice);
					$result = array("status"=> "Success","billing_no"=>$gen_no,"cash_billing_id"=>$data_id, "message"=>"Bill updated successfully")	;
				}
				else
				{
					
					if($cash_invoice["lab_details_ids"] && count($cash_invoice["lab_details_ids"]) > 0)
					{
						$gen_no=$this->GenerateBillNo($cash_invoice);
						$data["BILLING_INVOICE_NUMBER"]=$gen_no["data"];
						$data["DUPLICATE_INVOICE_NUMBER"]=$gen_no["duplicate"];	
						$this->db->insert("BILLING",$data);
						$data_id = $this->db->insert_id();
						$this->addBillStatusofInvestigation($data_id,$cash_invoice);
						$this->addBillDetails($data_id,$cash_invoice);

						$result = array("status"=> "Success","billing_no"=>$gen_no,"cash_billing_id"=>$data_id, "message"=>"Bill saved successfully");
					}
						
				}
			}
		}
		
		return $result;
	}

	function addBillStatusofInvestigation($data_id,$post_data)
	{	
		// print_r($lab_details_ids);
		$lab_details_ids = $post_data["lab_details_ids"];
		if($data_id > 0 )
		{
			if(is_array($lab_details_ids) && count($lab_details_ids) > 0 )
			{

				$this->db->start_cache();
				$this->db->select("*");
				$this->db->from("LAB_INVESTIGATION_DETAILS LD");
				$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS C","C.CURRENT_PROCEDURAL_CODE_ID = LD.CURRENT_PROCEDURAL_CODE_ID");
				$this->db->join("LAB_INVESTIGATION L","L.LAB_INVESTIGATION_ID = LD.LAB_INVESTIGATION_ID");
				if(isset($post_data["department_id"]) && $post_data["department_id"] > 0)
				{
					$this->db->where("C.PROCEDURE_CODE_CATEGORY",$post_data["department_id"]);
				}
				$this->db->where("L.ASSESSMENT_ID",$post_data["assessment_id"]);
				$query = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$variable = $query->result_array();
					foreach ($variable as $key => $value) 
					{
						// code...
						// $data = array("BILL_STATUS_BY_DEPT"=> 0);
						// $this->db->start_cache();
						// $this->db->where("LAB_INVESTIGATION_DETAILS_ID",$value["LAB_INVESTIGATION_DETAILS_ID"]);
						// $this->db->update("LAB_INVESTIGATION_DETAILS",$data);	
						// $this->db->stop_cache();
						// $this->db->flush_cache();
					}
					
				}
				// $data = array("BILL_STATUS_BY_DEPT"=> 1);
				// $this->db->start_cache();
				// $this->db->where_in("LAB_INVESTIGATION_DETAILS_ID",$lab_details_ids);
				// $this->db->update("LAB_INVESTIGATION_DETAILS",$data);	
				// $this->db->stop_cache();
				// $this->db->flush_cache();
			}
			else
			{
				$this->db->start_cache();
				$this->db->select("BD.*");
				$this->db->from("BILLING_DETAILS BD");
				$this->db->join("BILLING B","B.BILLING_ID = BD.BILLING_ID");
				$this->db->where("BD.BILLING_ID",$data_id);
				if(isset($post_data["department_id"]) && $post_data["department_id"] > 0)
				{
					$this->db->where("B.DEPARTMENT_ID",$post_data["department_id"]);
				}
				$query = $this->db->get();
				// echo $this->db->last_query();exit;
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$bill_data = $query->result_array();
					foreach ($bill_data as $key => $value) {
						// code...
						// $data = array("BILL_STATUS_BY_DEPT"=> 0);
						// $this->db->start_cache();
						// $this->db->where("LAB_INVESTIGATION_DETAILS_ID",$value["LAB_INVESTIGATION_DETAILS_ID"]);
						// $this->db->update("LAB_INVESTIGATION_DETAILS",$data);	
						// $this->db->stop_cache();
						// $this->db->flush_cache();
					}
					
				}
			
			}
		}
	}

	function addBillDetails($data_id=0,$post_data=array())
	{	

		$lab_details_ids = $post_data["lab_details_ids"];
		$cpt_code_ids = $post_data["cpt_code_ids"];
		$price_list = $post_data["price"];
		$co_payments = $post_data["co_payment"];
		$co_payments_type = $post_data["co_payment_type"];
		$patient_pay_list = $post_data["patient_pay"];
		$ins_amount_list = $post_data["total"];
		$patient_payable_total = $post_data["patient_payable_total"];
		$insurance_payable_total = $post_data["insurance_payable_total"];
		$prior_authorization = $post_data["prior_authorization"];
		$insurance_status =  $post_data["insurance_status"];
		$client_date = format_date($post_data["client_date"]);
		if($data_id> 0)
		{
			$this->db->start_cache();
			$this->db->where("BILLING_ID",$data_id);
			$this->db->delete("BILLING_DETAILS");
			$this->db->stop_cache();
			$this->db->flush_cache();
			if(!empty($cpt_code_ids)){
				foreach ($cpt_code_ids as $key => $value) {
					$data = array(
						"BILLING_ID"=>$data_id,
						"LAB_INVESTIGATION_DETAILS_ID"=>$lab_details_ids[$key],
						"CPT_ID"=>$cpt_code_ids[$key],
						"PRIOR_AUTHORIZATION"=>$prior_authorization[$key],
						"TOTAL_PATIENT_PAYABLE"=>$patient_payable_total[$key],
						"TOTAL_INSURED_AMOUNT"=>$insurance_payable_total[$key],
						"INSURANCE_STATUS"=>$insurance_status[$key],
						"TOTAL_AMOUNT"=>$price_list[$key],
						"PATIENT_PAYABLE"=>$patient_pay_list[$key],
						"INSURED_AMOUNT"=>$ins_amount_list[$key],
						"COINS"=>$co_payments[$key],
						"COINS_TYPE"=>$co_payments_type[$key],
						"CLIENT_DATE"=>$client_date[$key],

					);
					if ($value != '')
					{
						$this->db->insert("BILLING_DETAILS",$data);
					}
				}
			}
		}
	}

	function saveBillresult($post_data=array())
	{	

		$billing_details_ids = $post_data["billing_details_ids"];
		$prior_authorization = $post_data["prior_authorization"];
		if($billing_details_ids != '' && $post_data["prior_authorization"] != '')
		{
			if(is_array($billing_details_ids) && count($billing_details_ids) > 0)
			{
				foreach ($billing_details_ids as $key => $value) {
					# code...
					$this->db->start_cache();
					$this->db->where("BILLING_DETAILS_ID",$value);
					$this->db->update("BILLING_DETAILS",array("PRIOR_AUTHORIZATION"=>$prior_authorization[$key]));
					$this->db->stop_cache();
					$this->db->flush_cache();
				}
				$result 	= array('msg'=> "Data saved successfully",
							'status'=>'Success',
							);

				return $result;	
			}

			
		}
		$result 	= array('msg'=> "Invalid bill details id",
							'status'=>'Failed',
							);
		return $result;		
		
		

	}

	public function getLabInvestigation($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$patient_id = (int)$post_data["patient_id"];
		$assessment_id = (int)$post_data["assessment_id"];
		$department_id = (int)$post_data["department_id"];
		$this->db->start_cache();			
		$this->db->select("DM.*");
		$this->db->where("PATIENT_ID", $patient_id);
		$this->db->where("ASSESSMENT_ID", $assessment_id);
		$this->db->from("LAB_INVESTIGATION DM");			
						
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();		
		
		if($query->num_rows() > 0)
		{
			$data = $query->row_array();
			$data['LAB_INVESTIGATION_DETAILS'] = $this->getLabInvestigationDetails($data['LAB_INVESTIGATION_ID'],'LAB_INVESTIGATION_DETAILS LID',$department_id);										
		}
		if(!empty($data))
			$result = array("status"=> "Success", "data"=> $data);
				
		
		return $result;
		
	}
	
	public function getLabInvestigationDetails($lab_investigation_id, $table,$department_id)
	{
			$this->db->start_cache();			
			$this->db->select("BD.*,B.*,LID.*");	
			$this->db->select("CPT.PROCEDURE_CODE as PROCEDURE_CODE, CPT.PROCEDURE_CODE_NAME as PROCEDURE_CODE_NAME,CPT.PROCEDURE_CODE_CATEGORY as PROCEDURE_CODE_CATEGORY");																			
			$this->db->from($table);
			$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CPT.CURRENT_PROCEDURAL_CODE_ID = LID.CURRENT_PROCEDURAL_CODE_ID","left");						
			$this->db->join("BILLING_DETAILS BD","LID.LAB_INVESTIGATION_DETAILS_ID = BD.LAB_INVESTIGATION_DETAILS_ID","left");	
			$this->db->join("BILLING B","B.BILLING_ID = BD.BILLING_ID","left");					
			$this->db->where("LAB_INVESTIGATION_ID", $lab_investigation_id);
			if(isset($department_id) && $department_id>0)
			{
				$this->db->where("PROCEDURE_CODE_CATEGORY", $department_id);
			}
			$this->db->order_by("LID.LAB_INVESTIGATION_DETAILS_ID","ASC");			
			$this->db->group_by("LID.LAB_INVESTIGATION_DETAILS_ID");			
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
	public function getLabInvestigationResults($post_data)
	{
		if($post_data["lab_investigation_id"] == '')
		{
			$result 	= array('msg'=> "Invalid lab investigation id",
							'status'=>'Failed',
							);
			return $result;		
		}
			$this->db->start_cache();			
			$this->db->select("LID.LAB_INVESTIGATION_DETAILS_ID,CR.CODE,CPT.PROCEDURE_CODE as PROCEDURE_CODE, CPT.PROCEDURE_CODE_CATEGORY as PROCEDURE_CODE_CATEGORY,LR.RESULT_VALUE,CR.CPT_REQUIRED_ID,CR.DISPLAY_NAME");																			
			$this->db->from("LAB_INVESTIGATION_DETAILS LID");
			$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CPT.CURRENT_PROCEDURAL_CODE_ID = LID.CURRENT_PROCEDURAL_CODE_ID","left");						
			$this->db->join("CPT_REQUIRED CR","CR.CPT_GROUP = CPT.CPT_GROUP_ID AND (CR.CPT_CODES LIKE CONCAT('%', CPT.PROCEDURE_CODE, '%') OR CR.CPT_CODES is null)","left");
			$this->db->join("LAB_INVESTIGATION_RESULT LR","LR.REQ_ID = CR.CPT_REQUIRED_ID AND LR.LAB_INVESTIGATION_DETAILS_ID = LID.LAB_INVESTIGATION_DETAILS_ID","left");
			$this->db->where("LID.LAB_INVESTIGATION_ID", $post_data["lab_investigation_id"]);
			$this->db->order_by("LID.SERIAL_NO","ASC");			
			$query = $this->db->get();
			$this->db->stop_cache();
			$this->db->flush_cache();
			$value = array();
			if($query->num_rows() > 0)
			{
				$ck_cpt = $query->result_array();	
				$k = 0;	
				foreach($ck_cpt as $ck)
				{
					if($ck["CODE"]){
						
						switch ($ck["CODE"]) {
							case 'value':
								# code...
								break;
							
							
							case  "PresentingComplaint":
									$bill_data = select_data("LAB_INVESTIGATION","ASSESSMENT_ID","LAB_INVESTIGATION_ID = ".$post_data["lab_investigation_id"]);
									$data = select_data("CHIEF_COMPLAINTS","COMPLAINTS ","ASSESSMENT_ID = ".$bill_data["ASSESSMENT_ID"]);
									$value[$k]["PROCEDURE_CODE"] = 	$ck["PROCEDURE_CODE"];
									$value[$k]["CODE"] = $ck["DISPLAY_NAME"];
									$value[$k]["VALUE"] = $data["COMPLAINTS"];
									$value[$k]["LAB_INVESTIGATION_DETAILS_ID"] = $ck["LAB_INVESTIGATION_DETAILS_ID"];
									$value[$k]["CPT_REQUIRED_ID"] = $ck["CPT_REQUIRED_ID"];
									$value[$k]["EDIT"] = FALSE;
								# code...
								break;
							case "BPS":
									$bill_data = select_data("LAB_INVESTIGATION","ASSESSMENT_ID","LAB_INVESTIGATION_ID = ".$post_data["lab_investigation_id"]);
									$data = select_data("NURSING_ASSESSMENT_DETAILS","PARAMETER_VALUE","ASSESSSMENT_ID = ".$bill_data["ASSESSMENT_ID"]." AND PARAMETER_ID = 8");
									$value[$k]["PROCEDURE_CODE"] = 	$ck["PROCEDURE_CODE"];
									$value[$k]["CODE"] = $ck["DISPLAY_NAME"];
									$value[$k]["VALUE"] = $data["PARAMETER_VALUE"];
									$value[$k]["LAB_INVESTIGATION_DETAILS_ID"] = $ck["LAB_INVESTIGATION_DETAILS_ID"];
									$value[$k]["EDIT"] = FALSE;
								# code...
								break;
							case "BPD":
									$bill_data = select_data("LAB_INVESTIGATION","ASSESSMENT_ID","LAB_INVESTIGATION_ID = ".$post_data["lab_investigation_id"]);
									$data = select_data("NURSING_ASSESSMENT_DETAILS","PARAMETER_VALUE","ASSESSSMENT_ID = ".$bill_data["ASSESSMENT_ID"]." AND PARAMETER_ID = 9");
									$value[$k]["PROCEDURE_CODE"] = 	$ck["PROCEDURE_CODE"];
									$value[$k]["CODE"] = $ck["DISPLAY_NAME"];
									$value[$k]["VALUE"] = $data["PARAMETER_VALUE"];
									$value[$k]["LAB_INVESTIGATION_DETAILS_ID"] = $ck["LAB_INVESTIGATION_DETAILS_ID"];
									$value[$k]["CPT_REQUIRED_ID"] = $ck["CPT_REQUIRED_ID"];
									$value[$k]["EDIT"] = FALSE;
								# code...
								break;
							default:
									
									$value[$k]["PROCEDURE_CODE"] = 	$ck["PROCEDURE_CODE"];
									$value[$k]["CODE"] = $ck["CODE"];
									$value[$k]["VALUE"] = $ck["RESULT_VALUE"];
									$value[$k]["LAB_INVESTIGATION_DETAILS_ID"] = $ck["LAB_INVESTIGATION_DETAILS_ID"];
									$value[$k]["CPT_REQUIRED_ID"] = $ck["CPT_REQUIRED_ID"];
									$value[$k]["EDIT"] = TRUE;
								# code...
								break;
						}
						$k++;
					}
				}

			}
			$result 	= array('msg'=> "", 'status'=>'Success','data'=>$value );
			return $result;	
	}
	function saveLabInvestigationResults($post_data=array())
	{	

		$save_details = $post_data["save_details"];
		if($save_details != '')
		{
			if(is_array($save_details) && count($save_details) > 0)
			{
				foreach ($save_details as $key => $value) {
					# code...
					if($value["EDIT"] == true)
					{

						$result_data = select_data("LAB_INVESTIGATION_RESULT","RESULT_VALUE","LAB_INVESTIGATION_DETAILS_ID = ".$value["LAB_INVESTIGATION_DETAILS_ID"]." AND REQ_ID = ".$value["CPT_REQUIRED_ID"]);
						if($result_data)
						{
							$ins_array = array(
								"RESULT_VALUE"=>$value["VALUE"]
							);
							$this->db->start_cache();
							$this->db->where("LAB_INVESTIGATION_DETAILS_ID",$value["LAB_INVESTIGATION_DETAILS_ID"]);
							$this->db->where("REQ_ID",$value["CPT_REQUIRED_ID"]);
							$this->db->update("LAB_INVESTIGATION_RESULT",$ins_array);
							$this->db->stop_cache();
							$this->db->flush_cache();
						}
						else
						{
							$ins_array = array(
								"REQ_ID"=>$value["CPT_REQUIRED_ID"],
								"LAB_INVESTIGATION_DETAILS_ID"=>$value["LAB_INVESTIGATION_DETAILS_ID"],
								"RESULT_CODE"=>$value["CODE"],
								"CREATED_DATE"=>date('Y-m-d H:i:s'),
								"RESULT_VALUE"=>$value["VALUE"]
							);
							$this->db->start_cache();
							$this->db->insert("LAB_INVESTIGATION_RESULT",$ins_array);
							$this->db->stop_cache();
							$this->db->flush_cache();
						}
					}
				}
				$result 	= array('msg'=> "Data saved successfully",
							'status'=>'Success',
							);

				return $result;	
			}

			
		}
		$result 	= array('msg'=> "Invalid details",
							'status'=>'Failed',
							);
		return $result;		
		
		

	}

	function GenerateBillNo($post_data=array())
	{
		$this->db->start_cache();
		$this->db->select("max(RIGHT(BILLING_INVOICE_NUMBER,6)) as BILLING_NO");
		$this->db->from("BILLING");
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$BILLING_NO = (int) $query->row()->BILLING_NO + 1;
			$prefix = 'INV';
			$duplicate_prefix = 'DINV';
			
			$result = array(
						'data'=> $prefix.sprintf('%06d', $BILLING_NO), 
						'duplicate' => $duplicate_prefix.sprintf('%06d', $BILLING_NO),
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
		$check=0;
		$result 	= array('data'=>"",
									'status'=>'Failed',
									);
		if(!empty($post_data))
		{
			if (isset($post_data["assessment_id"]))
			{
				if(isset($post_data["department_id"]) && $post_data["department_id"]==0 )
				{	
					$this->db->select("B.*");
					$this->db->from("BILLING B");
					$this->db->where("ASSESSMENT_ID",$post_data["assessment_id"]);
					$query = $this->db->get();
					if($query->num_rows() > 0)
					{
						$datas = $query->result_array();
						$flag=[];
						foreach ($datas as $key => $value) {
							if($value["GENERATED"]==1){
								$flag[]=$value["GENERATED"];
							}
						}
						if(count(array_unique($flag)) === 1) {
						  $check=1;
						} 
					}
				}
				$this->db->start_cache();
				$this->db->select("B.*");
				$this->db->from("BILLING B");
				$this->db->where("ASSESSMENT_ID",$post_data["assessment_id"]);
				if(isset($post_data["department_id"]) && $check==0 ){			
					$this->db->where("DEPARTMENT_ID",$post_data["department_id"]);
				}
				$query = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$bill_data = $query->result_array();
					foreach ($bill_data as $key => $value) {
						# code...
						$bill_data[$key]["bill_details"] = $this->getBillDetails($value["BILLING_ID"],$post_data);
							$bill_data[$key]["pateint_details"] = $this->getPatient($value);
					}
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
	public function getBillDetails($billing_id,$data = array())
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
				$this->db->join("BILLING BB","CPT.CURRENT_PROCEDURAL_CODE_ID = BB.DEPARTMENT_ID","left");						
				if(isset($data["department_id"]) && $data["department_id"] > 0){			
					$this->db->where("CPT.PROCEDURE_CODE_CATEGORY",$data["department_id"]);
					//$this->db->where("LID.BILL_STATUS_BY_DEPT",1);
				}
				$this->db->where("B.BILLING_ID",$billing_id);
				$query = $this->db->get();
				//echo $this->db->last_query();exit;
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$result = $query->result_array();
				}
			}
			return $result;
	}
	public function assessmentListByDatefordept($post_data = array())
	{

		$result = array("status"=> "Failed", "data"=> array());
		if(!empty($post_data))
		{
			if ($post_data["dateVal"] && $post_data["timeZone"] != "")
			{
				$this->db->start_cache();
				$this->db->where("DATE(CONVERT_TZ(P.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateVal"],1));	
				$this->db->where("NA.DOCTOR_STAT",1);	
				$this->db->where("NA.DOCTOR_STAT",1);	
				$this->db->select("B.*,NA.*,OP.*,
					IFNULL(P.DISCOUNT_SITE_ID,0) AS DISCOUNT_SITE_ID,
					NA.START_TIME,P.VISIT_DATE,
					OP.OP_REGISTRATION_NUMBER AS PATIENT_NO,
					C.COUNTRY_ISO3,C.COUNTRY_NAME,
					SP.OPTIONS_NAME as DEPARTMENT_NAME");
				$this->db->from("NURSING_ASSESSMENT NA");
				$this->db->join("PATIENT_VISIT_LIST P","NA.VISIT_ID = P.PATIENT_VISIT_LIST_ID","left");
				$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = NA.PATIENT_ID","left");	
				$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");	
				$this->db->join("OPTIONS SP","SP.OPTIONS_ID = P.DEPARTMENT_ID AND SP.OPTIONS_TYPE = 8","left");	
				$this->db->join("BILLING B","NA.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","left");
				$this->db->join("LAB_INVESTIGATION L","L.ASSESSMENT_ID = NA.NURSING_ASSESSMENT_ID","left");
				$this->db->join("LAB_INVESTIGATION_DETAILS LD","LD.LAB_INVESTIGATION_ID = L.LAB_INVESTIGATION_ID","left");
				// if(isset($post_data["department"]) && $post_data["department"] > 0){
				// 	$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CPT.CURRENT_PROCEDURAL_CODE_ID = LD.CURRENT_PROCEDURAL_CODE_ID AND CPT.PROCEDURE_CODE_CATEGORY = '".$post_data["department"]."'");
				// }
				$this->db->join("OP_INS_DETAILS INS","INS.OP_REGISTRATION_ID = OP.OP_REGISTRATION_ID","left");
				$this->db->order_by("NA.END_TIME","DESC");
				$this->db->order_by("NA.NURSING_ASSESSMENT_ID","DESC");
				$this->db->group_by("NA.NURSING_ASSESSMENT_ID");
				$query = $this->db->get();
				
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



	public function saveInvestigationByCashier($post_data)
	{
	
		$result = array("status"=> "Failed", "data"=> array());	
			
		$dp_data = array(
			"PATIENT_ID" => trim($post_data["patient_id"]),
			"ASSESSMENT_ID" => trim($post_data["assessment_id"])							
		 );		 
		$data = array(
			"PATIENT_ID" => trim($post_data["patient_id"]),
			"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
			"LAB_INV_PRIORITY_ID" => trim($post_data["priority"]),
			
		);	 										
		$data_id = trim($post_data["lab_investigation_id"]);
									
		$ret = $this->ApiModel->mandatory_check( $post_data , array('patient_id','assessment_id','current_procedural_code_id'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> '"'.$ret.'"Mandatory field missing');   
         }	
                       									
			if($this->utility->is_Duplicate("LAB_INVESTIGATION",array_keys($dp_data), array_values($dp_data),"LAB_INVESTIGATION_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}	
												
			if ($data_id > 0)
			{				
				$this->db->start_cache();
				$data["UPDATED_DATE"] = format_date($post_data["client_date"]);
				$data["UPDATED_BY"] = trim($post_data["user_id"]);

				$this->db->where("LAB_INVESTIGATION_ID",$post_data["lab_investigation_id"]);
				$this->db->update("LAB_INVESTIGATION",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();	

				$this->db->start_cache();

				$sql = "UPDATE LAB_INVESTIGATION SET BILLED_AMOUNT = BILLED_AMOUNT + ".$post_data['rate']." WHERE LAB_INVESTIGATION_ID = ".$data_id;

				$this->db->query($sql);
				$this->db->stop_cache();
				$this->db->flush_cache();	
										
			}
			else
			{		
				$data["DATE_LOG"] = toUtc(trim($post_data["date"]),$post_data["timeZone"]);//date('Y-m-d H:i:s'),	
				$data["CLIENT_DATE"] = format_date($post_data["client_date"]);
				$data["USER_ID"] = trim($post_data["user_id"]);
				$data["BILLED_AMOUNT"] = trim($post_data["rate"]);		
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
				$cpt  = array(
					'LAB_INVESTIGATION_ID' => $data_id,
					'CURRENT_PROCEDURAL_CODE_ID' => $post_data['current_procedural_code_id'],
					'DESCRIPTION' => $post_data['description'],
					'QUANTITY' => $post_data['quantity'],
					'RATE' => $post_data['rate'],
					'CHANGE_TO_FUTURE' => $post_data['change_of_future'],
					'REMARKS' => $post_data['remarks'],
					'LAB_PRIORITY_ID' => $post_data['priority'],
					'USER_ID' => $post_data['user_id'],
					);
				$lab_investigation_details_id = $post_data['lab_investigation_details_id'];
		
				if($post_data['lab_investigation_details_id'] > 0)
				{
					$this->db->start_cache();			
					$this->db->where("LAB_INVESTIGATION_DETAILS_ID",$post_data["lab_investigation_details_id"]);
					$this->db->where("LAB_INVESTIGATION_ID",$data_id);
					$this->db->update("LAB_INVESTIGATION_DETAILS",$cpt);	
					$this->db->stop_cache();
					$this->db->flush_cache();
					$result =  array("status"=> "Success", "data_id"=>$data_id, "lab_investigation_details_id"=>$lab_investigation_details_id ,"msg"=> "CPT items added successfully..!")	;
				}
				else
				{
					$dc_data = array(
						"CURRENT_PROCEDURAL_CODE_ID" => trim($post_data["current_procedural_code_id"])
				 	);		
		                       									
					$this->db->start_cache();			
					$this->db->insert("LAB_INVESTIGATION_DETAILS",$cpt);
					$lab_investigation_details_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					$result =  array("status"=> "Success", "data_id"=>$data_id, "lab_investigation_details_id"=>$lab_investigation_details_id ,"msg"=> "CPT items added successfully..!")	;
				}
			}
		
		return $result;
	}

	public function deleteInvestigationByCashier($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data_id = $post_data["lab_investigation_id"];

		if($post_data['lab_investigation_details_id'] > 0)
		{
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("LAB_INVESTIGATION_DETAILS");
			$this->db->where("LAB_INVESTIGATION_DETAILS_ID", $post_data["lab_investigation_details_id"]);
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() == 0)
			{				
				$result = array("status"=> "Failed","msg"=>"Record Can't be deleted, Reference data is not available");
				
			}	
			else
			{
				$this->db->start_cache();

				$sql = "UPDATE LAB_INVESTIGATION SET BILLED_AMOUNT = BILLED_AMOUNT - ".$post_data['rate']." WHERE LAB_INVESTIGATION_ID = ".$data_id;

				$this->db->query($sql);
				$this->db->stop_cache();
				$this->db->flush_cache();	
										
				$this->db->start_cache();			
				$this->db->where("LAB_INVESTIGATION_DETAILS_ID",$post_data["lab_investigation_details_id"]);
				$this->db->delete("LAB_INVESTIGATION_DETAILS");	
				$this->db->stop_cache();
				$this->db->flush_cache();
				$result =  array("status"=> "Success",  "msg"=> "CPT items deleted successfully..!")	;
			}	
			

			
		}
		return $result;
		
	}



	public function getPendingAmount($post_data = array())
 	{
 		if ($post_data["patient_id"] > 0 && (isset($post_data["patient_id"])) && (isset($post_data["assessment_id"])))
 		{
	 		$this->db->where("B.PATIENT_ID",$post_data["patient_id"]);	
	 		$this->db->where("B.ASSESSMENT_ID<",$post_data["assessment_id"]);	
	 		$this->db->where("B.IS_AVAILABLE_PENDING",1);	
			$this->db->select("B.BALANCE_PENDING");
			$this->db->from("BILLING B");
			$this->db->limit(1);
			
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$data=$query->row_array();
				$result = array('data'=>$data["BALANCE_PENDING"],
									'status'=>'Success',
									);
				return $result;
			}
			else 
			{
				$result 	= array('data'=>0,
									'status'=>'Failed',
									);
				return $result;
			}
 		}
 		else
 		{
 			$result 	= array('data'=>0,
									'status'=>'Failed',
									);
				return $result;
 		}

 	}

 	public function updatependingpayment($id){

 		$ins_array  = array('IS_AVAILABLE_PENDING' =>0 );
 		$this->db->start_cache();
		$this->db->where("PATIENT_ID",$id);
		$this->db->update("BILLING",$ins_array);
		$this->db->stop_cache();
		$this->db->flush_cache();
 	}


} 
?>
