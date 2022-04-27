<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class Modules extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/ModulesModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	  }

  
  public function getModules()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              //$result      = $post_data;
              $result      = $this->ModulesModel->getModules($post_data);
              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));     
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
  public function getNotificationCount()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              //$result      = $post_data;
              $count      =   $this->ModulesModel->getSubmodulesBadgeCount($post_data);
              //$result      =  array("count"=>rand ( 1 , 123 ),"status"=>"Success");//
              $result      =  array("count"=>$count,"status"=>"Success");//
                log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
   public function getModuleSummary()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              //$result      = $post_data;
              $count      =   $this->ModulesModel->getModuleSummary($post_data);
              if($count["COUNT"] == [0,0,0,0,0,0])
              {
                $count["COUNT"] = 1 ;
                $count["NAME"] = ["None"];
              }
              //$result      =  array("count"=>rand ( 1 , 123 ),"status"=>"Success");//
              $result      =  array("data"=>$count,"status"=>"Success");//
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
