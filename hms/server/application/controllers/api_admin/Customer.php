<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {


	public function __construct() {
		parent::__construct();
		//to disallow browser back property once got log out
		$this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('api/ApiModel');        
        $this->load->model('api/CustomerModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
		//$this->load->model("MenuModel");
		//$this->data["menu_listing"] = $this->MenuModel->menu_listing();
		//$this->user_rights=$this->CustomerModel->get_user_rights($this->router->fetch_class(),$this->session->userdata("user_id"),$this->session->userdata("login_type"));	
	}
	
	public function deletedata()
  {
    switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result     = $this->CustomerModel->deletedata($post_data);
              log_activity('Admin-DeleteCusrtomer',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = array('data'=>"",'status'=>'Failed',"message"=>"Failed to delete Customer");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
  }
	
}

