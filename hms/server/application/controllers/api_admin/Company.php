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
	  public function ListAllCompany()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result     = $this->CompanyModel->ListAllCompany($post_data);
              log_activity('Admin-ListPromotion',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = $this->ApiAdminModel->ListAllCompany();
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
 public function createCompany(){
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $status     = $this->CompanyModel->createCompany($post_data);
              if ($status==1)
              {
                $output=array("status"=>"Success","message"=>'Sucessfully updated') ;
              }
              elseif ($status==2)
              {
                $output=array("status"=>'Failed',"message"=>'Duplicate Company code!!') ;
              }
              elseif ($status==3)
              {
                $output=array("status"=>'Failed',"message"=>'Duplicate Company name!!') ;
              }
              elseif ($status==4)
              {
                $output=array("status"=>'Failed',"message"=>'Duplicate Private Id!!') ;
              }
              else
              {
                $output=array("status"=>'Failed',"message"=>'Error') ;
              }
              $output ["data"]  = array();
              log_activity('Admin-Create Company',"Message : ".json_encode($output),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($output, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = array('data'=>"",'status'=>'Failed',"message"=>'Failed To Update/Create');
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
  public function get_data(){
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result     = $this->CompanyModel->getCompanyData($post_data);
             
              log_activity('Admin-GetCompany',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = array('data'=>"",'status'=>'Failed');
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
  public function get_company_info_text(){
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $data     = $this->CompanyModel->CompanyInfo($post_data);
              $result   = array('data'=>$data,'status'=>'Success');
              log_activity('Admin-GetPromotion',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = array('data'=>"",'status'=>'Failed');
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
  public function getCodeByCountry(){
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $data     = $this->CompanyModel->getCodeByCountry($post_data);
              $result   = array('data'=>$data,'status'=>'Success');
              log_activity('Admin-GetPromotion',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = array('data'=>"",'status'=>'Failed');
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
  public function deleteCompany(){
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result     = $this->CompanyModel->deleteCompany($post_data);
              
              if($result == 1)
              {
                $output=array("status"=>"Success","message"=>'Sucessfully deleted') ;
              }
              elseif($result == 2)
              {
                $output=array("status"=>'Failed',"message"=>'Invalid Company') ;
              }
              else
              {
                $output=array("status"=>'Failed',"message"=>'Error') ;
              }
              $output["data"]  = array();
              log_activity('Admin-Delete Company',"Message : ".json_encode($output),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($output, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = array('data'=>"",'status'=>'Failed',"message"=>'Failed To Update/Create');
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
  public function getStateByCountry()
  {
    switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              
              $options = "<option value=''></option>";
              if($post_data["data_id"] != "")
              {
                $country = $post_data["data_id"];
                $state_list = $this->MasterModel->master_dropdown_listing("STATE","STATE_ID,STATE_NAME","STATE_NAME","ASC","STATE_COUNTRY_ID = ".$country);
                if(is_array($state_list))
                {
                  foreach ($state_list as $key => $value) {
                  $options .= "<option value='".$value['STATE_ID']."'>".$value['STATE_NAME']."</option>";
                } 
                }
                
              }
              log_activity('Admin-Get State',"Message : ".json_encode($options),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($options, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = "";
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
  public function getDistrictByCountryState()
  {
    switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              
              $options = "<option value=''></option>";
              if($post_data["data_id"] != "" && $post_data["country_id"] != "")
              {
                $state = $post_data["data_id"];
                $country = $post_data["country_id"];
                $district_list = $this->MasterModel->master_dropdown_listing("DISTRICT","DISTRICT_ID,DISTRICT_NAME","DISTRICT_NAME","ASC","DISTRICT_STATE_ID = ".$state." AND DISTRICT_COUNTRY_ID = ".$country."");
                if(is_array($district_list))
                {
                  foreach ($district_list as $key => $value) {
                    $options .= "<option value='".$value['DISTRICT_ID']."'>".$value['DISTRICT_NAME']."</option>";
                  } 
                }
                
              }
              log_activity('Admin-Get District',"Message : ".json_encode($options),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($options, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = "";
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
  public function check_private_id()
  {
    switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              
              $id = $post_data["data_id"];
              $pv_id = $post_data["pv_id"];
              if($this->utility->is_Duplicate("COMPANY","COMPANY_SYMP_ID",$pv_id,"COMPANY_ID",$id))
              {
                $result = array("status"=>"Failed","message"=>'Already Taken');
              }
              else
              {
                $result = array("status"=>"Success","message"=>'Valid Id');
              }
              log_activity('Admin-Get District',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
            
        
        break;
        
        default:                
              $result = array("status"=>"Failed","message"=>'');
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
  public function getCountryList()
  {
    switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              $cat_list = $this->MasterModel->master_dropdown_listing("COUNTRY","COUNTRY_ID,COUNTRY_NAME","COUNTRY_NAME");
             
              $result   = array('data'=>$cat_list,'status'=>'Success');
              log_activity('Admin-Get District',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); //
        break;
        
        default:                
              $result   = "";
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
  public function getCategoryList()
  {
    switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              $cat_list = $this->MasterModel->master_dropdown_listing("BUSINESS_CATEGORY","BUS_CAT_ID,BUS_CAT_NAME","BUS_CAT_NAME");
             
              $result   = array('data'=>$cat_list,'status'=>'Success');
              log_activity('Admin-Get District',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = array('data'=>"",'status'=>'Failed');
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
  public function generateCompanyCode()
  {
    switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              $result = $this->MasterModel->generateCompanyCode();
              log_activity('Admin-gen CompanyCode',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = "";
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }

  

}

?>
