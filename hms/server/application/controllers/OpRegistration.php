<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class OpRegistration extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/OpRegistrationModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	  }


public function getemri()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->OpRegistrationModel->getemri($post_data);
        log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
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
  
  public function options()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);  
              // $result["INSURANCE_CONST"] = INSURANCE_ID_CONSTANT;          
              $result["op_no"] = $this->OpRegistrationModel->GenerateOpNo($post_data);          
              $result["country"]      = $this->MasterModel->master_dropdown_listing("COUNTRY C","C.*","C.COUNTRY_NAME","ASC","C.COUNTRY_STATUS = 1");
              $result["type"]         = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 1 AND O.OPTIONS_STATUS = 1");
              // $result["info_source"]  = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 2 AND O.OPTIONS_STATUS = 1");
              // $result["emirates"]     = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 3 AND O.OPTIONS_STATUS = 1");
              // $result["tpa_receiver"]     = $this->MasterModel->master_dropdown_listing("TPA T","T.TPA_ID,T.TPA_NAME,T.TPA_ECLAIM_LINK_ID","T.TPA_ECLAIM_LINK_ID","ASC","T.TPA_STATUS = 1");
              // $result["networks"]     = $this->MasterModel->master_dropdown_listing("INS_NETWORK N","N.INS_NETWORK_ID,N.INS_NETWORK_NAME,N.INS_NETWORK_CODE,N.TPA_ID","N.INS_NETWORK_NAME ","ASC","N.INS_NETWORK_STATUS = 1");
              // $result["ins_com_pay"]     = $this->MasterModel->master_dropdown_listing("INSURANCE_PAYERS I","I.INSURANCE_PAYERS_ID,I.INSURANCE_PAYERS_ECLAIM_LINK_ID,I.INSURANCE_PAYERS_NAME","I.INSURANCE_PAYERS_ECLAIM_LINK_ID","ASC","I.INSURANCE_PAYERS_STATUS = 1");

              // $result["co_ins"]     = $this->MasterModel->master_dropdown_listing("CO_INS","CO_INS_ID,CO_INS_DESCREPTION,CO_INS_PERCENTAGE","CO_INS_DESCREPTION","ASC","CO_INS_STATUS = 1");
              // $result["enc_type"]     = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 6 AND O.OPTIONS_STATUS = 1");
              // $result["enc_str_type"]     = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 7 AND O.OPTIONS_STATUS = 1");
              // $result["co_in_types"]     = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 9 AND O.OPTIONS_STATUS = 1");


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
  public function newRegistration()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->OpRegistrationModel->registerNew($post_data);
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
              $result      = $this->OpRegistrationModel->getPatientDetails($post_data);
              //print_r($result);
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
  public function getPatientByEIDnumber()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->OpRegistrationModel->getPatientByEIDnumber($post_data);
              //print_r($result);
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
  public function getNetworkDetails()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);   
              $post_data["tpa_id"] = isset($post_data["tpa_id"]) ? $post_data["tpa_id"] : 0;           
              $result     = $this->MasterModel->master_dropdown_listing("INS_NETWORK N","N.INS_NETWORK_ID,N.INS_NETWORK_NAME,N.INS_NETWORK_CODE,N.TPA_ID","N.INS_NETWORK_NAME ","ASC","N.INS_NETWORK_STATUS = 1 AND TPA_ID = ".$post_data["tpa_id"]);
             
              //$result["status"] = "Success";
              //print_r($result);
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
              $result      = $this->OpRegistrationModel->updateInsuranceDetails($post_data);
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
}

?>
