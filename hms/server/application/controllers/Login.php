<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/AdminUserModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	  }
  public function checkCredentials()
  {
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              

              //$result["op_no"] = $this->OpRegistrationModel->GenerateOpNo($post_data);          
              if($post_data["log_user"] == "" || $post_data["log_user"] == null)
              {
                $result     = array("status"=> "Failed","message"=>"Failed to Login. Invalid Credentials !!!");
              }
              if($post_data["log_pass"] == "" || $post_data["log_pass"] == null)
              {
                  $result     = array("status"=> "Failed","message"=>"Failed to Login. Invalid Credentials !!!");
              }
              else
              {
                   $result      = $this->AdminUserModel->check_login($post_data);
              }
             
            //  print_r($result);  
            //   exit();
              //log_activity('API-LOGIN',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed","message"=>"Failed to Login. Invalid Credentials !!!");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
  public function getAccessTypes()
   {       

      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);  
              //$result["op_no"] = $this->OpRegistrationModel->GenerateOpNo($post_data);          
              
              $result["user_access_types"]         = $this->MasterModel->master_dropdown_listing("USER_ACCESS_TYPE UA","UA.USER_ACCESS_TYPE_ID,UA.USER_ACCESS_TYPE_NAME,","UA.USER_ACCESS_TYPE_INDEX","ASC","UA.USER_ACCESS_TYPE_STATUS = 1");
              
             //  log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));          
              $result["status"] = "Success";
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
 
 
}

?>
