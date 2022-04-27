<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LabInvestigationModel extends CI_Model 
{
	public function listLabInvestigation($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());		
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("LAB_INVESTIGATION PA");		
		$this->db->where("STAT",1);
		if($post_data["lab_investigation_id"]!='')
		{
			$this->db->where("LAB_INVESTIGATION_ID", $post_data["lab_investigation_id"]);
		}			
		if($post_data["patient_id"]!='')
		{
			$this->db->where("PATIENT_ID", $post_data["patient_id"]);
		}
		
		if($post_data["assessment_id"]!='')
		{
			$this->db->where("ASSESSMENT_ID", $post_data["assessment_id"]);
		}
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
	
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();											
			$result = array("status"=> "Success", "data"=> $data);
		}
		
		return $result;
	}
	
	public function saveLabInvestigation($post_data)
	{
	
		$result = array("status"=> "Failed", "data"=> array());	
			
			$dp_data = array(
							"PATIENT_ID" => trim($post_data["patient_id"]),
							"ASSESSMENT_ID" => trim($post_data["assessment_id"])							
							 );
							 
			$data = array(
							"PATIENT_ID" => trim($post_data["patient_id"]),
							"USER_ID" => trim($post_data["user_id"]),
							"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
							"LAB_INV_PRIORITY_ID" => trim($post_data["lab_investigation_priority_id"]),
							"BILLED_AMOUNT" => trim($post_data["billed_amount"]),
							"UN_BILLED_AMOUNT" => trim($post_data["un_billed_amount"]),
							"INSTRUCTION_TO_CASHIER" => trim($post_data["instruction_to_cashier"]),
							"DATE_LOG" => toUtc(trim($post_data["date"]),$post_data["timeZone"]),//date('Y-m-d H:i:s'),	
							"CLIENT_DATE" => format_date($post_data["client_date"])
							 );
						 										
			
			$data_id = trim($post_data["lab_investigation_id"]);
									
			$ret = $this->ApiModel->mandatory_check( $post_data , array('user_id','patient_id','assessment_id','current_procedural_code_id_arr'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }	
                       									
			if($this->utility->is_Duplicate("LAB_INVESTIGATION",array_keys($dp_data), array_values($dp_data),"LAB_INVESTIGATION_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}	
												
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("LAB_INVESTIGATION_ID",$data_id);
				$this->db->update("LAB_INVESTIGATION",$data);
					
				$this->db->stop_cache();
				$this->db->flush_cache();								
			}
			else
			{				
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
				$ret = $this->saveLabInvestigationDetails($data_id, $post_data);
				if($ret){
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}
		
		return $result;
	}
	
	public function saveLabInvestigationDetails($lab_investigation_id, $req_array)
	{
		$flag = false;
		if($lab_investigation_id > 0)
		{		
				if(is_array($req_array['current_procedural_code_id_arr']))
				{
					$this->db->start_cache();			
					$this->db->where("LAB_INVESTIGATION_ID", $lab_investigation_id);												
					$this->db->delete("LAB_INVESTIGATION_DETAILS");
					$this->db->stop_cache();
					$this->db->flush_cache();	
					$data = array();
					foreach($req_array['current_procedural_code_id_arr'] as $key => $cpt_code_id)
					{
						$data[$key]['SERIAL_NO'] = $key;			
						$data[$key]['LAB_INVESTIGATION_ID'] = $lab_investigation_id; 	
						$data[$key]['CURRENT_PROCEDURAL_CODE_ID'] = $cpt_code_id;																											
						$data[$key]['DESCRIPTION'] = (isset($req_array["description_arr"][$key]) ? $req_array["description_arr"][$key] :'' );	
							
						$data[$key]['QUANTITY'] = (isset($req_array["quantity_arr"][$key]) ? $req_array["quantity_arr"][$key] :'' );		
						$data[$key]['RATE'] = (isset($req_array["rate_arr"][$key]) ? $req_array["rate_arr"][$key] :'' );		
						$data[$key]['CHANGE_TO_FUTURE'] = (isset($req_array["change_to_future_arr"][$key]) ? $req_array["change_to_future_arr"][$key] :'' );
								
						$data[$key]['REMARKS'] = (isset($req_array["remarks_arr"][$key]) ? $req_array["remarks_arr"][$key] :'' );		
						$data[$key]['LAB_PRIORITY_ID'] = (isset($req_array["lab_priority_id_arr"][$key]) ? $req_array["lab_priority_id_arr"][$key] :'' );		
																											
					}
					$this->db->insert_batch('LAB_INVESTIGATION_DETAILS', $data);
					$flag = true;
				} 															
		}
		return $flag;						 
	}
	
	public function getbillStatus($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		 				
		$this->db->start_cache();			
		$this->db->select("DM.BILL_STATUS");
		
				
		if($post_data["patient_id"]!='')
		{
			$this->db->where("PATIENT_ID", $post_data["patient_id"]);
		}
		if($post_data["assessment_id"]!='')
		{
			$this->db->where("ASSESSMENT_ID", $post_data["assessment_id"]);
		}
		$this->db->from("BILLING DM");			
						
		$query = $this->db->get();
		//echo $this->db->last_query();exit;		
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$data["bill_status"] = $query->row()->BILL_STATUS;	
			//$data['LAB_INVESTIGATION_DETAILS'] = $this->getLabInvestigationDetails($data['LAB_INVESTIGATION_ID'],'LAB_INVESTIGATION_DETAILS LID');										
		}
		if(!empty($data))
			$result = array("status"=> "Success", "data"=> $data);
				
		
		return $result;
		
	}
	public function getLabInvestigation($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"]!='' && $post_data["assessment_id"]!=''){ 				
			$this->db->start_cache();			
			$this->db->select("DM.*");
			
			if($post_data["lab_investigation_id"]!='')
			{
				$this->db->where("LAB_INVESTIGATION_ID", $post_data["lab_investigation_id"]);
			}			
			if($post_data["patient_id"]!='')
			{
				$this->db->where("PATIENT_ID", $post_data["patient_id"]);
			}
			if($post_data["consultation_id"]!='')
			{
				$this->db->where("CONSULTATION_ID", $post_data["consultation_id"]);
			}
			if($post_data["assessment_id"]!='')
			{
				$this->db->where("ASSESSMENT_ID", $post_data["assessment_id"]);
			}
			$this->db->where("IS_DENTAL IS NULL");
			$this->db->from("LAB_INVESTIGATION DM");			
							
			$query = $this->db->get();
			 // echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();	
				$data['LAB_INVESTIGATION_DETAILS'] = $this->getLabInvestigationDetails($data['LAB_INVESTIGATION_ID'],'LAB_INVESTIGATION_DETAILS LID');										
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
					
		}	
		return $result;
		
	}
	
	public function getLabInvestigationDetails($lab_investigation_id, $table)
	{
			$this->db->start_cache();			
			$this->db->select("LID.*");	
			$this->db->select("CPT.PROCEDURE_CODE as PROCEDURE_CODE, CPT.PROCEDURE_CODE_NAME as PROCEDURE_CODE_NAME,CPT.PROCEDURE_CODE_CATEGORY as PROCEDURE_CODE_CATEGORY");																			
			$this->db->from($table);
			$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CPT.CURRENT_PROCEDURAL_CODE_ID = LID.CURRENT_PROCEDURAL_CODE_ID","left");						
			$this->db->where("LAB_INVESTIGATION_ID", $lab_investigation_id);
			// $this->db->order_by("LID.SERIAL_NO","ASC");			
			$this->db->order_by("LID.LAB_INVESTIGATION_DETAILS_ID","ASC");			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			$data = array();
			if($query->num_rows() > 0)
			{
				$data = $query->result_array();							
			}
			return $data;
	}
	
	public function deleteLabInvestigation($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		$data_id = $post_data["lab_investigation_id"];
		if($data_id > 0)
		{															
			$this->db->start_cache();		
			$this->db->select("*");
			$this->db->from("LAB_INVESTIGATION");
			$this->db->where("LAB_INVESTIGATION_ID", $data_id);			
			$this->db->where("STAT", 2);			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{				
				$result = array("status"=> "Failed","msg"=>"Record Can't be deleted, Reference data available", "data"=> array());
				return $result;	
			}else
			{
				$this->db->start_cache();			
				$this->db->where("LAB_INVESTIGATION_ID", $data_id);												
				$this->db->delete("LAB_INVESTIGATION_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();														
			
				$this->db->start_cache();
				$this->db->where("LAB_INVESTIGATION_ID", $data_id);
				$ret = $this->db->delete("LAB_INVESTIGATION");			
				//echo $this->db->last_query();exit;		
				$this->db->stop_cache();
				$this->db->flush_cache();				 
				if(!empty($ret))
					$result = array("status"=> "Success", "data"=> $data);
				
			}										
		}
		return $result;
		
	}
	
	public function saveInvestigation($post_data)
	{
	
		$result = array("status"=> "Failed", "data"=> array());	
			
		$dp_data = array(
			"PATIENT_ID" => trim($post_data["patient_id"]),
			"ASSESSMENT_ID" => trim($post_data["assessment_id"])							
		 );		 
		$data = array(
			"PATIENT_ID" => trim($post_data["patient_id"]),
			"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
			// "LAB_INV_PRIORITY_ID" => trim($post_data["priority"]),
			"BILLED_AMOUNT" => trim($post_data["billedamount"]),
			"UN_BILLED_AMOUNT" => trim($post_data["un_billedamount"]),
			"INSTRUCTION_TO_CASHIER" => trim($post_data["instruction"]),
			
		);	 										
		$data_id = trim($post_data["lab_investigation_id"]);
									
		$ret = $this->ApiModel->mandatory_check( $post_data , array('patient_id','assessment_id','current_procedural_code_id'));		  
	   if($ret!='')
	   {		  	 		  		                         	 		  
    	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> 'Mandatory field missing');   
     	}	
                       									
		// if($this->utility->is_Duplicate("LAB_INVESTIGATION",array_keys($dp_data), array_values($dp_data),"LAB_INVESTIGATION_ID",$data_id))
		// {								
		// 	return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
		// }	
											
		if ($data_id > 0)
		{				
			$this->db->start_cache();
			$data["UPDATED_DATE"] = format_date($post_data["client_date"]);
			$data["UPDATED_BY"] = trim($post_data["user_id"]);
			// if($post_data["billedamount"] > 0)
			// {
			// 	$data["BILLED_AMOUNT"] = trim($post_data["billedamount"]);
			// }	
			$this->db->where("LAB_INVESTIGATION_ID",$post_data["lab_investigation_id"]);
			$this->db->update("LAB_INVESTIGATION",$data);
				
			// $this->db->stop_cache();
			// $this->db->flush_cache();	
			// $this->db->start_cache();

			// $sql = "UPDATE LAB_INVESTIGATION SET BILLED_AMOUNT = BILLED_AMOUNT + ".$post_data['rate']." WHERE LAB_INVESTIGATION_ID = ".$data_id;

			// $this->db->query($sql);
			// $this->db->stop_cache();
			// $this->db->flush_cache();	
				
									
		}
		else
		{		
			$data["DATE_LOG"] = toUtc(trim($post_data["date"]),$post_data["timeZone"]);//date('Y-m-d H:i:s'),	
			$data["CLIENT_DATE"] = format_date($post_data["client_date"]);
			$data["USER_ID"] = trim($post_data["user_id"]);
			// $data["BILLED_AMOUNT"] = trim($post_data["rate"]);	
			// if($post_data["billedamount"] > 0)
			// {
			// 	$data["BILLED_AMOUNT"] = trim($post_data["billedamount"]);
			// }	
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
			$ret = $this->ApiModel->mandatory_check( $post_data , array('current_procedural_code_id','user_id','quantity'));		  
		   	if($ret!='')
		    {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> 'Mandatory field missing');   
         	}	
	       	$cpt  = array(
				'LAB_INVESTIGATION_ID' => $data_id,
				'CURRENT_PROCEDURAL_CODE_ID' => trim($post_data['current_procedural_code_id']),
				'DESCRIPTION' => trim($post_data['description']),
				'QUANTITY' => trim($post_data['quantity']),
				'RATE' => trim($post_data['rate']),
				'CHANGE_TO_FUTURE' => trim($post_data['change_of_future']),
				'REMARKS' => trim($post_data['remarks']),
				'LAB_PRIORITY_ID' => trim($post_data['priority']),
				'USER_ID' => trim($post_data['user_id']),
				);
				$lab_investigation_details_id = $post_data['lab_investigation_details_id'];

				
			 // 	if($this->utility->is_Duplicate("LAB_INVESTIGATION_DETAILS","CURRENT_PROCEDURAL_CODE_ID",$post_data["current_procedural_code_id"],"LAB_INVESTIGATION_DETAILS_ID",$post_data['lab_investigation_details_id']))
				// {								
				// 	$result= array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
				// 	return $result;
				// }
				// $this->db->start_cache();			
				// $this->db->select("*");			
				// $this->db->from("LAB_INVESTIGATION_DETAILS");			
				// $this->db->where("LAB_INVESTIGATION_ID",$data_id);
				// $query = $this->db->get();
				// if($query->num_rows() > 0)
				// {
				// 	$this->db->start_cache();			
				// 	$this->db->select("*");			
				// 	$this->db->from("LAB_INVESTIGATION_DETAILS");			
				// 	$this->db->where("LAB_INVESTIGATION_ID",$data_id);
					
				// }
				// $this->db->stop_cache();
				// $this->db->flush_cache();

			if($post_data['lab_investigation_details_id'] > 0)
			{
				// if($this->utility->is_Duplicate("LAB_INVESTIGATION_DETAILS","CURRENT_PROCEDURAL_CODE_ID",$post_data["current_procedural_code_id"],"LAB_INVESTIGATION_DETAILS_ID",$post_data['lab_investigation_details_id']))
				// {								
				// 	$result= array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
				// 	return $result;
				// }
				$this->db->start_cache();			
				$this->db->where("LAB_INVESTIGATION_DETAILS_ID",$post_data["lab_investigation_details_id"]);
				$this->db->where("LAB_INVESTIGATION_ID",$data_id);
				$this->db->update("LAB_INVESTIGATION_DETAILS",$cpt);	
				$this->db->stop_cache();
				$this->db->flush_cache();
				$result =  array("status"=> "Success", "data_id"=>$data_id, "lab_investigation_details_id"=>$lab_investigation_details_id ,"msg"=> "CPT items saved successfully..!")	;
			}
			else
			{
				// if($this->utility->is_Duplicate("LAB_INVESTIGATION_DETAILS","CURRENT_PROCEDURAL_CODE_ID",$post_data["current_procedural_code_id"],"LAB_INVESTIGATION_DETAILS_ID",$post_data['lab_investigation_details_id']))
				// {								
				// 	$result= array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
				// 	return $result;
				// }
					$this->db->start_cache();			
					$this->db->insert("LAB_INVESTIGATION_DETAILS",$cpt);
					$lab_investigation_details_id = $this->db->insert_id();				
					$this->db->stop_cache();
					$this->db->flush_cache();
					$result =  array("status"=> "Success", "data_id"=>$data_id, "lab_investigation_details_id"=>$lab_investigation_details_id ,"msg"=> "CPT items saved successfully..!")	;
				}
			}
		
		return $result;
	}
	public function deleteInvestigation($post_data)
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

				$sql = "UPDATE LAB_INVESTIGATION SET BILLED_AMOUNT =  ".$post_data['billedamount']." WHERE LAB_INVESTIGATION_ID = ".$data_id;

				$this->db->query($sql);
				$this->db->stop_cache();
				$this->db->flush_cache();	
										
				$this->db->start_cache();			
				$this->db->where("LAB_INVESTIGATION_DETAILS_ID",$post_data["lab_investigation_details_id"]);
				// $this->db->where("LAB_INVESTIGATION_ID",$data_id);
				$this->db->delete("LAB_INVESTIGATION_DETAILS");	
				$this->db->stop_cache();
				$this->db->flush_cache();
				$result =  array("status"=> "Success",  "msg"=> "CPT items deleted successfully..!")	;
			}	
			

			
		}
		return $result;
		
	}


	// FOr dental

	public function saveDentalInvestigation($post_data)
	{
	
		$result = array("status"=> "Failed", "data"=> array());	
			
			$dp_data = array(
				"PATIENT_ID" => trim($post_data["patient_id"]),
				"ASSESSMENT_ID" => trim($post_data["assessment_id"])							
			);
							 
			$data = array(
				"PATIENT_ID" => trim($post_data["patient_id"]),
				"USER_ID" => trim($post_data["user_id"]),
				"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
				"IS_DENTAL" => 1,	
				"DATE_LOG" => toUtc(trim($post_data["date"]),$post_data["timeZone"]),//date('Y-m-d H:i:s'),	
				"CLIENT_DATE" => format_date($post_data["client_date"])
			);
						 										
			
			$data_id = trim($post_data["lab_investigation_id"]);
									
			$ret = $this->ApiModel->mandatory_check( $post_data , array('user_id','patient_id','assessment_id','current_procedural_code_id_arr'));		  
		   if($ret!='')
		   {		  	 		  		                         	 		  
        	  return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
         }	
                       									
			if($this->utility->is_Duplicate("LAB_INVESTIGATION",array_keys($dp_data), array_values($dp_data),"LAB_INVESTIGATION_ID",$data_id))
			{								
				return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
			}	
												
			if ($data_id > 0)
			{				
				$this->db->start_cache();			
		
				$this->db->where("LAB_INVESTIGATION_ID",$data_id);
				$this->db->update("LAB_INVESTIGATION",$data);
				$this->db->stop_cache();
				$this->db->flush_cache();								
			}
			else
			{				
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
				$ret = $this->ssaveDentalInvestigationDetails($data_id, $post_data);
				if($ret){
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Data updated")	;
				}
			}
		
		return $result;
	}
	
	public function ssaveDentalInvestigationDetails($lab_investigation_id, $req_array)
	{
		$flag = false;
		if($lab_investigation_id > 0)
		{		
			if(is_array($req_array['current_procedural_code_id_arr']))
			{
				$this->db->start_cache();			
				$this->db->where("LAB_INVESTIGATION_ID", $lab_investigation_id);												
				$this->db->delete("LAB_INVESTIGATION_DETAILS");
				$this->db->stop_cache();
				$this->db->flush_cache();	
				$data = array();
				foreach($req_array['current_procedural_code_id_arr'] as $key => $cpt_code_id)
				{
					$data[$key]['SERIAL_NO'] = $key;			
					$data[$key]['LAB_INVESTIGATION_ID'] = $lab_investigation_id; 	
					$data[$key]['CURRENT_PROCEDURAL_CODE_ID'] = $cpt_code_id;																											
					$data[$key]['DESCRIPTION'] = (isset($req_array["description_arr"][$key]) ? $req_array["description_arr"][$key] :'' );	
						
					$data[$key]['QUANTITY'] = (isset($req_array["quantity_arr"][$key]) ? $req_array["quantity_arr"][$key] :'' );		
					$data[$key]['RATE'] = (isset($req_array["rate_arr"][$key]) ? $req_array["rate_arr"][$key] :'' );		
					$data[$key]['TOOTH_NUMBER'] = (isset($req_array["tooth_number"][$key]) ? $req_array["tooth_number"][$key] :'' );		
					$data[$key]['TOOTH_INDEX'] = (isset($req_array["tooth_index"][$key]) ? $req_array["tooth_index"][$key] :'' );		
					$data[$key]['REMARKS'] = (isset($req_array["remarks_arr"][$key]) ? $req_array["remarks_arr"][$key] :'' );		
																	
				}
				$this->db->insert_batch('LAB_INVESTIGATION_DETAILS', $data);
				$flag = true;
			} 															
		}
		return $flag;						 
	}

	public function getDentalInvestigation($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"]!='' && $post_data["assessment_id"]!=''){ 				
			$this->db->start_cache();			
			$this->db->select("DM.*");
			
			if($post_data["lab_investigation_id"]!='')
			{
				$this->db->where("LAB_INVESTIGATION_ID", $post_data["lab_investigation_id"]);
			}			
			if($post_data["patient_id"]!='')
			{
				$this->db->where("PATIENT_ID", $post_data["patient_id"]);
			}
			if($post_data["consultation_id"]!='')
			{
				$this->db->where("CONSULTATION_ID", $post_data["consultation_id"]);
			}
			if($post_data["assessment_id"]!='')
			{
				$this->db->where("ASSESSMENT_ID", $post_data["assessment_id"]);
			}
			$this->db->where("IS_DENTAL", 1);
			$this->db->from("LAB_INVESTIGATION DM");			
							
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();	
				$data['LAB_INVESTIGATION_DETAILS'] = $this->getDentalInvestigationDetails($data['LAB_INVESTIGATION_ID'],'LAB_INVESTIGATION_DETAILS LID');										
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
					
		}	
		return $result;
		
	}
	
	public function getDentalInvestigationDetails($lab_investigation_id, $table)
	{
			$this->db->start_cache();			
			$this->db->select("LID.*");	
			$this->db->select("CPT.PROCEDURE_CODE as PROCEDURE_CODE, CPT.PROCEDURE_CODE_NAME as PROCEDURE_CODE_NAME,CPT.PROCEDURE_CODE_CATEGORY as PROCEDURE_CODE_CATEGORY");																			
			$this->db->from($table);
			$this->db->join("CURRENT_PROCEDURAL_TERMINOLOGY_MS CPT","CPT.CURRENT_PROCEDURAL_CODE_ID = LID.CURRENT_PROCEDURAL_CODE_ID","left");						
			$this->db->where("LAB_INVESTIGATION_ID", $lab_investigation_id);
			// $this->db->order_by("LID.SERIAL_NO","ASC");			
			$this->db->order_by("LID.LAB_INVESTIGATION_DETAILS_ID","ASC");			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;		
			$this->db->stop_cache();
			$this->db->flush_cache();
			$data = array();
			if($query->num_rows() > 0)
			{
				$data = $query->result_array();							
			}
			return $data;
	}
	
	
} 
?>
