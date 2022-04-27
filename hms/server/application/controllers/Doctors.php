<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class Doctors extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');       
        $this->load->model('api/DoctorsModel');
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
              
              $result["department"]         = $this->MasterModel->master_dropdown_listing("OPTIONS O","O.OPTIONS_ID,O.OPTIONS_NAME,O.OPTIONS_TYPE","O.OPTIONS_NAME","ASC","O.OPTIONS_TYPE = 8 AND O.OPTIONS_STATUS = 1");
              $result["doc_type"]         = $this->MasterModel->master_dropdown_listing("SERVICES S","S.SERVICES_ID,concat(S.SERVICE_NAME,' -',S.CPT_CODE) as SERVICE_CODE","S.SERVICE_NAME","ASC","S.SERVICE_TYPE = 1 AND S.SERVICE_STATUS = 1");
              
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
  public function addNewDoctor()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
             // print_r($post_data);            
              //$result      = $post_data;
              $result      = $this->DoctorsModel->addNewDoctor($post_data);
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data));   
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed","response"=>"<strong>Failed </strong> to saved the details");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }

  public function getScheduleById()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
             // print_r($post_data);            
              //$result      = $post_data;
              $result      = $this->DoctorScheduleModel->getScheduleById($post_data);
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
  public function getDoctorList()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
             // print_r($post_data);            
              //$result      = $post_data;
               $post_data["start"] = isset($post_data["start"]) ? $post_data["start"] : '';     
                $post_data["limit"] = isset($post_data["limit"]) ? $post_data["limit"] : '';     
                $post_data["search_text"] = isset($post_data["search_text"]) ? $post_data["search_text"] : '';  
              $result      = $this->DoctorsModel->getDoctorList($post_data);
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
