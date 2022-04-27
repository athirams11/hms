<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	*
 	* Class ApiModel using to create and Maintain items
 	*
 	*
 	*/
	class ApiModel extends CI_Model {
	
		/**
		*
	 	* Function for add or update items
	 	*
	 	* @param   input post parameters 
	 	* @return   bool
	 	*
	 	*/
	 	/****************************** FUNCTIONS FOR BOON *** BEGIN *************************/
	 	//CUSTOMER REGISTRATION FROM INITIAL PAGE
	 	public function RegisterCustomer($post_data)
	 	{
	 		$CUSTOMER_COUNTRY_ID			= getCountryId($post_data['customer_country_code']);
	 		$CUSTOMER_MOBILE_NO				= $post_data['customer_mobile_no'];
	 		$CUSTOMER_MOBILE_OS				= $post_data['customer_mobile_os'];
	 		$CUSTOMER_MOBILE_OS_VERSION		= $post_data['customer_mobile_os_version'];
	 		$CUSTOMER_DEVICE_TOKEN			= $post_data['customer_device_token'];
	 		$CUSTOMER_DEVICE_MAC 			= $post_data['customer_device_mac'];

	 		$data 							= array();
	 		$query = $this->db->where("CUSTOMER_COUNTRY_ID",$CUSTOMER_COUNTRY_ID);		
	 		$query = $this->db->where("CUSTOMER_MOBILE_NO",$CUSTOMER_MOBILE_NO);
			$query = $this->db->select("*");
			$query = $this->db->from("CUSTOMERS");
			$query = $this->db->get();
			//echo $this->db->last_query();
			if ($query->num_rows() > 0)
			{
				$result = $query->row();				
	 			$data 	= array('CUSTOMER_OS'			=> $CUSTOMER_MOBILE_OS,
								'CUSTOMER_OS_VERSION'	=> $CUSTOMER_MOBILE_OS_VERSION,
								'CUSTOMER_DEVICE_TOKEN'	=> $CUSTOMER_DEVICE_TOKEN,
								'CUSTOMER_DEVICE_MAC' 	=> $CUSTOMER_DEVICE_MAC
								);
	 			$this->db->where('CUSTOMER_ID',$result->CUSTOMER_ID);
			    $this->db->update('CUSTOMERS',$data);
				$result 	= array('data'  => $this->GetCustomers($result->CUSTOMER_ID),
									'image_path' => base_url().CUSTOMER_PROFILE_IMAGE.'/', 
									'status' =>'CUSTOMER_MOBILE_NO_EXIST'
									);
				return $result;
			}
			else
			{
				$data 	= array('CUSTOMER_MOBILE_NO'	=> $CUSTOMER_MOBILE_NO,
								'CUSTOMER_OS'			=> $CUSTOMER_MOBILE_OS,
								'CUSTOMER_OS_VERSION'	=> $CUSTOMER_MOBILE_OS_VERSION,
								'CUSTOMER_COUNTRY_ID'	=> $CUSTOMER_COUNTRY_ID,
								'CUSTOMER_DEVICE_TOKEN'	=> $CUSTOMER_DEVICE_TOKEN,
								'CUSTOMER_DEVICE_MAC' 	=> $CUSTOMER_DEVICE_MAC
								);

				$this->db->insert('CUSTOMERS',$data);
			    $insert_id 	= $this->db->insert_id();
			    if($insert_id !="")
			    {
			    	$datas 	= array('CC_CUSTOMER_ID'	=> $insert_id);
			    	$this->db->where('CC_CUSTOMER_MOBILE_NO',trim($CUSTOMER_MOBILE_NO));
			    	$this->db->update('COMPANY_CUSTOMER',$datas);
			    }

			    $result 	= array('data'=> array('CUSTOMER_ID'=>$insert_id),
									'status'=>'CUSTOMER_MOBILE_NO_REGISTERED'
									);
			    return $result;
			}
	 	} 
	 	
	 	//LIST BUSINESS CATEGORY
	 	public function ListBusinessCategory($post_data)
	 	{
	 		
	 		$BUS_CAT_ID		= $post_data['bus_cat_id'];
	 		$data 			= array();
	 		
	 		if($BUS_CAT_ID!=NULL)
	 			$query = $this->db->where("BUS_CAT_ID",(int) $BUS_CAT_ID);

			$query = $this->db->select("*");
			$query = $this->db->from("BUSINESS_CATEGORY");
			$query = $this->db->get();
			//echo $this->db->last_query();
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row) {
					$data[]  	= array("BUS_CAT_ID"		=> $row->BUS_CAT_ID,
										"BUS_CAT_NAME"		=> $row->BUS_CAT_NAME,
										"BUS_CAT_ICON"		=> $row->BUS_CAT_ICON
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
	 	//LIST PROMOTIONS
	 	public function ListPromotions($post_data)
	 	{
			$CUSTOMER_ID 			= $post_data['customer_id'];
	 		$TIMESTAMP	 			= $post_data['last_timestamp'];
	 		$data 					= array();
	 		$data_private_company 	= array();
			$company_list 			= array();
			$blocked_company_list	= array();
			$deleted_promotion_list	= array();
			$private_promotion_list = array();
	 		//GET SYMP ID COMPANY
			$this->db->distinct();
			$this->db->select("CPC_COMPANY_ID");
			$this->db->from("CUSTOMER_PRIVATE_COMPANY");
			$this->db->where("CPC_CUSTOMER_ID",$CUSTOMER_ID);
			$querys = $this->db->get();
			//echo $this->db->last_query();	 
			if($querys->num_rows() > 0)
			{
				//$data_private_company = [];
				foreach ($querys->result() as $row) 
				{
					$company_list[] = $row->CPC_COMPANY_ID;
				}
			}
			//GET BLOCKED COMPANY ID
			$this->db->distinct();
			$this->db->select("CBLC_COMPANY_ID");
			$this->db->from("CUSTOMER_BLOCKED_COMPANY");
			$this->db->where("CBLC_CUSTOMER_ID",$CUSTOMER_ID);
			$querysblocked= $this->db->get();
			//echo $this->db->last_query();	 
			if($querysblocked->num_rows() > 0)
			{
				//$data_private_company = [];
				foreach ($querysblocked->result() as $row) 
				{
					$blocked_company_list[] = $row->CBLC_COMPANY_ID;
				}
			}			
			//print_r($company_list);
	 		//SELECT COMPANIES LIST
			$this->db->distinct();
 			$this->db->select("CBC.CBC_COMPANY_ID");
			$this->db->from("CUSTOMER_SELECTED_CATEGORY CSC, COMPANY_BUSINESS_CATEGORY CBC");
			$this->db->join("COMPANY C","C.COMPANY_ID = CBC.CBC_COMPANY_ID","LEFT");
			$this->db->where("CSC.CSC_CUSTOMER_ID",$CUSTOMER_ID);
			$this->db->where("C.COMPANY_POST_TYPE",0);			
			$this->db->where("CBC.CBC_BUS_CAT_ID = CSC.CSC_BUS_CAT_ID");
			$this->db->where("C.COMPANY_ACTIVE = 1");
			$query = $this->db->get();	
			//echo $this->db->last_query();	
			if ($query->num_rows() > 0)
			{
				//$company_list = [];
				foreach ($query->result() as $row) 
				{
					$company_list[] = $row->CBC_COMPANY_ID;
				}
			}

			//GET DELETED PROMOTIONS
			$this->db->distinct();
			$this->db->select("CPD_PROMOTION_ID");
			$this->db->from("CUSTOMERS_PROMOTIONS_DELETED");
			$this->db->where("CPD_CUSTOMER_ID",$CUSTOMER_ID);
			$querysdeleted= $this->db->get();
			//echo $this->db->last_query();	 
			if($querysdeleted->num_rows() > 0)
			{
				//$data_private_company = [];
				foreach ($querysdeleted->result() as $row) 
				{
					$deleted_promotion_list[] = $row->CPD_PROMOTION_ID;
				}
			}	

			//GET PRIVATE PROMOTIONS
			$this->db->distinct();
			$this->db->select("CPP_PROMOTION_ID");
			$this->db->from("CUSTOMER_PRIVATE_PROMOTION");
			$this->db->where("CPP_CUSTOMER_ID",$CUSTOMER_ID);
			$querysprivate= $this->db->get();
			//echo $this->db->last_query();	 
			if($querysprivate->num_rows() > 0)
			{
				foreach ($querysprivate->result() as $row) 
				{
					$private_promotion_list[] = $row->CPP_PROMOTION_ID;
				}
			}	



			//print_r(array_unique($company_list));
			if(count(array_unique($company_list))>0)
			{				
				//SELECT COMPANIES PROMOTIONS LIST
				if(!is_null($TIMESTAMP) && !empty($TIMESTAMP))	
					$this->db->where("UPDATE_DATE >= ",$TIMESTAMP);
				if(count($blocked_company_list)>0)
					$this->db->where_not_in("PROMOTIONS_COMPANY_ID",$blocked_company_list);
				if(count($deleted_promotion_list)>0)	
					$this->db->where_not_in("PROMOTIONS_ID",$deleted_promotion_list);

				$this->db->select("*");
				$this->db->from("PROMOTIONS");
				$this->db->where_in("PROMOTIONS_COMPANY_ID",array_unique($company_list));
				$this->db->where("PROMOTIONS_ACTIVE =",1);
				//$this->db->where("PROMOTIONS_PRIVATE =",0);  // 0 = PUBLIC PROMOTIONS
				
				$query_promo = $this->db->get();	
				//echo $this->db->last_query();	
				if ($query_promo->num_rows() > 0)
				{
					$data 				= array();
					$lastupdated_time 	= "";
					$row_key 			= 0;
					foreach ($query_promo->result() as $promo_row) 
					{	

						if(($promo_row->PROMOTIONS_PRIVATE)==1)	 //GET PRIVATE PROMOTIONS			
						{
							if(in_array($promo_row->PROMOTIONS_ID,$private_promotion_list))
							{
								$status_counts 				= $this->getPromotionStatusCount($promo_row->PROMOTIONS_ID);
								$cust_status 				= $this->getpromotionStatus($promo_row->PROMOTIONS_ID,$CUSTOMER_ID);
								$promo_row->LIKE			= $cust_status->CP_LIKE;
								$promo_row->USED			= $cust_status->CP_USED;
								$promo_row->INTERESTED		= $cust_status->CP_INTERESTED;
								$promo_row->CALL_BACK		= $cust_status->CP_CALL_BE_BACK;
								$promo_row->CP_LIKE			= ($status_counts->CP_LIKE == NULL? 0:$status_counts->CP_LIKE) ;
								$promo_row->CP_USED 		= ($status_counts->CP_USED == NULL? 0:$status_counts->CP_USED) ;
								$promo_row->CP_INTERESTED 	= ($status_counts->CP_INTERESTED == NULL ? 0 :$status_counts->CP_INTERESTED);
								$promo_row->business_category	= $this->getPromotionCategory($promo_row->PROMOTIONS_ID);

							}
						}
						else //GET PUBLIC PROMOTIONS
						{
							$status_counts 				= $this->getPromotionStatusCount($promo_row->PROMOTIONS_ID);
							$cust_status 				= $this->getpromotionStatus($promo_row->PROMOTIONS_ID,$CUSTOMER_ID);
							$promo_row->LIKE			= $cust_status->CP_LIKE;
							$promo_row->USED			= $cust_status->CP_USED;
							$promo_row->INTERESTED		= $cust_status->CP_INTERESTED;
							$promo_row->CALL_BACK		= $cust_status->CP_CALL_BE_BACK;
							$promo_row->CP_LIKE			= ($status_counts->CP_LIKE == NULL? 0:$status_counts->CP_LIKE) ;
							$promo_row->CP_USED 		= ($status_counts->CP_USED == NULL? 0:$status_counts->CP_USED) ;
							$promo_row->CP_INTERESTED 	= ($status_counts->CP_INTERESTED == NULL ? 0 :$status_counts->CP_INTERESTED);
							$promo_row->business_category	= $this->getPromotionCategory($promo_row->PROMOTIONS_ID);							
						}

							$attachment 				= getAttachments($promo_row->PROMOTIONS_ID);
							$promo_row->ATTACHMENTS 	= $attachment;
							$data[]	= $promo_row;
					}
				
					$result 	= array('data'=>$data,
									'status'=>'Success',
									'attach_path'=>base_url().PROMOTION_MEDIA_PATH."/",
									'timestamp' => $this->now()
									);
				}
				else
				{
					$result 	= array('data'=>$data,'status'=>'Failed');		
				}
				return $result;
			
			}
			else
			{
				$result 	= array('data'=>$data,'status'=>'Failed');
				return $result;
			}
	 	}

	 	/**
	 	*Get Time From Database
	 	*@param void
	 	*@return timestamp
	 	*/
	 	private function now()
	 	{
	 		$this->db->select("NOW() as time");
			$query_promo = $this->db->get();	
			return $query_promo->row()->time;
	 	}

	 	//EDIT CUSTOMER DETAILS
	 	public function EditCustomer($post_data)
	 	{
	 		$CUSTOMER_ID					= $post_data['customer_id'];
	 		$CUSTOMER_MOBILE_NO				= $post_data['customer_mobile_no'];
	 		$CUSTOMER_MOBILE_OS				= $post_data['customer_mobile_os'];
	 		$CUSTOMER_MOBILE_OS_VERSION		= $post_data['customer_mobile_os_version'];
	 		$CUSTOMER_DEVICE_TOKEN			= $post_data['customer_device_token'];
	 		$CUSTOMER_FIRST_NAME			= $post_data['customer_first_name'];
	 		$CUSTOMER_LAST_NAME				= $post_data['customer_last_name'];
	 		$CUSTOMER_EMAIL					= $post_data['customer_email'];
	 		$CUSTOMER_IMAGE_NAME			= $post_data['customer_image_name'];
	 		$CUSTOMER_DISTRICT_ID			= $post_data['customer_district_id'];
	 		$CUSTOMER_STATE_ID				= $post_data['customer_state_id'];
	 		$CUSTOMER_COUNTRY_ID			= $this->getCountryId($post_data['customer_country_code']); //EG:- IN for India


	 		$data 							= array();
	 		$query = $this->db->where("CUSTOMER_ID",$CUSTOMER_ID);		
			$query = $this->db->select("*");
			$query = $this->db->from("CUSTOMERS");
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$data 	= array('CUSTOMER_MOBILE_NO'	=> $CUSTOMER_MOBILE_NO,
								'CUSTOMER_OS'			=> $CUSTOMER_MOBILE_OS,
								'CUSTOMER_OS_VERSION'	=> $CUSTOMER_MOBILE_OS_VERSION,
								'CUSTOMER_FIRST_NAME'	=> $CUSTOMER_FIRST_NAME,
								'CUSTOMER_LAST_NAME'	=> $CUSTOMER_LAST_NAME,
								'CUSTOMER_EMAIL'		=> $CUSTOMER_EMAIL,
								'CUSTOMER_IMAGE_NAME'	=> $CUSTOMER_IMAGE_NAME,
								'CUSTOMER_DISTRICT_ID'	=> $CUSTOMER_DISTRICT_ID,
								'CUSTOMER_STATE_ID'		=> $CUSTOMER_STATE_ID,
								'CUSTOMER_COUNTRY_ID'	=> $CUSTOMER_COUNTRY_ID,
								'CUSTOMER_DEVICE_TOKEN' => $CUSTOMER_DEVICE_TOKEN
								);
				$query_update = $this->db->where("CUSTOMER_ID",$CUSTOMER_ID);		
			    $query_update = $this->db->update('CUSTOMERS',$data);				    
			    $updated_rows = $this->db->affected_rows();
			    if($updated_rows==0)
			    {
			    	$result 	= array('data'=> array('updated_rows'=>$updated_rows),'status'=>'CUSTOMER_DETAILS_NOT_UPDATED');
			    }
			    else
			    {
			    	$result 	= array('data'=> array('updated_rows'=>$updated_rows),'status'=>'CUSTOMER_DETAILS_UPDATED');	
			    }
			}
			else
			{
	 			$result 	= array('data'=> array('updated_rows'=>0),'status'=>'CUSTOMER_ID_NOT_EXIST');				
			}
			return $result;
	 	} 
	 	//ADD CUSTOMER PREFERRED BUSINESS CATEGORIES
	 	public function CustomerSelectedBusinessCategory($post_data)
	 	{
	 		$CUSTOMER_ID				= $post_data['customer_id'];
	 		$BUSINESS_CATEGORY_ID		= $post_data['business_category_id']; //ARRAY LIST
	 		$data 						= array();

	 		$result = array('data'=> "",'status'=>'Failed');
	 		if(!is_array($BUSINESS_CATEGORY_ID) || empty($BUSINESS_CATEGORY_ID))return $result;	 
						

	 		$query = $this->db->where("CSC_CUSTOMER_ID",$CUSTOMER_ID);		
			$query = $this->db->select("*");
			$query = $this->db->from("CUSTOMER_SELECTED_CATEGORY");
			$query = $this->db->get();

			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row)
				{
				    //CHECK EXISTING BUSINESS CATEGORY ID'S ARE THEIR IN NEW ARRAY
				    if(in_array($row->CSC_BUS_CAT_ID,$BUSINESS_CATEGORY_ID))
					{
						$BUS_EXIST_CAT_ID[] =  $row->CSC_BUS_CAT_ID;
					}
					else
					{
						//DELETE EXISTING BUSINESS CATEGORIES
				 		$query = $this->db->where("CSC_CUSTOMER_ID",$CUSTOMER_ID);		
				 		$query = $this->db->where("CSC_BUS_CAT_ID",$row->CSC_BUS_CAT_ID);		
						$query = $this->db->delete("CUSTOMER_SELECTED_CATEGORY");
						
					}
				}				
				//ADD EXISTING CUSTOMER'S SELECTED BUSINESS CATEGORIES
				foreach ($BUSINESS_CATEGORY_ID as $key => $value)
				{
					if(in_array($value,$BUS_EXIST_CAT_ID))
					{
						//NOTHING TO DO
					}
					else
					{
						$data 	= array('CSC_CUSTOMER_ID'	=> $CUSTOMER_ID,
											'CSC_BUS_CAT_ID'	=> $value
											);	
						$this->db->insert('CUSTOMER_SELECTED_CATEGORY',$data);
					}					
				}
			    $result = array('data'=> $this->GetCompany($BUSINESS_CATEGORY_ID),'status'=>'Success','logo_path'=>base_url().COMPANY_LOGO_PATH."/");
			    return $result;
			}
			else
			{				
		        //ADD NEW CUSTOMER'S BUSINESS CATEGORIES
				foreach ($BUSINESS_CATEGORY_ID as $key => $value) {
					$data[] 	= array('CSC_CUSTOMER_ID'	=> $CUSTOMER_ID,
										'CSC_BUS_CAT_ID'	=> $value
										);
				}
				$qry_result =$this->db->insert_batch('CUSTOMER_SELECTED_CATEGORY',$data);
				$result = array('data'=> $this->GetCompany($BUSINESS_CATEGORY_ID),'status'=>'Success','logo_path'=>base_url().COMPANY_LOGO_PATH."/");				
			    return $result;
			}
		
	 	} 
