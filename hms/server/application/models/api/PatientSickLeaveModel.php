<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PatientSickLeaveModel extends CI_Model 
{
	public function listPatientSickLeave($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());		
		$this->db->start_cache();			
		$this->db->select("*");
		$this->db->from("PATIENT_SICK_LEAVES DM");
		if($post_data["patient_id"] !='')
		{
			$this->db->where("DM.PATIENT_ID", $post_data["patient_id"]);			
		}
					
		$this->db->order_by("DM.PATIENT_SICK_LEAVES_ID","DESC");
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();											
			$result = array("status"=> "Success", "data"=> $data);
		}
		
		return $result;
	}
	
	public function savePatientSickLeave($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());	
		
		$data = array(
				"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
				"PATIENT_ID" => trim($post_data["patient_id"])		
		);
		$data_id = trim($post_data["patient_sick_leave_id"]);
			
		$ret = $this->ApiModel->mandatory_check( $post_data , array('patient_id'));		  
		if($ret!='')
		{		  	 		  		                         	 		  
        	return array("status" => "Failed", "data_id" => $data_id, "msg"=> $ret.' : Mandatory field missing');   
        }	
         
		if($this->utility->is_Duplicate("PATIENT_SICK_LEAVES",array_keys($data), array_values($data),"PATIENT_SICK_LEAVES_ID",$data_id))
		{								
			return array("status"=> "Failed", "data_id"=>$data_id, "msg"=> "Duplicate Data Found");
		}
			
		$data["USER_ID"] = trim($post_data["user_id"]);
		$data["ISSUED_DATE"] = format_date($post_data["issueddate"]);
		$data["SICK_REASON"] = trim($post_data["sickReason"]);
		$data["FROM_DATE"] = format_date($post_data["sickFromdate"]);
		$data["TO_DATE"] = format_date($post_data["sickTodate"]);
		$data["DURATION"] = trim($post_data["duration"]);
		$data["CREATED_DATE"] = toUtc(trim($post_data["date"]),$post_data["timeZone"]);

													
		if ($data_id > 0)
		{				
			$this->db->start_cache();
			$this->db->where("PATIENT_ID", $post_data["patient_id"]);	
			$this->db->where("PATIENT_SICK_LEAVES_ID",$data_id);
			$this->db->update("PATIENT_SICK_LEAVES",$data);
			$this->db->stop_cache();
			$this->db->flush_cache();	
			return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Sick leave details saved successfully..!")	;							
		}
		else
		{	
			$gen_no = $this->GenerateCertificateNo($post_data);	
			$data["CERTIFICATE_NUMBER"] = $gen_no["data"];		
			if($this->db->insert("PATIENT_SICK_LEAVES",$data))
			{
				$this->db->start_cache();			
				$data_id = $this->db->insert_id();				
				$this->db->stop_cache();
				$this->db->flush_cache();
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Sick leave details saved successfully..!")	;
			}
		}		
		
	
		return $result;
	}
	
	public function getPatientSickLeave($post_data)
	{
		$result = array("status"=> "Failed", "data"=> $post_data);
		$data = array();
		if($post_data["patient_id"] > 0 )
		{
			$this->db->start_cache();			
			$this->db->select("DM.*,CONCAT(OP.FIRST_NAME,' ',OP.MIDDLE_NAME,' ',OP.LAST_NAME) AS PATIENT_NAME,OP.GENDER");
			$this->db->from("PATIENT_SICK_LEAVES DM");
			if($post_data["patient_sick_leave_id"] > 0)
			{
				$this->db->where("DM.PATIENT_SICK_LEAVES_ID", $post_data["patient_sick_leave_id"]);			
			}						
			if($post_data["assessment_id"] > 0)
			{
				$this->db->where("DM.ASSESSMENT_ID", $post_data["assessment_id"]);			
			}
			if($post_data["patient_id"] > 0)
			{
				$this->db->where("DM.PATIENT_ID", $post_data["patient_id"]);			
			}
			$this->db->join("OP_REGISTRATION OP","OP.OP_REGISTRATION_ID = '".$post_data["patient_id"]."'","left");			
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$data = $query->row_array();
			}
			if(!empty($data))
				$result = array("status"=> "Success", "data"=> $data);
				
		}
		return $result;
	}
	function GenerateCertificateNo($post_data=array())
	{
		$this->db->start_cache();
		$this->db->select("max(RIGHT(CERTIFICATE_NUMBER,4)) as CERTIFICATE_NO");
		$this->db->from("PATIENT_SICK_LEAVES");
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
		{
			$CERTIFICATE_NO = (int) $query->row()->CERTIFICATE_NO + 1;
			$prefix = date('Y')."/";
			$result = array(
						'data'=> $prefix.sprintf('%04d', $CERTIFICATE_NO),
						'status'=>'Success',
					);

			return $result;				
		}
		else 
		{
			$result 	= array('data'=> array(),
								'status'=>'Failed',
								);
			return $result;
		}
	}
	public function downloaSickleavePdf($post_data)
	{
		if(file_exists(FCPATH . "public/uploads/sickleave.pdf"))
		{
			unlink(FCPATH . "public/uploads/sickleave.pdf");
		}
		$result = array("status"=> "Failed", "data"=> array());
		$data = array();
		if($post_data["patient_id"] != '')	
		{							
			$this->load->model('api/InstitutionManagementModel'); ''; 
			$post_data["start"] = isset($post["start"]) ? $post["start"] : 1;     
			$post_data["limit"] = isset($post["limit"]) ? $post["limit"] : 0;     
			$post_data["search_text"] = isset($post["search_text"]) ? $post["search_text"] : ''; 
			$institution      = $this->InstitutionManagementModel->listInstitution($post_data);
			$data =  array();
			$sickdata = $this->getPatientSickLeave($post_data);
			$data["sickdata"] = $sickdata;
			$data["institution"] = $institution;
			// print_r($data["sickdata"]);exit;
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->pdf;
			$html = $this->load->view('export/exportSickLeavepdf.php', $data, true);
			 // print_r($sickdata);
			$pdfFilePath = FCPATH . "public/uploads/sickleave.pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "F");
			
			 $result = array("status"=> "Success", "data"=> "public/uploads/sickleave.pdf",
			 	"filename" => $sickdata["data"]["CERTIFICATE_NUMBER"] );
		}		
		
		return $result;
		
	}

} 
?>