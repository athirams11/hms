<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class AdminUserModel extends CI_Model {
	

	public function check_login($post_data=array()) 
	{	
		$result     = array("status"=> "Failed","message"=>"Failed to Login. Invalid Credentials !!!");		
		if(is_array($post_data) && !empty($post_data))
		{
			if($post_data["log_user"] != "" && $post_data["log_pass"] != "" )
			{
				$this->load->library("encryption");
				$username = $post_data["log_user"];		
			    $password = trim($post_data["log_pass"]);				
				$this->db->select("U.*,UA.USER_DEFAULT_PAGE");		
				$this->db->from("USERS U");		
				$this->db->join("USER_ACCESS_GROUP UA","UA.USER_ACCESS_GROUP_ID = U.USER_ACCESS_TYPE","left");		
				$this->db->where("USERNAME", $username);
				$this->db->where("BINARY(PASSWORD)", md5($password));		
				$this->db->where("STATUS",1);

				$query = $this->db->get();	
			}
								 
			if ($query->num_rows() == 1)
			{
				$api_pass = uniqid(); 
				$this->db->where("USERNAME", $username);
				$this->db->where("BINARY(PASSWORD)", md5($password));
				$this->db->update("USERS",array("API_PASSWORD"=>$api_pass,"LAST_LOGIN"=>date('Y-m-d H:i:s')));


				$response_array = array();
				$row = $query->row();
				$response_array["user"] = $row->USERNAME;
				$response_array["first_name"] = $row->FIRSTNAME;
				$response_array["last_name"] = $row->LASTNAME;
				$response_array["user_id"] = $row->USER_SPK;
				$response_array["user_email"] = $row->EMAIL;
				$response_array["user_group"] = $row->USER_ACCESS_TYPE;
				$response_array["user_login"] = $row->USER_ACCESS_TYPE;
				$response_array["redirect_page"] = $row->USER_DEFAULT_PAGE;
				$response_array["api_key"] = $row->PASSWORD;
				$response_array["department"] = $row->DEPARTMENT_ID;
				$response_array["default_date"] = $this->getSettingsDate();
				$result["status"]="Success";
				$result["message"]="Logged in Successfully.";
				$result["data"]=$response_array;

				if($row->USER_ACCESS_TYPE == 4)
				{
					$result['doctor_department'] = $this->getDepartment($row->USER_SPK);
				}
				else
				{
					$result['doctor_department'] = [];
				}
			}
		}
		return $result;	
	}
	public function getSettingsDate()
	{
		//if(CUSTOM_DATE == true){
			$this->db->start_cache();

			$this->db->select("CUSTOM_DATE AS DF,CUSTOM_DATE_STATUS",FALSE);
			$this->db->from("INSTITUTION_SETTINGS");	
			
			$query = $this->db->get();	
		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if( $query->row()->CUSTOM_DATE_STATUS ==1)
				return $query->row()->DF;
			else
				return null;
		//}
		//else
		//{
			//return null;
		//}
	}

	public function getDepartment($user_id)
	{
		//if(CUSTOM_DATE == true){
			$this->db->start_cache();

			$this->db->select("*");
			$this->db->from("DOCTORS D");	
			$this->db->where("D.LOGIN_ID",$user_id);	
			
			$query = $this->db->get();	
		
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() ==1)
			{
				$this->db->start_cache();

				$this->db->select("O.*,D.DOCTORS_ID,D.DEPARTMENT_ID");
				$this->db->from("DOCTORS_DEPARTMENTS D");	
				$this->db->join("OPTIONS O","O.OPTIONS_ID = D.DEPARTMENT_ID AND D.DOCTORS_DEPARTMENTS_STATUS = 1","left");	
				$this->db->where("D.DOCTORS_ID",$query->row()->DOCTORS_ID);	
				
				$rel = $this->db->get();	
			
				$this->db->stop_cache();
				$this->db->flush_cache();
				if($rel->num_rows() > 0)
				{
					return $rel->result_array();
				}
			}
			else
				return null;
		//}
		//else
		//{
			//return null;
		//}
	}

