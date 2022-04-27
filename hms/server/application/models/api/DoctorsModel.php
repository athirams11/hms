<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DoctorsModel extends CI_Model 
{
	
	function getDoctorList($post_data = array())
	{

		$this->db->start_cache();				
			if($post_data["search_text"] != '')
			{
				//$this->db->where("DM.PROCEDURE_CODE",$post_data["search_text"]);
				$this->db->group_start();
				$this->db->like("DR.DOCTORS_NAME",$post_data["search_text"]);
				$this->db->or_like("S.SERVICE_NAME",$post_data["search_text"]);
				$this->db->or_like("DR.DOCTORS_LISCENCE_NO",$post_data["search_text"]);
				$this->db->or_like("DR.DOCTORS_DEGREE",$post_data["search_text"]);
				$this->db->or_like("DR.DOCTORS_PHONE_NO",$post_data["search_text"]);
				$this->db->or_like("DR.DOCTORS_EMAIL",$post_data["search_text"]);
				$this->db->group_end();
			}	
				$this->db->from("DOCTORS DR");
				$this->db->join("SERVICES S","S.SERVICES_ID = DR.DOCTORS_CATEGORY AND S.SERVICE_STATUS = 1","left");
				$count = $this->db->count_all_results();	
		$this->db->stop_cache();
		$this->db->flush_cache();

		
			$this->db->start_cache();
			$this->db->select("DR.*,S.SERVICE_NAME as TYPE_NAME");
			$this->db->from("DOCTORS DR");
			$this->db->join("SERVICES S","S.SERVICES_ID = DR.DOCTORS_CATEGORY AND S.SERVICE_STATUS = 1","left");
			//$this->db->join("DOCTORS_SCHEDULE_TIME_TABLE DT","DT.DOCTORS_SCHEDULE_ID = DS.DOCTORS_SCHEDULE_ID AND DT.DOCTORS_SCHEDULE_TIME_TABLE_STATUS = 1","left");
			if($post_data["search_text"] != '')
			{
				//$this->db->where("DM.PROCEDURE_CODE",$post_data["search_text"]);
				$this->db->like("DR.DOCTORS_NAME",$post_data["search_text"]);
				$this->db->or_like("S.SERVICE_NAME",$post_data["search_text"]);
				$this->db->or_like("DR.DOCTORS_LISCENCE_NO",$post_data["search_text"]);
				$this->db->or_like("DR.DOCTORS_DEGREE",$post_data["search_text"]);
				$this->db->or_like("DR.DOCTORS_PHONE_NO",$post_data["search_text"]);
				$this->db->or_like("DR.DOCTORS_EMAIL",$post_data["search_text"]);
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
			$this->db->order_by("DR.DOCTORS_NAME","asc");
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$list_data = $query->result_array();
				$return_array = $list_data;
				$i = 0;
				foreach ($list_data as $key => $value) {
					$return_array[$key]["DOCTOR_DEPARTMENTS"] = $this->getDoctorDeptList($value['DOCTORS_ID']);	
					$return_array[$key]["DOCTOR_CATEGORY"] = $this->getDoctorCatList($value['DOCTORS_CATEGORY']);	
				}
				$result = array("status"=> "Success","total_count"=>$count, "data"=> $return_array);
				

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
	function getDoctorCatList($cat_id)
	{
		
			$this->db->start_cache();
			$this->db->select("S.SERVICES_ID,concat(S.SERVICE_NAME,' -',S.CPT_CODE) as SERVICE_CODE");
			$this->db->from("SERVICES S");
			$this->db->where("S.SERVICES_ID",$cat_id);
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			return $query->result_array();
			
	}
	function getDoctorDeptList($doc_id)
	{
		
			$this->db->start_cache();
			$this->db->select("DD.DEPARTMENT_ID as OPTIONS_ID,O.OPTIONS_NAME as OPTIONS_NAME");
			$this->db->from("DOCTORS_DEPARTMENTS DD");
			$this->db->join("OPTIONS O","O.OPTIONS_ID = DD.DEPARTMENT_ID AND O.OPTIONS_STATUS = 1","left");
			$this->db->where("DD.DOCTORS_ID",$doc_id);

			$this->db->order_by("O.OPTIONS_NAME","asc");
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				
				
				$result 	=  $query->result_array();

				return $result;				
			}
			else 
			{
				$result 	= $query->result_array();
				return $result;
			}	
	}
	
	public function activeDays($schedule_id = 0)
	{
		$this->db->start_cache();
			$this->db->select("GROUP_CONCAT(DISTINCT DT.DAY) AS DAYS_ON_WEEK");
			$this->db->from("DOCTORS_SCHEDULE_TIME_TABLE DT");
			$this->db->where("DT.DOCTORS_SCHEDULE_ID = ".$schedule_id."");
			//$this->db->join("DOCTORS_SCHEDULE_TIME_TABLE DT","DT.DOCTORS_SCHEDULE_ID = DS.DOCTORS_SCHEDULE_ID AND DT.DOCTORS_SCHEDULE_TIME_TABLE_STATUS = 1","left");
			//$this->db->group_by("DT.DAY");
			
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				return $query->row()->DAYS_ON_WEEK;				
			}
			else 
			{
				return "";
			}	
	}
	public function addNewDoctor($post_data = array())
	{
		$result = array("status"=> "Failed", "response"=>"<strong>Failed </strong> to saved the details");
		$cat_ar = $post_data["doc_cat"];
		$cat  = "";
		if(is_array($post_data["doc_cat"]))
		{
			$cat = $cat_ar[0]["SERVICES_ID"];
		}
		$data=array(
		
		"DOCTORS_NAME" 		=> trim($post_data["doc_name"]),
		"DOCTORS_DEGREE" 	=> trim($post_data["doc_degree"]),
		"DOCTORS_PHONE_NO" 		=> trim($post_data["doc_phone"]),
		"DOCTORS_EMAIL" 		=> trim($post_data["doc_email"]),
		"DOCTORS_CATEGORY" 		=> $cat,
		"DOCTORS_GENDER" 		=> trim($post_data["doc_gender"]),
		"DOCTORS_LISCENCE_NO" 		=> trim($post_data["doc_lisc_no"]),
		"CLIENT_DATE" => date('Y-m-d H:i:s',strtotime($post_data["client_date"]))	
		
				);
		if($post_data["clinician_user"]!="" && $post_data["clinician_pass"] !="")
		{
			$data["CLINICIAN_USER"]  = trim($post_data["clinician_user"]);
			$data["CLINICIAN_PASS"]  = trim($post_data["clinician_pass"]);
		}
		/*$this->load->model("MasterModel");
		if($post_data["imagedataurl"] != "")
		{
			$image_name=$this->MasterModel->Writebase64($post_data["imagedataurl"],FCPATH.COMPANY_LOGO_PATH);
			if($image_name!="")
			{
				$data["COMPANY_LOGO_NAME"]=$image_name;			
			}	
		}*/
		$data_id = (int) $post_data["doc_id"];
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
		
		if ($data_id> 0)
		{
			$data["UPDATED_BY"] = (int) $post_data['user_id'];
			$data["UPDATED_ON"] = date("Y-M-d H:i:s");
			$this->db->where("DOCTORS_ID",$data_id);
			if($this->db->update("DOCTORS",$data))
			{
				$this->addDoctorDepartments($data_id,$post_data["doc_dept"]);
				$result = array("status"=> "Success", "response"=>"Doctor details  saved successfully...");
			}
			
		}
		else
		{
			
			
			$data["CRATED_BY"] = (int) $post_data['user_id'];
			$data["CREATED_ON"] = date("Y-M-d H:i:s");
			if($this->db->insert("DOCTORS",$data))
			{
				$data_id = $this->db->insert_id();
			 	$this->addDoctorDepartments($data_id,$post_data["doc_dept"]);
				$result = array("status"=> "Success", "response"=>"Doctor details  saved successfully...");
			
			}
		}
		return $result;
	}
	function addDoctorDepartments($doc_id,$depts_array)
	{
		if(is_array($depts_array) && $doc_id != "" && $doc_id != null)
		{
			$this->db->delete("DOCTORS_DEPARTMENTS",array("DOCTORS_ID" =>$doc_id));
			foreach ($depts_array as $key => $value) {
				$t_data["DOCTORS_ID"] = $doc_id;
				$t_data["DEPARTMENT_ID"] = $value["OPTIONS_ID"];
				if($t_data["DEPARTMENT_ID"] != null)
					$this->db->insert("DOCTORS_DEPARTMENTS",$t_data);
			}
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

} 
?>