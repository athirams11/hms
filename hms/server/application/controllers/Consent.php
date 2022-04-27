<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class Consent extends CI_Controller
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
        $this->load->model('api/InstitutionManagementModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	  }

 
  public function newConsent()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->OpRegistrationModel->consentNew($post_data);
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
  public function newGeneralConsent()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result      = $this->OpRegistrationModel->consentNewGeneral($post_data);
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
              $result      = $this->OpRegistrationModel->getPatientDetailsVisit($post_data);
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
  public function getInstitution()
    {           
        switch($this->input->method(TRUE))
        {     
          case 'POST':          
                $post  = json_decode(file_get_contents("php://input"),true);
                //print_r($post ); 
                $post_data["hospital_id"] = isset($post["hospital_id"]) ? $post["hospital_id"] : '';       
                $result      = $this->InstitutionManagementModel->getOneInstitution($post_data);
                //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                 log_activity('API-Consent-getInstitution',"Message : ".json_encode($result),"Posted Data :".json_encode($post));    
                $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
          break;
          
          default:                
                $result     = array("status"=> "Failed");
                $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
          break;          
        }
   }


   public function downloadgeneralconsent()
   {       
       switch($this->input->method(TRUE))
         {     
         case 'POST':          
               $post_data  = json_decode(file_get_contents("php://input"),true); 
               $result      = $this->OpRegistrationModel->downloadgeneralconsent($post_data);
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
   public function downloadgeneralconsent_reg()
   {       
       switch($this->input->method(TRUE))
         {     
         case 'POST':          
               $post_data  = json_decode(file_get_contents("php://input"),true); 
               $result      = $this->OpRegistrationModel->downloadgeneralconsent_reg($post_data);
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
