<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DoctorScheduleModel extends CI_Model 
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
	function getScheduleList($post_data = array())
	{
		
			$this->db->start_cache();
			$this->db->select("DS.DOCTORS_NAME,DS.DOCTORS_SCHEDULE_ID,DS.AVG_CONS_TIME,DS.DOCTORS_ID,O.OPTIONS_NAME as SPECIALIZED_IN");
			$this->db->from("DOCTORS_SCHEDULE DS");
			$this->db->join("OPTIONS O","O.OPTIONS_ID = DS.SPECIALIZED_IN AND O.OPTIONS_STATUS = 1","left");
			//$this->db->join("DOCTORS_SCHEDULE_TIME_TABLE DT","DT.DOCTORS_SCHEDULE_ID = DS.DOCTORS_SCHEDULE_ID AND DT.DOCTORS_SCHEDULE_TIME_TABLE_STATUS = 1","left");

			$this->db->order_by("DS.DOCTORS_NAME","asc");
			$this->db->group_by("DS.DOCTORS_SCHEDULE_ID");
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$list_data = $query->result();
				$return_array = array();
				$i = 0;
				foreach ($list_data as $key => $value) {
					$return_array[$i]["DOCTORS_NAME"] = $value->DOCTORS_NAME;
					$return_array[$i]["DOCTORS_SCHEDULE_ID"] = $value->DOCTORS_SCHEDULE_ID;
					$return_array[$i]["AVG_CONS_TIME"] = $value->AVG_CONS_TIME;
					$return_array[$i]["DOCTORS_ID"] = $value->DOCTORS_ID;
					$return_array[$i]["SPECIALIZED_IN"] = $value->SPECIALIZED_IN;
					
					$return_array[$i]["DAYS_ON_WEEK"] =$this->activeDays($value->DOCTORS_SCHEDULE_ID);
					$i++;
				}
				$result 	= array('data'=>$return_array,
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
	function getDoctorDetail($doctor_id)
	{
		$this->db->start_cache();
			$this->db->select("DOCTORS_ID,DOCTORS_NAME");
			$this->db->from("DOCTORS");
			$this->db->where("DOCTORS_ID",$doctor_id);
			$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		return $query->result_array();
	}
	function getScheduleById($post_data = array())
	{
		
			$this->db->start_cache();
			$this->db->select("DS.DOCTORS_NAME,DS.DOCTORS_SCHEDULE_ID,DS.AVG_CONS_TIME,DS.DOCTORS_ID,DS.SPECIALIZED_IN,DT.START_AT,DT.END_AT,DT.SCHEDULE_NO,DT.DAY");
			$this->db->from("DOCTORS_SCHEDULE DS");
			//$this->db->join("OPTIONS O","O.OPTIONS_ID = DS.SPECIALIZED_IN AND O.OPTIONS_STATUS = 1","left");
			$this->db->join("DOCTORS_SCHEDULE_TIME_TABLE DT","DT.DOCTORS_SCHEDULE_ID = DS.DOCTORS_SCHEDULE_ID AND DT.DOCTORS_SCHEDULE_TIME_TABLE_STATUS = 1","left");
			$this->db->where("DS.DOCTORS_SCHEDULE_ID",$post_data['id']);

			//$this->db->order_by("DS.DOCTORS_SCHEDULE_ID","asc");
			//$this->db->group_by("DS.DOCTORS_SCHEDULE_ID");
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$list_data = $query->result();
				$return_array = array();
				$i = 0;
				foreach ($list_data as $key => $value) {
					$return_array["DOCTORS_NAME"] = $value->DOCTORS_NAME;
					$return_array["DOCTORS_SCHEDULE_ID"] = $value->DOCTORS_SCHEDULE_ID;
					$return_array["AVG_CONS_TIME"] = $value->AVG_CONS_TIME;
					$return_array["DOCTORS_ID"] = $value->DOCTORS_ID;
					$return_array["SPECIALIZED_IN"] = $value->SPECIALIZED_IN;
					$return_array["DOCTOR_DETAILS"] =$this->getDoctorDetail($value->DOCTORS_ID);
					$return_array[$value->DAY][$value->SCHEDULE_NO]["from"] = $value->START_AT;
					$return_array[$value->DAY][$value->SCHEDULE_NO]["to"] = $value->END_AT;
					$return_array[$value->DAY][$value->SCHEDULE_NO]["SLOT"] = $value->SCHEDULE_NO;
					//$i++;
				}
				$result 	= array('data'=>$return_array,
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
	public function addNewSchedule($post_data = array())
	{
		$result = array("status"=> "Failed", "response"=>"Failed to saved the details");
		$data=array(
		
		"DOCTORS_ID" 		=> trim($post_data["dr_id"]),
		"DOCTORS_NAME" 		=> trim($post_data["dr_name"]),
		"SPECIALIZED_IN" 	=> trim($post_data["sel_spec_in"]),
		"AVG_CONS_TIME" 	=> trim($post_data["avg_con_t"]),
		"CREATED_USER" 		=> (int) $post_data['user_id'],
		"CREATED_TIME"	 	=> date('Y-m-d H:i:s'),		
		"CLIENT_DATE" 		=> format_date($post_data["client_date"])		
				);
		$data_id = (int) $post_data["schedule_id"];
		if($this->utility->is_Duplicate("DOCTORS_SCHEDULE",array("0"=>"DOCTORS_ID"), array("0"=>trim($post_data["dr_id"])),"DOCTORS_SCHEDULE_ID",$data_id))
		{								
			return array("status"=> "Failed", "data_id"=>$data_id, "response"=> "Already schedule exists for Dr.".trim($post_data["dr_name"]));
		}
		
		if ($data_id> 0)
		{
			$this->db->where("DOCTORS_SCHEDULE_ID",$data_id);
			if($this->db->update("DOCTORS_SCHEDULE",$data))
			{
				$this->addScheduleTable($data_id,$post_data["time_table"]);
				$result = array("status"=> "Success", "response"=>"Schedule  saved successfully...");
			}
			
		}
		else
		{
			
		
			if($this->db->insert("DOCTORS_SCHEDULE",$data))
			{
				$data_id = $this->db->insert_id();
			 	$this->addScheduleTable($data_id,$post_data["time_table"]);
				$result = array("status"=> "Success", "response"=>"Schedule  saved successfully...");
			
			}
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

							if($schedule != "" && $data["from"] != null)
								$this->db->insert("DOCTORS_SCHEDULE_TIME_TABLE",$t_data);
						}
						# code...
					}
					
					
				}
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
	public function getDoctorsSpecialized($post_data = array())
	{
		$this->db->start_cache();
			$this->db->select("OPTIONS_ID,OPTIONS_NAME");
			$this->db->from("DOCTORS_DEPARTMENTS DD");
			$this->db->join("OPTIONS O","O.OPTIONS_ID = DD.DEPARTMENT_ID AND O.OPTIONS_STATUS = 1","left");
			$this->db->where("DD.DOCTORS_ID ",$post_data["doctor_id"]);
			//$this->db->group_by("DT.DAY");
			
			$query = $this->db->get();
			//echo $this->db->last_query();
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result 	= array('data'=>$query->result_array(),
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

} 
?>