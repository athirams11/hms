<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CompanyModel extends CI_Model 
{
	function ListAllCompany($post_data = array())
	{
		
		
		if ($post_data["login_type"] == WEB_ADMINISTRATOR)
		{
			$this->db->start_cache();
			$this->db->select("C.*");
			$this->db->from("COMPANY C");
			$this->db->order_by("C.COMPANY_NAME","asc");
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
	public function CompanyInfo($post_data = array())
 	{
 		$this->db->where("COMPANY_ID",$post_data["company_id"]);	
		$this->db->select("INFO");
		$this->db->from("COMPANY");
		$query = $this->db->get();

		if ($query->num_rows() ==1)
		{	 		
 			$data = ($query->row()->INFO == NULL ? "": $query->row()->INFO);
 		}
 		else
 		{
 			$data = "";
 		}
 		return $data;

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
	public function createCompany($post_data = array())
	{
		$data=array(
		//"COMPANY_CODE" 	=> trim($post_data["COMPANY_CODE")),
		"COMPANY_NAME" 			=> trim($post_data["COMPANY_NAME"]),
		"COMPANY_ADRESS" 		=> trim($post_data["COMPANY_ADRESS"]),
		"COMPANY_DISTRICT_ID" 	=> trim($post_data["COMPANY_DISTRICT_ID"]),
		"COMPANY_STATE_ID" 		=> trim($post_data["COMPANY_STATE_ID"]),
		"COMPANY_COUNTRY_ID" 	=> trim($post_data["COMPANY_COUNTRY_ID"]),
		"COMPANY_PHONE_NO" 		=> trim($post_data["COMPANY_PHONE_NO"]),
		"COMPANY_EMAIL" 		=> trim($post_data["COMPANY_EMAIL"]),
		"COMPANY_WEBSITE" 		=> trim($post_data["COMPANY_WEBSITE"]),
		"INFO" 					=> trim($post_data["INFO"]),
		"COMPANY_ABOUT" 		=> trim($post_data["COMPANY_ABOUT"]),
		"COMPANY_ACCESS_TYPE" 	=> trim($post_data["COMPANY_ACCESS_TYPE"]),
		"COMPANY_POST_TYPE" 	=> trim($post_data["COMPANY_POST_TYPE"]),
		"COMPANY_ACTIVE" 		=> trim($post_data["COMPANY_ACTIVE"]),
		"COMPANY_SMS" 			=> trim($post_data["COMPANY_SMS"]),
		"USER_ID" 				=> $post_data['USER_ID'],
		"CLIENT_DATE" => date('Y-m-d H:i:s',strtotime($post_data["client_date"]))
				);
		$this->load->model("MasterModel");
		if($post_data["imagedataurl"] != "")
		{
			$image_name=$this->MasterModel->Writebase64($post_data["imagedataurl"],FCPATH.COMPANY_LOGO_PATH);
			if($image_name!="")
			{
				$data["COMPANY_LOGO_NAME"]=$image_name;			
			}	
		}
		$data_id = $post_data["COMPANY_ID"];
		//$fields = array("0"=>"COMPANY_NAME","1"=>"COMPANY_CODE");
		//$values = array("0"=>,"1"=>$post_data['COMPANY_CODE'));
		if($this->utility->is_Duplicate("COMPANY","COMPANY_CODE",$post_data['COMPANY_CODE'],"COMPANY_ID",$data_id))
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
		
		
		if ($data_id> 0)
		{
			$this->db->where("COMPANY_ID",$data_id);
			$this->db->update("COMPANY",$data);
			$this->add_company_category($data_id,$post_data["CBC_BUS_CAT_ID"]);
		}
		else
		{
			$this->load->model("MasterModel");
			$data["COMPANY_CODE"]=$this->MasterModel->generateCompanyCode();
			$this->db->insert("COMPANY",$data);
			$data_id = $this->db->insert_id();
			$this->add_company_category($data_id,$post_data["CBC_BUS_CAT_ID"]);
			//return $this->db->last_query();	
		}
		return 1;
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