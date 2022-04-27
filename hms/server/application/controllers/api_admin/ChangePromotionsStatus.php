<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class ChangePromotionsStatus extends REST_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiAdminModel');
        //$this->load->model('api/ItemsModel');
        
	  }
	 
    public function index_get()
    {
     	// for GET Method
    }

    public function index_post()
    {
      // for POST Method    	
      $post_data  = json_decode(file_get_contents("php://input"),true);             
      $result     = $this->ApiAdminModel->ChangePromotionsStatus($post_data);
      log_activity('Admin-ChangePromotionsStatus',"Message : ".json_encode($result),"Posted Data :".json_encode($post_data)); 
      $this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
    
    public function index_put()
    {    	
    
    		// for sending raw
    }   
}

?>
