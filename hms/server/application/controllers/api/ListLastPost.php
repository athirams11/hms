<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
$origin = $_SERVER['HTTP_ORIGIN'];
$allowed_domains = [
    'http://www.wiktait.com',
    'https://www.wiktait.com',
    'http://symp.mobi',
];

if (in_array($origin, $allowed_domains)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}
//header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class ListLastPost extends REST_Controller
{	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model('ApiModel');
        //$this->load->model('api/ItemsModel');
        
	  }
	 
    public function index_get()
    {
     	// for GET Method
       $result     = $this->ApiModel->ListLastPost();
      $this->response($result); 
    }

    public function index_post()
    {
      // for POST Method    	
           
    }
    
    public function index_put()
    {    	
    
    		// for sending raw
    }   
}

?>
