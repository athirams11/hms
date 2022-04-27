<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class OpVisitEntry extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/OpVisitModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	  }

  public function options()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 

        $result["INSURANCE_CONST"] = INSURANCE_ID_CONSTANT;  

        $result["specialized_in"]         = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 8 AND O.OPTIONS_STATUS = 1");

        $result["tpa_receiver"]     = $this->MasterModel->master_dropdown_listing("TPA T","T.TPA_ID,T.TPA_NAME,T.TPA_ECLAIM_LINK_ID","T.TPA_ECLAIM_LINK_ID","ASC","T.TPA_STATUS = 1");

        $result["networks"]     = $this->MasterModel->master_dropdown_listing("INS_NETWORK N","N.INS_NETWORK_ID,N.INS_NETWORK_NAME,N.INS_NETWORK_CODE,N.TPA_ID","N.INS_NETWORK_NAME ","ASC","N.INS_NETWORK_STATUS = 1");

        $result["ins_com_pay"]     = $this->MasterModel->master_dropdown_listing("INSURANCE_PAYERS I","I.INSURANCE_PAYERS_ID,I.INSURANCE_PAYERS_ECLAIM_LINK_ID,I.INSURANCE_PAYERS_NAME","I.INSURANCE_PAYERS_ECLAIM_LINK_ID","ASC","I.INSURANCE_PAYERS_STATUS = 1");

        $result["co_in_types"]     = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 9 AND O.OPTIONS_STATUS = 1");

        $result["discount_sites"]   = $this->MasterModel->master_dropdown_listing("MASTER_DISCOUNT_SITES","DISCOUNT_SITES_ID,DISCOUNT_SITES_NAME,STATUS","DISCOUNT_SITES_NAME","ASC","STATUS = 1");

        log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
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
  public function getDrByDateDept()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->OpVisitModel->getDrByDateDept($post_data);
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
  public function addVisit()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->OpVisitModel->addVisit($post_data);
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
  public function updateInsuranceDetails()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->OpVisitModel->updateInsuranceDetails($post_data);
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
  public function updateCompanyDetails()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->OpVisitModel->updateCompanyDetails($post_data);
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
  
  public function getPatientDetails()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->OpVisitModel->getPatientDetails($post_data);
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
  public function getVisitListByDate()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->OpVisitModel->getVisitListByDate($post_data);
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
  public function getCPTBySites()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post  = json_decode(file_get_contents("php://input"),true); 
        $post_data["discount_site_id"] = isset($post["discount_site_id"]) ? $post["discount_site_id"] : 0; 
        $result = $this->OpVisitModel->getCPTBySites($post_data);
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
