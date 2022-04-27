<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller
{	
   function __construct()
   {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');               
        $this->load->model('api/MasterDataModel');
        $this->load->model('api/ModulesModel');
        
	}

  	public function get_sub_modules()
   {       
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              $post_data["module_id"] = isset($post["module_id"]) ? $post["module_id"] : 0;       
              $result['menu_list'] = $this->ModulesModel->get_sub_modules( $post_data["module_id"] );  
              $result["status"] = "Success";    
                log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));                 
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = array("status"=> "Failed");
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
   }
   
   public function get_master_data()
   {       
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post  = json_decode(file_get_contents("php://input"),true); 
              $post_data["master_id"] = isset($post["master_id"]) ? $post["master_id"] : 0;       
              $result['master_list'] = $this->MasterDataModel->getMasterDataList( $post_data["master_id"] );  
              $result["status"] = "Success";              
                log_activity('API-'.$this->router->fetch_class().'-'.$this->router->fetch_method().'',"Message : ".json_encode($result),"Posted Data :".json_encode($post));       
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