/**
 Log out and cleare session

**/
	function logout()
	 {
			//log_activity('logged_out','User logged Out ');	
			$this->clearSession($this->session->admin_user_id);	
			$this->session->unset_userdata();
			$this->session->sess_destroy();

	}


	function add_users($post_data)
	{ 
		$result     = array("status"=> "Failed","message"=>"Failed to Create. Invalid Values !!!");		
		if(is_array($post_data) && !empty($post_data))
		{
			if(!$post_data["username"]) {
		 		return $result;
			}
			
			
			$data = array(
				"USERNAME" => $post_data["username"],
				"FIRSTNAME" => $post_data["firstname"],
				"LASTNAME" => $post_data["lastname"],
				"USER_ACCESS_TYPE" =>$post_data["useraccesstype"],
				"EMAIL" =>$post_data["email"],
				"STATUS" => $post_data["status"],
				"CLIENT_DATE" => date('Y-m-d H:i:s',strtotime($post_data["client_date"]))
			); 
			
			if($post_data["password"]!=""){
				$data["PASSWORD"]  = md5(trim($post_data["password"]));
			} 
			// if($post_data["department_id"] > 0){
			// 	$data["DEPARTMENT_ID"]  = $post_data["department_id"];
			// } 
			
			$this->db->where('USER_SPK !=',trim($post_data["user_id"]));
			$this->db->group_start();
			$this->db->or_where('USERNAME',$post_data["username"]);
			$this->db->or_where('EMAIL',$post_data["email"]);
			$this->db->group_end();
			$this->db->from('USERS');
			$sql_query =$this->db->get();
			//echo $this->db->last_query();			 				 										
			if ($sql_query->num_rows() == 0) 
			{
			
				if ($post_data["user_id"] && trim($post_data["user_id"]) != "") 
				{
					if($post_data["useraccesstype"]==4){
						$this->db->select("D.LOGIN_ID");
						$this->db->from("DOCTORS D");
						$this->db->where('DOCTORS_ID !=',$post_data["doctor_id"]);		
						$this->db->where('LOGIN_ID IS NOT NULL');	
						$this->db->where('LOGIN_ID',$post_data["user_id"]);	
						$sql_query_1 =$this->db->get();
						if ($sql_query_1->num_rows() > 0) 
							return array("status"=> "Failed","message"=>"Another login already assigned for selected doctor !!!");	
					    else{
						$this->db->trans_begin();
					$this->db->where("USER_SPK", $post_data["user_id"]);
					$query = $this->db->update("USERS", $data);
					log_activity('update','User updated in USERS :'.$this->input->post("user_id") );	
					$data = array(
				        		"LOGIN_ID" => $post_data["user_id"]
			              	); 
					    	$this->db->where("DOCTORS_ID",$post_data["doctor_id"]);
					    	$this->db->update("DOCTORS",$data);							  			
					if ($this->db->trans_status() === FALSE)
					{
						$this->db->trans_rollback();
						$result     = array("status"=> "Failed","message"=>"Failed to update user data !!!");
						return $result;	
					}
					else
					{
						$this->db->trans_commit();
						$result     = array("status"=> "Success","message"=>"User details updated sucessfully !!!");
						return $result;	
						
						
					}
				}
				}else{
					// if(isset($post_data["department_id"])){
					// 	$data["DEPARTMENT_ID"]  = $post_data["department_id"];
					// } 
					$this->db->trans_begin();
					$this->db->where("USER_SPK", $post_data["user_id"]);
					$query = $this->db->update("USERS", $data);
					log_activity('update','User updated in USERS :'.$this->input->post("user_id") );								  			
					if ($this->db->trans_status() === FALSE)
					{
						$this->db->trans_rollback();
						$result     = array("status"=> "Failed","message"=>"Failed to update user data !!!");
						return $result;	
					}
					else
					{
						$this->db->trans_commit();
						$result     = array("status"=> "Success","message"=>"User details updated sucessfully !!!");
						return $result;	
						
					}
				}
				} 
				else{ 
				   	if($post_data["useraccesstype"]==4) {

				    	$this->db->select("D.LOGIN_ID");
						$this->db->from("DOCTORS D");
						$this->db->where('DOCTORS_ID',$post_data["doctor_id"]);		
						$this->db->where('LOGIN_ID IS NOT NULL');	
						$sql_query_1 =$this->db->get();
						if ($sql_query_1->num_rows() > 0) 
							return array("status"=> "Failed","message"=>"Another login already assigned for selected doctor !!!");	
					    else{
						    	$this->db->trans_begin();
							$query = $this->db->insert("USERS", $data);
							//log_activity('insert','User added in USERS :'.$this->input->post("username") );	
							$data_id = $this->db->insert_id();
							$data = array(
				        		"LOGIN_ID" => $data_id
			              	); 
					    	$this->db->where("DOCTORS_ID",$post_data["doctor_id"]);
					    	$this->db->update("DOCTORS",$data);
														  			
							if ($this->db->trans_status() === FALSE)
							{
								$this->db->trans_rollback();
								$result     = array("status"=> "Failed","message"=>"Failed to create user !!!");
								return $result;	
							}
							else
							{
								if ($this->db->affected_rows() > 0)
								{
									$this->db->trans_commit();
									$result     = array("status"=> "Success","message"=>"User ceated successfully !!!");
									return $result;
								}
								else
								{
									$this->db->trans_commit();
									$result     = array("status"=> "Failed","message"=>"Failed to create user  !!!");
									return $result;
								}
							}
						}
					}
					else{
						$this->db->trans_begin();
							$query = $this->db->insert("USERS", $data);
							//log_activity('insert','User added in USERS :'.$this->input->post("username") );	
														  			
							if ($this->db->trans_status() === FALSE)
							{
								$this->db->trans_rollback();
								$result     = array("status"=> "Failed","message"=>"Failed to create user !!!");
								return $result;	
							}
							else
							{
								if ($this->db->affected_rows() > 0)
								{
									$this->db->trans_commit();
									$result     = array("status"=> "Success","message"=>"User ceated successfully !!!");
									return $result;
								}
								else
								{
									$this->db->trans_commit();
									$result     = array("status"=> "Failed","message"=>"Failed to create user  !!!");
									return $result;
								}
							}
						}
					}
				} 
			else
			{
				$result     = array("status"=> "Failed","message"=>"User name OR email already exists !!!");	
				return $result;	
			}
		}
		return $result;
	}
