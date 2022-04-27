<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');

class Promotions extends CI_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        $this->load->model('MasterModel');
        $this->load->model('api/ApiModel');        
        $this->load->model('api/PromotionModel');
        $this->load->library("Xml2json");
        $this->load->library("Xmlparse");
        //$this->load->model('api/ItemsModel');
        
	  }
	  public function ListCompanyPromotions()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result     = $this->ApiAdminModel->ListCompanyPromotions($post_data);
              log_activity('Admin-ListPromotion',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = $this->ApiAdminModel->ListCompanyPromotions();
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
  public function DeletePromotionFile()
  {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result     = $this->PromotionModel->deletePromotionFile($post_data);
              log_activity('Admin-ListPromotion',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = $this->PromotionModel->deletePromotionFile();
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
   public function CompanyCustomers()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result     = $this->PromotionModel->CompanyCustomers($post_data);
              log_activity('Admin-ListPromotion',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = $this->PromotionModel->CompanyCustomers();
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }
  }
  
  public function ListAllPromotions()
   {       
      switch($this->input->method(TRUE))
        {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $result     = $this->PromotionModel->ListAllPromotions($post_data);
              log_activity('Admin-ListPromotion',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              $this->ApiModel->response($result, "201"); // CREATED (201) being the HTTP response code
        break;
        
        default:                
              $result     = $this->PromotionModel->ListAllPromotions();
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
              $result     = $this->PromotionModel->get_data($post_data);
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
  public function get_company_info_text(){
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $data     = $this->PromotionModel->CompanyInfo($post_data);
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
  
  public function get_company_categories(){
      switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              //print_r($post_data);            
              $data     = $this->PromotionModel->getCompanyCategories($post_data);
              $result   = array('data'=>$data,'status'=>'Success');
              log_activity('Admin-GetCompanyCat',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
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
  public function getCategoryList()
  {
    switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
              $tables = array('0' => "COMPANY_BUSINESS_CATEGORY CB",'1'=>"BUSINESS_CATEGORY BC" );
              $inCond = array('1' => "CB.CBC_BUS_CAT_ID = BC.BUS_CAT_ID" );
              $cat_list = $this->MasterModel->master_dropdown_listing_joint($tables,$inCond,"BC.BUS_CAT_ID,BC.BUS_CAT_NAME","BC.BUS_CAT_NAME","ASC","CB.CBC_COMPANY_ID = ".$post_data["company_id"]);
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
  public function uploadFile()
  {
    switch($this->input->method(TRUE))
      {     
        case 'POST':          
              $post_data  = json_decode(file_get_contents("php://input"),true); 
             // $tables = array('0' => "COMPANY_BUSINESS_CATEGORY CB",'1'=>"BUSINESS_CATEGORY BC" );
             // $inCond = array('1' => "CB.CBC_BUS_CAT_ID = BC.BUS_CAT_ID" );
              //$cat_list = $this->MasterModel->master_dropdown_listing_joint($tables,$inCond,"BC.BUS_CAT_ID,BC.BUS_CAT_NAME","BC.BUS_CAT_NAME","ASC","CB.CBC_COMPANY_ID = ".$post_data["company_id"]);
             // $result   = array('data'=>$cat_list,'status'=>'Success');
              //log_activity('Admin-Get District',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
              //$this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
              //$response = str_replace($this->ApiModel->replace_xml_tag('added_plans'), $replace, $response);
              if($post_data["imagedataurl"] != "")
              {
                $image_name=$this->MasterModel->Writebase64($post_data["imagedataurl"],FCPATH.PROMOTION_MEDIA_PATH.'/');
                if($image_name!="")
                {
                  $message = [
                            'status' => 'Success',
                            'message'=> "File  saved successfully",
                            'data' => $image_name
                         ]; 
                }
                else 
                {
                    $message = [
                              'status' => 'failed',
                              'message'=> "File uploading Failed",
                              'data' => "no location",
                              'msg'=>$post_data
                           ];

                }

              }
              else 
              {
                  $message = [
                            'status' => 'failed',
                            'message'=> "File uploading Failed",
                            'data' => "no data url",
                            'msg'=>$post_data
                         ];

              }
              $this->ApiModel->response($message, "201"); // CREATED (201) being the HTTP response code
            
              
        
        break;
        
        default:                
              $result   = array('data'=>"",'status'=>'Failed');
              $this->ApiModel->response($result,'201'); // CREATED (201) being the HTTP response code
        break;
          
      }   
    
  }
 
  
}

?>
