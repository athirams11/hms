<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class Querys extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/QuerysModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	  }

  
  public function getPatientList()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              //$result      = $post_data;
               $post_data["start"] = isset($post_data["start"]) ? $post_data["start"] : '';     
                $post_data["limit"] = isset($post_data["limit"]) ? $post_data["limit"] : '';     
                $post_data["search_text"] = isset($post_data["search_text"]) ? $post_data["search_text"] : '';  
              $result      = $this->QuerysModel->getPatientList($post_data);
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
               log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));           
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
  

  public function getPatientList_bydate()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              $post_data["dateVal"] = isset($post_data["dateVal"]) ? $post_data["dateVal"] : '';     
              $post_data["timeZone"] = isset($post_data["timeZone"]) ? $post_data["timeZone"] : '';    
              $result      = $this->QuerysModel->getPatientList_bydate($post_data);
               log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));           
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
