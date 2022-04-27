<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OpRegistrationModel extends CI_Model 
{
	public function getemri($post_data = array())
 	{
 		if($post_data["app_id"] && $post_data["app_id"]  != "")
 		{
 			$this->db->where("IN.OPTIONS_NAME",$post_data["app_id"]);	
 			$this->db->where("IN.OPTIONS_TYPE",3);	
 			$this->db->where("IN.OPTIONS_STATUS",1);
			$this->db->select("IN.OPTIONS_ID");
			$this->db->from("OPTIONS IN");
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$data= $query->row();
				$result 	= array('data'=>$data,
								   'status'=>'Success',
								   );
				return $result ;
			}
			else
			{
				 $result 	= array('data'=>"",
								   'status'=>'Failed',
								   );
				return $result ;
			}
 		}
 		else
 		{
 			 $result 	= array('data'=>"",
								   'status'=>'Failed',
								   );
 			return $result ;
 		}
 	}
 	
	function GenerateOpNo($post_data=array())
	{
		$this->db->start_cache();
		$this->db->select("max(RIGHT(OP_REGISTRATION_NUMBER,7)) as REG_NO");
		$this->db->from("OP_REGISTRATION");
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$REG_NO = (int) $query->row()->REG_NO + 1;
			// $prefix = date('ym');
			$prefix = OP_NUMBER_PREFIX;
			
			$result 	= array('data'=> $prefix.sprintf('%07d', $REG_NO),
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
	function ListCountry($post_data = array())
	{
		
		
		//if ($post_data["login_type"] == WEB_ADMINISTRATOR)
		//{
			$this->db->start_cache();
			$this->db->select("C.*");
			$this->db->from("COUNTRY C");
			$this->db->order_by("C.COUNTRY_NAME","asc");
			$query = $this->db->get();
			
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result 	= array('data'=>$query->result(),
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
		/*}
		else 
		{
			$result 	= array('data'=>"",
								'status'=>'Failed',
								);
			return $result;
		}*/
			
		//return $query->row();		
	}
	function ListCompanyByCategory($post_data = array())
	{
		
		
		if (!empty($post_data) && $post_data["cat_id"] != "")
		{
			$this->db->start_cache();
			$this->db->select("C.COMPANY_NAME,C.COMPANY_ID,C.COMPANY_LOGO_NAME");
			$this->db->from("COMPANY_BUSINESS_CATEGORY CB");
			$this->db->join("COMPANY C","CB.CBC_COMPANY_ID = C.COMPANY_ID","left");
			$this->db->where("CB.CBC_BUS_CAT_ID",$post_data["cat_id"]);
			$this->db->order_by("C.COMPANY_NAME","asc");
			$this->db->group_by("C.COMPANY_ID");
			$query = $this->db->get();
			//echo  $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->result();
				//print_r($data);
				$result 	= array('data'=>$data,
									'status'=>'Success',
									'logo_path'=>base_url().COMPANY_LOGO_PATH,
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
	public function getPatientDetailsVisit($post_data = array())
 	{
 		if (!empty($post_data))
 		{
 			if($post_data["op_number"] != "" || $post_data["op_reg_id"] > 0)
 			{
 				if($post_data["op_number"])
	 			$this->db->where("PV.PATIENT_NO",$post_data["op_number"]);	
		 		if($post_data["op_reg_id"])
		 			$this->db->where("PV.PATIENT_ID",$post_data["op_reg_id"]);	
		 		$this->db->where("OP_REGISTRATION_STATUS",1);	
		 		$this->db->where("DATE(CONVERT_TZ(PV.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateVal"],1));
				$this->db->select("PV.PATIENT_VISIT_LIST_ID,OP.*, RT.OPTIONS_NAME as PAY_TYPE, C.COUNTRY_NAME as COUNTRY_NAME, C.COUNTRY_NAME as NATIONALITY_NAME, EM.OPTIONS_NAME as EMIRATES_NAME, SI.OPTIONS_NAME as SOURCE_OF_INFO_NAME");
				$this->db->from("PATIENT_VISIT_LIST PV");
				$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = PV.PATIENT_ID","left");
				$this->db->join("OPTIONS RT","RT.OPTIONS_ID = OP.OP_REGISTRATION_TYPE","left");
				$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.COUNTRY","left");
				$this->db->join("COUNTRY N","N.COUNTRY_ID = OP.NATIONALITY","left");
				$this->db->join("OPTIONS EM","EM.OPTIONS_ID = OP.EMIRATES","left");
				$this->db->join("OPTIONS SI","SI.OPTIONS_ID = OP.SOURCE_OF_INFO","left");
				$query = $this->db->get();
				//echo $this->db->last_query();
				if($query->num_rows() > 0)
				{
					$data["patient_data"] = $query->row();  
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
 		if (!empty($post_data))
 		{
 			if($post_data["op_number"] != "" || $post_data["op_reg_id"] > 0)
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
				//echo $this->db->last_query();
				if($query->num_rows() > 0)
				{
					$data["patient_data"] = $query->row();

					$data["ins_data"] = $this->getInsDetails($data["patient_data"]->OP_REGISTRATION_ID);
					$data["co_ins"] = array();
					if($data["ins_data"] != 0)
					{
						if($data["ins_data"]->OP_INS_IS_ALL != 1)
						{
							$data["co_ins"] = $this->getCoinsDetails($data["ins_data"]->OP_INS_DETAILS_ID);
						}
					}
					$data["corporate_data"] = $this->getCorporateDetails($data["patient_data"]->OP_REGISTRATION_ID);
					$data["op_attachment"] = $this->getAttachment($data["patient_data"]->OP_REGISTRATION_ID);
					$data["old_insurance"] = $this->getOldInsDetails($data["patient_data"]->OP_REGISTRATION_ID);
					$data["patient_data"]->general=$this->getPatientConsents_id($data["patient_data"]->OP_REGISTRATION_NUMBER,3);
					$data["patient_data"]->dental=$this->getPatientConsents_id($data["patient_data"]->OP_REGISTRATION_NUMBER,1);
					$data["patient_data"]->covid=$this->getPatientConsents_id($data["patient_data"]->OP_REGISTRATION_NUMBER,2);
					//$data["old_co_ins"] = array();
					if($data["old_insurance"] != 0)
					{
						foreach($data["old_insurance"] as $key => $value) {
							if($value['OP_INS_IS_ALL'] != 1)
							{
								$data["old_insurance"][$key]["old_co_ins"] =  $this->getOldCoinsDetails($value["OP_INS_DETAILS_ID"]);
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
	 	else
 		{
 			$result 	= array('data'=>"",
									'status'=>'Failed',
									);
				return $result;
 		}

 	}

	 public function getPatientConsents_id($p_id,$type)
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
 	public function getPatientByEIDnumber($post_data = array())
 	{
 		$result = array('data'=>"",'status'=>'Failed');
 		if (!empty($post_data))
 		{
 			if($post_data["eid_number"] != "")
 			{
 				if($post_data["eid_number"]){
	 				$this->db->where("NATIONAL_ID",trim($post_data["eid_number"]));	
	 			}
				$this->db->select("OP.*, RT.OPTIONS_NAME as PAY_TYPE, C.COUNTRY_NAME as COUNTRY_NAME, C.COUNTRY_NAME as NATIONALITY_NAME, EM.OPTIONS_NAME as EMIRATES_NAME, SI.OPTIONS_NAME as SOURCE_OF_INFO_NAME");
				$this->db->from("OP_REGISTRATION OP");
				$this->db->join("OPTIONS RT","RT.OPTIONS_ID = OP.OP_REGISTRATION_TYPE","left");
				$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.COUNTRY","left");
				$this->db->join("COUNTRY N","N.COUNTRY_ID = OP.NATIONALITY","left");
				$this->db->join("OPTIONS EM","EM.OPTIONS_ID = OP.EMIRATES","left");
				$this->db->join("OPTIONS SI","SI.OPTIONS_ID = OP.SOURCE_OF_INFO","left");
				$query = $this->db->get();
				//echo $this->db->last_query();
				if($query->num_rows() > 0)
				{
					$data["patient_data"] = $query->row();

					$data["ins_data"] = $this->getInsDetails($data["patient_data"]->OP_REGISTRATION_ID);
					$data["co_ins"] = array();
					if($data["ins_data"] != 0)
					{
						if($data["ins_data"]->OP_INS_IS_ALL != 1)
						{
							$data["co_ins"] = $this->getCoinsDetails($data["ins_data"]->OP_INS_DETAILS_ID);
						}
					}
					$data["corporate_data"] = $this->getCorporateDetails($data["patient_data"]->OP_REGISTRATION_ID);
					$data["op_attachment"] = $this->getAttachment($data["patient_data"]->OP_REGISTRATION_ID);
					$data["old_insurance"] = $this->getOldInsDetails($data["patient_data"]->OP_REGISTRATION_ID);
					
					if($data["old_insurance"] != 0)
					{
						foreach($data["old_insurance"] as $key => $value) {
							if($value['OP_INS_IS_ALL'] != 1)
							{
								$data["old_insurance"][$key]["old_co_ins"] =  $this->getOldCoinsDetails($value["OP_INS_DETAILS_ID"]);
							}
						}
					}
					//print_r($data);
					$result 	= array('data'=>$data,'status'=>'Success'
										);
			
				}
				
	 		}
	 	}
		return $result;		
 	}
 	public function getAttachment($OP_REGISTRATION_ID =0)
 	{
 		if($OP_REGISTRATION_ID != 0)
 		{
 			$this->db->where("A.OP_REGISTRATION_ID",$OP_REGISTRATION_ID);	
			$this->db->select("A.*");
			$this->db->from("OP_REGISTRATION_ATTACHMENT A");
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

	public function getOldInsDetails($OP_REGISTRATION_ID =0)
 	{
 		if($OP_REGISTRATION_ID != 0)
 		{
 			$this->db->where("IN.OP_REGISTRATION_ID",$OP_REGISTRATION_ID);	
			$this->db->select("IN.*,INP.INSURANCE_PAYERS_ECLAIM_LINK_ID,
				INP.INSURANCE_PAYERS_NAME,INN.INS_NETWORK_NAME");
			$this->db->from("OP_INS_DETAILS IN");
			$this->db->join("INSURANCE_PAYERS INP","INP.INSURANCE_PAYERS_ID = IN.OP_INS_PAYER","left");
			$this->db->join("INS_NETWORK INN","INN.INS_NETWORK_ID = IN.OP_INS_NETWORK","left");
			$this->db->order_by("IN.OP_INS_STATUS","DESC");
			$this->db->order_by("IN.OP_INS_DETAILS_ID","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result_array();
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
 	public function getOldCoinsDetails($OP_INS_DETAILS_ID =0)
 	{
 		if($OP_INS_DETAILS_ID != 0)
 		{
 			$this->db->where("CI.OP_INS_DETAILS_ID",$OP_INS_DETAILS_ID);
 			// $this->db->where("CI.OP_COIN_DATA_STATUS",0);		
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
 	public function getCodeByCountry($post_data = array())
 	{
 		$this->db->where("COUNTRY_ID",$post_data["data_id"]);	
		$this->db->select("COUNTRY_PHONECODE");
		$this->db->from("COUNTRY");
		$query = $this->db->get();

		if ($query->num_rows() ==1)
		{	 		
 			$data = ($query->row()->COUNTRY_PHONECODE == NULL ? "": "+".$query->row()->COUNTRY_PHONECODE);
 		}
 		else
 		{
 			$data = "";
 		}
 		return $data;

 	}
 	public function isExistEidNumber($op_id = 0,$national_id)
	 	{
	 		$this->db->start_cache();
			$this->db->select("*");
			$this->db->from("OP_REGISTRATION");
			if($op_id > 0)
			{
				$this->db->where("OP_REGISTRATION_ID !=",$op_id);
			}
			$this->db->where("NATIONAL_ID",$national_id);
			$this->db->where("NATIONAL_ID !=",'111-1111-1111111-1');
			$this->db->where("NATIONAL_ID !=",'999-9999-9999999-9');
			$query = $this->db->get();
				// echo $this->db->last_query();
	 		$this->db->stop_cache();
	 		$this->db->flush_cache();
			if ($query->num_rows() > 0)
				return true;
			else
				return false;
 	}
 	public function getCompanyData($post_data = array())
 	{
		$this->db->select("C.*,GROUP_CONCAT(CC.CBC_BUS_CAT_ID) as CBC_BUS_CAT_ID");
		$this->db->from("COMPANY C");
		$this->db->join("COMPANY_BUSINESS_CATEGORY CC","CC.CBC_COMPANY_ID = C.COMPANY_ID","left");
 		$this->db->where("C.COMPANY_ID",$post_data["data_id"]);	
		$this->db->group_by("C.COMPANY_ID");
		$query = $this->db->get();

		if($query->num_rows() > 0)
		{

			$result 	= array('data'=>$query->row(),
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
	public function registerNew($post_data = array())
	{
		$data=array(
			
		"OP_REGISTRATION_DATE" 			=> toUtc(trim($post_data["dateVal"]),$post_data["timeZone"]),
		"OP_REGISTRATION_TYPE" 		=> trim($post_data["sel_pay_type"]),
		"FIRST_NAME" 	=> trim($post_data["f_name"]),
		"MIDDLE_NAME" 		=> trim($post_data["m_name"]),
		"LAST_NAME" 	=> trim($post_data["l_name"]),
		"GENDER" 		=> trim($post_data["gender"]),
		"DOB" 		=> trim($post_data["birthdate"]),
		"AGE" 		=> trim($post_data["age"]),
		"MONTHS" 	=> trim($post_data["months"]),
		"DAYS" 		=> trim($post_data["days"]),
		"MOBILE_NO" 	=> trim($post_data["mobile_no"]),
		"EMAIL_ID" 	=> trim($post_data["email"]),
		"ADDRESS" 		=> trim($post_data["address"]),
		"PO_BOX_NO" 			=> trim($post_data["po_box"]),
		"COUNTRY" 				=> $post_data['country'],
		"NATIONALITY" 				=> $post_data['nationality'],
		"NATIONAL_ID" 				=> $post_data['national_id'],
		"EMIRATES" 				=> $post_data['sel_emirates'],
		"RES_NO" 				=> $post_data['res_no'],
		"CITY" 				=> $post_data['city'],
		"SERVICE" 				=> $post_data['service'],
		"VISA_STATUS" 				=> $post_data['visa_stat'],
		"FAX" 				=> $post_data['fax'],
		"SOURCE_OF_INFO" 				=> $post_data['sel_source_of_info'],
		
		"CREATED_USER" 				=> (int) $post_data['user_id'],
		"CREATED_DATE" => date('Y-m-d H:i:s'),	
		"CLIENT_DATE" => format_date($post_data["client_date"])	
				);
		/*

		

		$this->load->model("MasterModel");
		if($post_data["imagedataurl"] != "")
		{
			$image_name=$this->MasterModel->Writebase64($post_data["imagedataurl"],FCPATH.COMPANY_LOGO_PATH);
			if($image_name!="")
			{
				$data["COMPANY_LOGO_NAME"]=$image_name;			
			}	
		}*/
		if(isset($post_data["reg_id"]))
			$data_id = (int) $post_data["reg_id"];
		else
			$data_id = 0;
		$ret = $this->isExistEidNumber($data_id,$post_data['national_id']);
		if($ret)
		{
			return $result = array("status"=> "Failed", "reg_id"=>0, "reg_no"=> 0 ,"msg"=>"National ID is already exsist");
		}
		//$fields = array("0"=>"COMPANY_NAME","1"=>"COMPANY_CODE");
		//$values = array("0"=>,"1"=>$post_data['COMPANY_CODE'));
		/*if($this->utility->is_Duplicate("COMPANY","COMPANY_CODE",$post_data['COMPANY_CODE'],"COMPANY_ID",$data_id))
		{
			return 2;
		}
		if($this->utility->is_Duplicate("COMPANY","COMPANY_NAME",$post_data['COMPANY_NAME'],"COMPANY_ID",$data_id))
		{
			return 3;
		}
		if( $post_data['COMPANY_ACCESS_TYPE'] == 1 && $post_data['SYMP_ID'] != "" && $post_data['SYMP_ID'] !=null)
		{
			if($this->utility->is_Duplicate("COMPANY","COMPANY_SYMP_ID",$post_data['SYMP_ID'],"COMPANY_ID",$data_id))
			{
				return 4;
			}
			$data["COMPANY_SYMP_ID"] = trim($post_data["COMPANY_SYMP_ID"]);
		}
		*/
		$result = array("status"=> "Failed", "reg_id"=>0, "reg_no"=> 0 ,"msg" =>"Failed to save the details !");
		if ($data_id> 0)
		{
			
			$this->db->where("OP_REGISTRATION_ID",$data_id);
			$this->db->update("OP_REGISTRATION",$data);

			$corporate_date = array(
				"OP_REGISTRATION_ID" 			=> $data_id,
				"OP_CORPORATE_COMPANY_ID" 		=> $post_data['corporate_company'],
				"OP_COMPANY_STATUS" 			=> 1,
				"UPDATED_DATE" 				    => date('Y-m-d H:i:s'),
				"CLIENT_DATE" 				    => format_date($post_data["client_date"])	
			);
			if($post_data['sel_pay_type'] == 3)
			{

				$this->db->start_cache();
				$this->db->select("*");
				$this->db->from("OP_CORPORATE_DETAILS");
				$this->db->where("OP_REGISTRATION_ID",$data_id);
				$query = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$this->db->where("OP_REGISTRATION_ID",$data_id);
					$this->db->update("OP_CORPORATE_DETAILS",array("OP_COMPANY_STATUS"=>0));
				}

				$this->db->start_cache();
				$this->db->select("*");
				$this->db->from("OP_INS_DETAILS");
				$this->db->where("OP_REGISTRATION_ID",$data_id);
				$querys = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($querys->num_rows() > 0)
				{
					$this->db->where("OP_REGISTRATION_ID",$data_id);
					$this->db->update("OP_INS_DETAILS",array("OP_INS_STATUS"=>0));
				}

				$this->db->insert("OP_CORPORATE_DETAILS",$corporate_date);
			}
			// print_r($post_data);exit();
			// if($post_data['rest_of_all_check'] == 1)
			// {
			// 	$INS_DATA["OP_INS_IS_ALL"] = $post_data['rest_of_all_check'];
			// 	$INS_DATA["OP_INS_ALL_TYPE"] = $post_data['rest_of_all_type'];
			// 	$INS_DATA["OP_INS_ALL_VALUE"] = $post_data['rest_of_all'];
			// }
			
			
			$INS_DATA = array(
				"OP_REGISTRATION_ID" 			=> $data_id,
				"OP_INS_TPA" 					=> $post_data['sel_tpa_receiver'],
				"OP_INS_PAYER" 					=> $post_data['sel_ins_co'],
				"OP_INS_NETWORK" 				=> $post_data['sel_network'],
				"OP_INS_MEMBER_ID" 				=> $post_data['memebr_id'],
				"OP_INS_POLICY_NO" 				=> $post_data['policy_no'],
				"OP_INS_VALID_FROM" 			=> format_date($post_data['valid_from']),
				"OP_INS_VALID_TO" 				=> format_date($post_data['valid_to']),
				"OP_INS_DEDUCTIBLE" 			=> $post_data['deductible'],
				"OP_INS_DEDUCT_TYPE" 			=> $post_data['deductible_type'],
				"OP_INS_IS_ALL" 			    => $post_data['rest_of_all_check'],
				"OP_INS_ALL_TYPE" 			    => $post_data['rest_of_all_type'],
				"OP_INS_ALL_VALUE" 			    => $post_data['rest_of_all'],
			);
			// print_r($post_data);exit();
			// if($post_data['rest_of_all_check'] == 1)
			// {
			// 	$INS_DATA["OP_INS_IS_ALL"] = $post_data['rest_of_all_check'];
			// 	$INS_DATA["OP_INS_ALL_TYPE"] = $post_data['rest_of_all_type'];
			// 	$INS_DATA["OP_INS_ALL_VALUE"] = $post_data['rest_of_all'];
			// }
			if($post_data['sel_pay_type'] == INSURANCE_ID_CONSTANT)
			{

				
				$this->db->start_cache();
				$this->db->select("*");
				$this->db->from("OP_CORPORATE_DETAILS");
				$this->db->where("OP_REGISTRATION_ID",$data_id);
				$query = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$this->db->where("OP_REGISTRATION_ID",$data_id);
					$this->db->update("OP_CORPORATE_DETAILS",array("OP_COMPANY_STATUS"=>0));
				}

				// $this->db->start_cache();
				// $this->db->select("*");
				// $this->db->from("OP_INS_DETAILS");
				// $this->db->where("OP_REGISTRATION_ID",$data_id);
				// $querys = $this->db->get();
				// $this->db->stop_cache();
				// $this->db->flush_cache();
				// if($querys->num_rows() > 0)
				// {
				// 	$this->db->where("OP_REGISTRATION_ID",$data_id);
				// 	$this->db->update("OP_INS_DETAILS",array("OP_INS_STATUS"=>0));

				// }
				// $this->db->insert("OP_INS_DETAILS",$INS_DATA);
				// $ins_id = $this->db->insert_id();
				if(isset($post_data['ins_detail_id']) && $post_data['ins_detail_id'] > 0 && isset($post_data['edit_ins']) && $post_data['edit_ins'] == 1)
				{
					
					$this->db->where("OP_INS_DETAILS_ID",$post_data['ins_detail_id']);
					$this->db->where("OP_REGISTRATION_ID",$data_id);
					$this->db->update("OP_INS_DETAILS",$INS_DATA);

					$ins_id = $post_data['ins_detail_id'];
				}
				else
				{

					
					if($post_data['sel_tpa_receiver']!= '' || $post_data['sel_ins_co'] != '' || $post_data['sel_network'] != ''  )
					{
						if($post_data['sel_tpa_receiver'] == '')
						{
							return array("status" => "warning" ,"msg"=> "Please select TPA reciever");
						}
						
						if($post_data['sel_ins_co'] == '')
						{
							return array("status" => "warning" ,"msg"=> "Please select Insurance co payer");
						}
						if($post_data['sel_network'] == '')
						{
							return array("status" => "warning" ,"msg"=> "Please select IInsurance nerwork");
						}
						if($post_data['memebr_id'] == '')
						{
							return array("status" => "warning" ,"msg"=> "Please select Insurance member id !");
						}
						if($post_data['policy_no'] == '')
						{
							return array("status" => "warning" ,"msg"=> "Please select Insurance policy number");
						}
						if($post_data['valid_from'] == '')
						{
							return array("status" => "warning" ,"msg"=> "Please select Insurance validity");
						}
						if($post_data['valid_to'] == '')
						{
							return array("status" => "warning" ,"msg"=> "Please select Insurance validity");
						}
					
						$this->db->start_cache();
						$this->db->select("*");
						$this->db->from("OP_INS_DETAILS");
						$this->db->where("OP_REGISTRATION_ID",$data_id);
						$querys = $this->db->get();
						$this->db->stop_cache();
						$this->db->flush_cache();
						if($querys->num_rows() > 0)
						{
							$this->db->where("OP_REGISTRATION_ID",$data_id);
							$this->db->update("OP_INS_DETAILS",array("OP_INS_STATUS"=>0));

						}
						$this->db->insert("OP_INS_DETAILS",$INS_DATA);
						$ins_id = $this->db->insert_id();
					}
				}

			}
			
			//echo $this->db->last_query();
			if($ins_id > 0)
			{
				if($post_data['rest_of_all_check'] == 0 && !empty($post_data['co_in_selects']))
				{
					$coins = $post_data['co_in_selects'];

					$coin_arr = array();
					if(is_array($coins))
					{
						$this->db->start_cache();
						$this->db->select("*");
						$this->db->from("OP_COIN_DATA");
						$this->db->where("OP_REGISTRATION_ID",$data_id);
						$this->db->where("OP_INS_DETAILS_ID",$ins_id);
						$querys = $this->db->get();
						$this->db->stop_cache();
						$this->db->flush_cache();
						if($querys->num_rows() > 0)
						{
							$this->db->where("OP_INS_DETAILS_ID",$ins_id);
							$this->db->where("OP_REGISTRATION_ID",$data_id);
							$this->db->delete("OP_COIN_DATA");
						}
						foreach ($coins as $key => $value) 
						{
							$coin_arr[$key]["OP_REGISTRATION_ID"] = $data_id;
							$coin_arr[$key]["OP_INS_DETAILS_ID"] = $ins_id;
							$coin_arr[$key]["COIN_ID"] = $value["type_id"];
							$coin_arr[$key]["COIN_NAME"] = $value["type_name"];
							$coin_arr[$key]["COIN_VALUE"] = $value["deduct_val"];
							$coin_arr[$key]["COIN_VALUE_TYPE"] = $value["deduct_type"];
					
						}	# code...			
						if($post_data['sel_pay_type'] == 1)
							$this->db->insert_batch('OP_COIN_DATA', $coin_arr); 
					}
				}
			// if($post_data['sel_pay_type'] == 1 && !empty($coin_arr))
			// {
			// 	if(isset($post_data['ins_detail_id']) && $post_data['ins_detail_id'] > 0)
			// 	{
			// 		$this->db->start_cache();
			// 		$this->db->select("*");
			// 		$this->db->from("OP_COIN_DATA");
			// 		$this->db->where("OP_REGISTRATION_ID",$data_id);
			// 		$this->db->where("OP_INS_DETAILS_ID",$post_data['ins_detail_id']);
			// 		$querys = $this->db->get();
			// 		$this->db->stop_cache();
			// 		$this->db->flush_cache();
			// 		if($querys->num_rows() > 0)
			// 		{
			// 			$this->db->where("OP_INS_DETAILS_ID",$post_data['ins_detail_id']);
			// 			$this->db->where("OP_REGISTRATION_ID",$data_id);
			// 			$this->db->delete("OP_COIN_DATA");
			// 		}
			// 	}
			// 	$this->db->insert_batch("OP_COIN_DATA", $coin_arr); 
			// }
			}
			if(!empty($post_data['upload_file']))
			{
				$this->op_attachment($post_data,$data_id);
			}
			if(!empty($post_data["removeUpload"]))
			{
				$this->remove_attachment($post_data,$data_id);
			}		
				//$coin_id = $this->db->insert_id();
			$result = array("status"=> "Success", "reg_id"=>$data_id, "reg_no"=> $data["OP_REGISTRATION_NUMBER"] ,"msg" =>"Op registration details saved successfully..!")	;
		}
		else
		{
			
			$gen_no=$this->GenerateOpNo($post_data);
			$data["OP_REGISTRATION_NUMBER"]=$gen_no["data"];
			$this->db->insert("OP_REGISTRATION",$data);
			$data_id = $this->db->insert_id();
			if(!empty($post_data['upload_file']))
			{
				$this->op_attachment($post_data,$data_id);
			}

			$corporate_data = array(
				"OP_REGISTRATION_ID" 			=> $data_id,
				"OP_CORPORATE_COMPANY_ID" 		=> $post_data['corporate_company'],
				"OP_COMPANY_STATUS" 			=> 1,
				"CREATED_DATE" 				    => date('Y-m-d H:i:s'),
				"CLIENT_DATE" 				    => format_date($post_data["client_date"])
			);
			if($post_data['sel_pay_type'] == 3)
			{

				$this->db->start_cache();
				$this->db->select("*");
				$this->db->from("OP_CORPORATE_DETAILS");
				$this->db->where("OP_REGISTRATION_ID",$data_id);
				$query = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$this->db->where("OP_REGISTRATION_ID",$data_id);
					$this->db->update("OP_CORPORATE_DETAILS",array("OP_COMPANY_STATUS"=>0));
				}

				$this->db->start_cache();
				$this->db->select("*");
				$this->db->from("OP_INS_DETAILS");
				$this->db->where("OP_REGISTRATION_ID",$data_id);
				$querys = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($querys->num_rows() > 0)
				{
					$this->db->where("OP_REGISTRATION_ID",$data_id);
					$this->db->update("OP_INS_DETAILS",array("OP_INS_STATUS"=>0));
				}

				$this->db->insert("OP_CORPORATE_DETAILS",$corporate_data);
			}
			$INS_DATA = array(
				"OP_REGISTRATION_ID" 			=> $data_id,
				"OP_INS_TPA" 					=> $post_data['sel_tpa_receiver'],
				"OP_INS_PAYER" 					=> $post_data['sel_ins_co'],
				"OP_INS_NETWORK" 				=> $post_data['sel_network'],
				"OP_INS_MEMBER_ID" 				=> $post_data['memebr_id'],
				"OP_INS_POLICY_NO" 				=> $post_data['policy_no'],
				"OP_INS_VALID_FROM" 			=> format_date($post_data['valid_from']),
				"OP_INS_VALID_TO" 				=> format_date($post_data['valid_to']),
				"OP_INS_DEDUCTIBLE" 			=> $post_data['deductible'],
				"OP_INS_DEDUCT_TYPE" 			=> $post_data['deductible_type'],
				"OP_INS_IS_ALL" 				=> $post_data['rest_of_all_check'],
				"OP_INS_ALL_TYPE" 				=> $post_data['rest_of_all_type'],
				"OP_INS_ALL_VALUE" 				=> $post_data['rest_of_all']
			);
			if($post_data['sel_pay_type'] == INSURANCE_ID_CONSTANT){

				$this->db->start_cache();
				$this->db->select("*");
				$this->db->from("OP_CORPORATE_DETAILS");
				$this->db->where("OP_REGISTRATION_ID",$data_id);
				$query = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$this->db->where("OP_REGISTRATION_ID",$data_id);
					$this->db->update("OP_CORPORATE_DETAILS",array("OP_COMPANY_STATUS"=>0));
				}

				$this->db->start_cache();
				$this->db->select("*");
				$this->db->from("OP_INS_DETAILS");
				$this->db->where("OP_REGISTRATION_ID",$data_id);
				$querys = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($querys->num_rows() > 0)
				{
					$this->db->where("OP_REGISTRATION_ID",$data_id);
					$this->db->update("OP_INS_DETAILS",array("OP_INS_STATUS"=>0));
				}
				
				$this->db->insert("OP_INS_DETAILS",$INS_DATA);
			}
			$ins_id = $this->db->insert_id();
			//echo $this->db->last_query();
			if($post_data["app_id"] != 0)
			{
				$this->apoinmentUpdate($post_data["app_id"],$data_id,$data["OP_REGISTRATION_NUMBER"]);
			}
			if($post_data['rest_of_all_check'] == 0 && !empty($post_data['co_in_selects']))
			{
				$coins = $post_data['co_in_selects'];

				$coin_arr = array();
				if(is_array($coins))
				{
					foreach ($coins as $key => $value) 
					{
						$coin_arr[$key]["OP_REGISTRATION_ID"] = $data_id;
						$coin_arr[$key]["OP_INS_DETAILS_ID"] = $ins_id;
						$coin_arr[$key]["COIN_ID"] = $value["type_id"];
						$coin_arr[$key]["COIN_NAME"] = $value["type_name"];
						$coin_arr[$key]["COIN_VALUE"] = $value["deduct_val"];
						$coin_arr[$key]["COIN_VALUE_TYPE"] = $value["deduct_type"];
				
					}	# code...			
					if($post_data['sel_pay_type'] == 1)
						$this->db->insert_batch('OP_COIN_DATA', $coin_arr); 
				}
			}
			
			$result = array("status"=> "Success", "reg_id"=>$data_id, "reg_no"=> $data["OP_REGISTRATION_NUMBER"], "msg"=>"OP registration details saved successfully!")	;
		}
		return $result;
	}
	public function consentNew($post_data = array())
	{
	

		$data=array(
			
			"SIGNED_ON" 			=> toUtc(trim($post_data["dateVal"]),$post_data["timeZone"]),
			"VISIT_ID" 	=> trim($post_data["patient_visit"]),
			//"ACCET_TERMS" 		=> trim($post_data["accept_terms"]),
			"OP_NUMBER" 	=> trim($post_data["opnumber"]),
			"CONSENT_TYPE" 		=> trim($post_data["type"]),
			"DATA" 		=> json_encode($post_data["selected_options"]),
			//"SIGNATURE" 		=> $post_data["sign"],
			"LANGUAGE_READ" 		=> trim($post_data["lang"]),
			"CREATED_DATE" => date('Y-m-d H:i:s'),
		);
		

		

		if($post_data["sign"] != "")
		{
			$this->load->model("api/FileUploadModel");
			$base64url = str_replace('data:image/png;base64,', '', $post_data["sign"]);
			$base64url = str_replace(' ', '+', $base64url);
			$image_name=$this->FileUploadModel->Writebase64Files($base64url,'consent');
			if($image_name!="")
			{
				$data["SIGNATURE"]=$image_name;			
			}	
		}
		
			
			
			$this->db->insert("PATIENT_CONSENT",$data);
			$data_id = $this->db->insert_id();
			
			$result = array("status"=> "Success", "reg_id"=>$data_id,  "msg"=>"Consent details saved successfully!")	;
		
		return $result;
	}
	public function consentNewGeneral($post_data = array())
	{
	

		$data=array(
			
			"SIGNED_ON" 			=> toUtc(trim($post_data["dateVal"]),$post_data["timeZone"]),
			"VISIT_ID" 	=> trim($post_data["patient_visit"]),
			"LANGUAGE_READ" 		=> trim($post_data["lang"]),
			"CONSENT_TYPE" 		=> trim($post_data["type"]),
			"OP_NUMBER" 	=> trim($post_data["opnumber"]),
			//"DATA" 		=> json_encode($post_data["selected_options"]),
			//"SIGNATURE" 		=> $post_data["sign"],
			"CREATED_DATE" => date('Y-m-d H:i:s'),
		);
		

		

		if($post_data["sign"] != "")
		{
			$this->load->model("api/FileUploadModel");
			$base64url = str_replace('data:image/png;base64,', '', $post_data["sign"]);
			$base64url = str_replace(' ', '+', $base64url);
			$image_name=$this->FileUploadModel->Writebase64Files($base64url,'consent');
			if($image_name!="")
			{
				$data["SIGNATURE"]=$image_name;			
			}	
		}
		
			
			
			$this->db->insert("PATIENT_CONSENT",$data);
			$data_id = $this->db->insert_id();
			
			$result = array("status"=> "Success", "reg_id"=>$data_id,  "msg"=>"Consent details saved successfully!")	;
		
		return $result;
	}
	public function updateInsuranceDetails($post_data = array())
	{

		$OP_REGISTRATION_ID = trim($post_data["OP_REGISTRATION_ID"]);
		$INS_DATA = $post_data["INS_DATA"];
		$CO_IN_DATA = $post_data["CO_IN_DATA"];
		$STATUS = $post_data["STATUS"];
		
		$data = array();	
		$result = array("status"=> "Failed", "message"=> "Insurance details update failed" ,"data"=>array());
		if ($OP_REGISTRATION_ID> 0)
		{

			if($STATUS == 1)
			{
				$this->db->start_cache();
				$this->db->select("*");
				$this->db->from("OP_INS_DETAILS");
				$this->db->where("OP_REGISTRATION_ID",$OP_REGISTRATION_ID);
				$querys = $this->db->get();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($querys->num_rows() > 0)
				{
					$this->db->where("OP_REGISTRATION_ID",$OP_REGISTRATION_ID);
					$this->db->update("OP_INS_DETAILS",array("OP_INS_STATUS"=>0));
				}
			}

			$this->db->start_cache();
			$this->db->where("OP_REGISTRATION_ID",$OP_REGISTRATION_ID);
			$this->db->where("OP_INS_DETAILS_ID",$INS_DATA["OP_INS_DETAILS_ID"]);
			$this->db->update("OP_INS_DETAILS",$INS_DATA);
			$this->db->stop_cache();
			$this->db->flush_cache();
			


			if($INS_DATA['OP_INS_IS_ALL'] == false && !empty($CO_IN_DATA))
			{
				$coin_arr = array();
				$this->db->start_cache();
				$this->db->where("OP_REGISTRATION_ID",$OP_REGISTRATION_ID);
				$this->db->where("OP_INS_DETAILS_ID",$INS_DATA["OP_INS_DETAILS_ID"]);
				$this->db->delete("OP_COIN_DATA");
				$this->db->stop_cache();
				$this->db->flush_cache();

				foreach ($CO_IN_DATA as $key => $value) 
				{
					$coin_arr[$key]["OP_REGISTRATION_ID"] = $OP_REGISTRATION_ID;
					$coin_arr[$key]["OP_INS_DETAILS_ID"] = $INS_DATA["OP_INS_DETAILS_ID"];
					$coin_arr[$key]["COIN_ID"] = $value["type_id"];
					$coin_arr[$key]["COIN_NAME"] = $value["type_name"];
					$coin_arr[$key]["COIN_VALUE"] = $value["deduct_val"];
					$coin_arr[$key]["COIN_VALUE_TYPE"] = $value["deduct_type"];
			
				}	# code...			
					
				if(!empty($coin_arr))
					$this->db->insert_batch('OP_COIN_DATA', $coin_arr); 

			}
			$data["ins_data"] = $this->getInsDetails($OP_REGISTRATION_ID);
			$data["co_ins"] = array();
			if($data["ins_data"] != 0)
			{
				if($data["ins_data"]->OP_INS_IS_ALL != 1)
				{
					$data["co_ins"] = $this->getCoinsDetails($data["ins_data"]->OP_INS_DETAILS_ID);
				}
			}
			$data["old_ins"] = $this->getOldInsDetails($OP_REGISTRATION_ID);
					//$data["old_co_ins"] = array();
				if($data["old_ins"] != 0)
				{
					foreach($data["old_ins"] as $key => $value) {
						if($value['OP_INS_IS_ALL'] != 1)
						{
							$data["old_ins"][$key]["old_co_ins"] =  $this->getOldCoinsDetails($value["OP_INS_DETAILS_ID"]);
						}
					}
				}
			$result = array("status"=> "Success", "message"=> "Insurance details updated" ,"data" => $data)	;
		}
		return $result;
	}
	function add_company_category($company_id,$category_list)
	{
		if(is_array($category_list) && $company_id != "" && $company_id != null)
		{
			$this->db->delete("COMPANY_BUSINESS_CATEGORY",array("CBC_COMPANY_ID" =>$company_id));
			foreach ($category_list as $key => $value) {
				# code...
				if($value != "" && $value!= null)
					$this->db->insert("COMPANY_BUSINESS_CATEGORY",array("CBC_COMPANY_ID" => $company_id, "CBC_BUS_CAT_ID" => $value, "USER_ID" => $this->session->userdata('user_id')));
			}
		}
	}
	function apoinmentUpdate($app_id=0,$p_id="",$op_no="")
	{
		if($app_id !=0 && $p_id !="")
		{
			$this->db->where("APPOINTMENT_ID",$app_id);	
			$query =  $this->db->update("APPOINTMENT",array("PATIENT_ID"=>$p_id,"PATIENT_NO"=>$op_no));
		}
	}
	public function deleteCompany($post_data = array())
	{
		$data_id = $post_data["data_id"];
		//$data_id = 1;
		if ($data_id > 0)
		{
			
				$this->db->start_cache();
		
				$this->db->where("COMPANY_ID",$data_id);
				//$this->db->check_execute();
				$rata = $this->db->delete("COMPANY");
				
				$this->db->stop_cache();
				$this->db->flush_cache();
				
				//$this->db->error();
				
				return 1;
		}else
		{
		  return 2 ;	
		}
		
	}
	function op_attachment($post_data,$data_id)
	{
	
		if(is_array($post_data['upload_file']))
		{
			$files = $post_data['upload_file'];
			$attachment_arr = array();
			foreach ($files as $key => $value) 
			{
				if($this->getBase64FileSize($value) > DOCUMENT_UPLOAD_MAX_SIZE)
				{
				  	return  array("status"=> "Failed", "data"=> array("message"=>"Max file upload size exceeded!"));
				}
				else
				{	

					$file_name = $this->Writebase64FilesUpload($value,OP_ATTACHMENT);
					
					if($file_name!='')
					{
					 	
						$attachment_arr[$key]["OP_REGISTRATION_ID"] = $data_id;
						$attachment_arr[$key]["ATTACHMENT_TITLE"] = $file_name;
						$attachment_arr[$key]["CREATED_BY"] =(int) $post_data['user_id'];
						$attachment_arr[$key]["CLIENT_DATE"] =  format_date($post_data["client_date"]);
						$attachment_arr[$key]["CREATED_DATE"] = date('Y-m-d H:i:s');
						
					}

		  		}
			}
			// print_r($attachment_arr);exit;
			$this->db->insert_batch('OP_REGISTRATION_ATTACHMENT', $attachment_arr);
		}
	}
	function remove_attachment($post_data,$data_id)
	{
		if(is_array($post_data['removeUpload']))
		{
			
			$this->db->start_cache();				
			$this->db->where('OP_REGISTRATION_ID', $data_id);
			$this->db->where_in('OP_REGISTRATION_ATTACHMENT_ID', $post_data['removeUpload']);	
			$this->db->delete('OP_REGISTRATION_ATTACHMENT');
			$this->db->stop_cache();
			$this->db->flush_cache();
			// echo $this->db->last_query();
			
		}
	}
	function Writebase64FilesUpload($base64_encoded_string,$path)
	{
		if($base64_encoded_string != "")
		{		   
		  if(!file_exists($path))
        {        	   
            mkdir($path);
            //if (!is_dir($path)) mkdir($path, 0777, true);                           
            chmod($path,0755);                    
        }
        if(is_writable($path))
        {
        	
        	$decoded_file = base64_decode($base64_encoded_string); // decode the file
		    $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
		    $extension = $this->mime2ext($mime_type); // extract extension from mime type
		    //filename
		    $filename = uniqid().".".$extension;
		    $filepath = $path.$filename;		            
			 $success = file_put_contents($filepath, $decoded_file); // save
			 if($success)
				return $filename;
			 else
				return '';
				
		   }			
		}
		//echo $path;exit();
		return '';
	}
	
	/*
	to take mime type as a parameter and return the equivalent extension
	*/
	function mime2ext($mime)
	{
	    $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
	    "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
	    "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
	    "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
	    "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
	    "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
	    "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
	    "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
	    "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
	    "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
	    "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
	    "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
	    "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
	    "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
	    "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
	    "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
	    "pdf":["application\/pdf","application\/octet-stream"],
	    "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
	    "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
	    "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
	    "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
	    "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
	    "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
	    "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
	    "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
	    "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
	    "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
	    "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
	    "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
	    "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
	    "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
	    "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
	    "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
	    "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
	    "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
	    "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
	    "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
	    "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
	    "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
	    "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
	    "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
	    "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
	    "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
	    "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
	    "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
	    $all_mimes = json_decode($all_mimes,true);
	    foreach ($all_mimes as $key => $value) {
	        if(array_search($mime,$value) !== false) return $key;
	    }
	    return false;
	}

	//return memory size in B, KB, MB	 
	public function getBase64FileSize($base64File)
	{
	    try{
	        $size_in_bytes = (int) (strlen(rtrim($base64File, '=')) * 3 / 4);
	        $size_in_kb    = $size_in_bytes / 1024;
	        $size_in_mb    = $size_in_kb / 1024;
	
	        return $size_in_mb;
	    }
	    catch(Exception $e){
	        return $e;
	    }
	}

	public function downloadgeneralconsent($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["visit_id"] != '')	
		{	
			$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
			$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
			$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : ''; 
			$this->load->model('api/InstitutionManagementModel');
			if($post_data["p_number"]!=0){
				$PatientConsent	= $this->getPatientConsent($post_data);	
			}
			else{
				$PatientConsent	= $this->getPatientConsent_id($post_data);	
			}
			$institution      = $this->InstitutionManagementModel->listInstitution($post_data);
			$pateint_details	= $this->getPatientDetailss($post_data);	
			if($PatientConsent["status"]=="Success"){
			$data["institution"] = $institution;
			$data["consent"] = $PatientConsent;
			$data["pateint_details"] = $pateint_details;
			$cons=json_decode($PatientConsent["data"]["DATA"],true);
			//  foreach ($cons["questions"] as $value) {
			   // print_r($PatientConsent);
			//  exit();
			//  }
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->pdf;
			$pdf->autoScriptToLang = true;
			$pdf->autoLangToFont = true;
			if($post_data["type"]==3){
				$html = $this->load->view('export/generalconsent.php', $data, true);
				$time=time();
				$pdfFilePath = FCPATH . "public/uploads/generalconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf";
				$pdf->WriteHTML($html);
				$pdf->Output($pdfFilePath, "F");
				$result = array("status"=> "Success", "data"=> "public/uploads/generalconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf","filename" => "generalconsent".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf" );
			}
			else if($post_data["type"]==1){
				$html = $this->load->view('export/dentalconsent.php', $data, true);
				$time=time();
				$pdfFilePath = FCPATH . "public/uploads/dentalconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf";
				$pdf->WriteHTML($html);
				$pdf->Output($pdfFilePath, "F");
				$result = array("status"=> "Success", "data"=> "public/uploads/dentalconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf","filename" => "dentalconsent".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf" );
			}
			else{
				$html = $this->load->view('export/covidconsent.php', $data, true);
				$time=time();
				$pdfFilePath = FCPATH . "public/uploads/covidconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf";
				$pdf->WriteHTML($html);
				$pdf->Output($pdfFilePath, "F");
				$result = array("status"=> "Success", "data"=> "public/uploads/covidconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf","filename" => "covidconsent".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf" );
			}
			
			}
			else{
				$result 	= array('data'=>"",
										'status'=>'Failed',
										'message'=>'No result is found',

										);
			}
			
		}
		return $result;
	}




	public function downloadgeneralconsent_reg($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["p_id"] != '')	
		{	
			$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
			$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
			$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : ''; 
			$this->load->model('api/InstitutionManagementModel');
			$PatientConsent	= $this->getPatientConsent_id($post_data);
			$institution      = $this->InstitutionManagementModel->listInstitution($post_data);
			$pateint_details	= $this->getPatientDetailss($post_data);	
			// print_r($pateint_details);
			//  exit();
			if($PatientConsent["status"]=="Success"){
			$data["institution"] = $institution;
			$data["consent"] = $PatientConsent;
			$data["pateint_details"] = $pateint_details;
			$cons=json_decode($PatientConsent["data"]["DATA"],true);
			//  foreach ($cons["questions"] as $value) {
			   // print_r($PatientConsent);
			//  exit();
			//  }
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->pdf;
			$pdf->autoScriptToLang = true;
			$pdf->autoLangToFont = true;
			if($post_data["type"]==3){
				$html = $this->load->view('export/generalconsent.php', $data, true);
				$time=time();
				$pdfFilePath = FCPATH . "public/uploads/generalconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf";
				$pdf->WriteHTML($html);
				$pdf->Output($pdfFilePath, "F");
				$result = array("status"=> "Success", "data"=> "public/uploads/generalconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf","filename" => "generalconsent".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf" );
			}
			else if($post_data["type"]==1){
				$html = $this->load->view('export/dentalconsent.php', $data, true);
				$time=time();
				$pdfFilePath = FCPATH . "public/uploads/dentalconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf";
				$pdf->WriteHTML($html);
				$pdf->Output($pdfFilePath, "F");
				$result = array("status"=> "Success", "data"=> "public/uploads/dentalconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf","filename" => "dentalconsent".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf" );
			}
			else{
				$html = $this->load->view('export/covidconsent.php', $data, true);
				$time=time();
				$pdfFilePath = FCPATH . "public/uploads/covidconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf";
				$pdf->WriteHTML($html);
				$pdf->Output($pdfFilePath, "F");
				$result = array("status"=> "Success", "data"=> "public/uploads/covidconsent".$PatientConsent["data"]->VISIT_ID.$time.".pdf","filename" => "covidconsent".$pateint_details["data"]->OP_REGISTRATION_NUMBER.$time.".pdf" );
			}
			
			}
			else{
				$result 	= array('data'=>"",
										'status'=>'Failed',
										'message'=>'No result is found',

										);
			}
			
		}
		return $result;
	}


	public function getPatientDetailss($post_data = array())
 	{
 			if($post_data["p_id"] != "")
 			{
	 			$this->db->where("OP_REGISTRATION_ID",$post_data["p_id"]);	
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

	public function getPatientConsent($post_data)
 	{
 			if($post_data["visit_id"] != "" ||$post_data["visit_id"] > 0)
 			{
 				//if($visit_id)
	 			$this->db->where("PV.VISIT_ID",$post_data["visit_id"]);
		 		$this->db->where("CONSENT_TYPE",$post_data["type"]);	
				$this->db->select("PV.*");
				$this->db->from("PATIENT_CONSENT PV");
				$this->db->order_by("PV.PATIENT_CONSENT_ID","desc");
				$query = $this->db->get();
				//echo $this->db->last_query();
				if($query->num_rows() > 0)
				{
					$data = $query->row_array();  
					//print_r($data);
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
	 		else
	 		{
	 			$result 	= array('data'=>"",
										'status'=>'Failed',
										);
					return $result;
	 		}
 	}


	 public function getPatientConsent_id($post_data)
 	{
 			
	 			$this->db->where("PV.OP_NUMBER",$post_data["p_number"]);
		 		$this->db->where("CONSENT_TYPE",$post_data["type"]);	
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