<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PromotionModel extends CI_Model 
{
	function get_data($post_data = array())
	{
		
		$this->db->start_cache();
		
		 $data_id = $post_data["data_id"];
		$this->db->select("P.*,GROUP_CONCAT(PC.PBC_CATEGORY_ID) as PBC_CATEGORY_ID,GROUP_CONCAT(C.CUSTOMER_MOBILE_NO) as PROMOTIONS_PRIVATE_CUST");
		$this->db->where("PROMOTIONS_ID", $data_id);
		$this->db->from("PROMOTIONS P");
		$this->db->join("PROMOTION_BUSINESS_CATEGORY PC","PC.PBC_PROMO_ID = P.PROMOTIONS_ID","left");
		$this->db->join("CUSTOMER_PRIVATE_PROMOTION PP","PP.CPP_PROMOTION_ID = P.PROMOTIONS_ID","left");
		$this->db->join("CUSTOMERS C","C.CUSTOMER_ID = PP.CPP_CUSTOMER_ID","left");
		$this->db->group_by("P.PROMOTIONS_ID");
		$query = $this->db->get();
		
		$this->db->stop_cache();
		$this->db->flush_cache();
		if($query->num_rows() > 0)
			{

				$result 	= array('data'=>$query->row(),
									'status'=>'Success',
									);
				$Attach_names 				= getAttachments($data_id);
				$result["data"]->ATTACHMENTS = $Attach_names ;
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
	 	
 	public function getCompanyCategories($post_data = array())
 	{
 		$this->db->where("CBC_COMPANY_ID",$post_data["company_id"]);	
		$this->db->select("CBC.CBC_BUS_CAT_ID,BC.BUS_CAT_NAME");
		$this->db->from("COMPANY_BUSINESS_CATEGORY CBC");
		$this->db->join("BUSINESS_CATEGORY BC","BC.BUS_CAT_ID = CBC.CBC_BUS_CAT_ID","left");
		$query = $this->db->get();

		if ($query->num_rows() >=1)
		{	 		
 			$data = ($query->result_array() == NULL ? "": $query->result_array());
 		}
 		else
 		{
 			$data = "";
 		}
 		return $data;

 	}
 	public function deletePromotionFile($post_data = array())
 	{
 		if($post_data["file_id"] != "" && $post_data["file_id"] != NULL )
 		{
 			$this->db->where("PA_ID",$post_data["file_id"]);
			if ($this->db->delete("PROMOTIONS_ATTACHMENTS"))
			{	 		
	 			$result = array('data'=>"",'status'=>'Success',"message"=>"File deleted successfully");
	 		}
	 		else
	 		{
	 			$result = array('data'=>"",'status'=>'Failed',"message"=>"Failed to delete file");
	 		}
 		}
 		else
 		{
 			$result = array('data'=>"",'status'=>'Failed',"message"=>"Failed to delete file");
 		}
 		return $result;

 	}
	public function ListAllPromotions($post_data = array())
	{
		$user_id 		= $post_data['user_id'];
	 		$company_id 	= $post_data['company_id']; 
	 		$last_timestamp = $post_data['last_timestamp'];
			$data 			= array();	 	

			//SELECT COMPANIES PROMOTIONS LIST
			if(!is_null($last_timestamp) && !empty($last_timestamp))	
				$this->db->where("UPDATE_DATE >= ",$last_timestamp);

	 		$this->db->select("P.*,C.COMPANY_NAME");
			$this->db->from("PROMOTIONS P");
			$this->db->join("COMPANY C","P.PROMOTIONS_COMPANY_ID = C.COMPANY_ID","left");
			$this->db->order_by("PROMOTIONS_VALID_FROM","DESC");
			$this->db->order_by("PROMOTIONS_ID","DESC");
			$query_promo = $this->db->get();	
			//echo $this->db->last_query();	
			$this->load->model("ApiAdminModel");
			if ($query_promo->num_rows() > 0)
			{
				foreach ($query_promo->result() as $promo_row) 
					{
						$promotion_categories       = $this->ApiAdminModel->getPromotionCategory($promo_row->PROMOTIONS_ID);
						$status_counts 				= $this->ApiAdminModel->getPromotionStatusCount($promo_row->PROMOTIONS_ID);
						$promo_row->CP_LIKE			= ($status_counts->CP_LIKE== NULL? 0:$status_counts->CP_LIKE) ;
						$promo_row->CP_USED			= ($status_counts->CP_USED== NULL? 0:$status_counts->CP_USED) ;
						$promo_row->CP_INTERESTED 	= ($status_counts->CP_INTERESTED== NULL ? 0 :$status_counts->CP_INTERESTED);
						$promo_row->CP_CALL_BE_BACK = ($status_counts->CP_CALL_BE_BACK== NULL ? 0 :$status_counts->CP_CALL_BE_BACK);
						$promo_row->PROMO_CATEGORIES= $promotion_categories;
						$data[] = $promo_row;
					}


				$result 	= array('data'=>$data,
								'status'=>'Success',
								'attach_path'=>base_url().PROMOTION_MEDIA_PATH."/",
								'timestamp' => $this->ApiAdminModel->now()
								);
			}	
			else
			{
				$result = array('data'=>array(),'status'=>'Failed');
			}				
			return $result;	
	}
	public function CompanyCustomers($post_data)
 	{
 		$CC_ID 				= $post_data['cc_id'];
 		$COMPANY_ID 		= $post_data['company_id'];
 		$data 				= array();

 		if($CC_ID != NULL)
 			$this->db->where("CC_ID",$CC_ID);

		$this->db->select("*");
		$this->db->from("COMPANY_CUSTOMER");
		$this->db->where("CC_COMPANY_ID",$COMPANY_ID);
		$this->db->where("CC_CUSTOMER_ID >",0);


		$query = $this->db->get();
		//echo $this->db->last_query();	 
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$result 	= array('data'=> $data,
								'status'=>'Success',
								);
		}
		else
		{
			$result 	= array('data'=> $data,
								'message' =>'No data found',
								'status'=>'Failed',
								);
		}
		return $result;
 	}

} 
?>