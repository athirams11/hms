<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class AdminUserModel extends CI_Model {
	

	public function check_login() 
	{			

		$this->load->library("encryption");
		$username = $this->input->post("username");		
	    $password = trim($this->input->post("password"));				
		$this->db->from("USERS");		
		$this->db->where("USER_LOGIN_NAME", $username);
		$this->db->where("BINARY(USER_PWD)", md5($password));		
		$this->db->where("USER_ISACTIVE",1);

		$query = $this->db->get();						 
		if ($query->num_rows() == 1)
		{
			$row = $query->row();
			
			$userWorkingHour = $this->CheckUserWorkingHours($row->USER_ID,$row->USER_COMPANY_ID);
			if($userWorkingHour==false)
			{
				return 7;
			}

			if($row->USER_ISACTIVE == '0' ) 
			{					
				return 2;				
			}
			elseif($row->USER_ISACTIVE == 2 ) 
			{
				return 3;
			}	
			if($row->USER_ISACTIVE == 1 )
			{	

			/***  User Login Ckeck Host Base Start *****/
				if(base_url() == SITE_URL_PA)
					{
						if($row->USER_ROLE_ID == MASTER)
							{
								return 6;
							}
				}
				else if(base_url() == SITE_URL_DASHBOARD)
					{
						if($row->USER_ROLE_ID != COMPANY_MASTER_ADMIN && $row->USER_ROLE_ID != COMPANY_MASTER)
							{
								return 6;
							}	
					}
					else if(base_url() == SITE_URL_MASTER)
						{			
							if($row->USER_ROLE_ID != MASTER )
								{
									return 6;
								}								
						}	
			/*else if(base_url() == SITE_URL_ANYWHERE)
			{
				$this->db->group_start();
				$this->db->or_where("USER_ROLE_ID",MASTER);
				$this->db->or_where("USER_ROLE_ID",FRANCHISE_ADMIN);
				$this->db->group_end();
			}*/	

			/***  User Login Ckeck Host Base end *****/

			/*** @ Sane User Login Restriction Start  *****/
				
			if($row->SESSION_ID != "" && $this->logindelay($row->USER_ID))
					{
						return 5;							
					}
				/***  Sane User Login Restriction End  *****/
	
				if($this->get_menu_items($row->USER_ID, $row->USER_ROLE_ID))
				{

					/***  Number of licence check start   *****/
				
					$company_data=$this->company_details($row->USER_COMPANY_ID);	
					if(is_object($company_data))
					{
						if($row->USER_ROLE_ID != COMPANY_MASTER_ADMIN)
						{
							if($company_data->COMPANY_NO_OF_LICENCE <= $this->loggin_Count($row->USER_COMPANY_ID))
							{
								return 4;
							}							
						}

						$this->session->set_userdata("user_company_name",$company_data->COMPANY_NAME);
						$this->session->set_userdata("timezone",gettimezone($company_data->COMPANY_TIMEZONE_ID));
					}
					else
					{
						
						$this->session->set_userdata("timezone",TIMEZONE);
						
					}			

					/***  Number of licence check end   *****/
				
					$this->set_login($row->USER_ID);


					/***  Set Sesssion Data Start   *****/

					$this->session->set_userdata("admin_logged_in", true);	
					$this->session->set_userdata("alert", "enable");	
					$this->session->set_userdata("admin_user_id", $row->USER_ID);	
					$this->session->set_userdata("admin_username", $row->USER_LOGIN_NAME);	
					$this->session->set_userdata("admin_firstname", $row->USER_NAME);	
					$this->session->set_userdata("admin_lastname", "");			
					$this->session->set_userdata("admin_email", $row->USER_EMAIL);	
					$this->session->set_userdata("admin_department_level_1", $row->DEPARTMENT_LEVEL1_ID);
					$this->session->set_userdata("admin_department_level_2", $row->DEPARTMENT_LEVEL2_ID);
					$this->session->set_userdata("user_franchise_id", $row->USER_FRANCHISE_ID);
					$this->session->set_userdata("user_sim_contacts", $row->USER_SIMULTANEOUS_CONTACTS);

					$this->session->set_userdata("user_company_id", $row->USER_COMPANY_ID);//company id	
					$this->session->set_userdata("admin_status", $row->USER_ISACTIVE);	
					$this->session->set_userdata("admin_login_type_id", $row->USER_ROLE_ID);	
					$this->session->set_userdata("support_center_id", $row->USER_SUPPORT_CENTER_ID);	
					if($row->USER_SUPPORT_CENTER_ID!=0)
					{
						$support_center_details =   getSupportCenterDetails($row->USER_SUPPORT_CENTER_ID);
						$this->session->set_userdata("support_center_name", $support_center_details->SUPPORT_CENTER_NAME);	
					}
					$this->session->set_userdata("settings_access",1);
					$this->session->set_userdata("admin_access", $this->check_admin_access($this->session->userdata("admin_user_id"),$this->session->userdata("admin_login_type_id")));
					
					$this->session->set_userdata("company_details", $this->set_company_data($row->USER_COMPANY_ID));

					/***  Set Sesssion Data end   *****/

					$reset_login_attempt = $this->reset_user_login_tried_count();							

					log_activity('logged_in','User logged in ');
			
					return 0;	
				
				}
				else 
				{
					return 1;			
				}
			}
			else
			{
					return 1;			
			}
			
		}
		else
			{
				return $this->update_user_login_tried_count();							
			}
		
	}
/**
 Log out and cleare session

**/
	function logout()
	 {
			log_activity('logged_out','User logged Out ');	
			$this->clearSession($this->session->admin_user_id);	
			$this->session->unset_userdata();
			$this->session->sess_destroy();

	}

public function set_company_data($company_id)
	{
		if($company_id)
		{
			$this->db->start_cache();

			$this->db->select("C.*",FALSE);
			$this->db->from("COMPANY C");	
			$this->db->where("COMPANY_ID",$company_id);
			$query = $this->db->get();	
		
			$this->db->stop_cache();
			$this->db->flush_cache();
			log_activity('set_company_data','User company details set in session ');	
			if ($query->num_rows() ==1)
			return $query->row_array();
			else
			return false;
		}
		else
		{
			return false;
		}
	}
public function update_user_login_tried_count()
	{		
	
		$username = $this->input->post("username");		
		$this->db->from("USERS");		
		$this->db->where("USER_LOGIN_NAME", $username);		
		$query = $this->db->get();		
		//echo 'Count-'.$query->num_rows();
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			//UPDATE FALSE LOGIN ATTEMPT COUNT 
			$data = array();
			$this->db->set('TRIED_COUNT', 'TRIED_COUNT+1', FALSE);	
			$this->db->where("USER_LOGIN_NAME", $this->input->post("username"));
			$query = $this->db->update("USERS", $data);										 
			log_activity('update','User tried login USERS :'.$this->input->post("username") );	

			$TRIED_COUNT  = $row->TRIED_COUNT+1;
			if($TRIED_COUNT >= USER_LOGIN_TRIED_COUNT){
				//USER LOGIN ATTEMPT GREATER THAN SPECIFIED COUNT				
				return 3;				
			}else {
				//USER LOGIN ATTEMPT LESS THAN SPECIFIED COUNT				
				return 2;		
			}						 
		}else{													
			return 2;
		}				
	}
	public function reset_user_login_tried_count()
	{		
	
		$username = $this->input->post("username");		
		$this->db->from("USERS");		
		$this->db->where("USER_LOGIN_NAME", $username);		
		$query = $this->db->get();		
		//echo 'Count-'.$query->num_rows();
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			//UPDATE FALSE LOGIN ATTEMPT COUNT 
			$data = array();
			$this->db->set('TRIED_COUNT', 0, FALSE);	
			$this->db->where("USER_LOGIN_NAME", $this->input->post("username"));
			$query = $this->db->update("USERS", $data);										 
		}
		return true;				
	}	
	public function check_user_access_via_internet()
	{						
		$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "";
		if(stristr($host, $GLOBALS['host_range_start']) !== FALSE)
		{
			return true;
		}					
		return false;				
	}
	
	public function update_user_login_tried_reset($user_id)
	{							
		$data = array();
		$this->db->set('TRIED_COUNT', '0', FALSE);	
		$this->db->where("USER_ID", $user_id);		 
		$query = $this->db->update("USERS", $data);			
			//echo $this->db->last_query();				 										
		return 1;					
	}
	
	function send_password($user_email) 
	{
		//$query = $this->db->where("CUSTOMERS_PHONE_NO",$CUSTOMERS_PHONE_NO);	
		$query = $this->db->where("USER_EMAIL",$user_email);	
		$query = $this->db->select("*");
		$query = $this->db->from("USERS");
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{	
			foreach ($query->result() as $row) {
				$encrypted_userid 	= urlencode(encryptor('encrypt',$row->USER_ID));				
				$pwd_rest_url		= base_url()."Reset_pwd/reset/".$encrypted_userid;
		
				/******* SEND FORGOT PASSWORD MAIL TO WHATSAC WEB USER **************/
				$MailData	= array('user_id'=>$row->USER_ID,'user_name'=>$row->USER_NAME,'user_email'=>$row->USER_EMAIL,'reset_url'=>$pwd_rest_url);
				$subject	= "Forgot Whatsac Password";
				$to 		= $row->USER_EMAIL;
				$from_mail	= CUSTOMER_OTP_FROM_MAIL_ID;
				$from_name	= CUSTOMER_OTP_FROM_MAIL_NAME;
				//$body		= "WhatSAC Customer verification OTP <br>".$OTP;
				$body = $this->load->view('email/forgot_password_email.php',$MailData,TRUE);
				$this->load->library('email');
				$this->email->from($from_mail,$from_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->set_mailtype("html");
				$this->email->message($body);
				if($this->email->send())
				{
					return 'MAIL_SEND_TO_USER';
				}
				else
				{
					return 'MAIL_NOT_SEND_TO_USER';

				}
				/******* SEND FORGOT PASSWORD MAIL TO USER **************/
			}
		}
		else
		{
			return "MAIL_ID_NOT_EXIST";
		}
	}


	function add_users()
	{ 
	
		if(!$this->input->post("username")) {
	 		return 3;
		}
		
		
		$data = array(
		"USER_LOGIN_NAME" => $this->input->post("username"),
		"USER_NAME" => $this->input->post("firstname")." ".$this->input->post("lastname"),
		//"LASTNAME" => $this->input->post("lastname"),
		
		"USER_EMAIL" => $this->input->post("email"),
		"USER_ISACTIVE" => $this->input->post("status"),
		"USER_ROLE_ID" => $this->input->post("login_type_id"),
		//"INTERNET_ACCESS" => $this->input->post("internet_access")
		); 
		
		if($this->input->post("password")!=""){
			$data["USER_PWD"]  = md5(trim($this->input->post("password")));
		} 
		
		$this->db->where('USER_ID !=',$this->input->post("user_id"));
		$this->db->group_start();
		$this->db->or_where('USER_LOGIN_NAME',$this->input->post("username"));
		$this->db->or_where('USER_EMAIL',$this->input->post("email"));
		$this->db->group_end();
		$this->db->from('USERS');
		$sql_query =$this->db->get();
		//echo $this->db->last_query();			 				 										
		if ($sql_query->num_rows() == 0) 
		{
		
			if ($this->input->post("user_id") && trim($this->input->post("user_id")) != "") 
			{
				$this->db->trans_begin();
				$this->db->where("USER_ID", $this->input->post("user_id"));
				/*if($this->input->post("status")==1){
				$this->db->set('TRIED_COUNT', '0', FALSE);
				}*/	
				$query = $this->db->update("USERS", $data);
				log_activity('update','User updated in USERS :'.$this->input->post("user_id") );								  			
				if ($this->db->trans_status() === FALSE)
				$this->db->trans_rollback();
				else
				{
					if($this->db->affected_rows() > 0)
					{
						$this->db->trans_commit();
						return 2;
					}
					else
					{
						$this->db->trans_commit();
						return 0;
					}
				}
			} 
			else
			{ 
				
					$this->db->trans_begin();
					$query = $this->db->insert("USERS", $data);
					log_activity('insert','User added in USERS :'.$this->input->post("username") );								  			
					if ($this->db->trans_status() === FALSE)
					$this->db->trans_rollback();
					else
					{
						if ($this->db->affected_rows() > 0)
						{
							$this->db->trans_commit();
							return 1;
						}
						else
						{
							$this->db->trans_commit();
							return 3;
						}
					}

			}
		} 
		else
			return 4;   
	}
	
	
	function update_profile()
	{ 
	
		if(!$this->input->post("user_id")) {
	 		return 3;
		}
 
		$data = array(
		"USER_LOGIN_NAME" => $this->input->post("username"),
		"USER_NAME" => $this->input->post("firstname"),
		//"LASTNAME" => $this->input->post("lastname"),
		
		"USER_EMAIL" => $this->input->post("email"),
		"USER_ISACTIVE" => $this->input->post("status")
		); 
		
		if($this->input->post("password")!=""){
			$data["USER_PWD"]  = md5(trim($this->input->post("password")));
		} 
		
		$this->db->where("USER_ID", $this->input->post("user_id"));
		$query = $this->db->update("USERS", $data);
				
	  return 1;		
	}


	function del_users() 
	{
		return false;
		
		$user_id = $this->input->post("user_id");
		if($this->session->userdata('user_id') == $user_id) {
		return 2;
		}
		

		$this->db->where("USER_SFK", $this->input->post("user_id"));
		$this->db->from('USER_ACTIVITY');
		$sql_query_1 =$this->db->get();

		 
		if ($sql_query_1->num_rows() > 0) 
		{
			return 3;
		}
		
		$this->db->where("USER_ID", $user_id);
		$query = $this->db->delete("USERS");
		log_activity('delete', $this->db->last_query());
		if ($this->db->affected_rows() > 0)
		return true;
		else
		return false;
	}
	public function count_data_list() {
	 	$this->db->select("SQL_CALC_FOUND_ROWS U.*,LT.*",FALSE);
		$this->db->from("USERS U");		
		$this->db->join("ROLES LT", "LT.ROLE_ID = U.USER_ROLE_ID", "LEFT");
		
			if (trim($this->input->post("firstname"))) 
		{
			$this->db->like("U.USER_NAME", trim($this->input->post("firstname"))) ;
		}
		if (trim($this->input->post("username"))) 
		{
			$this->db->like("U.USER_LOGIN_NAME", trim($this->input->post("username"))) ;
		}
		if (trim($this->input->post("email"))) 
		{
			$this->db->like("U.USER_EMAIL", trim($this->input->post("email"))) ;
		}
		if (trim($this->input->post("status"))) 
		{
			$this->db->where("U.USER_ISACTIVE", trim($this->input->post("status"))) ;
		}
		if (trim($this->input->post("login_type_id"))) 
		{
			$this->db->where("U.USER_ROLE_ID", trim($this->input->post("login_type_id"))) ;
		}
		if ($this->input->post("sort_by")) 
		{
		$sort_by = $this->input->post("sort_by");
		$sort_type = $this->input->post("sort_type"); 
		$this->db->order_by("$sort_by $sort_type");
		}
		else
		{
		$this->db->order_by("U.USER_NAME ASC");
		}
	      
        return $this->db->count_all_results();
    }
	function user_listing($limit="", $start="")
	{
		$this->db->select("U.*,LT.*",FALSE);
		$this->db->from("USERS U");		
		$this->db->join("ROLES LT", "LT.ROLE_ID = U.USER_ROLE_ID", "LEFT");
		
		if (trim($this->input->post("firstname"))) 
		{
			$this->db->like("U.USER_NAME", trim($this->input->post("firstname"))) ;
		}
		if (trim($this->input->post("username"))) 
		{
			$this->db->like("U.USER_LOGIN_NAME", trim($this->input->post("username"))) ;
		}
		if (trim($this->input->post("email"))) 
		{
			$this->db->like("U.USER_EMAIL", trim($this->input->post("email"))) ;
		}
		if ($this->input->post("status")!='') 
		{
			$this->db->where("U.USER_ISACTIVE", trim($this->input->post("status"))) ;
		}
		if (trim($this->input->post("login_type_id"))) 
		{
			$this->db->where("U.USER_ROLE_ID", trim($this->input->post("login_type_id"))) ;
		}
		if ($this->input->post("sort_by")) 
		{
		$sort_by = $this->input->post("sort_by");
		$sort_type = $this->input->post("sort_type"); 
		$this->db->order_by("$sort_by $sort_type");
		}
		else
		{
		$this->db->order_by("U.USER_NAME ASC");
		}
		if($limit != "" || $start != "") {
			$this->db->limit($limit, $start);
			
		}
	      
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		return $query->result_array();
		else
		return false;
	}

	
	function get_user() 
		{ 
			$user_id = $this->input->post("user_id");
			$this->db->where("USER_ID", $user_id);
			$this->db->from("USERS");
			$query = $this->db->get();				
			return $query->row();
		}
		
	function get_login_type() 
	{

		$this->db->order_by("ROLE_NAME","ASC");
		$query = $this->db->get("ROLES");
		if ($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	
	function get_user_rights($page_name,$login_id,$login_type_id)
	{
 		
			$this->db->start_cache();
		
			$this->db->select("U.VIEW_DATA,U.ADD_DATA,U.EDIT_DATA,U.DELETE_DATA,U.APPROVAL");		
			$this->db->from("USER_RIGHTS U");
			$this->db->join("MODULE M", "M.MODULE_SPK = U.MODULE_SFK", "left");
			$this->db->where("UPPER(M.CONTROLLER_NAME)",strtoupper($page_name));
			//$this->db->where("M.MODULE_TYPE",'admin');
			$this->db->where("U.LOGIN_TYPE_SFK",$login_type_id);	
			$query = $this->db->get();
			// echo $this->db->last_query();				 
			$this->db->stop_cache();
			$this->db->flush_cache();
	 
		$user_rights=array('ADD'=>0,
						   'EDIT'=>0,
						   'DELETE'=>0,
						   'VIEW'=>0,
						   'APPROVE'=>0
						   );
						   
		if ($query->num_rows() > 0)
		{	
			$rights = $query->row();
			$user_rights['ADD'] =$rights->ADD_DATA;
			$user_rights['EDIT'] =$rights->EDIT_DATA;
			$user_rights['DELETE'] =$rights->DELETE_DATA;
			$user_rights['VIEW'] =$rights->VIEW_DATA;
			$user_rights['APPROVE'] =$rights->APPROVAL;
		}		
		
		return $user_rights;				
	}	

	function get_module_details($page_name)
	{
 		
			$this->db->start_cache();
		
			$this->db->select("M.*");		
			$this->db->from("MODULE M");
			
			$this->db->where("UPPER(M.CONTROLLER_NAME)",strtoupper($page_name));
			
			$query = $this->db->get();
			// echo $this->db->last_query();				 
			$this->db->stop_cache();
			$this->db->flush_cache();
	 
			if ($query->num_rows() > 0)
			{	
				return $query->row_array();
			}	
			else
			{
				return false;
			}	
					
	}	
	function get_user_rights_tab($page_name,$login_id,$login_type_id)
	{
 		
			$this->db->start_cache();
		
			$this->db->select("U.VIEW_DATA,U.ADD_DATA,U.EDIT_DATA,U.DELETE_DATA,U.APPROVAL");		
			$this->db->from("USER_RIGHTS U");
			$this->db->join("MODULE M", "M.MODULE_SPK = U.MODULE_SFK", "left");
			$this->db->where("UPPER(M.CONTROLLER_NAME)",strtoupper($page_name));
			$this->db->where("M.MODULE_TYPE",'tab');
			$this->db->where("U.LOGIN_TYPE_SFK",$login_type_id);	
			$query = $this->db->get();
			// echo $this->db->last_query();				 
			$this->db->stop_cache();
			$this->db->flush_cache();
	 
		$user_rights=array('ADD'=>0,
						   'EDIT'=>0,
						   'DELETE'=>0,
						   'VIEW'=>0,
						   'APPROVE'=>0
						   );
						   
		if ($query->num_rows() > 0)
		{	
			$rights = $query->row();
			$user_rights['ADD'] =$rights->ADD_DATA;
			$user_rights['EDIT'] =$rights->EDIT_DATA;
			$user_rights['DELETE'] =$rights->DELETE_DATA;
			$user_rights['VIEW'] =$rights->VIEW_DATA;
			$user_rights['APPROVE'] =$rights->APPROVAL;
		}		
		
		return $user_rights;				
	}	
	
	
	function get_menu_items($login_id,$login_type_id)
	{
 		
			$this->db->start_cache();
		
			$this->db->select("M.MODULE_NAME,M.CONTROLLER_NAME,M.ICON_NAME,M.MODULE_TYPE");		
			$this->db->from("USER_RIGHTS U");
			$this->db->join("MODULE M", "M.MODULE_SPK = U.MODULE_SFK", "left");
			//$this->db->where("MODULE_TYPE",'admin');	
			$this->db->where("M.STATUS",1);
			$this->db->where("M.SHOW_MENU",1);
			$this->db->where("U.VIEW_DATA",1);	
			$this->db->where("U.LOGIN_TYPE_SFK",$login_type_id);	
			$this->db->order_by("order",'ASC');	
			$query = $this->db->get();
			 // echo $this->db->last_query();				 
			$this->db->stop_cache();
			$this->db->flush_cache();
	 

						   
		if ($query->num_rows() > 0)
		{	
			return $query->result();
		}		
		
		 				
	}	
	
	function check_admin_access($login_id, $login_type_id) 
	{
			$this->db->select("M.MODULE_NAME,M.CONTROLLER_NAME,M.ICON_NAME");		
			$this->db->from("USER_RIGHTS U");
			$this->db->join("MODULE M", "M.MODULE_SPK = U.MODULE_SFK", "left");
			$this->db->where("MODULE_TYPE",'admin');	
			$this->db->where("M.STATUS",1);	
			$this->db->where("U.VIEW_DATA",1);	
			$this->db->where("U.LOGIN_TYPE_SFK",$login_type_id);	
			$this->db->order_by("order",'ASC');	
			$query = $this->db->get();
			//	  echo $this->db->last_query();		  exit;		   
			if ($query->num_rows() > 0)
			{
				return true;
			}	
		return false;
	}
	
	#function count_user_activity_listing()
	function count_user_activity_listing()
	{
		$this->db->select("COUNT(*) as RECOUNT ",FALSE);
		$this->db->from("USER_ACTIVITY UA");						
		
		
		if (trim($this->input->post("username")!='') || trim($this->input->post("login_type_id")!='')) 
		{
			$this->db->join("USERS U", "UA.USER_SFK= U.USER_ID", "LEFT");
			if (trim($this->input->post("username"))){
				$this->db->like("upper(U.USER_LOGIN_NAME)", strtoupper( trim($this->input->post("username")) ) ) ;
			}
			if (trim($this->input->post("login_type_id"))!=''){
				$this->db->where("upper(U.USER_ROLE_ID)", $this->input->post("login_type_id") ) ;
			}
		}
		
		if (trim($this->input->post("subscriber_number"))) 
		{
			$this->db->like("upper(UA.PHONE_NUMBER)", strtoupper( trim($this->input->post("subscriber_number")) ) ) ;
		}

		if (trim($this->input->post("activity_date_from"))) 
		{
			$this->db->where(" date_format(UA.ACTIVITY_TIME, '%Y-%m-%d %H:%i') >= ", date('Y-m-d H:i',strtotime($this->input->post("activity_date_from"))) ) ;
		}
		if (trim($this->input->post("activity_date_to"))) 
		{
			$this->db->where(" date_format(UA.ACTIVITY_TIME, '%Y-%m-%d %H:%i') <= ", date('Y-m-d H:i',strtotime($this->input->post("activity_date_to"))) ) ;
		}
		
		$query = $this->db->get();	
			//echo $this->db->last_query();	exit;	
		if ($query->num_rows() > 0){
			return $query->row()->RECOUNT;
		}else{
			return 0;
		}
	}
	
	function user_activity_listing($limit="", $start="")
	{
		$this->db->select("U.*,UA.*",FALSE);
		$this->db->from("USER_ACTIVITY UA");
		$this->db->join("USERS U", "UA.USER_SFK= U.USER_ID", "LEFT");						
		if (trim($this->input->post("username"))!='')
		{
			$this->db->like("upper(U.USER_LOGIN_NAME)", strtoupper( trim($this->input->post("username")) ) ) ;
		}
		if (trim($this->input->post("login_type_id"))!=''){
				$this->db->where("upper(U.USER_ROLE_ID)", $this->input->post("login_type_id") ) ;
		}
		if (trim($this->input->post("subscriber_number"))!='') 
		{
			$this->db->like("upper(UA.PHONE_NUMBER)", strtoupper( trim($this->input->post("subscriber_number")) ) ) ;
		}
		if (trim($this->input->post("activity_date_from"))!='')
		{
			$this->db->where(" date_format(UA.ACTIVITY_TIME, '%Y-%m-%d %H:%i') >= ", date('Y-m-d H:i',strtotime($this->input->post("activity_date_from"))) ) ;
		}
		if (trim($this->input->post("activity_date_to"))!='') 
		{
			$this->db->where(" date_format(UA.ACTIVITY_TIME, '%Y-%m-%d %H:%i') <= ", date('Y-m-d H:i',strtotime($this->input->post("activity_date_to"))) ) ;
		}
		
		if ($this->input->post("sort_by")) 
		{
		$sort_by = $this->input->post("sort_by");
		$sort_type = $this->input->post("sort_type"); 
		$this->db->order_by("$sort_by $sort_type");
		}
		else
		{
			$this->db->order_by("UA.ACTIVITY_TIME DESC");
		}
	      
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		return $query->result_array();
		else
		return false;
	}
	
	
	
	public function check_activity_log_maximum_period()
	{
		$this->db->select("*",FALSE);
		
		if (trim($this->input->post("activity_date_from"))) 
		{
			$date_check = date('Y-m-d',strtotime($this->input->post("activity_date_from")))  ;
			if (trim($this->input->post("activity_date_to"))) 
			{
				$date_check_from = date('Y-m-d',strtotime($this->input->post("activity_date_from")))  ;
				$date_check_to = date('Y-m-d',strtotime($this->input->post("activity_date_to")))  ;
				$SQL = "SELECT TIMESTAMPDIFF(DAY, '".$date_check_from."','".$date_check_to."' ) as DAYS";
			}
		}
		
		
		$query = $this->db->query($SQL);
	 
	//	echo $this->db->last_query();exit;
		$check_day = (get_app_setting('activity_log_maximum_period') ?: 1);			
		if($query->row()->DAYS <=  $check_day ){			
			return false;
		}else {			
			return $check_day; 
		}
	}
	
	
	function get_user_activity($act_id)
	{
		$this->db->select("U.USER_LOGIN_NAME, U.USER_NAME, U.LASTNAME, UA.*",FALSE);
		$this->db->from("USER_ACTIVITY UA");						
		$this->db->join("USERS U", "UA.USER_SFK= U.USER_ID", "LEFT");

		$this->db->where("USER_ACTIVITY_SPK", $act_id ) ;
		
		$query = $this->db->get();	
			//	echo $this->db->last_query();			
		return $query->row();
	}
	
	
	function admin_user_dropdown() 
	{
			
			$this->db->order_by("USER_LOGIN_NAME","asc");
			$query = $this->db->get("USERS");
			if ($query->num_rows() > 0)
				return $query->result();
			else
				return false;
	}
	
	
	function user_provinces_listing()
	{
		$this->db->select("UP.*,P.PROVINCE_NAME,AU.USER_ID,AU.USER_LOGIN_NAME,AU.USER_NAME,AU.LASTNAME",FALSE);
		$this->db->from("USERS_PROVINCES UP");		
		$this->db->join("PROVINCES P", "P.PROVINCE_SPK = UP.PROVINCE_SFK", "LEFT");		
		$this->db->join("USERS AU", "AU.USER_ID = UP.USER_SFK", "LEFT");		
		$this->db->order_by("AU.USER_LOGIN_NAME ASC");		 
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		return $query->result_array();
		else
		return false;
	}
	
	
	function add_user_province()
	{		
	  
		$this->db->where("USER_SFK", $this->input->post("user_id"));
		$query = $this->db->delete("USERS_PROVINCES");
 
		if ($this->db->affected_rows() >= 0)
		{
				$arr_province = $this->input->post('province_id'); 		 
				for ($i = 0; $i < count($arr_province); $i++)
				{			 
					$data = array(												
								'USER_SFK' => $this->input->post("user_id"),
								'PROVINCE_SFK' => $arr_province[$i],
								);					
					$this->db->insert('USERS_PROVINCES', $data);
						
					log_activity('insert', $this->db->last_query(), $data); 
				}
		 }
		 return 1;
	}
	
 
	public function get_user_province()
	{ 		
			
			$this->db->select("UP.*,( concat((GROUP_CONCAT(UP.PROVINCE_SFK)),',') ) AS PROVINCE_ID ");
			$this->db->from("USERS_PROVINCES UP");			
			$this->db->where("UP.USER_SFK", $this->input->post("user_id"));					
 			$query = $this->db->get();	 	
 			//  echo $this->db->last_query();		
			return $query->row();
	}
	
	public function get_user_province_ids($user_id)
	{ 		
			
			$this->db->select("( concat((GROUP_CONCAT(UP.PROVINCE_SFK))) ) AS PROVINCE_ID ");
			$this->db->from("USERS_PROVINCES UP");			
			$this->db->where("UP.USER_SFK", $user_id);					
 			$query = $this->db->get();	 	
 			//  echo $this->db->last_query();		
			return $query->row()->PROVINCE_ID;
	}
	
	public function get_user_province_data($user_id)
	{ 		
			
			$this->db->select("UP.PROVINCE_SFK AS PROVINCE_ID, P.PROVINCE_NAME, P.PROVINCE_MAP_CODE ");
			$this->db->from("USERS_PROVINCES UP");		
			$this->db->join("PROVINCES P", "P.PROVINCE_SPK = UP.PROVINCE_SFK", "LEFT");
			$this->db->where("UP.USER_SFK", $user_id);					
 			$query = $this->db->get();	 	
 			  //echo $this->db->last_query();
			return $query->result();
	}
	
	
	public function del_user_province()
	{ 		
			
		$this->db->where("USER_SFK", $this->input->post("user_id"));
		$query = $this->db->delete("USERS_PROVINCES");
		return 1;
	}
	
	public function get_active_users()
	{ 					
			$this->db->from("USERS");
			$this->db->where("USER_ISACTIVE", "1");					
			$query = $this->db->get();												
 			return $query->result();
	}
	
	public function update_phonenumber()	
	{
		
		$this->db->select("USER_ACTIVITY_SPK,DATA_JSON",FALSE);
		$this->db->from("USER_ACTIVITY UA");						 
		$this->db->where("PHONE_NUMBER is null ",null);						 
		$this->db->like("MODULE","manage/");						 
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		$arr = $query->result();
		set_time_limit(0);
		foreach($arr as $row){
						$data = json_decode($row->DATA_JSON, true);					

						$number =  isset($data["SUBSCRIBER_NUMBER"]) ?  $data["SUBSCRIBER_NUMBER"] : (isset($data["PrimaryIdentity"]) ?  $data["PrimaryIdentity"] : (isset($data["subscriber_number"]) ?  $data["subscriber_number"] : (isset($data["msisdn"]) ?  $data["msisdn"] : '')));
						if($number!=''){
							$updata = array(
										"PHONE_NUMBER" => substr($number,-9)	
										);
							$this->db->where("USER_ACTIVITY_SPK",  $row->USER_ACTIVITY_SPK);
							$query = $this->db->update("USER_ACTIVITY", $updata);
							echo $row->USER_ACTIVITY_SPK.'--'.'<br>';	
						}
						
		}		
	}
	
	function get_user_summery($act_id="")
	{
		$this->db->select("U.USER_LOGIN_NAME, U.USER_NAME, U.LASTNAME, UA.*",FALSE);
		$this->db->from("USER_ACTIVITY UA");						
		$this->db->join("USERS U", "UA.USER_SFK= U.USER_ID", "LEFT");
		if($act_id)
		{
			$this->db->where("USER_ACTIVITY_SPK", $act_id );
			$query = $this->db->get();	
			return $query->row();			
		}
		else
		{
			$query = $this->db->get();	
			return $query->result_array();	
		}
		//	echo $this->db->last_query();	
	}


	//get company name
function company_details($id=0)
{
		$this->db->start_cache();
		$this->db->select("*");
		$this->db->from("COMPANY");
		$this->db->where("COMPANY_ID",$id);
		$query = $this->db->get();
		$this->db->stop_cache();
		$this->db->flush_cache();
		
		if ($query->num_rows() ==1) 
		{
			return  $query->row();
			
		}			
		else
			return;
}
function get_user_details($user_id) 
{ 
	$this->db->where("USER_ID", $user_id);
	$this->db->from("USERS");
	$query = $this->db->get();
	//print_r($query->row());				
	return $query->row();
}

	public function reset_password()
	{
		$this->load->library("encryption");
		$user_email 				= trim($this->input->post("username"));		
		$encrypted_userid	 	= trim($this->input->post("hdnid"));		
		$password 				= md5(trim($this->input->post("password")));				

		$decrypted_user_id 	= encryptor('decrypt',urldecode($encrypted_userid));

		$this->db->from("USERS");		
		$this->db->where("USER_ID", $decrypted_user_id);
		$query = $this->db->get();		
		if ($query->num_rows() > 0)
		{		
			$data 	= array("USER_PWD" => $password); 				
			$this->db->where("USER_ID",$decrypted_user_id);
			$this->db->update("USERS",$data);
			//echo $afftectedRows = $this->db->affected_rows();
			//echo $this->db->last_query();		
			return 1;
		}
		else
		{
			return 0;
		}
	}
/***** Check No Of License **********/

	public function loggin_Count($company_id = "")
	{
			$this->db->start_cache();

			$this->db->select("COUNT(*) AS LCOUNT",FALSE);
			$this->db->from("USERS");	
			$this->db->where("USER_COMPANY_ID",$company_id);
			$this->db->where("USER_ROLE_ID !=",COMPANY_MASTER_ADMIN);
			$this->db->where("USER_ISACTIVE",1);
			$this->db->where("LOGIN_ACTIVE_DATE_TIME > DATE_SUB(NOW(), INTERVAL ".LOGIN_CHECK_MIN." MINUTE)");
			$this->db->where("LOGIN_STATUS",1);
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			//echo $this->db->last_query();
			if ($query->num_rows() ==1)
			return $query->row()->LCOUNT;
		
	}
	
	/*
	*
 	* Function checking connect status
 	*
 	*/

public function set_login($user_id = "")
	{
			if($user_id == ""){$user_id = $this->session->admin_user_id;}
			$this->load->library('user_agent');
			$agent = $this->agent->platform().' '.$this->agent->browser().' '.$this->agent->version();
			$ip_address = $this->input->ip_address();

			$updatedata = array(
							        'SESSION_ID'  	=> $this->session->session_id,
							        'IP_ADDRESS' 	=> $ip_address,
							        'BROWSER_AGENT' => $agent,
							        'LOGIN_STATUS'  => 1
								 );

			$this->db->trans_begin();
			$this->db->set("LOGIN_ACTIVE_DATE_TIME","NOW()",FALSE);
			$this->db->where("USER_ID", $user_id);
			$query = $this->db->update("USERS",$updatedata);
			log_activity('update', $this->db->last_query(), $user_id);
			if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
			else
			{
				
				if($this->db->affected_rows() > 0)
				{	$this->db->trans_commit();
					return 2;
				}
				else
				{
					$this->db->trans_commit();
					return 0;
				}
			}			
		
	}

	/**
	*
	* Function for Check user loging in in a system
	*@param call by value user id
	*@return bool
	**/
	public function logindelay($userId = "")
		{
			$this->db->start_cache();
			$this->db->select("*",FALSE);
			$this->db->from("USERS");
			$this->db->where("USER_ID",$userId);	
			$this->db->where("LOGIN_ACTIVE_DATE_TIME > DATE_SUB(NOW(), INTERVAL ".LOGIN_CHECK_MIN." MINUTE)");
			$query = $this->db->get();	
			$this->db->stop_cache();
			$this->db->flush_cache();
			if ($query->num_rows() == 1)
				return true;
			else 
				return false;
		
		}


	/**
	*
	* Function for clear Session Id from Table
	*@param call by value user id
	*@return bool
	**/
	private function clearSession($userId = 0)
	{
			$this->db->trans_begin();
				$updatedata = array(
							        'SESSION_ID'  	=> NULL,	
							        'LOGIN_STATUS'  => 0
								 );
			$this->db->where("USER_ID", $userId);
			$query = $this->db->update("USERS",$updatedata);
			log_activity('update', $this->db->last_query(), $user_id);
			if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
			else
			{
				
				if($this->db->affected_rows() > 0)
				{	$this->db->trans_commit();
					return 2;
				}
				else
				{
					$this->db->trans_commit();
					return 0;
				}
			}			

	} 
	// VALIDATE COMPANY ADMINISTRATOR OR OPERATOR CAN LOGIN HIS/HER ALLOWED DAY AND TIME
	public function CheckUserWorkingHours($userId=0,$companyId=0)
	{
		$this->db->start_cache();
		$this->db->select("*");
		$this->db->from("USER_OFFICE_HOUR");
		$this->db->where("USER_HOUR_USER_ID",$userId);	
		$this->db->where("USER_HOUR_COMPANY_ID",$companyId);			
		$query = $this->db->get();	
		$this->db->stop_cache();
		$this->db->flush_cache();
		//echo $this->db->last_query().'<br>';
		//echo $query->num_rows().'<br>';
		if ($query->num_rows() > 0) // SCHEDULED HOUR WORKING 
		{
			$COMPANY_TIMEZONE_ID 	 = getCompanytimezone( $companyId); //GET COMPANY TIMEZONE
			$LOGIN_DATE_TIME		 = DBTimeFormater("",date("Y-m-d H:i:s O"),$COMPANY_TIMEZONE_ID,"Y-m-d H:i:s"); // GET COMPANY'S TIMEZONE DATE AND TIME

			$LOGIN_TIMESTAMP = strtotime($LOGIN_DATE_TIME);
			$DAY_OF_THE_WEEK = strtoupper(date("l", $LOGIN_TIMESTAMP)); //GET DAY NAME

			$SCHEDULED_DAY 	 = 0;	//FLAG FOR SCHEDULED DAY 0= FALSE, 1= TRUE;
			foreach ($query->result() as $row) 
			{
				if($row->USER_HOUR_WEEKDAYS==$DAY_OF_THE_WEEK)
				{

					$START_TIME 	= strtotime($row->USER_HOUR_START_TIME);
					$END_TIME 		= strtotime($row->USER_HOUR_END_TIME);
					$LOGIN_TIME 	= strtotime(date('H:i',$LOGIN_TIMESTAMP));
					$SCHEDULED_DAY 	= 1;
					// LOGIN TIME IS BETWEEN ALLOWED TIME
					if($LOGIN_TIME >=$START_TIME && $LOGIN_TIME <= $END_TIME )
						return true;
					else
						return false;
				}				
			}			
			if($SCHEDULED_DAY ==0)
				return false;
		}
		else  // 24 HOUR WORKING
			return true;  
	}
}
?>