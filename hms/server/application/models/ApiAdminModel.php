<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	*
 	* Class ApiModel using to create and Maintain items
 	*
 	*
 	*/
	class ApiAdminModel extends CI_Model {
	
		/**
		*
	 	* Function for add or update items
	 	*
	 	* @param   input post parameters 
	 	* @return   bool
	 	*
	 	*/
	 	/****************************** FUNCTIONS FOR BOON *** BEGIN *************************/
	 	public function CheckAppAdminLogin($post_data)
	 	{
	 		$username 	= $post_data['username'];
	 		$password 	= $post_data['password']; 
	 		$company_code = $post_data['company_code'];


	 		$data 		= array();

	 		$this->db->select("U.USER_ID,U.USER_FIRST_NAME,U.USER_LAST_NAME,U.USER_ROLE_ID,U.USER_COMPANY_ID,C.COMPANY_CODE,C.COMPANY_NAME,C.COMPANY_LOGO_NAME,U.USER_NAME,U.USER_ACTIVE");
			$this->db->from("USERS U");
			$this->db->join("COMPANY C","C.COMPANY_ID = U.USER_COMPANY_ID","LEFT");
	 		$this->db->where("U.USER_NAME",trim($username));
	 		$this->db->where("U.USER_PWD",md5(trim($password)));
	 		$this->db->where("U.USER_ACTIVE",(int) 1);	 		
	 		$this->db->where("C.COMPANY_CODE",$company_code);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{
				$data = $query->result();
				$result 	= array('data'=>$data,
									'message'=>'Login successfully',
									'status'=>'Success',
									'logo_path'=>base_url().COMPANY_LOGO_PATH."/");
			}
			else
			{
				$result 	= array('data'=>$data,
									'message'=> 'Login details mismatch',
									'status'=>'Failure');
			}
			return $result;
	 	}
	 	public function ListCompanyPromotions($post_data)
	 	{
 			$user_id 		= $post_data['user_id'];
	 		$company_id 	= $post_data['company_id']; 
	 		$last_timestamp = $post_data['last_timestamp'];
			$data 			= array();	 	

			//SELECT COMPANIES PROMOTIONS LIST
			if(!is_null($last_timestamp) && !empty($last_timestamp))	
				$this->db->where("UPDATE_DATE >= ",$last_timestamp);

	 		$this->db->select("*");
			$this->db->from("PROMOTIONS");
			$this->db->where_in("PROMOTIONS_COMPANY_ID",$company_id);
			$this->db->order_by("PROMOTIONS_VALID_FROM","DESC");
			$this->db->order_by("PROMOTIONS_ID","DESC");
			$query_promo = $this->db->get();	
			//echo $this->db->last_query();	
			if ($query_promo->num_rows() > 0)
			{
				foreach ($query_promo->result() as $promo_row) 
					{
						$promotion_categories       = $this->getPromotionCategory($promo_row->PROMOTIONS_ID);
						$status_counts 				= $this->getPromotionStatusCount($promo_row->PROMOTIONS_ID);
						$private_customer_ids 		= getPrivatePromotionCustomers($promo_row->PROMOTIONS_ID);

						$Attach_names 				= getAttachments($promo_row->PROMOTIONS_ID);
						
						$promo_row->CP_LIKE			= ($status_counts->CP_LIKE== NULL? 0:$status_counts->CP_LIKE) ;
						$promo_row->CP_USED			= ($status_counts->CP_USED== NULL? 0:$status_counts->CP_USED) ;
						$promo_row->CP_INTERESTED 	= ($status_counts->CP_INTERESTED== NULL ? 0 :$status_counts->CP_INTERESTED);
						$promo_row->CP_CALL_BE_BACK = ($status_counts->CP_CALL_BE_BACK== NULL ? 0 :$status_counts->CP_CALL_BE_BACK);
						$promo_row->CP_READ 		= ($status_counts->CP_READ== NULL ? 0 :$status_counts->CP_READ);
						$promo_row->PROMO_CATEGORIES= $promotion_categories;
						$promo_row->PVT_CUSTOMERS 	= $private_customer_ids;
						$promo_row->ATTACHMENTS 	= $Attach_names;
						$data[] = $promo_row;
					}


				$result 	= array('data'=>$data,
								'status'=>'Success',
								'attach_path'=>base_url().PROMOTION_MEDIA_PATH."/",
								'timestamp' => $this->now()
								);
			}	
			else
			{
				$result = array('data'=>array(),'status'=>'Failed');
			}				
			return $result;	 		
	 	}
	 	//GET PROMOTION CATEGORIES
	 	public function getPromotionCategory($promotion_id)
	 	{
	 		$data 				= array();

	 		$this->db->select("PBC.PBC_CATEGORY_ID,BC.BUS_CAT_NAME");
			$this->db->from("PROMOTION_BUSINESS_CATEGORY PBC");
			$this->db->join("BUSINESS_CATEGORY BC","BC.BUS_CAT_ID = PBC.PBC_CATEGORY_ID","LEFT");
	 		$this->db->where("PBC.PBC_PROMO_ID",$promotion_id);
			$query = $this->db->get();
			//echo $this->db->last_query();	 
			if($query->num_rows() > 0)
			{
				$data = $query->result();
			}
			//print_r($data);
			return $data;

	 	}
	 	/**
	 	*Get Time From Database
	 	*@param void
	 	*@return timestamp
	 	*/
	 	public function now()
	 	{
	 		$this->db->select("NOW() as time");
			$query_promo = $this->db->get();	
			return $query_promo->row()->time;
	 	}

	 	/**
	 	*GEt Promotion Status Count
	 	*@param void
	 	*@return Result row
	 	*/

	 	public function getPromotionStatusCount($promotion_id)
	 	{
	 		$data 				= array();
	 		$this->db->select("SUM(CP_LIKE) AS CP_LIKE,SUM(CP_USED) AS CP_USED,SUM(CP_INTERESTED) AS CP_INTERESTED,SUM(CP_CALL_BE_BACK) AS CP_CALL_BE_BACK,SUM(CP_READ) AS CP_READ");
			$this->db->from("CUSTOMERS_PROMOTIONS");
	 		$this->db->where("CP_PROMOTION_ID",$promotion_id);
			$query = $this->db->get();
			if($query->num_rows() ==1)
			{
				$data = $query->row();
			}
			return $data;

	 	}

	 	public function CreatePromotions($post_data)
	 	{
	 		$RANDOM_NO				= rand(100000,999999);					
	 		$PROMOTIONS_ID 			= $post_data['promotion_id'];
	 		$PROMOTIONS_COMPANY_ID 	= $post_data['company_id'];
	 		$PROMOTIONS_TITLE 	 	= $post_data['promotion_title'];
	 		$PROMOTIONS_DESCRIPTION	= $post_data['promotion_desc'];
	 		$PROMOTIONS_ATTACH_B64 	= $post_data['attached_base64_string'];
	 		$PROMOTIONS_ATTACH_NAME = ($PROMOTIONS_ATTACH_B64!=''? md5($RANDOM_NO.date('Y-m-d H:i:s')).'.jpg':null);
	 		$PROMOTIONS_VALID_FROM 	= $post_data['promotion_valid_from'];
	 		$PROMOTIONS_VALID_TO 	= $post_data['promotion_valid_to'];
	 		$PROMOTIONS_ACTIVE	 	= $post_data['promotion_status'];
	 		$PROMOTIONS_CREATE_MEDIA= 1; // 0 = WEB, 1= MOBILE APP
	 		$IS_LIKE 				= $post_data['is_like'];
	 		$IS_INTERESTED 			= $post_data['is_interested'];
	 		$IS_CALLBACK 			= $post_data['is_called'];
	 		$IS_USED 				= $post_data['is_used'];
	 		$IS_SHARED 				= $post_data['is_shared'];
	 		$IS_SHOW_VALID_TO 		= $post_data['is_show_valid'];
	 		$USER_ID 				= $post_data['user_id'];
	 		$PROMOTIONS_CATEGORY	= $post_data['promotion_category'];
	 		$PROMOTION_INFO			= $post_data['promotion_info'];
	 		$PROMOTION_INFO_DESCRIPTION = $post_data['promotion_info_description'];

	 		$PROMOTIONS_PRIVATE 			= $post_data['promotions_private']; // 0= PUBLIC, 1= PRIVATE
	 		$PROMOTIONS_CUSTOMERS_MOBILE_NO = $post_data['customers_mobile_no'];  // array
	 		$PROMOTIONS_ATTCHMENT = $post_data['attach_data'];  // array
	 		
	 		$data=array(
				"PROMOTIONS_COMPANY_ID" 	=> trim($PROMOTIONS_COMPANY_ID), 
				"PROMOTIONS_TITLE" 			=> trim($PROMOTIONS_TITLE),
				"PROMOTIONS_DESCRIPTION"	=> trim($PROMOTIONS_DESCRIPTION),
				//"PROMOTIONS_ATTACH_NAME" 	=> trim($PROMOTIONS_ATTACH_NAME),
				"PROMOTIONS_VALID_FROM" 	=> format_date($PROMOTIONS_VALID_FROM),
				"PROMOTIONS_VALID_TO" 		=> format_date($PROMOTIONS_VALID_TO),
				//"PROMOTIONS_TYPE" => trim($this->input->post("PROMOTIONS_TYPE")),
				"PROMOTIONS_ACTIVE" 		=> trim($PROMOTIONS_ACTIVE),
				"PROMOTIONS_CREATE_MEDIA"	=> $PROMOTIONS_CREATE_MEDIA,
				"IS_LIKE"					=> $IS_LIKE,
				"IS_INTERESTED"				=> $IS_INTERESTED,
				"IS_CALLBACK"				=> $IS_CALLBACK,
				"IS_SHOW_VALID_TO"			=> $IS_SHOW_VALID_TO,
				"IS_USED"					=> $IS_USED,
				"IS_SHARED"					=> $IS_SHARED,
				"USER_ID" 					=> $USER_ID,
				"INFO" 						=> $PROMOTION_INFO,
				"INFO_DESCRIPTION" 			=> $PROMOTION_INFO_DESCRIPTION,
				"PROMOTIONS_PRIVATE" 		=> $PROMOTIONS_PRIVATE
				);
	 		
	 		if(is_array($PROMOTIONS_ATTCHMENT) && sizeof($PROMOTIONS_ATTCHMENT) > 0)
	 		{
	 			$data['PROMOTIONS_ATTACH_NAME'] = $PROMOTIONS_ATTCHMENT[0];
	 		}



	 		if($PROMOTIONS_ID!="" || $PROMOTIONS_ID!=NULL)  // UPDATE EXISTING PROMOTION
	 		{
	 			if($PROMOTIONS_ATTACH_B64!='')
	 			{
	 				$data['PROMOTIONS_ATTACH_NAME'] = $PROMOTIONS_ATTACH_NAME;
	 				$fileupload = $this->SaveBase64($PROMOTIONS_ATTACH_B64,$PROMOTIONS_ATTACH_NAME,PROMOTION_MEDIA_PATH);
		 		}
	 			$query_update = $this->db->where("PROMOTIONS_ID",$PROMOTIONS_ID);		
			    $query_update = $this->db->update('PROMOTIONS',$data);				    
			    $updated_rows = $this->db->affected_rows();		

			    /*Save Attach Data*/
				if($this->Add_Attachents($PROMOTIONS_ATTCHMENT,$PROMOTIONS_ID))$updated_rows =1;

			    //DELETE PRIVATE PROMTOTIONS FROM CUSTOMERS
		 		$this->db->where("CPP_PROMOTION_ID",$PROMOTIONS_ID);		
				$this->db->delete("CUSTOMER_PRIVATE_PROMOTION");

				$PROMO_CATEGORY = $this->AddPromotionBusinessCategory($PROMOTIONS_ID,$PROMOTIONS_CATEGORY);
			    //ADD TO PRIVATE PROMOTIONS TO CUSTOMERS
				foreach($PROMOTIONS_CUSTOMERS_MOBILE_NO as $customer_key =>$customers_mob_no)
				{
					$Customerid 	= getCustomerIdFromMobileNo(trim($customers_mob_no));
					if($Customerid!=0)
					{
						$data_private = array(
											"CPP_CUSTOMER_ID" 	=> $Customerid,
											"CPP_PROMOTION_ID" 	=> trim($PROMOTIONS_ID),
											 );
						$this->db->insert("CUSTOMER_PRIVATE_PROMOTION",$data_private);
					}
				}	

			    if($updated_rows==0)
			    {
			    	$result 	= array('data'=> array('updated_rows'=>$updated_rows),
			    						'message'=>'Post details not updated',
			    						'status'=>'Failed'
			    						);
			    	return $result;
			    }
			    else
			    {
			    	$result 	= array('data'=> array('updated_rows'=>$updated_rows),
			    						'message'=>'Post details updated',
			    						'status'=>'Success'
			    						);
			    	return $result;
			    }
	 		}
	 		else  //ADD NEW PROMOTION
	 		{
	 		
	 			if($PROMOTIONS_ATTACH_B64!='')
	 			{
	 				$data['PROMOTIONS_ATTACH_NAME'] = $PROMOTIONS_ATTACH_NAME;
	 				$fileupload = $this->SaveBase64($PROMOTIONS_ATTACH_B64,$PROMOTIONS_ATTACH_NAME,PROMOTION_MEDIA_PATH);
		 		}

	 			$this->db->insert("PROMOTIONS",$data);
				$data_id = $this->db->insert_id();	

				if($data_id)
				{
					/*Save Attach Data*/
					 $this->Add_Attachents($PROMOTIONS_ATTCHMENT,$data_id);

					//ADD TO PRIVATE PROMOTION FOR CUSTOMER
					$DeviceTokenPvtCustomer 	= array();
					if(is_array($PROMOTIONS_CUSTOMERS_MOBILE_NO))
					{
						
						foreach($PROMOTIONS_CUSTOMERS_MOBILE_NO as $customer_key =>$customers_mob_no)
						{
							$Customerid 				= getCustomerIdFromMobileNo(trim($customers_mob_no));
							if($Customerid!=0)
							{
								$data_private 	= array(
													"CPP_CUSTOMER_ID" 	=> $Customerid,
													"CPP_PROMOTION_ID" 	=> trim($data_id),
													 );
								$this->db->insert("CUSTOMER_PRIVATE_PROMOTION",$data_private);
								//GET DEVICE TOKEN
								$DeviceTokenPvtCustomer 	= getMobileDeviceToken($Customerid);
								if($DeviceTokenPvtCustomer != 0)
								{
									$DeviceTokenPvtCust['ANDROID'][] 	= $DeviceTokenPvtCustomer['ANDROID'][0];
									$DeviceTokenPvtCust['IOS'][] 		= $DeviceTokenPvtCustomer['IOS'][0];
								}
							}
							
						}
					}

					$PROMO_CATEGORY = $this->AddPromotionBusinessCategory($data_id,$PROMOTIONS_CATEGORY);
					$company_name = getCompanyDetails($PROMOTIONS_COMPANY_ID);

					$Addl_Parameters = array('company_id'  =>$PROMOTIONS_COMPANY_ID,
											 'company_name'=>$company_name->COMPANY_NAME,
											 'promotion_id'=>$data_id);
					
					if($PROMOTIONS_PRIVATE==1)
					{
						$DeviceTokens 	= $DeviceTokenPvtCust;
					}
					else
					{
						$DeviceTokens 	= $this->getCustomerDeviceToken($PROMOTIONS_COMPANY_ID);
					}
					//print_r($DeviceTokens);
					
					if (sizeof($DeviceTokens['ANDROID'])>0)
					{
						//SendNotification($DeviceTokens['ANDROID'],NOTIFY_NEW_PROMOTION,$PROMOTIONS_TITLE ,'PROMOTION','ANDROID',$Addl_Parameters);
						SendNotification($DeviceTokens['ANDROID'],$company_name->COMPANY_NAME ,$PROMOTIONS_TITLE ,'PROMOTION','ANDROID',$Addl_Parameters);
					}
					if(sizeof($DeviceTokens['IOS'])>0)
					{
						SendNotification($DeviceTokens['IOS'],$company_name->COMPANY_NAME ,$PROMOTIONS_TITLE ,'PROMOTION','IOS',$Addl_Parameters);
						//SendNotification($DeviceTokens['IOS'],NOTIFY_NEW_PROMOTION,$PROMOTIONS_TITLE ,'PROMOTION','IOS',$Addl_Parameters);
					}
				}

				$result 	= array('data'=>$data_id,
									'message' =>'Promotion added successfully',
									'status'=>'Success'
									);
				return $result;					 			
	 		}
	 	}

	 	public function Add_Attachents($PROMOTIONS_ATTCHMENT,$promotion_id)
	 	{

			/*Add File in PROMOTIONS_ATTACHMENTS */
			 if(is_array($PROMOTIONS_ATTCHMENT) && sizeof($PROMOTIONS_ATTCHMENT) > 1)
			    {
			    	$attach_data = array();
			    	for ($i=1; $i  < sizeof($PROMOTIONS_ATTCHMENT) ; $i++) 
			    	{ 
			    		$attach_data[] = array
			    		(
							"PROMOTIONS_ID" 			=> $promotion_id, 
							"PROMOTIONS_ATTACH_NAME" 	=> $PROMOTIONS_ATTCHMENT[$i], 	
							"EXTENSION" 				=>pathinfo($PROMOTIONS_ATTCHMENT[$i], PATHINFO_EXTENSION), 	
							"FILE_TYPE" 				=> 0	
						);
			    	}		

					$this->db->from("PROMOTIONS_ATTACHMENTS");
			 		$this->db->where("PROMOTIONS_ID",$promotion_id);
					$query = $this->db->get();
					if($query->num_rows() > 0)
					{
			 			$this->db->where("PROMOTIONS_ID",$promotion_id);
    					$this->db->delete('PROMOTIONS_ATTACHMENTS'); 
					}
					if($this->db->insert_batch("PROMOTIONS_ATTACHMENTS", $attach_data))return true;
					else return false; 
			    }else return false;

	 	}
	 	public function AddPromotionBusinessCategory($PROMOTION_ID,$BUSINESS_CATEGORY_ID=array())
	 	{
	 		$PROMOTIONS_ID				= $PROMOTION_ID;
	 		$PROMOTIONS_CATEGORY_ID		= $BUSINESS_CATEGORY_ID; //ARRAY LIST
	 		$data 						= array();


	 		if(!is_array($PROMOTIONS_CATEGORY_ID) || empty($PROMOTIONS_CATEGORY_ID)) return false;	 
						

	 		$query = $this->db->where("PBC_PROMO_ID",$PROMOTIONS_ID);		
			$query = $this->db->select("*");
			$query = $this->db->from("PROMOTION_BUSINESS_CATEGORY");
			$query = $this->db->get();

			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row)
				{
				    //CHECK EXISTING BUSINESS CATEGORY ID'S ARE THEIR IN NEW ARRAY
				    if(in_array($row->PBC_CATEGORY_ID,$PROMOTIONS_CATEGORY_ID))
					{
						$PROMO_EXIST_CAT_ID[] =  $row->PBC_CATEGORY_ID;
					}
					else
					{
						//DELETE EXISTING BUSINESS CATEGORIES
				 		$query = $this->db->where("PBC_PROMO_ID",$PROMOTIONS_ID);		
				 		$query = $this->db->where("PBC_CATEGORY_ID",$row->PBC_CATEGORY_ID);		
						$query = $this->db->delete("PROMOTION_BUSINESS_CATEGORY");
						
					}
				}				
				//ADD EXISTING CUSTOMER'S SELECTED BUSINESS CATEGORIES
				foreach ($PROMOTIONS_CATEGORY_ID as $key => $value)
				{
					if(in_array($value,$PROMO_EXIST_CAT_ID))
					{
						//NOTHING TO DO
					}
					else
					{
						if($value != "" && $value!= null)
						{
							$data 	= array('PBC_PROMO_ID'	=> $PROMOTIONS_ID,
											'PBC_CATEGORY_ID'	=> $value
											);	
							$this->db->insert('PROMOTION_BUSINESS_CATEGORY',$data);
						}
					}					
				}
			    return true;
			}
			else
			{				
		        //ADD NEW BUSINESS CATEGORIES OF PROMOTION
				foreach ($PROMOTIONS_CATEGORY_ID as $key => $value) {
					if($value != "" && $value!= null)
						{
							$data[] 	= array('PBC_PROMO_ID'		=> $PROMOTIONS_ID,
											'PBC_CATEGORY_ID'	=> $value
											);
					}
				}
				$qry_result =$this->db->insert_batch('PROMOTION_BUSINESS_CATEGORY',$data);
			    return true;
			}
	 	}
	 	public function SaveBase64($base64_str,$filename,$filepath)
	 	{
			//$base_to_php = explode(',', $base64_str);
			$data = base64_decode($base64_str);
			//$filepath = FCPATH.PROMOTION_MEDIA_PATH.'/'.$filename; 
			$filepath = FCPATH.$filepath.'/'.$filename; 
			file_put_contents($filepath,$data);
	 	}

	 	public function ListCompanyCategory($post_data)
	 	{
	 		$COMPANY_ID 	= $post_data['company_id'];

	 		$this->db->select("CBC.CBC_ID,CBC.CBC_COMPANY_ID,CBC.CBC_BUS_CAT_ID,BC.BUS_CAT_NAME,BC.BUS_CAT_ICON");
			$this->db->from("COMPANY_BUSINESS_CATEGORY CBC");
			$this->db->join("BUSINESS_CATEGORY BC","CBC.CBC_BUS_CAT_ID= BC.BUS_CAT_ID","INNER");
	 		$this->db->where("CBC.CBC_COMPANY_ID",$COMPANY_ID);
			$query = $this->db->get();
			//echo $this->db->last_query();
			if($query->num_rows() > 0)
			{
				$result 	= array('data'=>$query->result(),
									'category_path'=>base_url().CATEGORY_LOGO_PATH."/",
									'info_desc'=>$this->CompanyInfo($COMPANY_ID),
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
	 	public function ChangePromotionsStatus($post_data)
	 	{
			$PROMOTION_ID 		= $post_data['promotion_id'];
			$PROMOTION_STATUS 	= $post_data['promotion_status'];
			
			//0 = DELETE ,1 = ACTIVE,2 = IN ACTIVE,3 = EXPIRED
			$data = array("PROMOTIONS_ACTIVE" => (int) $PROMOTION_STATUS);

			$query_update = $this->db->where("PROMOTIONS_ID",$PROMOTION_ID);		
			$query_update = $this->db->update('PROMOTIONS',$data);	

			//echo $this->db->last_query();	
			$updated_rows = $this->db->affected_rows();			    
			if($updated_rows==0)
			{
				$result 	= array('data'=> array('updated_rows'=>$updated_rows),
									'message'=>'Promotion details not updated',
									'status'=>'Failed'
									);
				return $result;
			}
			else
			{
				$result 	= array('data'=> array('updated_rows'=>$updated_rows),
									'message'=>'Promotion details updated successfully',
									'status'=>'Success'
									);
				return $result;	
			}
	 	}

	 	public function CreateCustomer($post_data)
	 	{
	 		$CC_ID 					= $post_data['cc_id'];
	 		$COMPANY_ID 			= $post_data['company_id'];
	 		$CUSTOMER_FIRST_NAME 	= $post_data['customer_first_name'];
	 		$CUSTOMER_LAST_NAME 	= $post_data['customer_last_name'];
	 		$CUSTOMER_EMAIL 	 	= $post_data['customer_email'];
	 		$CUSTOMER_MOBILE_NO		= $post_data['customer_mobile_no'];
	 		$CUSTOMER_ACTIVE 		= $post_data['customer_active'];
	 		//$CUSTOMER_DISTRICT_ID 	= $post_data['customer_district_id'];
	 		//$CUSTOMER_STATE_ID 		= $post_data['customer_state_id'];
	 		$CUSTOMER_COUNTRY_ID 	= CountryCodetoID($post_data['customer_country_id']);
	 		$COMPANY_USER_ID 		= $post_data['company_user_id'];
	 		$data=array(
	 					"CC_COMPANY_ID" 			=> trim($COMPANY_ID),
						"CC_CUSTOMER_FIRST_NAME" 	=> trim($CUSTOMER_FIRST_NAME), 
						"CC_CUSTOMER_LAST_NAME" 	=> trim($CUSTOMER_LAST_NAME),
						"CC_CUSTOMER_EMAIL"			=> trim($CUSTOMER_EMAIL),
						"CC_CUSTOMER_MOBILE_NO" 	=> trim($CUSTOMER_MOBILE_NO),				
						"CC_CUSTOMER_ACTIVE" 		=> trim($CUSTOMER_ACTIVE),
						/*"CC_CUSTOMER_DISTRICT_ID" 	=> trim($CUSTOMER_DISTRICT_ID),
						"CC_CUSTOMER_STATE_ID" 		=> trim($CUSTOMER_STATE_ID),*/
						"CC_CUSTOMER_COUNTRY_ID" 	=> trim($CUSTOMER_COUNTRY_ID),
						"USER_ID" 					=> trim($COMPANY_USER_ID)
						);
	 		if(!is_null($CC_ID) && !empty($CC_ID))  // UPDATE EXISTING CUSTOMER
	 		{
	 			$this->db->where("CC_COMPANY_ID",$COMPANY_ID);		
	 			$this->db->where("CC_ID !=",$CC_ID);		
	 			$this->db->where("CC_CUSTOMER_ACTIVE =",1);			 			
		 		$this->db->where("CC_CUSTOMER_MOBILE_NO",$CUSTOMER_MOBILE_NO);	
				$this->db->select("*");
				$this->db->from("COMPANY_CUSTOMER");
				$query = $this->db->get();
				if ($query->num_rows() > 0)
				{
					$result 	= array('data'=> array('updated_rows'=>$updated_rows),
				    						'message'=>'Customer mobile no already exist',
				    						'status'=>'Failed'
				    						);
				    return $result;
				}
				else
				{
		 			$this->db->where("CC_ID",$CC_ID);		
				    $this->db->update('COMPANY_CUSTOMER',$data);	
				    $updated_rows = $this->db->affected_rows();			    
				    if($updated_rows==0)
				    {
				    	$result 	= array('data'=> array('updated_rows'=>$updated_rows),
				    						'message'=>'Customer details updation failed',
				    						'status'=>'Failed'
				    						);
				    	return $result;
				    }
				    else
				    {
				    	$result 	= array('data'=> array('updated_rows'=>$updated_rows),
				    						'message'=>"Customer details updated successfully",
				    						'status'=>'Success'
				    						);
				    	return $result;
				    }
				}
	 		}
	 		else  //ADD NEW CUSTOMER
	 		{
		 		$this->db->where("CC_COMPANY_ID",$COMPANY_ID);		
		 		$this->db->where("CC_CUSTOMER_MOBILE_NO",$CUSTOMER_MOBILE_NO);	
		 		$this->db->where("CC_CUSTOMER_ACTIVE!=",0);	
				$this->db->select("*");
				$this->db->from("COMPANY_CUSTOMER");
				$query = $this->db->get();

				if ($query->num_rows() > 0)
				{	 		
		 			$result 	= array('data'=>"",
		 								'message'=>"Customer mobile no already exist",
										'status'=>'Failed',
										);
					return $result;
		 		}
		 		else
		 		{
		 			$CUSTOMER_ID = $this->linkPrioratisedCustomer($CUSTOMER_MOBILE_NO);
		 			if($CUSTOMER_ID != null)
		 			{
	 					$data["CC_CUSTOMER_ID"] = trim($CUSTOMER_ID);
		 			}
	 				$this->db->insert("COMPANY_CUSTOMER",$data);
					$insert_id = $this->db->insert_id();	
					if($insert_id)
					{
	 					//SEND INVITATION SMS TO NEW CUSTOMER or END USER
	 					$COMPANY_DETAILS 	= getCompanyDetails($COMPANY_ID);
	 					$COMPANY_NAME 		= $COMPANY_DETAILS->COMPANY_NAME;
	 					$MOBILE_NO 			= explode(" ",$CUSTOMER_MOBILE_NO);
	 					if($MOBILE_NO[0]=='+91')
	 						$SMS_Status 	= SendSMS(array($MOBILE_NO[1]),SMS_INVITE_CUSTOMER."%0a".$COMPANY_NAME);
	 					else // OTHER COUNRIES
	 					{
	 						$CUSTOMER_DETAILS['name'] 			= $CUSTOMER_FIRST_NAME;
	 						$CUSTOMER_DETAILS['email_id'] 		= $CUSTOMER_EMAIL;
	 						$CUSTOMER_DETAILS['company_id'] 	= $COMPANY_ID;
	 						$CUSTOMER_DETAILS['company_name'] 	= $COMPANY_NAME;
	 						$CUSTOMER_DETAILS['mobile_no'] 		= $CUSTOMER_MOBILE_NO;

	 						$result = $this->sendInviteMail($CUSTOMER_DETAILS);
	 					}
	 				}
					$result 	= array('data'=> array('cc_customer_id'=>$insert_id),
										'message'=>"Customer added successfully",
										'status'=>'Success',
										);
					return $result;					 			
		 		}
	 		}

	 	}
	 	//SEND INVITATION MAIL
	 	function sendInviteMail($customer_details)
	 	{
	 		$OTP 		= CreateRandomOTP($customer_details['mobile_no']);
			/******* SEND OTP MAIL TO CUSTOMER **************/
			$MailData	= array('customer_name'=>$customer_details['name'],'otp'=>$OTP);						
			$subject	= "Symp Invitation";
			$to 		= $customer_details['email_id'];
			$from_mail	= CUSTOMER_OTP_FROM_MAIL_ID;
			$from_name	= CUSTOMER_OTP_FROM_MAIL_NAME;
			$body		= SMS_INVITE_CUSTOMER."<br>";
			$body		.= "Symp invitation code is ".$OTP;
			
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
			/******* SEND OTP MAIL TO CUSTOMER **************/
	 	}

	 	//LIST CUSTOMERS
	 	public function ListCustomers($post_data)
	 	{
	 		$CC_ID 				= $post_data['cc_id'];
	 		$COMPANY_ID 		= $post_data['company_id'];
	 		$data 				= array();

	 		if($CC_ID != NULL)
	 			$this->db->where("CC_ID",$CC_ID);

			$this->db->select("*");
			$this->db->from("COMPANY_CUSTOMER");
			$this->db->where("CC_COMPANY_ID",$COMPANY_ID);
			//$this->db->where("CC_CUSTOMER_ACTIVE !=",0);


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
	 	//LIST PROMOTIONS STATUS AND CUSTOMER 
	 	public function ListPromotionCustomerStatus($post_data)
	 	{
	 		$PROMOTION_ID 		= $post_data['promotion_id'];
	 		$PROMOTION_STATUS	= $post_data['promotion_status']; //LIKE,USED,INTERESTED,CALL_BE_BACK
	 		$COMPANY_ID 		= 0;

	 		$this->db->select("PROMOTIONS_COMPANY_ID");
	 		$this->db->where("PROMOTIONS_ID",$PROMOTION_ID);	 
	 		$this->db->from("PROMOTIONS");	 
	 		$querys = $this->db->get();
	 		if($querys->num_rows() > 0)
			{
				$COMPANY_ID = $querys->row()->PROMOTIONS_COMPANY_ID;
			}

	 		if($PROMOTION_STATUS == "LIKE")
	 		{
	 			//$this->db->select("CP.CP_CUSTOMER_ID,CP.CP_LIKE,C.CUSTOMER_FIRST_NAME,C.CUSTOMER_LAST_NAME,C.CUSTOMER_EMAIL,C.CUSTOMER_MOBILE_NO,C.CUSTOMER_ACTIVE,C.CUSTOMER_OS,C.CUSTOMER_OS_VERSION,C.CUSTOMER_DEVICE_TOKEN,C.CUSTOMER_IMAGE_NAME,C.CUSTOMER_DISTRICT_ID,C.CUSTOMER_STATE_ID,C.CUSTOMER_COUNTRY_ID");
	 			$this->db->select("CP.CP_LIKE");
	 			$this->db->where("CP.CP_LIKE!=",(int) 0);	 		
	 		}
	 		else if($PROMOTION_STATUS == "USED")
	 		{
	 			//$this->db->select("CP.CP_CUSTOMER_ID,CP.CP_USED,C.CUSTOMER_FIRST_NAME,C.CUSTOMER_LAST_NAME,C.CUSTOMER_EMAIL,C.CUSTOMER_MOBILE_NO,C.CUSTOMER_ACTIVE,C.CUSTOMER_OS,C.CUSTOMER_OS_VERSION,C.CUSTOMER_DEVICE_TOKEN,C.CUSTOMER_IMAGE_NAME,C.CUSTOMER_DISTRICT_ID,C.CUSTOMER_STATE_ID,C.CUSTOMER_COUNTRY_ID");
	 			$this->db->select("CP.CP_USED");
	 			$this->db->where("CP.CP_USED !=",(int) 0);	 		
	 		}
	 		else if($PROMOTION_STATUS == "INTERESTED")
	 		{
	 			//$this->db->select("CP.CP_CUSTOMER_ID,CP.CP_INTERESTED,C.CUSTOMER_FIRST_NAME,C.CUSTOMER_LAST_NAME,C.CUSTOMER_EMAIL,C.CUSTOMER_MOBILE_NO,C.CUSTOMER_ACTIVE,C.CUSTOMER_OS,C.CUSTOMER_OS_VERSION,C.CUSTOMER_DEVICE_TOKEN,C.CUSTOMER_IMAGE_NAME,C.CUSTOMER_DISTRICT_ID,C.CUSTOMER_STATE_ID,C.CUSTOMER_COUNTRY_ID");
	 			$this->db->select("CP.CP_INTERESTED");
	 			$this->db->where("CP.CP_INTERESTED !=",(int) 0);	 		
	 		}
	 		else if($PROMOTION_STATUS == "CALL_BE_BACK")
	 		{
	 			//$this->db->select("CP.CP_CUSTOMER_ID,CP.CP_CALL_BE_BACK,C.CUSTOMER_FIRST_NAME,C.CUSTOMER_LAST_NAME,C.CUSTOMER_EMAIL,C.CUSTOMER_MOBILE_NO,C.CUSTOMER_ACTIVE,C.CUSTOMER_OS,C.CUSTOMER_OS_VERSION,C.CUSTOMER_DEVICE_TOKEN,C.CUSTOMER_IMAGE_NAME,C.CUSTOMER_DISTRICT_ID,C.CUSTOMER_STATE_ID,C.CUSTOMER_COUNTRY_ID");
	 			$this->db->select("CP.CP_CALL_BE_BACK");
	 			$this->db->where("CP.CP_CALL_BE_BACK !=",(int) 0);	 		
	 		}
	 		else if($PROMOTION_STATUS == "READ")
	 		{
	 			//$this->db->select("CP.CP_CUSTOMER_ID,CP.CP_CALL_BE_BACK,C.CUSTOMER_FIRST_NAME,C.CUSTOMER_LAST_NAME,C.CUSTOMER_EMAIL,C.CUSTOMER_MOBILE_NO,C.CUSTOMER_ACTIVE,C.CUSTOMER_OS,C.CUSTOMER_OS_VERSION,C.CUSTOMER_DEVICE_TOKEN,C.CUSTOMER_IMAGE_NAME,C.CUSTOMER_DISTRICT_ID,C.CUSTOMER_STATE_ID,C.CUSTOMER_COUNTRY_ID");
	 			$this->db->select("CP.CP_READ");
	 			$this->db->where("CP.CP_READ !=",(int) 0);	 		
	 		}
			

			
	 		$this->db->select("CP.CP_CUSTOMER_ID,CC.CC_CUSTOMER_FIRST_NAME,CC.CC_CUSTOMER_LAST_NAME,
	 							CC.CC_CUSTOMER_EMAIL,C.CUSTOMER_MOBILE_NO,C.CUSTOMER_ACTIVE,C.CUSTOMER_OS,
	 							C.CUSTOMER_OS_VERSION,C.CUSTOMER_DEVICE_TOKEN,C.CUSTOMER_IMAGE_NAME,
	 							CC.CC_CUSTOMER_DISTRICT_ID,CC.CC_CUSTOMER_STATE_ID,CC.CC_CUSTOMER_COUNTRY_ID,
	 							CP.CP_CALL_BE_BACK_FEEDBACK");
	 		$this->db->where("CP.CP_PROMOTION_ID",$PROMOTION_ID);	 		
	 		//$this->db->where("CC.CC_COMPANY_ID",$COMPANY_ID);	 		
	 		$this->db->where("C.CUSTOMER_ACTIVE !=",0);	 		
			$this->db->from("CUSTOMERS_PROMOTIONS CP");
			$this->db->join("CUSTOMERS C","C.CUSTOMER_ID= CP.CP_CUSTOMER_ID ","INNER");
			$this->db->join("COMPANY_CUSTOMER CC","CC.CC_CUSTOMER_ID= C.CUSTOMER_ID AND CC.CC_COMPANY_ID=".$COMPANY_ID,"LEFT");
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
	 	
	 	public function RenewPromotions($post_data)
	 	{
	 		$PROMOTION_ID 				= $post_data['promotion_id'];
	 		$PROMOTION_PREV_TO_DATE 	= $post_data['promotion_prev_to_date'];
	 		$PROMOTION_NEW_TO_DATE 		= $post_data['promotion_new_to_date'];
	 		$PROMOTION_UPDATE_USER_ID	= $post_data['promotion_update_user_id'];

	 		$data=array(
						"PR_PROMOTIONS_ID" 		=> trim($PROMOTION_ID), 
						"PR_RENEWAL_FROM_DATE" 	=> trim($PROMOTION_PREV_TO_DATE),
						"PR_RENEWAL_TO_DATE" 	=> trim($PROMOTION_NEW_TO_DATE),
						"USER_ID" 				=> trim($PROMOTION_UPDATE_USER_ID)
						);
	 		$this->db->insert("PROMOTIONS_RENEWAL",$data);
			$data_id = $this->db->insert_id();	
			if($data_id)
			{
				$data_update 	= array("PROMOTIONS_VALID_TO" 	=> trim($PROMOTION_NEW_TO_DATE));

				$query_update 	= $this->db->where("PROMOTIONS_ID",$PROMOTION_ID);		
		    	$query_update 	= $this->db->update('PROMOTIONS',$data_update);	

		    	$result = array('data'=> array('inserted_i'=>$data_id),
	    						'message'=>'Promotion renewed successfully',
	    						'status'=>'Success'
	    						);
			}
			else
			{
				$result 	= array('data'=> array(),
		    						'message'=>'Promotion renewal failed',
		    						'status'=>'Failed'
		    						);
			}
		    return $result;
	 	}
	 	public function CompanyInfo($COMPANY_ID)
	 	{
	 		$this->db->where("COMPANY_ID",$COMPANY_ID);	
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
	 	public function linkPrioratisedCustomer($CUSTOMER_MOBILE_NO)
	 	{
	 		$data 	= "";
	 		$query 	= $this->db->where("CUSTOMER_MOBILE_NO",$CUSTOMER_MOBILE_NO);
			$query 	= $this->db->select("*");
			$query 	= $this->db->from("CUSTOMERS");
			$query 	= $this->db->get();
			//echo $this->db->last_query();	
			if ($query->num_rows() == 1)
			{
				$data = $query->row()->CUSTOMER_ID;
			}
			return $data;
	 	}
	 	//GET DEVICE TOKEN FOR SEND PROMOTION NOTIFICATION
	 	public function getCustomerDeviceToken($company_id)
	 	{
	 		$data 					= array();
	 		$data_private_company 	= array();
			$customer_list 			= array();
			$blocked_customer_list	= array();
			$deleted_promotion_list	= array();

	 		//GET SYMP ID COMPANY
			$this->db->distinct();
			$this->db->select("CPC_CUSTOMER_ID");
			$this->db->from("CUSTOMER_PRIVATE_COMPANY");
			$this->db->where("CPC_COMPANY_ID",$company_id);
			$querys = $this->db->get();
			//echo $this->db->last_query();	 
			if($querys->num_rows() > 0)
			{
				//$data_private_company = [];
				foreach ($querys->result() as $row) 
				{
					$customer_list[] = $row->CPC_CUSTOMER_ID;
				}
			}
			//GET BLOCKED COMPANY ID
			$this->db->distinct();
			$this->db->select("CBLC_CUSTOMER_ID");
			$this->db->from("CUSTOMER_BLOCKED_COMPANY");
			$this->db->where("CBLC_COMPANY_ID",$company_id);
			$querysblocked= $this->db->get();
			//echo $this->db->last_query();	 
			if($querysblocked->num_rows() > 0)
			{
				//$data_private_company = [];
				foreach ($querysblocked->result() as $row) 
				{
					$blocked_customer_list[] = $row->CBLC_CUSTOMER_ID;
				}
			}
			//SELECT COMPANIES LIST
			$this->db->distinct();
 			$this->db->select("CSC.CSC_CUSTOMER_ID");
			$this->db->from("CUSTOMER_SELECTED_CATEGORY CSC, COMPANY_BUSINESS_CATEGORY CBC");
			$this->db->join("COMPANY C","C.COMPANY_ID = CBC.CBC_COMPANY_ID","LEFT");
			$this->db->where("CBC.CBC_COMPANY_ID",$company_id);
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
					$customer_list[] = $row->CSC_CUSTOMER_ID;
				}
			}
			//print_r($customer_list);
			//exit();
			if(count(array_unique($customer_list))>0)
			{
				//SELECT COMPANIES PROMOTIONS LIST
				if(count($blocked_customer_list)>0)
					$this->db->where_not_in("CUSTOMER_ID",$blocked_customer_list);

				$this->db->select("CUSTOMER_OS,CUSTOMER_DEVICE_TOKEN");
				$this->db->from("CUSTOMERS");
				$this->db->where_in("CUSTOMER_ID",array_unique($customer_list));
				$this->db->where("CUSTOMER_DEVICE_TOKEN IS NOT NULL");				
				$query = $this->db->get();	

				if ($query->num_rows() > 0)
				{
					foreach ($query->result() as $row) {
						if($row->CUSTOMER_OS==0)
		            		$data['ANDROID'][] 	= $row->CUSTOMER_DEVICE_TOKEN;
		            	else if($row->CUSTOMER_OS==1)
		            		$data['IOS'][] 		= $row->CUSTOMER_DEVICE_TOKEN;
		        	}

				}
			}
			return  $data;

	 	}

	 	public function GetCompanyDetails($post_data)
	 	{
	 		$COMPANY_ID 		= $post_data['company_id'];
	 		$COMPANY_DETAILS	= array();
	 		$COMPANY_DETAILS 	= getCompanyDetails($COMPANY_ID);
	 		
	 		if(sizeof($COMPANY_DETAILS)>0)
	 		{
	 			$result = array('data'=> $COMPANY_DETAILS,
	    						'message'=>'Company details available',
	    						'logo_path'=>base_url().COMPANY_LOGO_PATH."/",
		    					'banner_path'=>base_url().COMPANY_BANNER_PATH."/",
	    						'status'=>'Success'
	    						);
	 		}
			else
			{
				$result 	= array('data'=> $COMPANY_DETAILS,
		    						'message'=>'Company details not available',
		    						'status'=>'Failed'
		    						);
			}
	 		return $result;
	 	}
	 	public function UpdateCompanyDetails($post_data)
	 	{
	 		$COMPANY_ID 			= $post_data['company_id'];
	 		$COMPANY_ABOUT 		 	= $post_data['company_about'];
	 		$COMPANY_INFO 			= $post_data['company_info'];
	 		$COMPANY_EMAIL 			= $post_data['company_email'];
	 		$COMPANY_PHONE_NO		= $post_data['company_phoneno'];
	 		$COMPANY_WEBSITE 		= $post_data['company_website'];
	 		$COMPANY_ADDRESS		= $post_data['company_address'];
	 		$COMPANY_LOGO_B64 		= $post_data['company_logo_base64'];
	 		$RANDOM_NO				= rand(100000,999999);					
	 		$COMPANY_LOGO_NAME 		= ($COMPANY_LOGO_B64!=''? md5($RANDOM_NO.date('Y-m-d H:i:s')).'.jpg':null);
	 		$COMPANY_BANNER_B64 	= $post_data['company_banner_base64'];
	 		$RANDOM_NO				= rand(100000,999999);					
	 		$COMPANY_BANNER_NAME 	= ($COMPANY_BANNER_B64!=''? md5($RANDOM_NO.date('Y-m-d H:i:s')).'.jpg':null);
	 		

	 		$data = array(	"COMPANY_ABOUT" 			=> trim($COMPANY_ABOUT),
							"INFO"						=> trim($COMPANY_INFO),
							"COMPANY_ADRESS" 			=> trim($COMPANY_ADDRESS),
							"COMPANY_PHONE_NO"			=> trim($COMPANY_PHONE_NO),
							"COMPANY_EMAIL"				=> trim($COMPANY_EMAIL),
							"COMPANY_WEBSITE"			=> trim($COMPANY_WEBSITE)
						);
	 		if($COMPANY_ID!="" || $COMPANY_ID!=NULL)  // COMPANY ID IS EXIST
	 		{
	 			if($COMPANY_LOGO_B64!='' && !is_null($COMPANY_LOGO_B64))
	 			{
	 				$data['COMPANY_LOGO_NAME'] = $COMPANY_LOGO_NAME;
	 				$logoupload = $this->SaveBase64($COMPANY_LOGO_B64,$COMPANY_LOGO_NAME,COMPANY_LOGO_PATH);
		 		}
		 		if($COMPANY_BANNER_B64!='' && !is_null($COMPANY_BANNER_B64))
	 			{
	 				$data['COMPANY_BANNER_NAME'] = $COMPANY_BANNER_NAME;
	 				$bannerupload = $this->SaveBase64($COMPANY_BANNER_B64,$COMPANY_BANNER_NAME,COMPANY_BANNER_PATH);
		 		}
	 			$query_update = $this->db->where("COMPANY_ID",$COMPANY_ID);		
			    $query_update = $this->db->update('COMPANY',$data);				    
			    $updated_rows = $this->db->affected_rows();			    
			   
			    if($updated_rows==0 || $updated_rows>0)
			    {
			    	$result 	= array('data'=> array('updated_rows'=>$updated_rows),
			    						'message'=>'Company details updated',
			    						'status'=>'Success'
			    						);
			    	return $result;
			    }
	 		}
	 		else
	 		{

				$result 	= array('data'=>array(),
									'message' =>'Company id is blank or null',
									'status'=>'Failure'
									);
				return $result;					 			
		 	}
	 		
	 	}
	 	public function ChangeCompanyUserPassword($post_data)
	 	{
	 		$COMPANY_ID		= $post_data['company_id'];
	 		$USER_ID 		= $post_data['user_id'];
	 		$OLD_PWD 		= $post_data['old_password'];
	 		$NEW_PWD 		= $post_data['new_password'];

	 		$data 			= array("USER_PWD"=> md5($NEW_PWD));

			$this->db->where("USER_ID",$USER_ID);
			$this->db->where("USER_PWD",md5($OLD_PWD));
			$this->db->where("USER_COMPANY_ID",$COMPANY_ID);
			$this->db->update('USERS',$data);			
			//echo $this->db->last_query();	 	    
			$affected_rows = $this->db->affected_rows();			    
			if($affected_rows>0)
			{
				$result = array('data'=> array('updated_rows'=>$updated_rows),
	    						'message'=>'Password changed successfully',
	    						'status'=>'Success'
	    						);
			}
			else{
				$result = array('data'=> array(),
	    						'message'=>'Password not changed',
	    						'status'=>'Failure'
	    						);	
			}
			return $result;
	 	}
	 	public function RegisterCompany($post_data)
	 	{
	 		$this->load->model('MasterModel');
	 		$COMPANY_NAME			= $post_data['company_name'];
	 		$COMPANY_ADDRESS 		= $post_data['company_address'];
	 		$COMPANY_COUNTRY_CODE 	= getCountryId($post_data['company_country_code']);
	 		$COMPANY_PHONE 			= $post_data['company_phone'];
	 		$COMPANY_EMAIL 			= $post_data['company_email'];
	 		$COMPANY_ACTIVE 		= 0;
	 		$COMPANY_CODE 			= $this->MasterModel->generateCompanyCode();

	 		$data=array(
						"COMPANY_CODE" 			=> trim($COMPANY_CODE), 
						"COMPANY_NAME" 			=> trim($COMPANY_NAME),
						"COMPANY_ADRESS" 		=> trim($COMPANY_ADDRESS),
						"COMPANY_COUNTRY_ID" 	=> trim($COMPANY_COUNTRY_CODE),
						"COMPANY_PHONE_NO"	 	=> trim($COMPANY_PHONE),
						"COMPANY_EMAIL" 		=> trim($COMPANY_EMAIL),
						"COMPANY_ACTIVE" 		=> $COMPANY_ACTIVE,
						);
	 		$this->db->insert("COMPANY",$data);
			$data_id = $this->db->insert_id();	
			if($data_id)
			{
		    	$result = array('data'=> array('inserted_id'=>$data_id),
	    						'message'=>'Registration completed',
	    						'status'=>'Success'
	    						);
			}
			else
			{
				$result 	= array('data'=> array(),
		    						'message'=>'Registration failed',
		    						'status'=>'Failed'
		    						);
			}
		    return $result;
	 	}
	}
?>
