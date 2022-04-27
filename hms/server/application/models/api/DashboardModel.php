<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DashboardModel extends CI_Model 
{
	function ListDashboardCategoryList($post_data = array())
	{
		$company_id		= $post_data['company_id'];
		$login_type		= $post_data['login_type'];
	 		$data 			= array();
	 		
	 		if($login_type==WEB_ADMINISTRATOR)
	 		{
	 			
				$this->db->select("B.*,count(P.PBC_PROMO_ID) as TOTAL_PROMO");
				$this->db->from("BUSINESS_CATEGORY B");
				$this->db->join("PROMOTION_BUSINESS_CATEGORY P","P.PBC_CATEGORY_ID = B.BUS_CAT_ID","left");
				$this->db->group_by("B.BUS_CAT_ID");
				$query = $this->db->get();
	 		}
	 		else
	 		{
	 			$this->db->select("B.BUS_CAT_NAME,B.BUS_CAT_ID,B.BUS_CAT_ICON,count(P.PROMOTIONS_ID) as TOTAL_PROMO");
				$this->db->from("COMPANY_BUSINESS_CATEGORY CB");
				$this->db->join("BUSINESS_CATEGORY B","CB.CBC_BUS_CAT_ID = B.BUS_CAT_ID","left");
				$this->db->join("PROMOTION_BUSINESS_CATEGORY PB","PB.PBC_CATEGORY_ID = B.BUS_CAT_ID","left");
				$this->db->join("PROMOTIONS P","P.PROMOTIONS_ID = PB.PBC_PROMO_ID AND PROMOTIONS_COMPANY_ID = ".$company_id,"left");
				$this->db->where("CB.CBC_COMPANY_ID",$company_id);
				$this->db->group_by("B.BUS_CAT_ID");
				$query = $this->db->get();
	 		}
			//echo $this->db->last_query();

			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row) {
					$data[]  	= array("BUS_CAT_ID"		=> $row->BUS_CAT_ID,
										"BUS_CAT_NAME"		=> $row->BUS_CAT_NAME,
										"BUS_CAT_ICON"		=> $row->BUS_CAT_ICON,
										"TOTAL_PROMO"		=> $row->TOTAL_PROMO
										);
				}
				$result 	= array('data'=>$data,
									'status'=>'Success',
									'category_path'=>base_url().CATEGORY_LOGO_PATH."/"
									);
				return $result;
			}
			else
			{
				$result 	= array('data'=>$data,
									'status'=>'Failure',
									'category_path'=>base_url().CATEGORY_LOGO_PATH."/"
									);
				return $result;
			}
	}
	function ListDashboardCompanyList($post_data = array())
	{
		$company_id		= $post_data['company_id'];
		$login_type		= $post_data['login_type'];
	 		$data 			= array();
	 		
	 		if($login_type==WEB_ADMINISTRATOR)
	 		{
	 			
				$this->db->select("C.*,count(P.PROMOTIONS_ID) as TOTAL_PROMO");
				$this->db->from("COMPANY C");
				$this->db->join("PROMOTIONS P","P.PROMOTIONS_COMPANY_ID = C.COMPANY_ID","left");
				$this->db->group_by("C.COMPANY_ID");
				$query = $this->db->get();
	 		}
	 		else
	 		{
	 			$this->db->select("C.*,count(P.PROMOTIONS_ID) as TOTAL_PROMO");
				$this->db->from("COMPANY C");
				$this->db->join("PROMOTIONS P","P.PROMOTIONS_COMPANY_ID = C.COMPANY_ID","left");
				$this->db->group_by("C.CBC_COMPANY_ID");
				$this->db->where("C.COMPANY_ID",$company_id);
				$this->db->group_by("C.COMPANY_ID");
				$query = $this->db->get();
	 		}
			//echo $this->db->last_query();

			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row) {
					$data[]  	= array("COMPANY_ID"		=> $row->COMPANY_ID,
										"COMPANY_NAME"		=> $row->COMPANY_NAME,
										"COMPANY_LOGO_NAME"	=> $row->COMPANY_LOGO_NAME,
										"TOTAL_PROMO"		=> $row->TOTAL_PROMO
										);
				}
				$result 	= array('data'=>$data,
									'status'=>'Success',
									'category_path'=>base_url().COMPANY_LOGO_PATH."/"
									);
				return $result;
			}
			else
			{
				$result 	= array('data'=>$data,
									'status'=>'Failure',
									'category_path'=>base_url().COMPANY_LOGO_PATH."/"
									);
				return $result;
			}
	}
	function ListPostsByMonth($post_data = array())
	{
		$YEAR		= date('Y', strtotime($post_data['cur_date']));
		$company_id		= $post_data['company_id'];
		$login_type		= $post_data['login_type'];
		if($login_type==WEB_ADMINISTRATOR)
	 	{
			$sql = "SELECT 
			    count(P.PROMOTIONS_ID) as count,DATE_FORMAT(M.CREATE_DATE,'%b') AS month
				FROM (
			           SELECT '".$YEAR."-01-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-02-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-03-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-04-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-05-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-06-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-07-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-08-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-09-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-10-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-11-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-12-01' AS CREATE_DATE
			          ) AS M
			    LEFT JOIN 
			    PROMOTIONS P    ON 
			    MONTH(M.CREATE_DATE) = MONTH(P.CREATE_DATE)
			    AND YEAR(P.CREATE_DATE) = '".$YEAR."'
			    group by MONTH(M.CREATE_DATE)  ";
		}
		else
		{
			$sql = "SELECT 
			    count(P.PROMOTIONS_ID) as count,DATE_FORMAT(M.CREATE_DATE,'%b') AS month
				FROM (
			           SELECT '".$YEAR."-01-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-02-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-03-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-04-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-05-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-06-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-07-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-08-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-09-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-10-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-11-01' AS CREATE_DATE
			           UNION SELECT '".$YEAR."-12-01' AS CREATE_DATE
			          ) AS M
			    LEFT JOIN 
			    PROMOTIONS P    ON 
			    MONTH(M.CREATE_DATE) = MONTH(P.CREATE_DATE) AND P.PROMOTIONS_COMPANY_ID = ".$company_id."
			    AND YEAR(P.CREATE_DATE) = '".$YEAR."'
			    group by MONTH(M.CREATE_DATE)  ";
		}
		$c_query = $this->db->query($sql);
		$data 			= array();
		if ($c_query->num_rows() > 0)
		{
			$data["result"] = $c_query->result();
		}
		return $data;
	}
	function ListDashboardDataList($post_data = array())
	{
		$DATE		= $post_data['cur_date'];
	 	$data 			= array();
	 	$company_id		= $post_data['company_id'];
		$login_type		= $post_data['login_type'];
		if($login_type==WEB_ADMINISTRATOR)
	 	{
			$this->db->start_cache();
		 	$this->db->select("count(*) as total_companies");
		 	$this->db->from("COMPANY");
		 	$c_query = $this->db->get();
		 	if ($c_query->num_rows() > 0)
			{
				$data["TOTAL_COMPANIES"] = $c_query->row()->total_companies;
			}
			$this->db->stop_cache();
			$this->db->flush_cache();


			$this->db->start_cache();
			$this->db->select("count(*) as total_users");
		 	$this->db->from("CUSTOMERS");
		 	$c_query = $this->db->get();
		 	if ($c_query->num_rows() > 0)
			{
				$data["TOTAL_USERS"] = $c_query->row()->total_users;
			}
			$this->db->stop_cache();
			$this->db->flush_cache();


			$this->db->start_cache();
			$this->db->select("count(*) as total_promotion");
		 	$this->db->from("PROMOTIONS");
		 	$c_query = $this->db->get();
		 	if ($c_query->num_rows() > 0)
			{
				$data["TOTAL_PROMOTIONS"] = $c_query->row()->total_promotion;
			}
			$this->db->stop_cache();
			$this->db->flush_cache();


			$this->db->start_cache();
			$this->db->select("count(*) as total_promotion_active");
		 	$this->db->from("PROMOTIONS");
		 	$this->db->where("PROMOTIONS_VALID_TO >= ",$DATE);
		 	$c_query = $this->db->get();
		 	if ($c_query->num_rows() > 0)
			{
				$data["TOTAL_PROMOTIONS_ACTIVE"] = $c_query->row()->total_promotion_active;
			}
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		else
		{

			$data["TOTAL_COMPANIES"] = 0;
			
			$this->db->start_cache();
			$this->db->select("count(*) as total_users");
		 	$this->db->from("COMPANY_CUSTOMER");
		 	$this->db->where("CC_COMPANY_ID",$company_id);
		 	$this->db->where("CC_CUSTOMER_ID!=",NULL);
		 	$c_query = $this->db->get();
		 	if ($c_query->num_rows() > 0)
			{
				$data["TOTAL_USERS"] = $c_query->row()->total_users;
			}
			$this->db->stop_cache();
			$this->db->flush_cache();


			$this->db->start_cache();
			$this->db->select("count(*) as total_promotion");
		 	$this->db->from("PROMOTIONS");
		 	$this->db->where("PROMOTIONS_COMPANY_ID",$company_id);
		 	$c_query = $this->db->get();
		 	if ($c_query->num_rows() > 0)
			{
				$data["TOTAL_PROMOTIONS"] = $c_query->row()->total_promotion;
			}
			$this->db->stop_cache();
			$this->db->flush_cache();


			$this->db->start_cache();
			$this->db->select("count(*) as total_promotion_active");
		 	$this->db->from("PROMOTIONS");
		 	$this->db->where("PROMOTIONS_COMPANY_ID",$company_id);
		 	$this->db->where("PROMOTIONS_VALID_TO >= ",$DATE);
		 	$c_query = $this->db->get();
		 	if ($c_query->num_rows() > 0)
			{
				$data["TOTAL_PROMOTIONS_ACTIVE"] = $c_query->row()->total_promotion_active;
			}
			$this->db->stop_cache();
			$this->db->flush_cache();
		}

		return $data;
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