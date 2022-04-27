<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/CompanyModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	  }

  public function ListCompanyByCategory()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result     = $this->CompanyModel->ListCompanyByCategory($post_data);
              log_activity('API-ListPromotion',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = $this->ApiAdminModel->ListCompanyByCategory();
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
 
}

?>
