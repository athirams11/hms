<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserAccessModel extends CI_Model 
{
	
	function getAccessData($post_data = array())
	{
		
		
		if ($post_data["user_group"] != "")
		{
			$this->db->start_cache();
			if ($post_data["user_group"] == WEB_ADMINISTRATOR)
			{
				$this->db->select("MG.*,'1' as MODULE_GROUP_ACCESS");
			}
			else
			{
				$this->db->select("MG.*,IFNULL(UA.MODULE_GROUP_ACCESS, '0') as MODULE_GROUP_ACCESS");
			}
			
			$this->db->from("MODULE_GROUP MG");
			$this->db->join("USER_ACCESS_RIGHTS UA","UA.MODULE_GROUP_ID = MG.MODULE_GROUP_ID AND UA.USER_ACCESS_GROUP_ID = ".$post_data["user_group"]." ","left");
			//$this->db->where("UA.USER_ACCESS_GROUP_ID",$user_group);
			$this->db->where("MG.MODULE_GROUP_STATUS",1);
			$this->db->order_by("MG.MODULE_GROUP_ORDER","asc");
			$query = $this->db->get();
			
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$response = $query->result();
				$result = array();
				//$i = 0;
				foreach ($response as $key => $value) {
				//print_r($value->MODULE_GROUP_ID);
					$result[$key] = $value;
					$result[$key]->sub_menu = $this->getSubmodules($value->MODULE_GROUP_ID,$post_data["user_group"]);
					//$result[$key]->MODULE_GROUP_ACCESS = $result[$key]->sub_menu[0]->MODULE_GROUP_ACCESS;
					//$i++;
				}
				$result 	= array('data'=>$result,
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
	function getSubmodules($MODULE_GROUP_ID=0,$user_group)
	{
		if($MODULE_GROUP_ID != 0)
		{
			$query = $this->db->get('USER_ACCESS_TYPE');
			$rows = $query->num_rows();
			$access_def = str_repeat("0", $rows);
			$this->db->start_cache();
			if ($user_group == WEB_ADMINISTRATOR)
			{
				$access_def = str_repeat("1", $rows);
				$this->db->select("M.*,'".$access_def."' AS MODULE_ACCESS_RIGHTS");
			}
			else
			{
				$this->db->select("M.*,IFNULL((CASE  WHEN LENGTH(MODULE_ACCESS_RIGHTS) > ".$rows." THEN MODULE_ACCESS_RIGHTS  ELSE RPAD( MODULE_ACCESS_RIGHTS,".$rows." ,'".$access_def."' )  END),'".$access_def."') AS MODULE_ACCESS_RIGHTS");
			}
			//$this->db->select("M.*,IFNULL((CASE WHEN LENGTH(MODULE_ACCESS_RIGHTS) > ".$rows." THEN MODULE_ACCESS_RIGHTS ELSE RPAD( MODULE_ACCESS_RIGHTS,".$rows." ,'".$access_def."' )  END),'".$access_def."') AS MODULE_ACCESS_RIGHTS");
			$this->db->from("MODULE M");
			$this->db->join("USER_ACCESS_RIGHTS UA","UA.MODULE_ID = M.MODULE_ID AND UA.USER_ACCESS_GROUP_ID = ".$user_group." ","left");
			$this->db->where("M.MODULE_GROUP_ID",$MODULE_GROUP_ID);
			//$this->db->where("UA.USER_ACCESS_GROUP_ID",$user_group);
			$this->db->where("M.MODULE_STATUS",1);
			$this->db->where("M.MODULE_TYPE","main");
			$this->db->order_by("M.MODULE_ORDER","asc");
			$query = $this->db->get();
			
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = $query->result();
				
				return $result;
			}
		}
		return array();
	}
	function changeAccessGroup($post_data)
	{
		$result 	= array('message'=>"", 'status'=>'Failed..');
		if ($post_data["user_group"] != "" && $post_data["group_id"] != "")
		{
			$this->db->start_cache();
			$this->db->where("USER_ACCESS_GROUP_ID",(int)$post_data["user_group"]);
			$this->db->where("MODULE_GROUP_ID",(int)$post_data["group_id"]);
			$query = $this->db->get("USER_ACCESS_RIGHTS");
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$this->db->where("USER_ACCESS_GROUP_ID",(int)$post_data["user_group"]);
				$this->db->where("MODULE_GROUP_ID",(int)$post_data["group_id"]);
				$this->db->update("USER_ACCESS_RIGHTS",array("MODULE_GROUP_ACCESS"=>(int)$post_data["value"]));
				if($this->db->affected_rows() > 0)
				{
					$result 	= array('message'=>"",
										'status'=>'Success',
										);
				}
			}
			else
			{
				if($this->db->insert("USER_ACCESS_RIGHTS",array("MODULE_GROUP_ACCESS"=>(int)$post_data["value"],"USER_ACCESS_GROUP_ID"=>(int)$post_data["user_group"],"MODULE_GROUP_ID"=>(int)$post_data["group_id"])))
				{
					$result 	= array('message'=>"",
										'status'=>'Success',
										);
				}
			}

			
		}
		return $result;
	}
	function changeAccessRights($post_data)
	{
		$result 	= array('message'=>"", 'status'=>'Failed..');
		if ($post_data["user_group"] != "" && $post_data["module_id"] != "")
		{
			$this->db->start_cache();
			$this->db->where("USER_ACCESS_GROUP_ID",(int)$post_data["user_group"]);
			$this->db->where("MODULE_ID",(int)$post_data["module_id"]);
			$query = $this->db->get("USER_ACCESS_RIGHTS");
			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$this->db->where("USER_ACCESS_GROUP_ID",(int)$post_data["user_group"]);
				$this->db->where("MODULE_ID",(int)$post_data["module_id"]);
				$this->db->update("USER_ACCESS_RIGHTS",array("MODULE_ACCESS_RIGHTS"=>$post_data["value"]));
				if($this->db->affected_rows() > 0)
				{
					$result 	= array('message'=>"",
										'status'=>'Success',
										);
				}
			}
			else
			{
				if($this->db->insert("USER_ACCESS_RIGHTS",array("MODULE_ACCESS_RIGHTS"=>$post_data["value"],"USER_ACCESS_GROUP_ID"=>(int)$post_data["user_group"],"MODULE_ID"=>(int)$post_data["module_id"])))
				{
					$result 	= array('message'=>"",
										'status'=>'Success',
										);
				}
			}

			
		}
		return $result;
	}
}
?>