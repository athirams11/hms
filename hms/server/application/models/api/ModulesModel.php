<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModulesModel extends CI_Model 
{
	
	function getModules($post_data = array())
	{
		
		
		if ($post_data["user_group"] != "")
		{
			$this->db->start_cache();
			
			if ($post_data["user_group"] == WEB_ADMINISTRATOR)
			{
				$this->db->select("MG.*,'1' as MODULE_GROUP_ACCESS");
			}
			else
			{
				$this->db->select("MG.*,IFNULL(UA.MODULE_GROUP_ACCESS, '0') as MODULE_GROUP_ACCESS");
			}
			$this->db->from("MODULE_GROUP MG");
			$this->db->join("USER_ACCESS_RIGHTS UA","UA.MODULE_GROUP_ID = MG.MODULE_GROUP_ID AND UA.USER_ACCESS_GROUP_ID = ".$post_data["user_group"]." ","left");
			$this->db->where("MG.MODULE_GROUP_STATUS",1);
			if ($post_data["user_group"] != WEB_ADMINISTRATOR)
			{
				$this->db->where("UA.MODULE_GROUP_ACCESS","1");
			}
			$this->db->order_by("MG.MODULE_GROUP_ORDER","asc");
			
			$query = $this->db->get();
			
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$response = $query->result();
				$result = array();
				//$i = 0;
				foreach ($response as $key => $value) {
				//print_r($value->MODULE_GROUP_ID);
					$result[$key] = $value;
					$result[$key]->sub_menu = $this->getSubmodules($value->MODULE_GROUP_ID,$post_data["user_group"]);
					//$i++;
				}
				$result 	= array('data'=>$result,
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
	
	function getSubmodules($MODULE_GROUP_ID=0,$user_group=0)
	{
		if($MODULE_GROUP_ID != 0)
		{
			//$this->db->get('SELECT COUNT(*) as count FROM USER_ACCESS_TYPE');
			$query = $this->db->get('USER_ACCESS_TYPE');
			$rows = $query->num_rows();
			$access_def = str_repeat("0", $rows);
			$this->db->start_cache();
			
			if ($user_group == WEB_ADMINISTRATOR)
			{
				$access_def = str_repeat("1", $rows);
				$this->db->select("M.*,'".$access_def."' AS MODULE_ACCESS_RIGHTS");
			}
			else
			{
				$this->db->select("M.*,IFNULL((CASE  WHEN LENGTH(MODULE_ACCESS_RIGHTS) > ".$rows." THEN MODULE_ACCESS_RIGHTS  ELSE RPAD( MODULE_ACCESS_RIGHTS,".$rows." ,'".$access_def."' )  END),'".$access_def."') AS MODULE_ACCESS_RIGHTS");
			}
			$this->db->from("MODULE M");
			$this->db->join("USER_ACCESS_RIGHTS UA","UA.MODULE_ID = M.MODULE_ID AND UA.USER_ACCESS_GROUP_ID = ".$user_group." ","left");
			$this->db->where("M.MODULE_GROUP_ID",$MODULE_GROUP_ID);
			$this->db->where("M.MODULE_STATUS",1);
			$this->db->where("M.MODULE_TYPE","main");

			if ($user_group != WEB_ADMINISTRATOR)
			{
				$this->db->like("UA.MODULE_ACCESS_RIGHTS","1", 'after');
			}
			$this->db->order_by("M.MODULE_ORDER","asc");
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}
		return array();
	}

	function getUserSubmodules($user_group=0)
	{
		
			//$this->db->get('SELECT COUNT(*) as count FROM USER_ACCESS_TYPE');
			$query = $this->db->get('USER_ACCESS_TYPE');
			$rows = $query->num_rows();
			$access_def = str_repeat("0", $rows);
			$this->db->start_cache();
			
			if ($user_group == WEB_ADMINISTRATOR)
			{
				$access_def = str_repeat("1", $rows);
				$this->db->select("M.*,'".$access_def."' AS MODULE_ACCESS_RIGHTS");
			}
			else
			{
				$this->db->select("M.*,IFNULL((CASE  WHEN LENGTH(MODULE_ACCESS_RIGHTS) > ".$rows." THEN MODULE_ACCESS_RIGHTS  ELSE RPAD( MODULE_ACCESS_RIGHTS,".$rows." ,'".$access_def."' )  END),'".$access_def."') AS MODULE_ACCESS_RIGHTS");
			}
			$this->db->from("MODULE M");
			$this->db->join("USER_ACCESS_RIGHTS UA","UA.MODULE_ID = M.MODULE_ID AND UA.USER_ACCESS_GROUP_ID = ".$user_group." ","left");
			//$this->db->where("M.MODULE_GROUP_ID",$MODULE_GROUP_ID);
			$this->db->where("M.MODULE_STATUS",1);
			$this->db->where("M.MODULE_TYPE","main");

			if ($user_group != WEB_ADMINISTRATOR)
			{
				$this->db->like("UA.MODULE_ACCESS_RIGHTS","1", 'after');
			}
			$this->db->order_by("M.MODULE_ORDER","asc");
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				return $query->result_array();
			}
		
		return array();
	}
	function getSubmodulesBadgeCount($post_data=array())
	{
		$return_array = array();
		if(!empty($post_data))
		{
			$modules = $this->getUserSubmodules($post_data["user_group"]);
			foreach ($modules as $key => $value) {
				switch ($value["MODULE_ID"]) {
					case MODULE_APPOINTMENT:
						# code...
						$this->db->start_cache();		
						$return_array[$value["MODULE_ID"]] = $this->pendingAppointmentCount($post_data);
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_VISIT:
						# code...
						$this->db->start_cache();		
						$return_array[$value["MODULE_ID"]] = $this->pendingAppointmentCount($post_data);
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_NURSING_ASESMENT:
						# code...
						$this->db->start_cache();		
						$return_array[$value["MODULE_ID"]] =$this->pendingVisitCount($post_data);
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_DOCTORS_ASESMENT:
						# code...
						$this->db->start_cache();		
						$return_array[$value["MODULE_ID"]] = $this->pendingAssessmentCount($post_data);
						//$return_array[$value["MODULE_ID"]] = $this->pendingNurseAssessmentCount($post_data);
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_BILLING:
						# code...
						$this->db->start_cache();		
						$return_array[$value["MODULE_ID"]] = $this->pendingBillCount($post_data);
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_BILL:
						# code...
						$this->db->start_cache();		
						$return_array[$value["MODULE_ID"]] = $this->pendingBillCount($post_data);
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_CLAIM:
						# code...
						$this->db->start_cache();		
						$return_array[$value["MODULE_ID"]] = $this->pendingInvoiceCount($post_data);
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					default:
						# code...
						$return_array[$value["MODULE_ID"]] = 0;
						break;
				}
				
			}
			
		}
		return $return_array;
	}
	
		
	function pendingAppointmentCount($post_data = array())
	{	

		$this->db->where('DATE(APPOINTMENT_DATE)', format_date($post_data["date"],1));
		
		//$this->db->where('APPOINTMENT_DATE', $post_data["date"]);
		$this->db->where('PATIENT_VISIT_LIST.PATIENT_VISIT_LIST_ID is null');	
		$this->db->where('APPOINTMENT.APPOINTMENT_STATUS != 2');	
		$this->db->from('APPOINTMENT');
		$this->db->join('PATIENT_VISIT_LIST',"APPOINTMENT.APPOINTMENT_ID = PATIENT_VISIT_LIST.APPOINTMENT_ID","left");
		return $this->db->count_all_results();
	}
	function pendingVisitCount($post_data = array())
	{

		//$this->db->where('VISIT_DATE', $post_data["date"]);
		$this->db->where('DATE(VISIT_DATE)', format_date($post_data["date"],1));
		$this->db->where('NURSING_ASSESSMENT.NURSING_ASSESSMENT_ID is null');
		$this->db->where('PATIENT_VISIT_LIST.VISIT_STATUS != 2');
		$this->db->from('PATIENT_VISIT_LIST');
		$this->db->join('NURSING_ASSESSMENT',"NURSING_ASSESSMENT.VISIT_ID = PATIENT_VISIT_LIST.PATIENT_VISIT_LIST_ID","left");
		return $this->db->count_all_results();
	}
	function pendingNurseAssessmentCount($post_data = array())
	{

		//$this->db->where('CREATED_TIME', $post_data["date"]);
		$this->db->where('DATE(CREATED_TIME)', format_date($post_data["date"],1));
		$this->db->where('END_TIME is null');
		//$this->db->where('VISIT_STATUS != 2');
		$this->db->from('NURSING_ASSESSMENT');
		
		return $this->db->count_all_results();
	}
	function pendingAssessmentCount($post_data = array())
	{
		if(isset($post_data["user_group"]) && isset($post_data["user_id"])){
			if($post_data["user_group"] == 4){
				$this->db->where('D.LOGIN_ID', $post_data["user_id"]);
			}
		}

		//$this->db->where('DATE(NA.CREATED_TIME)', format_date($post_data["date"],1));
		$this->db->where('DATE(NA.END_TIME)', format_date($post_data["date"],1));
		$this->db->where('NA.STAT = 1');
		$this->db->where('NA.DOCTOR_STAT IS NULL');
		// $this->db->where('B.BILLING_ID is Null');	
		$this->db->from('NURSING_ASSESSMENT NA');
		$this->db->join("PATIENT_VISIT_LIST PV","NA.VISIT_ID = PV.PATIENT_VISIT_LIST_ID","left");
		$this->db->join("DOCTORS D","PV.DOCTOR_ID = D.DOCTORS_ID","left");
		//$this->db->join("BILLING B","B.ASSESSMENT_ID = NA.NURSING_ASSESSMENT_ID","left");
		
		return $this->db->count_all_results();
	}
	function pendingBillCount($post_data = array())
	{
		$this->db->where('DATE(N.CREATED_TIME)', format_date($post_data["date"],1));
		//$this->db->where('CREATED_TIME', $post_data["date"]);
	
		$this->db->where('N.DOCTOR_STAT = 1');
		$this->db->from('NURSING_ASSESSMENT N');
		$this->db->join("BILLING B","B.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID AND B.BILL_TYPE = 0","left");
		$this->db->join("BILLING BL","BL.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID AND BL.BILL_TYPE = 1","left");
		$this->db->group_start();
			$this->db->group_start();
				$this->db->where('B.GENERATED IS NULL');
				$this->db->or_where('B.GENERATED != ',1);
			$this->db->group_end();
			$this->db->group_start();
				$this->db->where('BL.GENERATED IS NULL');
				$this->db->or_where('BL.GENERATED != ',1);
			$this->db->group_end();
		$this->db->group_end();
		return $this->db->count_all_results();
	}
	function pendingInvoiceCount($post_data = array())
	{

		//$this->db->where('BILLING_DATE', $post_data["date"]);
		$this->db->where('DATE(BILLING_DATE)', format_date($post_data["date"],1));
		//$this->db->where('END_TIME != null');
		$this->db->from('BILLING B');
		$this->db->join("SUBMISSION_CLAIM C","C.BILL_ID = B.BILLING_ID","left");
		$this->db->where("C.SUBMISSION_CLAIM_ID is null");
		$this->db->where('B.GENERATED',1);
		$this->db->where('B.PAYMENT_TYPE',1);
		$this->db->where('B.INSURED_AMOUNT >',0);
		
		return $this->db->count_all_results();
	}
	function get_sub_modules($MODULE_PARENT_ID=0,$user_group=0)
	{
		if($MODULE_PARENT_ID != 0)
		{			
			$this->db->start_cache();			
			$this->db->from("MODULE M");			
			$this->db->where("M.MODULE_PARENT_ID",$MODULE_PARENT_ID);
			$this->db->where("M.MODULE_STATUS",1);
			$this->db->where("M.MODULE_TYPE","sub");			
			$this->db->order_by("M.MODULE_ORDER","asc");
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = array();
				foreach($query->result_array() as $row)
				{
						$data[] = array(
											 'MODULE_ID' => $row['MODULE_ID'],
											 'MODULE_NAME' => $row['MODULE_NAME'],
											 'MENUS' =>   $this->get_sub_tab_modules($row['MODULE_ID'])
											); 
				}
				return $data;
			}
		}
		return array();
	}
	
			
	function get_sub_tab_modules($MODULE_PARENT_ID=0,$user_group=0)
	{
		if($MODULE_PARENT_ID != 0)
		{			
			$this->db->start_cache();			
			$this->db->from("MODULE M");			
			$this->db->where("M.MODULE_PARENT_ID",$MODULE_PARENT_ID);
			$this->db->where("M.MODULE_STATUS",1);
			$this->db->where("M.MODULE_TYPE","sub_tab");			
			$this->db->order_by("M.MODULE_ORDER","asc");
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				return $query->result_array();								
			}
		}
		return array();
	}
	
	function getModuleSummary($post_data=array())
	{
		//$result      =  array("status"=>"Failed");
		$return_array = array();
		if(!empty($post_data))
		{
			$modules = $this->getUserSubmodules($post_data["user_group"]);
			foreach ($modules as $key => $value) {
				switch ($value["MODULE_ID"]) {
					case MODULE_APPOINTMENT:
						# code...
						$this->db->start_cache();		
						$return_array["NAME"][]= 'Appointment';
						$return_array["COUNT"][] = $this->AppointmentCount($post_data);
						//print_r($return_array);exit
						//$return_array["COLOR"][] = 'red';
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_VISIT:
						# code...
						$this->db->start_cache();		
						$return_array["NAME"][]= 'Visit';
						$return_array["COUNT"][]= $this->VisitCount($post_data);
						//$return_array["COLOR"][] = 'yellow';
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_NURSING_ASESMENT:	
						# code...
						$this->db->start_cache();		
						$return_array["NAME"][]= 'Nurse Assessment';
						$return_array["COUNT"][]=$this->NurseAssessmentCount($post_data);
						//$return_array["COLOR"][] = 'green';
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_DOCTORS_ASESMENT:
						# code...
						$this->db->start_cache();		
						$return_array["NAME"][]= 'Doctor Assessment';
						$return_array["COUNT"][]= $this->AssessmentCount($post_data);
						//$return_array["COLOR"][] = 'orange';
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_BILLING:
						# code...
						$this->db->start_cache();		
						$return_array["NAME"][]='Biiling';
						$return_array["COUNT"][] = $this->BillCount($post_data);
						//$-return_array["COLOR"][] = 'Tomato';
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					case MODULE_CLAIM:
						# code...
						$this->db->start_cache();		
						$return_array["NAME"][]='Claim';
						$return_array["COUNT"][] = $this->InvoiceCount($post_data);
						//$return_array["COLOR"][] = 'blue';
						//echo $this->db->last_query();
						$this->db->stop_cache();
						$this->db->flush_cache();
						break;
					default:
						# code...
						//$return_array[] = 0;
						break;
				}
				
			}
			
		}
		return $return_array;
	}
	function AppointmentCount($post_data = array())
	{	

		$this->db->where('DATE(APPOINTMENT_DATE)', format_date($post_data["date"],1));
		
		//$this->db->where('APPOINTMENT_DATE', $post_data["date"]);
	//	$this->db->where('PATIENT_VISIT_LIST.PATIENT_VISIT_LIST_ID is null');
		$this->db->from('APPOINTMENT');
		//$this->db->join('PATIENT_VISIT_LIST',"APPOINTMENT.APPOINTMENT_ID = PATIENT_VISIT_LIST.APPOINTMENT_ID","left");
		return $this->db->count_all_results();
	}
	function VisitCount($post_data = array())
	{

		//$this->db->where('VISIT_DATE', $post_data["date"]);
		$this->db->where('DATE(VISIT_TIME)', format_date($post_data["date"],1));
		//$this->db->where('NURSING_ASSESSMENT.NURSING_ASSESSMENT_ID is null');
		$this->db->from('PATIENT_VISIT_LIST');
		//$this->db->join('NURSING_ASSESSMENT',"NURSING_ASSESSMENT.VISIT_ID = PATIENT_VISIT_LIST.PATIENT_VISIT_LIST_ID","left");
		return $this->db->count_all_results();
	}
	function NurseAssessmentCount($post_data = array())
	{

		//$this->db->where('CREATED_TIME', $post_data["date"]);
		$this->db->where('DATE(CREATED_TIME)', format_date($post_data["date"],1));
		//$this->db->where('END_TIME is null');
		$this->db->from('NURSING_ASSESSMENT');
		
		return $this->db->count_all_results();
	}
	function AssessmentCount($post_data = array())
	{
		if(isset($post_data["user_group"]) && isset($post_data["user_id"])){
			if($post_data["user_group"] == 4){
				$this->db->where('D.LOGIN_ID', $post_data["user_id"]);
			}
		}

		$this->db->where('DATE(NA.CREATED_TIME)', format_date($post_data["date"],1));
		$this->db->where('NA.STAT = 1');
		//$this->db->where('B.BILLING_ID is Null');
		$this->db->from('NURSING_ASSESSMENT NA');
		$this->db->join("PATIENT_VISIT_LIST PV","NA.VISIT_ID = PV.PATIENT_VISIT_LIST_ID","left");
		$this->db->join("DOCTORS D","PV.DOCTOR_ID = D.DOCTORS_ID","left");
		//$this->db->join("BILLING B","B.ASSESSMENT_ID = NA.NURSING_ASSESSMENT_ID","left");
		
		return $this->db->count_all_results();
	}
	function BillCount($post_data = array())
	{
		$this->db->where('DATE(N.CREATED_TIME)', format_date($post_data["date"],1));
		//$this->db->where('CREATED_TIME', $post_data["date"]);
		$this->db->where('STAT = 1');
		$this->db->where('B.GENERATED = ',1);
		$this->db->from('NURSING_ASSESSMENT N');
		$this->db->join("BILLING B","B.ASSESSMENT_ID = N.NURSING_ASSESSMENT_ID","left");
		
		return $this->db->count_all_results();
	}
	function InvoiceCount($post_data = array())
	{

		//$this->db->where('BILLING_DATE', $post_data["date"]);
		$this->db->where('DATE(BILLING_DATE)', format_date($post_data["date"],1));
		//$this->db->where('END_TIME != null');
		$this->db->from('BILLING B');
		$this->db->join("SUBMISSION_CLAIM C","C.BILL_ID = B.BILLING_ID","left");
		//$this->db->where("C.SUBMISSION_CLAIM_ID is null");
		$this->db->where('B.GENERATED',1);
		$this->db->where('B.PAYMENT_TYPE',1);
		$this->db->where('B.INSURED_AMOUNT >',0);
		
		return $this->db->count_all_results();
	}
	

} 
?>