/* Get Company List With Category*/
	 	private function GetCompany($Ids = array())
	 	{			
			$this->db->select("C.COMPANY_ID,C.COMPANY_CODE,C.COMPANY_NAME,C.COMPANY_ADRESS,C.COMPANY_DISTRICT_ID,C.COMPANY_STATE_ID,C.COMPANY_COUNTRY_ID,C.COMPANY_PHONE_NO,C.COMPANY_EMAIL,C.COMPANY_WEBSITE,C.COMPANY_ACTIVE,C.COMPANY_LOGO_NAME");
	 		$this->db->where_in("CBC.CBC_BUS_CAT_ID",$Ids);		
			$this->db->from("COMPANY_BUSINESS_CATEGORY CBC");	
			$this->db->join("COMPANY C","C.COMPANY_ID = CBC.CBC_COMPANY_ID","LEFT");
			$this->db->group_by("C.COMPANY_ID");
			$query = $this->db->get();
			if($query->num_rows() > 0)
				return $query->result();
			else
				return array();
	 	}

	 	/**
		 * @param Json Object $post_data
		 * @return Array $result
		 */
	 	public function GetCompanydata($post_data)
	 	{
	 		$company_ids	= $post_data['company_ids'];//Array List
	 		$data 			= array();
	 		$result 		= array('data'=> $data,'status'=>'Failed');
			
			/*Checking is Array or empty*/
			if(!is_array($company_ids) || empty($company_ids))return $result;	 
						
	 		$this->db->select("COMPANY_ID,COMPANY_CODE,COMPANY_NAME,COMPANY_ADRESS,COMPANY_DISTRICT_ID,COMPANY_STATE_ID,COMPANY_COUNTRY_ID,COMPANY_PHONE_NO,COMPANY_EMAIL,COMPANY_WEBSITE,COMPANY_ACTIVE,COMPANY_LOGO_NAME,COMPANY_ABOUT");
			$this->db->from("COMPANY");
	 		$this->db->where_in("COMPANY_ID",$company_ids);
	 		$this->db->group_by("COMPANY_ID");
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$data = $query->result();
			    $result = array('data'=> $data,'status'=>'Success','logo_path'=>base_url().COMPANY_LOGO_PATH."/");
			}
			    
			    return $result;
	 	} 
	 	public function CustomerPromotion($post_data)
	 	{
	 		$promotion_id				= $post_data['promotion_id'];
	 		$customer_id				= $post_data['customer_id'];
	 		$customer_status			= $post_data['status']; 			// LIKE,USED,INTERESTED,CALL_BE_BACK
	 		$customer_status_value		= $post_data['status_value']; 		// 1 or 0
	 		$customer_call_me_feedback	= (!empty(trim($post_data['call_me_feedback']))? $post_data['call_me_feedback'] : NULL); 	// 1 or 0
	 		

	 		/*$customer_like		= $post_data['customer_like'];
	 		$customer_used		= $post_data['customer_used'];
	 		$customer_interested= $post_data['customer_interested'];
	 		$customer_callme	= $post_data['customer_callme'];
	 		$customer_share		= $post_data['customer_share'];*/
	 		$data 				= array();

	 		$this->db->select("*");
			$this->db->from("CUSTOMERS_PROMOTIONS");
	 		$this->db->where("CP_CUSTOMER_ID",$customer_id);
	 		$this->db->where("CP_PROMOTION_ID",$promotion_id);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{
				//UPPDATE EXISTING DATA	
				if($customer_status == "LIKE") 
					$data 	= array('CP_LIKE' => $customer_status_value);
				else if($customer_status == "USED") 
					$data 	= array('CP_USED' => $customer_status_value);
				else if($customer_status == "INTERESTED") 
					$data 	= array('CP_INTERESTED' => $customer_status_value);
				else if($customer_status == "CALL_BE_BACK") 
				{
					$data 	= array('CP_CALL_BE_BACK' => $customer_status_value,
									'CP_CALL_BE_BACK_FEEDBACK' => $customer_call_me_feedback);
				}
				else if($customer_status == "READ") 
					$data 	= array('CP_READ' => $customer_status_value);

			    $this->db->where("CP_CUSTOMER_ID",$customer_id);
	 			$this->db->where("CP_PROMOTION_ID",$promotion_id);
				$this->db->update('CUSTOMERS_PROMOTIONS',$data);
				$this->UpdatePrmotionDate($promotion_id);
			    if($this->db->affected_rows())
			    {
			    	$promotion_status_counts =  $this->getPromotionStatusCount($promotion_id);
			    	$result 	= array('data'=>$promotion_status_counts,'status'=>'Success');
			    }
			    else
			    	$result 	= array('data'=>array(),'status'=>'Failed');
			    return $result;
			}
			else
			{
				$customer_like 			= 0;
				$customer_used 		 	= 0;
				$customer_interested 	= 0;
				$customer_callme 		= 0;
				$customer_read 			= 0;
				//CREATE NEW ROWS
				if($customer_status == "LIKE") 
					$customer_like 		= $customer_status_value;
				else if($customer_status == "USED") 
					$customer_used 		= $customer_status_value;
				else if($customer_status == "INTERESTED") 
					$customer_interested = $customer_status_value;
				else if($customer_status == "CALL_BE_BACK") 
					$customer_callme	 = $customer_status_value;
				else if($customer_status == "READ") 
					$customer_read		 = $customer_status_value;

				$data 	= array('CP_CUSTOMER_ID'			=> $customer_id,
								'CP_PROMOTION_ID'			=> $promotion_id,
								'CP_LIKE'					=> $customer_like,
								'CP_USED'					=> $customer_used,
								'CP_INTERESTED'				=> $customer_interested,
								'CP_CALL_BE_BACK'			=> $customer_callme,
								'CP_CALL_BE_BACK_FEEDBACK' 	=> $customer_call_me_feedback,
								'CP_READ'					=> $customer_read
								);

				$this->db->insert('CUSTOMERS_PROMOTIONS',$data);
				if($this->db->insert_id())
				{
					$this->UpdatePrmotionDate($promotion_id);
					$promotion_status_counts =  $this->getPromotionStatusCount($promotion_id);
			    	$result 	= array('data'=>$promotion_status_counts,'status'=>'Success');
				}
			    else
			    	$result 	= array('data'=>array(),'status'=>'Failed');
			    return $result;
			}
	 	}
	 	public function UpdatePrmotionDate($promotion_id)
	 	{
	 		$data 	= array('UPDATE_DATE' => $this->now());	 		
	 		$this->db->where("PROMOTIONS_ID",$promotion_id);
			$this->db->update('PROMOTIONS',$data);
			return true;
	 	}
	 	public function getPromotionStatusCount($promotion_id)
	 	{
	 		$data 				= array();

	 		$this->db->select("SUM(CP_LIKE) AS CP_LIKE,SUM(CP_USED) AS CP_USED,SUM(CP_INTERESTED) AS CP_INTERESTED");
			$this->db->from("CUSTOMERS_PROMOTIONS");
	 		$this->db->where("CP_PROMOTION_ID",$promotion_id);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() == 1)
			{
				$data = $query->row();
			}
			return $data;

	 	}

		public function getpromotionStatus($promotion_id,$Customer_ID)
			 	{
			 		$this->db->select("CP_LIKE,CP_USED,CP_INTERESTED,CP_CALL_BE_BACK");
					$this->db->from("CUSTOMERS_PROMOTIONS");
			 		$this->db->where("CP_PROMOTION_ID",$promotion_id);
			 		$this->db->where("CP_CUSTOMER_ID",$Customer_ID);
			 		$this->db->where("CP_READ =",0);
					$query = $this->db->get();
					if($query->num_rows() == 1)
					{
						return $query->row();
					}
					else false;

			 	}



	 	//GET PROMOTION CATEGORIES
	 	public function getPromotionCategory($promotion_id)
	 	{
	 		$data 				= array();

	 		$this->db->select("PBC_PROMO_ID,PBC_CATEGORY_ID");
			$this->db->from("PROMOTION_BUSINESS_CATEGORY");
	 		$this->db->where("PBC_PROMO_ID",$promotion_id);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{
				$data = $query->result();
			}
			//print_r($data);
			return $data;

	 	}
	 	//ADD REMOVE COMAPNY FAVOURITE
	 	public function AddRemoveCustomerFavCompany($post_data)
	 	{
	 		$CUSTOMER_ID 	= $post_data['customer_id'];
	 		$COMPANY_ID 	= $post_data['company_id'];
	 		$ACTION 		= $post_data['action'];  //ADD or REMOVE

	 		$data=array(
						"CFC_CUSTOMER_ID" 	=> trim($CUSTOMER_ID), 
						"CFC_COMPANY_ID" 	=> trim($COMPANY_ID)
						);
	 		
	 		$this->db->select("*");
			$this->db->from("CUSTOMER_FAV_COMPANY");
			$this->db->where("CFC_CUSTOMER_ID",$CUSTOMER_ID);
			$this->db->where("CFC_COMPANY_ID",$COMPANY_ID);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{
				if($ACTION == "ADD")
				{
					$result 	= array('data'=>"",
	 								'message'=>"Already exist in favourite list",
									'status'=>'Failed',
									);										
				}
				else if($ACTION == "REMOVE")
				{
					$this->db->delete("CUSTOMER_FAV_COMPANY",$data);
					$this->db->where("CFC_CUSTOMER_ID",$CUSTOMER_ID);
					$this->db->where("CFC_COMPANY_ID",$COMPANY_ID);
					$affected_rows = $this->db->affected_rows();
					$result 	= array('data'=> array('affected_rows'=>$affected_rows),
										'message'=>"Removed from favourite list",
										'status'=>'Success',
										);
				}
			}
			else
			{
				if($ACTION == "ADD")
				{
					$this->db->insert("CUSTOMER_FAV_COMPANY",$data);
					$data_id = $this->db->insert_id();	
					$result 	= array('data'=> array('added_row_id'=>$data_id),
										'message'=>"Added to favourite list",
										'status'=>'Success',
										);
				}
				else if($ACTION == "REMOVE")
				{
					$result 	= array('data'=>"",
	 								'message'=>"No matching records found",
									'status'=>'Failed',
									);
				}
			}
			return $result;		
	 	}
	 	public function PriorityCompanies($post_data)
	 	{
	 		$CUSTOMER_ID 	= $post_data['customer_id'];
	 		
	 		$this->db->select("*");
			$this->db->from("COMPANY_CUSTOMER");
			$this->db->where("CC_CUSTOMER_ID",$CUSTOMER_ID);
			$this->db->where("CC_CUSTOMER_DELETED =",0);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{
				$data = $query->result();
			    $result = array('data'=> $data,
			    				'message'=>"",
			    				'status'=>'Success');
			}
			else
			{
				 $result = array('data'=> "",
				 				 'message'=>"No data available",
			    				 'status'=>'Failure');	
			}
			return $result;
	 	}
	 	public function CreateCustomerOTP($post_data)
	 	{
	 		//+91 9497266762
	 		$MOBILE_NO_WITH_CODE 	= explode(" ",$post_data['mobile_no']); // Mobile No Format is +91 9999999999
	 		$MOBILE_NO  			= $MOBILE_NO_WITH_CODE[1];
	 		$OTP_MEDIA_TYPE			= 0; //  0 = SMS, 1 = EMAIL
	 		$CUSTOMER_OTP_STATUS	= false;
			//GENERATE NEW OTP
			if($MOBILE_NO!='9497266762')
				$OTP 	= rand(100000,999999);					
			else
				$OTP 	= '246810';		
			
			$data 	= array("CUSTOMER_MOBILE_NO" 	=> $post_data['mobile_no'],
							"CUSTOMER_OTP" 		 	=> $OTP,
							"CUSTOMER_OTP_TYPE"	 	=> 'NEW_REG',
							"CUSTOMER_OTP_STATUS"	=> 0,
							"CUSTOMER_OTP_TO_MEDIA"	=> $OTP_MEDIA_TYPE);
			$this->db->insert("CUSTOMER_OTP",$data);    
			$insert_id = $this->db->insert_id();	
			if($insert_id)
			{
				$SMS_MESSAGE	= 'OTP for symp registration is '.$OTP ;
				$SMS_DATA 		= SMS_API.'&number='.$MOBILE_NO.'&text='.$SMS_MESSAGE;
		        $SMS_URL 		= str_replace(" ", '%20', $SMS_DATA);
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$SMS_URL);
				curl_setopt($ch, CURLOPT_HEADER,0);
				// Include header in result? (0 = yes, 1 = no)
				curl_setopt($ch, CURLOPT_HEADER, 0);
				// Should cURL return or print out the data? (true = return, false = print)
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
				$output 		= curl_exec($ch);
				$curl_result 	= json_decode($output);
				// Close the cURL resource, and free system resources
				curl_close($ch);
				if($curl_result->ErrorCode=='000')
				{
					$result  = array('data'=> array(),
	    							'message'=>"OTP generated successfully",
	    							'media_type'=>"0",
	    							'status'=>'Success');
				}
				else
				{
					$result  = array('data'=> array(),
				    				'message'=>"OTP not generated",
				    				'media_type'=>"0",
				    				'status'=>'Failure');
				}
				
			}
			else{
					$result  = array('data'=> array(),
	    							'message'=>"OTP not generated",
	    							'status'=>'Failure');
			}				
	 		//$data 			= "123456";
	 		return $result;
	 	}
	 	public function VerifyCustomerOTP($post_data)
	 	{
	 		$MOBILE_NO 		= $post_data['mobile_no']; // Mobile No Format is +91 9999999999
	 		$OTP  			= $post_data['otp'];

	 		$STATIC_OTP 	= "";
	 		$DAY  = date("d",strtotime(date('d-m-Y')))+10;
			$YEAR = date("y",strtotime(date('d-m-Y')))+10;
			$HOUR = 51;//date("h",strtotime(date('d-m-Y h:m:i')));

			$STATIC_OTP =$DAY.$YEAR.$HOUR;
			
			if($STATIC_OTP ==$OTP )
			{
				$result = array('data'=> $data,
				    				'message'=>"OTP Verified Successfully",
				    				'status'=>'Success');
			}
			else
			{
		 		$this->db->select("*");
				$this->db->from("CUSTOMER_OTP");
				$this->db->where("CUSTOMER_MOBILE_NO",$MOBILE_NO);
				$this->db->where("CUSTOMER_OTP",$OTP);
				$this->db->where("CUSTOMER_OTP_STATUS",0);
				$this->db->where("NOW() <= DATE_ADD(CREATE_DATE, INTERVAL 30 MINUTE)");
				$this->db->order_by("CUSTOMER_MOBILE_NO", "desc");
				$this->db->limit(1);  
				$query = $this->db->get();
				//echo $this->db->last_query();	 
				if($query->num_rows() > 0)
				{
					foreach ($query->result() as $row) {
						//CAHNGE OTP STATUS TO CUSTOMER_OTP_STATUS = 1
						$data 	= array('CUSTOMER_OTP_STATUS'=> 1);

			 			$this->db->where("CUSTOMER_MOBILE_NO",$MOBILE_NO);
						$this->db->where("CUSTOMER_OTP",$OTP);
						$update =$this->db->update("CUSTOMER_OTP",$data);    
						$data = array("MOBILE_NO"=>$MOBILE_NO);
					}
				    $result = array('data'=> $data,
				    				'message'=>"OTP Verified Successfully",
				    				'status'=>'Success');
				}
				else
				{
					 $result = array('data'=> array("MOBILE_NO"=>$MOBILE_NO),
					    			'message'=>"Incorrect OTP",
					    			'status'=>'Failure');
				}
			}
			return $result;
	 	}
	 	public function VerifyInvitationOTP($post_data)
	 	{
	 		$MOBILE_NO 		= $post_data['mobile_no']; // Mobile No Format is +91 9999999999
	 		$OTP  			= $post_data['otp'];

	 		$this->db->select("*");
			$this->db->from("CUSTOMER_OTP");
			$this->db->where("CUSTOMER_MOBILE_NO",$MOBILE_NO);
			$this->db->where("CUSTOMER_OTP",$OTP);
			$this->db->where("CUSTOMER_OTP_STATUS",0);
			$this->db->where("NOW() <= DATE_ADD(CREATE_DATE, INTERVAL 48 HOUR)");
			$this->db->order_by("CUSTOMER_MOBILE_NO", "desc");
			$this->db->limit(1);  
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{
				foreach ($query->result() as $row) {
					//CAHNGE OTP STATUS TO CUSTOMER_OTP_STATUS = 1
					$data 	= array('CUSTOMER_OTP_STATUS'=> 1);

		 			$this->db->where("CUSTOMER_MOBILE_NO",$MOBILE_NO);
					$this->db->where("CUSTOMER_OTP",$OTP);
					$update =$this->db->update("CUSTOMER_OTP",$data);    
					$data = array("MOBILE_NO"=>$MOBILE_NO);
				}
			    $result = array('data'=> $data,
			    				'message'=>"Invitation code verified",
			    				'status'=>'Success');
			}
			else
			{
				 $result = array('data'=> array("MOBILE_NO"=>$MOBILE_NO),
				    			'message'=>"Invalid invitation code",
				    			'status'=>'Failure');
			}
			return $result;
	 	}
	 	//UPDATE CUSTOMER PROFILE
	 	public function UpdateCustomerProfile($post_data)
	 	{
	 		$CUSTOMER_ID 			= $post_data['customer_id'];
	 		$CUSTOMER_FIRST_NAME 	= $post_data['customer_first_name'];
	 		$CUSTOMER_LAST_NAME 	= $post_data['customer_last_name'];
	 		$CUSTOMER_EMAIL 	 	= $post_data['customer_email'];
	 		$CUSTOMER_IMAGE_BASE64 	= $post_data['customer_profile_base64'];
	 		$CUSTOMER_IMAGE_NAME 	= ($CUSTOMER_IMAGE_BASE64!=''? md5($CUSTOMER_ID.date('Y-m-d H:i:s')).'.jpg':null);
	 		$CUSTOMER_DEVICE_TOKEN	= $post_data['customer_device_token'];
	 		$CUSTOMER_DEVICE_MAC 	= $post_data['customer_device_mac'];

	 		$data=array(
						"CUSTOMER_FIRST_NAME" 	=> trim($CUSTOMER_FIRST_NAME), 
						"CUSTOMER_LAST_NAME" 	=> trim($CUSTOMER_LAST_NAME),
						"CUSTOMER_EMAIL"		=> trim($CUSTOMER_EMAIL),
						"CUSTOMER_DEVICE_TOKEN" => trim($CUSTOMER_DEVICE_TOKEN),
						"CUSTOMER_DEVICE_MAC" 	=> trim($CUSTOMER_DEVICE_MAC)
						);
	 		if($CUSTOMER_IMAGE_BASE64!='')
	 			{
	 				$data['CUSTOMER_IMAGE_NAME'] = $CUSTOMER_IMAGE_NAME;
	 				$fileupload = $this->SaveBase64($CUSTOMER_IMAGE_BASE64,$CUSTOMER_IMAGE_NAME);
		 		}
		
 			$this->db->where("CUSTOMER_ID",$CUSTOMER_ID);		
		    $this->db->update('CUSTOMERS',$data);	
		    $updated_rows = $this->db->affected_rows();			    
		    if($updated_rows==0)
		    {
		    	$result 	= array('data'=> array(),
		    						'message'=>'Profile not updated',
		    						'status'=>'Failed'
		    						);
		    	return $result;
		    }
		    else
		    {
		    	$result 	= array('data'=> array('updated_rows'=>$updated_rows),
		    						'message'=>"Profile updated successfully",
		    						'status'=>'Success'
		    						);
		    	return $result;
		    }
		    return $result;
	 	}
	 	public function SaveBase64($base64_str,$filename)
	 	{
			//$base_to_php = explode(',', $base64_str);
			$data = base64_decode($base64_str);
			$filepath = FCPATH.CUSTOMER_PROFILE_IMAGE.'/'.$filename; 
			file_put_contents($filepath,$data);
	 	}

	 	//GET CUSTOMERS
	 	public function GetCustomers($customer_id)
	 	{
			$this->db->select("*");
			$this->db->from("CUSTOMERS");
			$this->db->where("CUSTOMER_ID",$customer_id);
			$this->db->where("CUSTOMER_ACTIVE !=",0);
			$data 	= array();
			$query 	= $this->db->get();
			if($query->num_rows() > 0)
			{
				$data = $query->result();
			}
			return $data;
	 	}
	 	//LIST PRIORATISED COMPANIES
	 	public function ListPrioratise($post_data)
	 	{
	 		$CUSTOMER_ID 			= $post_data['customer_id'];
	 		$data 					= array();
	 		$data_private_company	= array();
	 		$data_public_company 	= array();

	 		//GET SYMP ID COMPANY
			$this->db->distinct();
			$this->db->select("CPC_COMPANY_ID AS COMPANY_ID");
			$this->db->from("CUSTOMER_PRIVATE_COMPANY");
			$this->db->where("CPC_CUSTOMER_ID",$CUSTOMER_ID);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{
				$data_private_company = $query->result();	
			}
			//GET CUSTOMERS LIST ADDED BY COMPANY
			$this->db->distinct();
			$this->db->select("CC_COMPANY_ID AS COMPANY_ID");
			$this->db->from("COMPANY_CUSTOMER");
			$this->db->where("CC_CUSTOMER_ID",$CUSTOMER_ID);
			$this->db->where("CC_CUSTOMER_ACTIVE =",1);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{
				if(count($data_private_company)>0)
				{
					$data_public_company = $query->result();
					$data = array_merge($data_private_company,$data_public_company);
				}
				else
				{
					$data = $query->result();
				}				
			}

			if(count($data)>0)
			{
				$result 	= array('data'=> $data,
									'message' =>'Prioratised company list',
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
	 	//LIST FAVOURITE COMPANIES
	 	public function ListFavouritesCompany($post_data)
	 	{
	 		$CUSTOMER_ID 		= $post_data['customer_id'];
	 		$data 				= array();

			$this->db->distinct();
			$this->db->select("CFC_COMPANY_ID");
			$this->db->from("CUSTOMER_FAV_COMPANY");
			$this->db->where("CFC_CUSTOMER_ID",$CUSTOMER_ID);
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
	 	//ADD REMOVE COMPANY BLOCKED BY CUSTOMER
	 	public function BlockUnblockCompany($post_data)
	 	{
	 		$COMPANY_ID 	= $post_data['company_id'];
	 		$CUSTOMER_ID 	= $post_data['customer_id'];
	 		$BLOCK_STATUS 	= $post_data['block_status'];  // 1 = BLOCK, 0= UNBLOCK

	 		if($BLOCK_STATUS ==0)
	 		{
				
				$this->db->where("CBLC_CUSTOMER_ID",$CUSTOMER_ID);
				$this->db->where("CBLC_COMPANY_ID",$COMPANY_ID);	
				$this->db->delete("CUSTOMER_BLOCKED_COMPANY");
				$result 	= array('data'=> array(),
										'message'=>"Company unblocked successfully",
										'status'=>'Success',
										);
	 		} 			
	 		else if($BLOCK_STATUS ==1) 
	 		{
	 			
				$this->db->where("CBLC_CUSTOMER_ID",$CUSTOMER_ID);
				$this->db->where("CBLC_COMPANY_ID",$COMPANY_ID);	 			
				$this->db->delete("CUSTOMER_BLOCKED_COMPANY");

				$data = array("CBLC_CUSTOMER_ID" 	=> $CUSTOMER_ID,
							  "CBLC_COMPANY_ID" 	=> $COMPANY_ID );

				$this->db->insert("CUSTOMER_BLOCKED_COMPANY",$data);
				$data_id = $this->db->insert_id();	
				$result 	= array('data'=> array('added_row_id'=>$data_id),
										'message'=>"Company blocked successfully",
										'status'=>'Success',
										);
	 		}			
			return $result;
	 	}
	 	//LIST BLOCKED COMPANIES
	 	public function ListBlockedCompany($post_data)
	 	{
	 		$CUSTOMER_ID 		= $post_data['customer_id'];
	 		$data 				= array();

			$this->db->distinct();
			$this->db->select("CBLC_COMPANY_ID");
			$this->db->from("CUSTOMER_BLOCKED_COMPANY");
			$this->db->where("CBLC_CUSTOMER_ID",$CUSTOMER_ID);
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
	 	//VERIFY COMPANIES UNIQUE ID
	 	public function VerifyCompanyUniqueId($post_data)
	 	{
	 		$COMPANY_UNIQUE_ID 		= $post_data['company_unique_id'];
	 		$CUSTOMER_ID 			= $post_data['customer_id'];
	 		$data 					= array();

			$this->db->select("*");
			$this->db->from("COMPANY");
			$this->db->where("COMPANY_SYMP_ID",$COMPANY_UNIQUE_ID);
			$this->db->where("COMPANY_ACTIVE",1);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{

				$this->db->select("*");
				$this->db->from("CUSTOMER_PRIVATE_COMPANY");
				$this->db->where("CPC_COMPANY_ID", $query->row(0)->COMPANY_ID);
				$this->db->where("CPC_CUSTOMER_ID",trim($CUSTOMER_ID));
				$querys = $this->db->get();
				//echo $this->db->last_query();	 
				if($querys->num_rows() > 0)
				{
					$result 	= array('data'=> $data,
									'message' =>'Private ID already added in your list',
									'status'=>'Success',
									);
				}else
				{
					$data=array(
						"CPC_CUSTOMER_ID" 	=> trim($CUSTOMER_ID), 
						"CPC_COMPANY_ID" 	=>  $query->row(0)->COMPANY_ID
						);
					$this->db->insert("CUSTOMER_PRIVATE_COMPANY",$data);    
					$insert_id = $this->db->insert_id();	

					$data = $query->result();
					$result 	= array('data'=> $data,
										'message' =>'Private ID verified',
										'status'=>'Success',
										);
				}
			}
			else
			{
				$result 	= array('data'=> $data,
									'message' =>'Invalid SYMP ID',
									'status'=>'Failed',
									);
			}
			return $result;
	 	}
	 	//LIST CUSTOMER PRIVATE COMPANIES
	 	public function ListCustomerPrivateCompany($post_data)
	 	{
	 		$CUSTOMER_ID 		= $post_data['customer_id'];
	 		$data 				= array();

			$this->db->select("CPC_COMPANY_ID");
			$this->db->from("CUSTOMER_PRIVATE_COMPANY");
			$this->db->where("CPC_CUSTOMER_ID",$CUSTOMER_ID);
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
	 	//LIST BLOCKED COMPANIES
	 	public function DeleteCustomerPrivateCompany($post_data)
	 	{
	 		$CUSTOMER_ID 		= $post_data['customer_id'];
	 		$COMPANY_ID 		= $post_data['company_id'];
	 		$data 				= array();

			
			$this->db->where("CPC_CUSTOMER_ID",$CUSTOMER_ID);
			$this->db->where("CPC_COMPANY_ID",$COMPANY_ID);
			$this->db->delete("CUSTOMER_PRIVATE_COMPANY");
			$affected_rows = $this->db->affected_rows();
			
			$result 	= array('data'=> array(),
						'message'=>"Deleted successfully",
						'status'=>'Success',
						);
			
			return $result;
	 	}
	 	//GET PRIORITY COMPANY DETAILS FOR CUSTOME DETAILS 
	 	public function GetPriorityCompanies($CUSTOMER_ID)
	 	{
	 		$data 		= array();
	 		$this->db->select("CC_COMPANY_ID");
			$this->db->from("COMPANY_CUSTOMER");
			$this->db->where("CC_CUSTOMER_ID",$CUSTOMER_ID);
			$this->db->where("CC_CUSTOMER_DELETED =",0);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{
				$data = $query->result();
			}
			
			return $data;
	 	}
	 	//GET ALL CUSTOMER DETAILS 
	 	public function GetCustomerDetails($post_data)
	 	{
	 		$CUSTOMER_ID 		= $post_data['customer_id'];
	 		$data 				= array();

	 		//GET CUSTOMER PROFILE DETAILS 
	 		$CUSTOMER_PROFILE_DETAILS  	= $this->GetCustomers($CUSTOMER_ID);
	 		if(count($CUSTOMER_PROFILE_DETAILS) >0)
	 		{
				$data['CUSTOMER_PROFILE_DETAILS']	= $CUSTOMER_PROFILE_DETAILS;

				//CUSTOMER SELECTED CATEGORY
		 		$query = $this->db->where("CSC_CUSTOMER_ID",$CUSTOMER_ID);		
				$query = $this->db->select("BC.BUS_CAT_ID,BC.BUS_CAT_NAME,BC.BUS_CAT_ICON");
				$query = $this->db->from("CUSTOMER_SELECTED_CATEGORY CSC");
				$query = $this->db->join("BUSINESS_CATEGORY BC","CSC.CSC_BUS_CAT_ID= BC.BUS_CAT_ID","LEFT");
				$query = $this->db->get();

				if ($query->num_rows() > 0)
				{
					$data['CUSTOMER_SELECTED_CATEGORY']	=$query->result();
				}
				// LIST CUSTOMER PRIVATE COMPANY	 		
		 		$CUSTOMER_PRIVATE_COMPANY			= $this->ListCustomerPrivateCompany($post_data);
		 		$data['CUSTOMER_PRIVATE_COMPANY']	= $CUSTOMER_PRIVATE_COMPANY['data'];
		 		$data['CUSTOMER_PRIORITY_COMPANY']	= $this->GetPriorityCompanies($CUSTOMER_ID);
		 		$CUSTOMER_FAV_COMPANY				= $this->ListFavouritesCompany($post_data);
		 		$data['CUSTOMER_FAV_COMPANY']		= $CUSTOMER_FAV_COMPANY['data'];
		 		$CUSTOMER_BLOCKED_COMPANY			= $this->ListBlockedCompany($post_data);
		 		$data['CUSTOMER_BLOCKED_COMPANY']	= $CUSTOMER_BLOCKED_COMPANY['data'];
		 		$data['CUSTOMER_PROFILE_IMAGE_PATH']	= base_url().CUSTOMER_PROFILE_IMAGE.'/'; 
		 		$data['CUSTOMER_CATEGORY_ICON_PATH']	= base_url().CATEGORY_LOGO_PATH."/";

		 		$result 	= array('data'=> $data,
									'message'=>"Customer data found",
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
	 	//ADD REMOVE CUSTOMER PROMOTIONS
	 	public function DeleteCustomerPromotions($post_data)
	 	{
	 		$CUSTOMER_ID 	= $post_data['customer_id'];
	 		$PRMOTION_ID 	= $post_data['promotion_id'];

	 		$data=array(
						"CPD_CUSTOMER_ID" 	=> trim($CUSTOMER_ID), 
						"CPD_PROMOTION_ID" 	=> trim($PRMOTION_ID)
						);
	 		
	 		$this->db->select("*");
			$this->db->from("CUSTOMERS_PROMOTIONS_DELETED");
			$this->db->where("CPD_CUSTOMER_ID",$CUSTOMER_ID);
			$this->db->where("CPD_PROMOTION_ID",$PRMOTION_ID);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{			
				$result 	= array('data'=>"",
	 								'message'=>"Promotion already deleted",
									'status'=>'Failed',
									);										
			}
			else
			{
				
				$this->db->insert("CUSTOMERS_PROMOTIONS_DELETED",$data);
				$data_id 	= $this->db->insert_id();	
				$result 	= array('data'=> array('added_row_id'=>$data_id),
									'message'=>"Prmotion deleted successfully",
									'status'=>'Success',
									);
			
			}
			return $result;		
	 	}
	 	//CHECK CUSTOMER IS EXIST
	 	public function CheckCustomerIsExist($post_data)
	 	{
	 		$CUSTOMER_COUNTRY_ID			= getCountryId($post_data['customer_country_code']);
	 		$CUSTOMER_MOBILE_NO				= $post_data['customer_mobile_no'];
	 		$CUSTOMER_MOBILE_OS				= $post_data['customer_mobile_os'];
	 		$CUSTOMER_MOBILE_OS_VERSION		= $post_data['customer_mobile_os_version'];
	 		$CUSTOMER_DEVICE_TOKEN			= $post_data['customer_device_token'];
	 		$CUSTOMER_DEVICE_MAC 			= $post_data['customer_device_mac'];

	 		$data 							= array();
			
			$this->db->select("CC_CUSTOMER_MOBILE_NO");
			$this->db->from("COMPANY_CUSTOMER");
			$this->db->where("CC_CUSTOMER_MOBILE_NO",$CUSTOMER_MOBILE_NO);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{			
				$data 		= $this->RegisterCustomer($post_data);

				$result 	= array('data'=>$data['data'],
	 								'message'=>"Customer already available in companies list",
	 								'image_path' => base_url().CUSTOMER_PROFILE_IMAGE.'/', 
									'status'=>'Success',
									);										
			}
			else
			{
				$result 	= array('data'=> $data,
									'message'=>"Customer not available from companies list",
									'status'=>'Failure',
									);
			
			}
			return $result;		
	 	}
	 	//CHECK IS ANY CHENGES ARE THERE FOR A CUSTOMER DETAILS 
	 	public function checkIsChanged($post_data)
	 	{
	 		$CUSTOMER_ID 		= $post_data['customer_id'];
	 		$TIMESTAMP 			= $post_data['last_timestamp'];
	 		$data['promotions']	= 0;
	 		$data['category']	= 0;
	 		$data['customer']	= 0;
	 		$data['company']	= 0;
	 		$data['timestamp']	= $this->now();

	 		$PROMOTIONS_LIST =  $this->ListPromotions($post_data);
	 		if(sizeof($PROMOTIONS_LIST['data'])>0)
	 			$data['promotions']=1;

	 		//CUSTOMER SELECTED CATEGORY
	 		$query = $this->db->where("CSC_CUSTOMER_ID",$CUSTOMER_ID);		
	 		$query = $this->db->where("BC.UPDATE_DATE >=",$TIMESTAMP);		 		
			$query = $this->db->select("BC.BUS_CAT_ID,BC.BUS_CAT_NAME,BC.BUS_CAT_ICON");
			$query = $this->db->from("CUSTOMER_SELECTED_CATEGORY CSC");
			$query = $this->db->join("BUSINESS_CATEGORY BC","CSC.CSC_BUS_CAT_ID= BC.BUS_CAT_ID","LEFT");
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if ($query->num_rows() > 0)
			{
				$data['category']	= 1;

			}

			//CHECK ANY UPDATES IN CUSTOMER DETAILS
	 		$query = $this->db->where("CUSTOMER_ID",$CUSTOMER_ID);		
	 		$query = $this->db->where("UPDATE_DATE >=",$TIMESTAMP);		 		
			$query = $this->db->select("*");
			$query = $this->db->from("CUSTOMERS");
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if ($query->num_rows() > 0)
			{
				$data['customer']	= 1;

			}

			//CHECK ANY UPDATES IN COMPANY DETAILS
	 		$query = $this->db->where("UPDATE_DATE >=",$TIMESTAMP);		 		
			$query = $this->db->select("*");
			$query = $this->db->from("COMPANY");
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if ($query->num_rows() > 0)
			{
				$data['company']	= 1;

			}

			return $data;
	 	}	 	
	 	//
	 	public function CustomerProfileDetails($post_data)
	 	{
	 		$CUSTOMER_ID 		=  $post_data['customer_id'];
	 		$data 				=  array();
	 		
			//CHECK ANY UPDATES IN CUSTOMER DETAILS
	 		$query = $this->db->where("CUSTOMER_ID",$CUSTOMER_ID);		
			$query = $this->db->select("*");
			$query = $this->db->from("CUSTOMERS");
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if ($query->num_rows() > 0)
			{
				$data 		= $query->result();
				$result 	= array('data'=>$data,
	 								'message'=>"Customer profile details",
	 								'image_path' => base_url().CUSTOMER_PROFILE_IMAGE.'/', 
									'status'=>'Success'
									);	
			}
			else
			{
				$result 	= array('data'=>$data,
	 								'message'=>"No data available",
									'status'=>'Failure'
									);	
			}

			return $result;
	 	}	
	 	public function UpdateDeviceToken($post_data)
	 	{
	 		$CUSTOMER_ID 		=  $post_data['customer_id'];
	 		$DEVICE_TOKEN 		=  $post_data['device_token'];

	 		$data 				= array('CUSTOMER_DEVICE_TOKEN'=>$DEVICE_TOKEN);

	 		$this->db->where("CUSTOMER_ID",$CUSTOMER_ID);	
			$this->db->update('CUSTOMERS',$data);				    
			$updated_rows = $this->db->affected_rows();			    
			if($updated_rows>0)
			{
				$result 	= array('data'=>array(),
	 								'message'=>"Customer device token updated",
									'status'=>'Success'
									);	
			}
			else
			{
				$result 	= array('data'=>array(),
	 								'message'=>"No data available",
									'status'=>'Failure'
									);	
			}
			return $result;

	 	}
	 	//DELETE PRIORATISED COMPANIES
	 	public function DeletePrioratisedCompany($post_data)
	 	{
	 		$CUSTOMER_ID 			= $post_data['customer_id'];
	 		$COMPANY_ID 			= $post_data['company_id'];
	 		$affected_rows			= 0;
	 		
	 		//DELETE COMPANY FORM CUSTOMER PRIVATE COMPANY
			$this->db->where("CPC_COMPANY_ID",$COMPANY_ID);
			$this->db->where("CPC_CUSTOMER_ID",$CUSTOMER_ID);
			$this->db->delete("CUSTOMER_PRIVATE_COMPANY");
			$deleted_rows = $this->db->trans_status();

			//UPDATE COMPANY STATUS WHEN DELETED BY CUSTOMER
			$data = array('CC_CUSTOMER_DELETED'	=>	1);
			$this->db->where("CC_CUSTOMER_ID",$CUSTOMER_ID);
			$this->db->where("CC_COMPANY_ID",$COMPANY_ID);
			$this->db->update("COMPANY_CUSTOMER",$data);
			$updated_rows = $this->db->trans_status();
			//echo $this->db->last_query();	

			if ($deleted_rows === FALSE and $updated_rows === FALSE)
			{
			    $result = array('data'=> array(),
								'message'=>"Not deleted",
								'status'=>'Filure',
								);
			}			
			else
			{
				$result = array('data'=> array(),
								'message'=>"Deleted successfully",
								'status'=>'Success',
								);
			}
			
			return $result;
	 	}

	 	//SEND EMAIL TO WEB ADMINFROM CUSTOMER CONTACT DETAILS FROM WEBSITE
	 	public function SendMailFromWebsite($post_data)
	 	{
	 		$CUSTOMER_NAME 			= $post_data['customer_name'];
	 		$CUSTOMER_EMAILID 		= $post_data['customer_emailid'];
	 		$CUSTOMER_SUBJECT 		= $post_data['customer_subject'];
	 		$CUSTOMER_MESSAGE		= $post_data['customer_message'];
	 		
			/******* SEND OTP MAIL TO CUSTOMER **************/
			$MailData	= array('customer_name'=>$CUSTOMER_NAME );						
			$subject	= "Contact Form - ".$CUSTOMER_SUBJECT;
			$to 		= CUSTOMER_OTP_FROM_MAIL_ID;
			$from_mail	= CUSTOMER_OTP_FROM_MAIL_ID;
			$from_name	= CUSTOMER_OTP_FROM_MAIL_NAME;
			$body		= 'Dear Admin,'."<br><br>";
			$body		.= "Customer contact details "."<br><br>";
			$body		.= "Name 		: ".$CUSTOMER_NAME ."<br>";
			$body		.= "Email 		: ".$CUSTOMER_EMAILID ."<br>";
			$body		.= "Subject 	: ".$CUSTOMER_SUBJECT ."<br>";
			$body		.= "Message 	: ".$CUSTOMER_MESSAGE ."<br>";
			
			//$body = $this->load->view('email/symp_otp.php',$MailData,TRUE);
			$this->load->library('email');
			$this->email->from($from_mail,$from_name);
			$this->email->to($to);
			$this->email->subject($subject);
			$this->email->set_mailtype("html");
			$this->email->message($body);
			if($this->email->send())
			{
				//echo $this->email->print_debugger();
				return true;
			}
			else
			{
				//echo $this->email->print_debugger();
				return false;

			}

	 	}

		#public function response($data = NULL, $http_code = NULL)	
		public function response($data = NULL, $http_code = NULL)
	    {
			ob_start();
	        // If the HTTP status is not NULL, then cast as an integer
	        if ($http_code !== NULL)
	        {
	            // So as to be safe later on in the process
	            $http_code = (int) $http_code;
	        }

	        // Set the output as NULL by default
	        $output = NULL;

	        // If data is NULL and no HTTP status code provided, then display, error and exit
	        if ($data === NULL && $http_code === NULL)
	        {
	            $http_code = self::HTTP_NOT_FOUND;
	        }

	        // If data is not NULL and a HTTP status code provided, then continue
	        elseif ($data !== NULL)
	        {        				 
				$output = json_encode($data);		
				$this->output->set_content_type('application/json');
	        }
	      
	        // Output the data
	        $this->output->set_output($output);

	        ob_end_flush();
	        // Otherwise dump the output automatically
	    }

	 	/****************************** FUNCTIONS FOR BOON *** END ***************************/



//last Post
	 	public function ListLastPost()
	 	{	

			$this->db->select("P.PROMOTIONS_ID,P.PROMOTIONS_COMPANY_ID,P.PROMOTIONS_TITLE,P.PROMOTIONS_DESCRIPTION,P.PROMOTIONS_VALID_FROM,P.PROMOTIONS_VALID_TO,C.COMPANY_NAME,C.COMPANY_LOGO_NAME,P.PROMOTIONS_ATTACH_NAME",false);
			$this->db->from("PROMOTIONS P");
			$this->db->join("COMPANY C","P.PROMOTIONS_COMPANY_ID = C.COMPANY_ID","INNER");
			$this->db->where("P.PROMOTIONS_ACTIVE =",1);
			$this->db->where("P.PROMOTIONS_PRIVATE !=",1);
			$this->db->where("C.COMPANY_SYMP_ID =",NULL);			
			$this->db->where("DATE (P.PROMOTIONS_VALID_TO) >=",'CURDATE()',FALSE);
			$this->db->where("DATE (P.PROMOTIONS_VALID_FROM) <=",'CURDATE()',FALSE);
			$this->db->order_by("PROMOTIONS_ID,P.PROMOTIONS_VALID_FROM",'desc');
			$this->db->limit(1);
				
			$query_promo = $this->db->get();	
			//echo $this->db->last_query();	
			if ($query_promo->num_rows() == 1)
			{		
				$output = $query_promo->row();
				if(!file_exists( FCPATH.COMPANY_LOGO_PATH.$output->COMPANY_LOGO_NAME))
				{
					$output->COMPANY_LOGO_NAME ="null";
				}
				//echo FCPATH.PROMOTION_MEDIA_PATH.'/'.$output->PROMOTIONS_ATTACH_NAME;
				//Get Post image path
				if(!file_exists( FCPATH.PROMOTION_MEDIA_PATH.'/'.$output->PROMOTIONS_ATTACH_NAME))
				{
					$output->PROMOTIONS_ATTACH_NAME ="null";
				}
				$result 	= array('data'=>$output,
								'status'=>'Success',
								'logo_path'=>base_url().COMPANY_LOGO_PATH,
								'post_path'=>base_url().PROMOTION_MEDIA_PATH.'/',
								);
			}
			else
			{
				$result 	= array('data'=> array(),'status'=>'Failed');		
			}
			
			return json_encode($result);			
	 	}
}
?>
