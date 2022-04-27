<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class QuerysModel extends CI_Model 
{
	
	function getPatientList($post_data = array())
	{
		
			$this->db->start_cache();				
			if($post_data["search_text"] != '')
			{
				//$this->db->where("DM.PROCEDURE_CODE",$post_data["search_text"]);
				$this->db->group_start();
				$this->db->like("OP.OP_REGISTRATION_NUMBER",trim($post_data["search_text"]));
				$this->db->or_like("CONCAT(OP.FIRST_NAME,' ',OP.MIDDLE_NAME,' ',OP.LAST_NAME)",trim($post_data["search_text"]));
				$this->db->or_like("OP.FIRST_NAME",trim($post_data["search_text"]));
				$this->db->or_like("OP.MIDDLE_NAME",trim($post_data["search_text"]));
				$this->db->or_like("OP.LAST_NAME",trim($post_data["search_text"]));
				$this->db->or_like("OP.MOBILE_NO",trim($post_data["search_text"]));
				$this->db->or_like("OP.NATIONAL_ID",trim($post_data["search_text"]));
				$this->db->group_end();
			}	
			$this->db->from('OP_REGISTRATION OP');
			$count = $this->db->count_all_results();	
			$this->db->stop_cache();
			$this->db->flush_cache();

			$this->db->start_cache();
			$this->db->select("CONCAT(OP.FIRST_NAME,' ',OP.MIDDLE_NAME,' ',OP.LAST_NAME) AS NAME,
				    OP_REGISTRATION_NUMBER,
				    OP_REGISTRATION_ID,NATIONAL_ID,
				    AGE,GENDER,MOBILE_NO,MONTHS,DAYS");
			$this->db->from("OP_REGISTRATION OP");
			$this->db->order_by("OP_REGISTRATION_ID	","desc");

			if($post_data["search_text"] != '')
			{
				//$this->db->where("DM.PROCEDURE_CODE",$post_data["search_text"]);
				$this->db->like("OP.OP_REGISTRATION_NUMBER",trim($post_data["search_text"]));
				$this->db->or_like("CONCAT(OP.FIRST_NAME,' ',OP.MIDDLE_NAME,' ',OP.LAST_NAME)",trim($post_data["search_text"]));
				$this->db->or_like("OP.FIRST_NAME",trim($post_data["search_text"]));
				$this->db->or_like("OP.MIDDLE_NAME",trim($post_data["search_text"]));
				$this->db->or_like("OP.LAST_NAME",trim($post_data["search_text"]));
				$this->db->or_like("OP.MOBILE_NO",trim($post_data["search_text"]));
				$this->db->or_like("OP.NATIONAL_ID",trim($post_data["search_text"]));
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
			//echo  $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = array("status"=> "Success","total_count"=>$count, "data"=> $query->result());
				
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




	function getPatientList_bydate($post_data = array())
	{
		
			$this->db->start_cache();				
			$this->db->where("DATE(CONVERT_TZ(PV.VISIT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateVal"],1));
			$this->db->select("PV.PATIENT_VISIT_LIST_ID,OP.*");
			$this->db->from("PATIENT_VISIT_LIST PV");
			$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = PV.PATIENT_ID","left");
			$query = $this->db->get();
			
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = array("status"=> "Success","data"=> $query->result());
				
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
		
		"OP_REGISTRATION_DATE" 			=> trim($post_data["dateVal"]),
		"OP_REGISTRATION_TYPE" 		=> trim($post_data["sel_type"]),
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
		"SOURCE_OF_INF0" 				=> $post_data['sel_source_of_info'],
		"INS_RECEIVER" 				=> $post_data['sel_tpa_receiver'],
		"INS_COMPANY" 				=> $post_data['sel_ins_co'],
		"INS_NETWORK" 				=> $post_data['sel_network'],
		"INS_MEMBER_ID" 				=> $post_data['memebr_id'],
		"INS_POLICY_NO" 				=> $post_data['policy_no'],
		"INS_DEDUCTIBLE" 				=> $post_data['deductible'],
		"INS_COINS" 				=> $post_data['sel_co_ins'],
		"ENC_TYPE" 				=> $post_data['sel_ec_type'],
		"ENC_STRT_TYPE" 				=> $post_data['sel_ec_start_type'],
		"TAX_REQ" 				=> $post_data['rd_tax_req'],
		"CREATED_USER" 				=> (int) $post_data['user_id']
				);
		/*$this->load->model("MasterModel");
		if($post_data["imagedataurl"] != "")
		{
			$image_name=$this->MasterModel->Writebase64($post_data["imagedataurl"],FCPATH.COMPANY_LOGO_PATH);
			if($image_name!="")
			{
				$data["COMPANY_LOGO_NAME"]=$image_name;			
			}	
		}*/
		$data_id = 0;
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
		$result = array("status"=> "Failed", "reg_id"=>0, "reg_no"=> 0);
		if ($data_id> 0)
		{
			$this->db->where("OP_REGISTRATION_ID",$data_id);
			$this->db->update("OP_REGISTRATION",$data);
			$result = array("status"=> "Success", "reg_id"=>$data_id, "reg_no"=> $data["OP_REGISTRATION_NUMBER"])	;
		}
		else
		{
			
			$gen_no=$this->GenerateOpNo($post_data);
			$data["OP_REGISTRATION_NUMBER"]=$gen_no["data"];
			$this->db->insert("OP_REGISTRATION",$data);
			$data_id = $this->db->insert_id();
			 
			$result = array("status"=> "Success", "reg_id"=>$data_id, "reg_no"=> $data["OP_REGISTRATION_NUMBER"])	;
		}
		return $result;
	}
	

} 
?>