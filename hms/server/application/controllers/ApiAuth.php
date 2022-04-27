<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class ApiAuth extends CI_Controller
{	
  function __construct()
  {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	}
  public function error_500()
  {       
                   
      $result     = array("Status"=> false, "response"=>"Invalid authentication key", "message"=>"Failed");
      $this->ApiModel->response($result,'200'); // CREATED (201) being the HTTP response code
  }
  public function error_501()
  {       
                   
      $result     = array("Status"=> false, "response"=>"Invalid user credentials", "message"=>"Failed");
      $this->ApiModel->response($result,'200'); // CREATED (201) being the HTTP response code
  }
  
 
}

?>
