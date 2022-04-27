<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class InsurancePrice extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/InsurancePriceModel');
        
	}

    public function saveInsurancePrice()
    {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true); 
            //print_r($post_data);            
            $result      = $this->InsurancePriceModel->saveInsurancePrice($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
    }

    public function getInsurancePrice()
    {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true); 
            //print_r($post_data);            
            $result      = $this->InsurancePriceModel->getInsurancePrice($post_data);
            log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
    }
    public function listInsurancePrice()
    {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
            $post_data  = json_decode(file_get_contents("php://input"),true); 
            //print_r($post_data);            
            $result      = $this->InsurancePriceModel->listInsurancePrice   ($post_data);
            // log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
    }


 }     