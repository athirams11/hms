<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AppointmentModel extends CI_Model 
{
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
	function getDrSchduleByDate($post_data = array())
	{
		
		//print_r($post_data);
		if ($post_data["dateVal"] && $post_data["timeZone"] != "")
		{
			$day = date('D', strtotime(format_date($post_data["dateVal"],1)));
			$date = format_date($post_data["dateVal"],1);
			$this->db->start_cache();
			$this->db->select("T.DOCTORS_SCHEDULE_ID,T.DAY,T.SCHEDULE_NO,T.START_AT,T.END_AT,DS.DOCTORS_ID,DS.DOCTORS_NAME,DS.SPECIALIZED_IN,DS.AVG_CONS_TIME,SP.OPTIONS_NAME as SPECIALIZED_NAME, count(AP.APPOINTMENT_ID) as AP_COUNT");
			$this->db->from("DOCTORS_SCHEDULE_TIME_TABLE T");
			$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = T.DOCTORS_SCHEDULE_ID","left");
			$this->db->join("OPTIONS SP","SP.OPTIONS_ID = DS.SPECIALIZED_IN AND SP.OPTIONS_TYPE = 8","left");
			$this->db->join("APPOINTMENT AP","AP.SCHEDULE_ID = DS.DOCTORS_SCHEDULE_ID AND AP.SLOT_NO = T.SCHEDULE_NO AND DATE(CONVERT_TZ(APPOINTMENT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) = '".$date."'","left");
			$this->db->where("UPPER(DAY)",strtoupper($day));
			$this->db->where("DOCTORS_SCHEDULE_TIME_TABLE_STATUS",1);
			if(isset($post_data["department_id"]) && $post_data["department_id"] != ""){
				$this->db->where("DS.SPECIALIZED_IN",$post_data["department_id"]);
			}
			if(isset($post_data["doctor_id"]) && $post_data["doctor_id"] != ""){
				$this->db->where("DS.DOCTORS_ID",$post_data["doctor_id"]);
			}
			$this->db->order_by("DS.DOCTORS_NAME,T.SCHEDULE_NO","asc");
			$this->db->group_by("T.DOCTORS_SCHEDULE_TIME_TABLE_ID,T.SCHEDULE_NO");
			$query = $this->db->get();
		//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();		
			if($query->num_rows() > 0)
			{
				$re_array = $query->result();
				$return_array = array();
				$res = array();
				foreach ($re_array as $key => $value) {
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["SCHEDULE_NO"] = $value->SCHEDULE_NO;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["START_AT"] = $value->START_AT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["END_AT"] = $value->END_AT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["END_AT"] = $value->END_AT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["AP_COUNT"] = $value->AP_COUNT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["DAY"] = $value->DAY;
					
					$return_array[$value->DOCTORS_SCHEDULE_ID]["name"]=$value->DOCTORS_NAME;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["specialized"]=$value->SPECIALIZED_NAME;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["id"]=$value->DOCTORS_ID;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["schedule_id"]=$value->DOCTORS_SCHEDULE_ID;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["avg_time"]=$value->AVG_CONS_TIME;
				}
				foreach ($return_array as $key => $value) {
					$slots = array();
					foreach($value["slots"]  as $s => $a)
					{
						$slots[] = $a;
					}
					$value["slotList"] = $slots;
					$res["DOCTORS"][] = $value;
					//$return_array[$value->DOCTORS_SCHEDULE_ID]["name"]=$value->DOCTORS_NAME;
				}
				$result 	= array('data'=>$res,
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
	function getDoctersByDate($post_data = array())
	{
		
		//print_r($post_data);
		if ($post_data["dateVal"] && $post_data["timeZone"] != "")
		{
			$day = date('D', strtotime(format_date($post_data["dateVal"],1)));
			$date = format_date($post_data["dateVal"],1);
			$this->db->start_cache();
			$this->db->select("T.DOCTORS_SCHEDULE_ID,T.DAY,T.SCHEDULE_NO,T.START_AT,T.END_AT,DS.DOCTORS_ID,DS.DOCTORS_NAME,DS.SPECIALIZED_IN,DS.AVG_CONS_TIME,SP.OPTIONS_NAME as SPECIALIZED_NAME, count(AP.APPOINTMENT_ID) as AP_COUNT");
			$this->db->from("DOCTORS_SCHEDULE_TIME_TABLE T");
			$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = T.DOCTORS_SCHEDULE_ID","left");
			$this->db->join("OPTIONS SP","SP.OPTIONS_ID = DS.SPECIALIZED_IN AND SP.OPTIONS_TYPE = 8","left");
			$this->db->join("APPOINTMENT AP","AP.SCHEDULE_ID = DS.DOCTORS_SCHEDULE_ID AND AP.SLOT_NO = T.SCHEDULE_NO AND DATE(CONVERT_TZ(APPOINTMENT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) = '".$date."'","left");
			$this->db->where("UPPER(DAY)",strtoupper($day));
			$this->db->where("DOCTORS_SCHEDULE_TIME_TABLE_STATUS",1);
			$this->db->order_by("DS.DOCTORS_NAME,T.SCHEDULE_NO","asc");
			$this->db->group_by("T.DOCTORS_SCHEDULE_TIME_TABLE_ID,T.SCHEDULE_NO");
			$query = $this->db->get();
				//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$re_array = $query->result();
				$return_array = array();
				$res = array();
				foreach ($re_array as $key => $value) {
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["SCHEDULE_NO"] = $value->SCHEDULE_NO;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["START_AT"] = $value->START_AT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["END_AT"] = $value->END_AT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["END_AT"] = $value->END_AT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["AP_COUNT"] = $value->AP_COUNT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["DAY"] = $value->DAY;
					
					$return_array[$value->DOCTORS_SCHEDULE_ID]["name"]=$value->DOCTORS_NAME;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["specialized"]=$value->SPECIALIZED_NAME;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["id"]=$value->DOCTORS_ID;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["schedule_id"]=$value->DOCTORS_SCHEDULE_ID;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["avg_time"]=$value->AVG_CONS_TIME;
				}
				foreach ($return_array as $key => $value) {
					$slots = array();
					foreach($value["slots"]  as $s => $a)
					{
						$slots[] = $a;
					}
					$value["slotList"] = $slots;
					$res["DOCTORS"][] = $value;
					//$return_array[$value->DOCTORS_SCHEDULE_ID]["name"]=$value->DOCTORS_NAME;
				}
				$result 	= array('data'=>$res,
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
	function getDrSchduleForWeek($post_data = array())
	{
		
		//print_r($post_data);
		$d = strtotime("today");
		if ($post_data["dateVal"] != "")
			$d = strtotime($post_data['dateVal']);
		if ($post_data["sel_dr"] != "")
		{
			if( strtoupper(date('D', strtotime(format_date($post_data["dateVal"],1)))) == "SUN")
			{
				$start_week = $d;
			}
			else
			{
				$start_week = strtotime("last sunday midnight",$d);
			}
			if( strtoupper(date('D', strtotime(format_date($post_data["dateVal"],1)))) == "SAT")
			{
				$end_week = $d;
			}
			else
			{
				$end_week = strtotime("next saturday",$d);
			}
			
			
			$start = date("Y-m-d",$start_week); 
			$end = date("Y-m-d",$end_week);  
			$begin = new DateTime( $start );
			$ends   = new DateTime( $end );
			$byDate = array();
			for($i = $begin; $i <= $ends; $i->modify('+1 day'))
			{
				$return_array = array();
				$res = array();
				
			    $day =  $i->format("D");
			    $date =  $i->format("Y-m-d");
			    //$day = date('D', strtotime(format_date($post_data["dateVal"],1)));
				//$date = format_date($post_data["dateVal"],1);
				$this->db->start_cache();
				$this->db->select("T.DOCTORS_SCHEDULE_ID,T.DAY,T.SCHEDULE_NO,T.START_AT,T.END_AT,DS.DOCTORS_ID,DS.DOCTORS_NAME,DS.SPECIALIZED_IN,DS.AVG_CONS_TIME,SP.OPTIONS_NAME as SPECIALIZED_NAME, count(AP.APPOINTMENT_ID) as AP_COUNT");
				$this->db->from("DOCTORS_SCHEDULE_TIME_TABLE T");
				$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = T.DOCTORS_SCHEDULE_ID","left");
				$this->db->join("OPTIONS SP","SP.OPTIONS_ID = DS.SPECIALIZED_IN AND SP.OPTIONS_TYPE = 8","left");
				$this->db->join("APPOINTMENT AP","AP.SCHEDULE_ID = DS.DOCTORS_SCHEDULE_ID AND AP.SLOT_NO = T.SCHEDULE_NO AND APPOINTMENT_DATE = '".$date."'","left");
				$this->db->where("UPPER(DAY)",strtoupper($day));
				$this->db->where("DOCTORS_SCHEDULE_TIME_TABLE_STATUS",1);
				$this->db->where("DS.DOCTORS_SCHEDULE_ID",$post_data["sel_dr"]);
				$this->db->order_by("DS.DOCTORS_NAME,T.SCHEDULE_NO","asc");
				$this->db->group_by("T.DOCTORS_SCHEDULE_TIME_TABLE_ID,T.SCHEDULE_NO");
				$query = $this->db->get();
					//echo $this->db->last_query();
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($query->num_rows() > 0)
				{
					$re_array = $query->result();
					
					foreach ($re_array as $key => $value) {
						$return_array["slots"][$value->SCHEDULE_NO]["SCHEDULE_NO"] = $value->SCHEDULE_NO;
						$return_array["slots"][$value->SCHEDULE_NO]["START_AT"] = $value->START_AT;
						$return_array["slots"][$value->SCHEDULE_NO]["END_AT"] = $value->END_AT;
						$return_array["slots"][$value->SCHEDULE_NO]["END_AT"] = $value->END_AT;
						$return_array["slots"][$value->SCHEDULE_NO]["AP_COUNT"] = $value->AP_COUNT;

						$return_array["DAY"] = $value->DAY;
						$return_array["date"]=$date;
						$return_array["name"]=$value->DOCTORS_NAME;
						$return_array["specialized"]=$value->SPECIALIZED_NAME;
						$return_array["id"]=$value->DOCTORS_ID;
						$return_array["schedule_id"]=$value->DOCTORS_SCHEDULE_ID;
						$return_array["avg_time"]=$value->AVG_CONS_TIME;
					}
					//foreach ($return_array as $key => $value) {
						$slots = array();
						foreach($return_array["slots"]  as $s => $a)
						{
							$slots[] = $a;
						}
						$return_array["slots"] = array();
						$return_array["slotList"] = $slots;
						//$res = $value;
						//$return_array[$value->DOCTORS_SCHEDULE_ID]["name"]=$value->DOCTORS_NAME;
					//}
					$byDate['DATES'][$date]	= $return_array;

					//return $result;				
				}
				else 
				{
					$byDate['DATES'][$date] = "";
					//return $result;
				}

			}
			
			foreach ($byDate['DATES'] as $key => $value) {
				$res['DATES'][] = $value;
			}
			$res['from'] = $start;
			$res['to'] = $end;
			$result = 	array(	'data'=>$res,
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
			
		//return $query->row();		
	}

	
		
	public function getAppointmentsByDate($post_data = array())
	{
		if ($post_data["dateVal"] && $post_data["timeZone"]  != "")
	   {
		   // $this->db->where("DATE(CONVERT_TZ(APPOINTMENT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateVal"],1));	
		   if(isset($post_data["doctor_id"]) && $post_data["doctor_id"] != '')
		   {
			   $this->db->where("A.DOCTOR_ID",$post_data["doctor_id"]);
		   }
		   $this->db->where("DATE(APPOINTMENT_DATE)",format_date($post_data["dateVal"],1));
		   $this->db->select("A.*,A.AGE as APP_AGE,A.GENDER as APP_GENDER,OP.*,C.COUNTRY_ISO3,C.COUNTRY_NAME,DS.DOCTORS_NAME,DS.SPECIALIZED_IN,DS.AVG_CONS_TIME,PV.PATIENT_VISIT_LIST_ID,NA.NURSING_ASSESSMENT_ID,NA.STAT,B.BILL_STATUS");
		   $this->db->from("APPOINTMENT A");
		   $this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = A.SCHEDULE_ID","left");
		   $this->db->join("PATIENT_VISIT_LIST PV","PV.APPOINTMENT_ID = A.APPOINTMENT_ID","left");
		   $this->db->join("NURSING_ASSESSMENT NA","NA.VISIT_ID = PV.PATIENT_VISIT_LIST_ID","left");
		   $this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = A.PATIENT_ID","left");	
		   $this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");	
		   $this->db->join("BILLING B","NA.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","left");
		   $this->db->order_by("A.APPOINTMENT_TIME","ASC");
		   //$this->db->order_by("A.APPOINTMENT_ID","DESC");
		   $query = $this->db->get();
		   //echo $this->db->last_query();
		   if ($query->num_rows() > 0)
		   {	 		
				$data=$query->result_array();
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
				$result 	= array('data'=>$data,
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
	public function getAppointmentsByfromtoDate($post_data = array())
 	{
 		if ($post_data["dateVal"] && $post_data["timeZone"]  != "")
		{
			// $this->db->where("DATE(CONVERT_TZ(APPOINTMENT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateVal"],1));	
			if(isset($post_data["doctor_id"]) && $post_data["doctor_id"] != '')
			{
				$this->db->where("A.DOCTOR_ID",$post_data["doctor_id"]);
			}
			if(isset($post_data["dateVal"]) && $post_data["dateVal"] != '')
			{
				$this->db->where("DATE(CONVERT_TZ(APPOINTMENT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) >=",format_date($post_data["dateVal"],1));	

				//$this->db->where("DATE(APPOINTMENT_DATE)",format_date($post_data["dateVal"],1));
			}
			if(isset($post_data["search_by_opnumber"]) && $post_data["search_by_opnumber"] != '')
			{
				$this->db->where("OP.OP_REGISTRATION_NUMBER",$post_data["search_by_opnumber"]);
			}
			if(isset($post_data["todate"]) && $post_data["todate"] != '')
			{
				//$this->db->where("DATE(APPOINTMENT_DATE)",format_date($post_data["dateVal"],1));
				$this->db->where("DATE(CONVERT_TZ(APPOINTMENT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) <=",format_date($post_data["todate"],1));	
			}	
			
			$this->db->select("A.*,A.AGE as APP_AGE,A.GENDER as APP_GENDER,OP.*,C.COUNTRY_ISO3,C.COUNTRY_NAME,DS.DOCTORS_NAME,DS.SPECIALIZED_IN,DS.AVG_CONS_TIME,PV.PATIENT_VISIT_LIST_ID,NA.NURSING_ASSESSMENT_ID,NA.STAT,B.BILL_STATUS");
			$this->db->from("APPOINTMENT A");
			$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = A.SCHEDULE_ID","left");
			$this->db->join("PATIENT_VISIT_LIST PV","PV.APPOINTMENT_ID = A.APPOINTMENT_ID","left");
			$this->db->join("NURSING_ASSESSMENT NA","NA.VISIT_ID = PV.PATIENT_VISIT_LIST_ID","left");
			$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = A.PATIENT_ID","left");	
			$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");	
			$this->db->join("BILLING B","NA.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","left");
			$this->db->order_by("A.APPOINTMENT_TIME","ASC");
			//$this->db->order_by("A.APPOINTMENT_ID","DESC");
			$query = $this->db->get();
			//echo $this->db->last_query();
			if ($query->num_rows() > 0)
			{	 		
	 			
	 			$data=$query->result_array();
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
				 $result 	= array('data'=>$data,
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
 	public function getAppointmentsByDoctor($post_data = array())
 	{
 		if ($post_data["doctor_id"] != "")
		{
			// $this->db->where("DATE(CONVERT_TZ(APPOINTMENT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."'))",format_date($post_data["dateVal"],1));	
			$this->db->where("A.DOCTOR_ID",$post_data["doctor_id"]);
			$this->db->select("A.*,A.AGE as APP_AGE,A.GENDER as APP_GENDER,OP.*,C.COUNTRY_ISO3,C.COUNTRY_NAME,DS.DOCTORS_NAME,DS.SPECIALIZED_IN,PV.PATIENT_VISIT_LIST_ID,NA.NURSING_ASSESSMENT_ID,NA.STAT,B.BILL_STATUS");
			$this->db->from("APPOINTMENT A");
			$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = A.SCHEDULE_ID","left");
			$this->db->join("PATIENT_VISIT_LIST PV","PV.APPOINTMENT_ID = A.APPOINTMENT_ID","left");
			$this->db->join("NURSING_ASSESSMENT NA","NA.VISIT_ID = PV.PATIENT_VISIT_LIST_ID","left");
			$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = A.PATIENT_ID","left");	
			$this->db->join("COUNTRY C","C.COUNTRY_ID = OP.NATIONALITY","left");	
			$this->db->join("BILLING B","NA.NURSING_ASSESSMENT_ID = B.ASSESSMENT_ID","left");
			$this->db->order_by("A.APPOINTMENT_ID","DESC");
			$query = $this->db->get();
			//echo $this->db->last_query();
			if ($query->num_rows() > 0)
			{	 		
	 			
	 			$result 	= array('data'=> $query->result(),
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
 	public function cancelAppointment($post_data = array())
 	{
 		if ($post_data["app_id"] != "")
		{
	 		$this->db->where("APPOINTMENT_ID",$post_data["app_id"]);	
			$query =  $this->db->update("APPOINTMENT",array("APPOINTMENT_STATUS"=>2));
			//echo $this->db->last_query();
			if ($this->db->affected_rows() > 0)
			{	 		
	 			
	 			$result 	= array('message'=> "Appointment cancelled successfully",
									'status'=>'Success',
									);

				return $result;	
	 		}
	 		else 
			{
				$result 	= array('message'=>"Failed to cancel Appointment",
									'status'=>'Failed',
									);
				return $result;
			}
		}
		else 
		{
			$result 	= array('message'=>"Invalid  Appointment",
									'status'=>'Failed',
									);
			return $result;
		}
		

 	}
 	public function getAppointment($post_data = array())
 	{
 		
 		$this->db->where("APPOINTMENT_ID",$post_data["app_id"]);	
		$this->db->select("A.*,A.AGE as APP_AGE,A.GENDER as APP_GENDER,OP.*,DS.DOCTORS_NAME");
		$this->db->from("APPOINTMENT A");
		$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = A.SCHEDULE_ID","left");
		$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = A.PATIENT_ID","left");	
		$this->db->order_by("A.APPOINTMENT_DATE,A.APPOINTMENT_TIME,A.PATIENT_NAME","asc");
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		{	 		
 			
 			$result 	= array('data'=> $query->row(),
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
 	public function getAllAppointments($post_data = array())
 	{
 		
 		$this->db->start_cache();				
			if($post_data["search_text"] != '')
			{
				//$this->db->where("DM.PROCEDURE_CODE",$post_data["search_text"]);
				$this->db->like("A.PATIENT_NAME",$post_data["search_text"]);
				$this->db->or_like("DS.DOCTORS_NAME",$post_data["search_text"]);
				$this->db->or_like("A.PATIENT_NO",$post_data["search_text"]);
				$this->db->or_like("A.APPOINTMENT_TIME",$post_data["search_text"]);
				$this->db->or_like("A.PATIENT_EMAIL",$post_data["search_text"]);
			}	
				$this->db->from("APPOINTMENT A");
				$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = A.SCHEDULE_ID","left");			$count = $this->db->count_all_results();	
		$this->db->stop_cache();
		$this->db->flush_cache();



 		$this->db->start_cache();				
		$this->db->select("A.*,DS.DOCTORS_NAME");
		$this->db->from("APPOINTMENT A");
		$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = A.SCHEDULE_ID","left");
		if($post_data["search_text"] != '')
		{
			//$this->db->where("DM.PROCEDURE_CODE",$post_data["search_text"]);
			$this->db->like("A.PATIENT_NAME",$post_data["search_text"]);
			$this->db->or_like("DS.DOCTORS_NAME",$post_data["search_text"]);
			$this->db->or_like("A.PATIENT_NO",$post_data["search_text"]);
			$this->db->or_like("A.APPOINTMENT_TIME",$post_data["search_text"]);
			$this->db->or_like("A.PATIENT_EMAIL",$post_data["search_text"]);
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
		$this->db->order_by("A.APPOINTMENT_DATE,A.APPOINTMENT_TIME,A.PATIENT_NAME","asc");
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
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
	

 	}
 	
	public function addNewAppointment($post_data = array())
	{
		$data=array(
			// "APPOINTMENT_DATE" 		=> date(toUtc(trim($post_data["dateVal"]),$post_data["timeZone"])." H:i:s"),
			"APPOINTMENT_DATE" 		=> format_date(trim($post_data["dateVal"])),
			"APPOINTMENT_TIME" 		=> trim($post_data["sel_time"]),
			"APPOINTMENT_END_TIME" 	=> trim($post_data["end_time"]),
			"SCHEDULE_ID" 			=> trim($post_data["sel_schedule"]),
			"DOCTOR_ID" 			=> trim($post_data["sel_doc"]),
			"SLOT_NO" 				=> trim($post_data["sel_slot"]),
			"PATIENT_NAME" 			=> trim($post_data["p_name"]),
			"PATIENT_NO" 			=> trim($post_data["p_number"]),
			"PATIENT_ID" 			=> trim($post_data["p_id"]),
			"PATIENT_PHONE_NO" 		=> trim($post_data["ph_no"]),
			"PATIENT_EMAIL" 		=> trim($post_data["email"]),
			"AGE" 					=> trim($post_data["age"]),
			"GENDER" 				=> trim($post_data["gender"]),
			"APPOINTMENT_TYPE" 		=> trim($post_data["app_type"]),
			"CREATED_USER" 			=> (int) $post_data['user_id'],
			"CREATED_DATE" 			=> date('Y-m-d H:i:s'),
			"CLIENT_DATE" 			=> format_date($post_data["client_date"])	
		);

		//print_r($post_data);exit;
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
		$data_id = (int)$post_data["app_id"];
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
			$this->db->where("APPOINTMENT_ID",$data_id);
			$this->db->update("APPOINTMENT",$data);
			$result = array("status"=> "Success", "reg_id"=>$data_id)	;
		}
		else
		{
			
			//$gen_no=$this->GenerateOpNo($post_data);
			
			$this->db->insert("APPOINTMENT",$data);
			$data_id = $this->db->insert_id();
			 //$this->addScheduleTable($data_id,$post_data["time_table"]);
			$result = array("status"=> "Success", "reg_id"=>$data_id)	;
		}
		return $result;
	}
	function addScheduleTable($schedule_id,$time_table)
	{
		if(is_array($time_table) && $schedule_id != "" && $schedule_id != null)
		{
			$this->db->delete("DOCTORS_SCHEDULE_TIME_TABLE",array("DOCTORS_SCHEDULE_ID" =>$schedule_id));
			foreach ($time_table as $key => $value) {
				if($value["de_select"] == false)
				{
					$t_data["DOCTORS_SCHEDULE_ID"] = $schedule_id;
					$t_data["DAY"] = $key;
					foreach ($value as $schedule => $data) {
						if($schedule != "de_select")
						{
							$t_data["SCHEDULE_NO"] = $schedule;
							$t_data["START_AT"] = $data["from"];
							$t_data["END_AT"] = $data["to"];

							if($schedule != "" && $value!= null)
								$this->db->insert("DOCTORS_SCHEDULE_TIME_TABLE",$t_data);
						}
						# code...
					}
					
					
				}
			}
		}
	}
	function getPatientsByPhoneNo($post_data = array())
	{	
		if ($post_data["ph_no"] != "" && strlen($post_data["ph_no"]) > 2) 
		{
	 		// $this->db->where("MOBILE_NO",$post_data["ph_no"]);	
	 		$this->db->like("MOBILE_NO",$post_data["ph_no"]);
			$this->db->select("CONCAT_WS(' ',OP.FIRST_NAME,OP.MIDDLE_NAME,OP.LAST_NAME) as name,MOBILE_NO as phone_no, OP_REGISTRATION_NUMBER as OP_no, GENDER as gender, AGE as age, OP_REGISTRATION_DATE as date, OP_REGISTRATION_ID as reg_id, EMAIL_ID as email ,'1' as type");
			$this->db->from("OP_REGISTRATION OP");
			$this->db->group_by("OP.OP_REGISTRATION_NUMBER");
			$query = $this->db->get();
			// echo $this->db->last_query();
			$result_arr = $query->result();
			
			// $this->db->where("PATIENT_PHONE_NO",$post_data["ph_no"]);	
			// $this->db->select("AP.PATIENT_NAME as name,AP.PATIENT_PHONE_NO as phone_no, OP.OP_REGISTRATION_NUMBER as OP_no, AP.GENDER as gender, AP.AGE as age, APPOINTMENT_DATE as date, AP.PATIENT_EMAIL as email ,'2' as type");
			// $this->db->from("APPOINTMENT AP");
			// $this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = AP.PATIENT_ID","left");	
			
			// $query = $this->db->get();
			// $array2 = $query->result();
			//if(is_array($array1) && is_array($array))
				// $result_arr = array_merge($array1, $array2);
			//else if(is_array($array1))


			if (count($result_arr) > 0)
			{	 		
	 			
	 			$result 	= array('data'=> $result_arr,
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

	
	public function checkAvailableSlots($post_data = array())
 	{
 		$result = array('data'=>"",'status'=>'Failed');
 		if($post_data["date"] != "" && $post_data["doctor_id"] != '' && $post_data["schedule_id"] != "" && $post_data["slot"] != "" && count($post_data["slots"]) > 0)
 		{
 			$this->db->where("DOCTOR_ID",$post_data["doctor_id"]);	
	 		$this->db->where("SCHEDULE_ID",$post_data["schedule_id"]);	
	 		$this->db->where("SLOT_NO",$post_data["slot"]);	
	 		$this->db->where("APPOINTMENT_STATUS !=",2);	
	 		$this->db->where("DATE(APPOINTMENT_DATE)",format_date($post_data["date"],1));	
	 		$this->db->where_in("APPOINTMENT_TIME",$post_data["slots"]);	
			$this->db->select("A.*");
			$this->db->from("APPOINTMENT A");
			$query = $this->db->get();
			// echo $this->db->last_query();
			if ($query->num_rows() > 0)	
			{	 
	 			$result = array('data'=> $query->result_array(),'status'=>'Success');
					
	 		}
 		}
 		
 		return $result;
	

 	}
 	public function changeAppointmentStatus($post_data = array())
 	{
 		$result = array('status'=>'Failed','message'=> "Failed");
 		if($post_data["app_id"] != "" && $post_data["appointment_status"] > 1)
		{
	 		$this->db->where("APPOINTMENT_ID",$post_data["app_id"]);	
			$query =  $this->db->update("APPOINTMENT",array("APPOINTMENT_STATUS"=>$post_data["appointment_status"]));
			if ($this->db->affected_rows() > 0)
			{	
	 			$result = array('message'=> "Appointment status changed successfully",'status'=>'Success');
	 		}
		}
		return $result;
 	}
 	

 	function getDrScheduleListByDate($post_data = array())
	{
		if ($post_data["dateVal"] && $post_data["timeZone"] != "")
		{
			$day = date('D', strtotime(format_date($post_data["dateVal"],1)));
			$date = format_date($post_data["dateVal"],1);
			$this->db->start_cache();
			$this->db->select("T.DOCTORS_SCHEDULE_ID,T.DAY,T.SCHEDULE_NO,T.START_AT,T.END_AT,DS.DOCTORS_ID,DS.DOCTORS_NAME,DS.SPECIALIZED_IN,DS.AVG_CONS_TIME,SP.OPTIONS_NAME as SPECIALIZED_NAME, count(AP.APPOINTMENT_ID) as AP_COUNT");
			$this->db->from("DOCTORS_SCHEDULE_TIME_TABLE T");
			$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = T.DOCTORS_SCHEDULE_ID","left");
			$this->db->join("OPTIONS SP","SP.OPTIONS_ID = DS.SPECIALIZED_IN AND SP.OPTIONS_TYPE = 8","left");
			$this->db->join("APPOINTMENT AP","AP.SCHEDULE_ID = DS.DOCTORS_SCHEDULE_ID AND AP.SLOT_NO = T.SCHEDULE_NO AND DATE(CONVERT_TZ(APPOINTMENT_DATE,'+00:00','".timezoneToOffset($post_data["timeZone"])."')) = '".$date."'","left");
			$this->db->where("UPPER(DAY)",strtoupper($day));
			$this->db->where("DOCTORS_SCHEDULE_TIME_TABLE_STATUS",1);
			$this->db->order_by("DS.DOCTORS_NAME,T.SCHEDULE_NO","asc");
			$this->db->group_by("T.DOCTORS_SCHEDULE_TIME_TABLE_ID,T.SCHEDULE_NO");
			$query = $this->db->get();
				//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$re_array = $query->result();
				$return_array = array();
				$res = array();
				foreach ($re_array as $key => $value) {
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["SCHEDULE_NO"] = $value->SCHEDULE_NO;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["START_AT"] = $value->START_AT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["END_AT"] = $value->END_AT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["END_AT"] = $value->END_AT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["AP_COUNT"] = $value->AP_COUNT;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["slots"][$value->SCHEDULE_NO]["DAY"] = $value->DAY;
					
					$return_array[$value->DOCTORS_SCHEDULE_ID]["name"]=$value->DOCTORS_NAME;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["specialized"]=$value->SPECIALIZED_NAME;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["id"]=$value->DOCTORS_ID;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["schedule_id"]=$value->DOCTORS_SCHEDULE_ID;
					$return_array[$value->DOCTORS_SCHEDULE_ID]["avg_time"]=$value->AVG_CONS_TIME;
				}
				foreach ($return_array as $key => $value) {
					$slots = array();
					foreach($value["slots"]  as $s => $a)
					{
						$slots[] = $a;
					}
					$value["slotList"] = $slots;
					$res["DOCTORS"][] = $value;
					//$return_array[$value->DOCTORS_SCHEDULE_ID]["name"]=$value->DOCTORS_NAME;
				}
				$result 	= array('data'=>$res,
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
			
		// $d = strtotime("today");
		// if ($post_data["dateVal"] != ""){
		// 	$choosedate = strtotime($post_data['dateVal']);
		// 	$return_array = array();
		//     $day =  $choosedate->format("D");
		//     $date =  $choosedate->format("Y-m-d");
		// 	$this->db->start_cache();
		// 	$this->db->select("T.DOCTORS_SCHEDULE_ID,T.DAY,T.SCHEDULE_NO,T.START_AT,T.END_AT,DS.DOCTORS_ID,DS.DOCTORS_NAME,DS.SPECIALIZED_IN,DS.AVG_CONS_TIME,SP.OPTIONS_NAME as SPECIALIZED_NAME, count(AP.APPOINTMENT_ID) as AP_COUNT");
		// 	$this->db->from("DOCTORS_SCHEDULE_TIME_TABLE T");
		// 	$this->db->join("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID = T.DOCTORS_SCHEDULE_ID","left");
		// 	$this->db->join("OPTIONS SP","SP.OPTIONS_ID = DS.SPECIALIZED_IN AND SP.OPTIONS_TYPE = 8","left");
		// 	$this->db->join("APPOINTMENT AP","AP.SCHEDULE_ID = DS.DOCTORS_SCHEDULE_ID AND AP.SLOT_NO = T.SCHEDULE_NO AND APPOINTMENT_DATE = '".$date."'","left");
		// 	$this->db->where("UPPER(DAY)",strtoupper($day));
		// 	$this->db->where("DOCTORS_SCHEDULE_TIME_TABLE_STATUS",1);
		// 	$this->db->order_by("DS.DOCTORS_NAME,T.SCHEDULE_NO","asc");
		// 	$this->db->group_by("T.DOCTORS_SCHEDULE_TIME_TABLE_ID,T.SCHEDULE_NO");
		// 	$query = $this->db->get();
		// 	$this->db->stop_cache();
		// 	$this->db->flush_cache();
		// 	if($query->num_rows() > 0)
		// 	{
		// 		$re_array = $query->result();
		// 		$byDate = array();
		// 		foreach ($re_array as $key => $value) {
		// 			$return_array["slots"][$value->SCHEDULE_NO]["SCHEDULE_NO"] = $value->SCHEDULE_NO;
		// 			$return_array["slots"][$value->SCHEDULE_NO]["START_AT"] = $value->START_AT;
		// 			$return_array["slots"][$value->SCHEDULE_NO]["END_AT"] = $value->END_AT;
		// 			$return_array["slots"][$value->SCHEDULE_NO]["END_AT"] = $value->END_AT;
		// 			$return_array["slots"][$value->SCHEDULE_NO]["AP_COUNT"] = $value->AP_COUNT;

		// 			$return_array["DAY"] = $value->DAY;
		// 			$return_array["date"]=$date;
		// 			$return_array["name"]=$value->DOCTORS_NAME;
		// 			$return_array["specialized"]=$value->SPECIALIZED_NAME;
		// 			$return_array["id"]=$value->DOCTORS_ID;
		// 			$return_array["schedule_id"]=$value->DOCTORS_SCHEDULE_ID;
		// 			$return_array["avg_time"]=$value->AVG_CONS_TIME;
		// 		}
		// 			$slots = array();
		// 			foreach($return_array["slots"]  as $s => $a)
		// 			{
		// 				$slots[] = $a;
		// 			}
		// 			$return_array["slots"] = array();
		// 			$return_array["slotList"] = $slots;
		// 		$byDate['DATES'][$date]	= $return_array;
		// 	}
		// 	else 
		// 	{
		// 		$byDate['DATES'][$date] = "";
		// 	}

		// 	$result = array('data'=>$re_array,'status'=>'Success');
		// 	return $result;
		// }
		// else 
		// {
		// 	$result 	= array('data'=>array(),'status'=>'Failed');
		// 	return $result;
		// }
	}

} 
?>