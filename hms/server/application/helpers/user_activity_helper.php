<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('log_activity'))
{
	function log_activity($action, $str_sql, $data_arr= array(), $user_id = 0){

        // $ci=& get_instance();
        // $user_id = $ci->session->userdata("admin_user_id") ? $ci->session->userdata("admin_user_id") : 0;
        // $data = array(
        // 					"USER_SFK" => $user_id,
        // 					"IP_ADDRESS" => $ci->input->ip_address(),
        // 					"MODULE" => $ci->router->directory.$ci->router->fetch_class().'/'.$ci->router->fetch_method() ,
        // 					"ACTION" => $action,
        // 					"DATA_JSON" => ( count($data_arr) > 0) ? json_encode($data_arr) :'',
        // 					//"STR_SQL" => $str_sql,
        // 					);
        // if(LOG_TYPE == 1)
        // {
        //   $ci->load->database();

        //   $ci->db->set('ACTIVITY_TIME', 'NOW()', FALSE);
        //   $query = $ci->db->insert("USER_ACTIVITY",$data);
        //   return $ci->db->insert_id();
        // }
        // else
        // {
        //     $path = FCPATH.ACTIVITY_LOG_PATH.date('d-m-y').'/';

        //       if (!is_dir($path)) {

        //           mkdir($path, 0777, TRUE);
        //       }


        //       $path = $path.$user_id.'.json';
        //       if($path){
        //         $data_arr = array();
        //         $data_string_temp = read_file($path);
        //         if($data_string_temp)
        //         {
        //           $data_arr = json_decode($data_string_temp,true);
        //           $data_arr[] = $data;
        //         }
        //         else
        //         {
        //           $data_arr[] = $data;
        //         }
        //         $data_string = json_encode($data_arr);
        //         if (!write_file($path, $data_string))
        //         {
        //           //echo 'Unable to write the file';
        //               return false;
        //         }
        //         else
        //         {
        //           //echo 'File written!';
        //               return true;
        //           }
        //       }
        //       return false;
        // }
   }
}
if ( ! function_exists('save_sop_request'))
{
  function save_sop_request($host, $url, $methode, $soap_action, $body, $result, $user_id){

        $ci=& get_instance();
        //$user_id = $ci->session->userdata("admin_user_id") ? $ci->session->userdata("admin_user_id") : 0;
        $data = array(
                  "USER_ID" => $user_id,
                  "HOST" => $host,
                  "URL" => $url,
                  "SOAP_ACTION" => $methode,
                  "METHODE" => $soap_action,
                  "BODY" => $body,
                  "RESULT" => $result,
                  "IP_ADDRESS" => $ci->input->ip_address(),
                  "REQUEST_TIME" =>  date('Y-m-d H:i:s')
                  );
        if(SOAP_LOG_TYPE == 1)
        {
          $ci->load->database();

          //$ci->db->set('REQUEST_TIME', 'NOW()', FALSE);
          $query = $ci->db->insert("SOAP_REQUESTS",$data);

          return $ci->db->insert_id();
        }
        else
        {
            $path = FCPATH.SOAP_LOG_PATH.date('d-m-y').'/';

              if (!is_dir($path)) {

                  mkdir($path, 0777, TRUE);
              }


              $path = $path.$user_id.'.json';
              if($path){
                $data_arr = array();
                $data_string_temp = read_file($path);
                if($data_string_temp)
                {
                  $data_arr = json_decode($data_string_temp,true);
                  $data_arr[] = $data;
                }
                else
                {
                  $data_arr[] = $data;
                }
                $data_string = json_encode($data_arr);
                if (!write_file($path, $data_string))
                {
                  //echo 'Unable to write the file';
                      return false;
                }
                else
                {
                  //echo 'File written!';
                      return true;
                  }
              }
              return false;
        }

  }
}

if ( ! function_exists('get_app_setting'))
{
	function get_app_setting($key)
	{
      $ci=& get_instance();
      $ci->load->database();
      if($key != "")
		{
			$ci->db->select("APP_SETTINGS_VALUE");
			$ci->db->from("APP_SETTINGS");
			$ci->db->where("APP_SETTINGS_KEY",$key);
			$ci->db->where("APP_SETTINGS_STATUS",1);
			$query = $ci->db->get();
			//echo $ci->db->last_query();
			if ($query->num_rows() > 0)
				return $query->row()->APP_SETTINGS_VALUE;
			else
				return false;
		}
		return false;
   }
}
if ( ! function_exists('check_cpt_req'))
{
  function check_cpt_req($cpt,$cpt_group)
  {
      $ci=& get_instance();
      $ci->load->database();
      if($cpt != "")
      {
        $ci->db->select("TYPE,CODE,VALUE_TYPE,CPT_REQUIRED_ID");
        $ci->db->from("CPT_REQUIRED");
        $ci->db->where("CPT_GROUP",$cpt_group);
        $ci->db->group_start();
        $ci->db->like("CPT_CODES",$cpt);
        $ci->db->or_where("CPT_CODES is null");
        $ci->db->group_end();
        $query = $ci->db->get();
        //echo $ci->db->last_query();
        if ($query->num_rows() > 0)
          return $query->result_array();
        else
          return array();
      }
    return array();
   }
 }
  if ( ! function_exists('select_data'))
  {
    function select_data($table,$column, $condetion)
    {
        $ci=& get_instance();
        $ci->load->database();
        if($table != "" && $column != '' && $condetion!= '')
        {
          $ci->db->select($column);
          $ci->db->from($table);
          $ci->db->where($condetion);
          //$ci->db->where("CPT_REQUIRED",1);
          $query = $ci->db->get();
          //echo $ci->db->last_query();
          if ($query->num_rows() > 0)
            return $query->row_array();
          else
            return false;
        }
      return false;
    }
}
if ( ! function_exists('check_api_user_auth'))
{
  function check_api_user_auth($user,$pass)
  {
    $ci=& get_instance();
    $ci->load->database();
    if($user != "" && $pass != "")
    {
      $ci->db->select("USER_SPK");
      $ci->db->from("USERS");
      $ci->db->where("USER_SPK",$user);
      $ci->db->where("PASSWORD",$pass);
      $ci->db->where("STATUS",1);
      $query = $ci->db->get();
      //echo $ci->db->last_query();
      if ($query->num_rows() > 0)
        return true;
      else
        return false;
    }
    return false;
   }
}
if ( ! function_exists('log_activity_save'))
{
  function log_activity_save($action, $data_arr= array(), $user_id = 0)
  {
    $ci=& get_instance();
    $data = array(
      "USER_SFK" => $user_id,
      "IP_ADDRESS" => $ci->input->ip_address(),
      "MODULE" => $ci->router->directory.$ci->router->fetch_class().'/'.$ci->router->fetch_method() ,
      "ACTION" => $action,
      "DATA_JSON" => json_encode($data_arr)
    );

    $path = FCPATH.ACTIVITY_LOG_PATH.date('d-m-y').'/';

    if (!is_dir($path)) {
      mkdir($path, 0777, TRUE);
    }
    $path = $path.$user_id.'.json';
    if($path)
    {
      $data_arr = array();
      $data_string_temp = read_file($path);
      if($data_string_temp)
      {
        $data_arr = json_decode($data_string_temp,true);
        $data_arr[] = $data;
      }
      else
      {
        $data_arr[] = $data;
      }
      $data_string = json_encode($data_arr);
      if (!write_file($path, $data_string))
      {
        //echo 'Unable to write the file';
        return false;
      }
      else
      {
        //echo 'File written!';
        return true;
      }
    }
    return false;
  }
}
