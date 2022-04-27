<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class Appointment extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/AppointmentModel');
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
        //print_r($post_data);  
        //$result["op_no"] = $this->OpRegistrationModel->GenerateOpNo($post_data);          

        $result["doctors_list"]         = $this->MasterModel->master_dropdown_listing("DOCTORS_SCHEDULE DS","DS.DOCTORS_SCHEDULE_ID,DS.DOCTORS_NAME,DS.SPECIALIZED_IN,DS.DOCTORS_ID","DS.DOCTORS_NAME","ASC","DS.DOCTORS_SCHEDULE_STATUS = 1");

         $result["specialized_in"]         = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 8 AND O.OPTIONS_STATUS = 1");

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
  public function getDrSchduleByDate()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
        //print_r($post_data);            
        //$result      = $post_data;


        //print_r($this->input->server());
        $result      = $this->AppointmentModel->getDrSchduleByDate($post_data);
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
  public function getDoctersByDate()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
        //print_r($post_data);            
        //$result      = $post_data;
       
       
        //print_r($this->input->server());
        $result      = $this->AppointmentModel->getDoctersByDate($post_data);
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
  public function getDrSchduleForWeek()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->AppointmentModel->getDrSchduleForWeek($post_data);
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
  public function getPatientsByPhoneNo()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->AppointmentModel->getPatientsByPhoneNo($post_data);
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
  public function getAppointmentsByDate()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->AppointmentModel->getAppointmentsByDate($post_data);
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
  public function getAppointmentsByfromtoDate()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->AppointmentModel->getAppointmentsByfromtoDate($post_data);
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
  public function getAppointmentsByDoctor()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->AppointmentModel->getAppointmentsByDoctor($post_data);
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
  
  public function getAppointment()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->AppointmentModel->getAppointment($post_data);
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
  public function getAllAppointments()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
        $post_data["start"] = isset($post_data["start"]) ? $post_data["start"] : '';     
        $post_data["limit"] = isset($post_data["limit"]) ? $post_data["limit"] : '';     
        $post_data["search_text"] = isset($post_data["search_text"]) ? $post_data["search_text"] : '';  
        // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->AppointmentModel->getAllAppointments($post_data);
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
  public function addNewAppointment()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->AppointmentModel->addNewAppointment($post_data);
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
  public function cancelAppointment()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->AppointmentModel->cancelAppointment($post_data);
        log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
        //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
        $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
        $result   = array('message'=>"Failed to cancel Appointment",
            'status'=>'Failed',
            );
        $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;
          
    }
  }
  public function checkAvailableSlots()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
        $result      = $this->AppointmentModel->checkAvailableSlots($post_data);
        log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));  
            $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      default:                
        $result     = array("status"=> "Failed");
        $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;
    }
  }
  public function changeAppointmentStatus()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
       // print_r($post_data);            
        //$result      = $post_data;
        $result      = $this->AppointmentModel->changeAppointmentStatus($post_data);
        log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
        //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
        $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
      break;
      
      default:                
        $result   = array('message'=>"Failed to cancel Appointment",
            'status'=>'Failed',
            );
        $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
      break;
          
    }
  }
  
  public function getDrScheduleListByDate()
  {       
    switch($this->input->method(TRUE))
    {     
      case 'POST':          
        $post_data  = json_decode(file_get_contents("php://input"),true); 
        $result      = $this->AppointmentModel->getDrScheduleListByDate($post_data);
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