/*
	
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
*/
	function user_listing($limit="", $start="")
	{
		$this->db->select("U.*,UG.*,D.DOCTORS_ID",FALSE);
		$this->db->from("USERS U");		
		$this->db->join("USER_ACCESS_GROUP UG", "UG.USER_ACCESS_GROUP_ID = U.USER_ACCESS_TYPE", "LEFT");
		$this->db->join("DOCTORS D", "D.LOGIN_ID = U.USER_SPK", "LEFT");
		
		$this->db->where("U.USER_ACCESS_TYPE != ",WEB_ADMINISTRATOR);
		$this->db->order_by("U.USERNAME ASC");
		
		if($limit != "" || $start != "") {
			$this->db->limit($limit, $start);
			
		}
	      
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		{
			$result 	= array('data'=>$query->result(), 'status'=>'Success');
			return $result;			

		}
		else
		{
			$result 	= array('data'=>array(), 'status'=>'Failed');
			return $result;	
		}
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

	function changePassword($post_data= array())
	{
		$result 	= array('message'=>"", 'status'=>'Failed..');
		//print_r($post_data);
		//exit;
		if(!empty($post_data))
		{
			$current_password	=	$post_data["current_password"];
			$new_password		=	$post_data["new_password"];
			$confirm_password	=	$post_data["confirm_password"];
			//$old_password=$post_data["old_password"])
			$this->db->start_cache();
			$this->db->where("USER_SPK",(int)$post_data["user_id"]);
			$query = $this->db->get("USERS");
			$this->db->stop_cache();
			$this->db->flush_cache();
			//echo $this->db->last_db_query();
			$row = $query->row();
			if($query->num_rows() > 0)
			{
				if($row->PASSWORD == md5($new_password) && $row->PASSWORD == md5($new_password))
				{
					$result 	= array('message'=>"No changes in password",
												'status'=>'Failed',
												);
				}
				if($row->PASSWORD == md5($current_password))
				{
            		if($new_password == $confirm_password)
            		{
						$this->db->where("USER_SPK",(int)$post_data["user_id"]);
						$this->db->update("USERS",array("PASSWORD"=>md5($new_password)));
						if($this->db->affected_rows() > 0)
						{
							$result 	= array('message'=>"Password changed Successfully ",
												'status'=>'Success',
												);
						}
					}
					else{
						$result 	= array('message'=>"New password and confirm password does not match",
												'status'=>'Failed',
												);
					}
				}
				else
				{
					$result 	= array('message'=>"Invalid current password",
												'status'=>'Failed',
												);
				}
				
			}	
		}
		return $result;
	}

}
?>