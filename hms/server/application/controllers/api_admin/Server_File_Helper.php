<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Server_File_Helper extends REST_Controller
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
    }

    public function index_post()
    {
        $file_ext  = pathinfo(basename($_FILES['uploaded_file']['name']), PATHINFO_EXTENSION);
        $new_file_name = md5(date("YdmHisO").rand ( 10000 , 99999 )).".".$file_ext;
        $new_file_path = FCPATH.PROMOTION_MEDIA_PATH."/".$new_file_name;
        $new_web_location = $new_file_name;
        if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$new_file_path)) 
        {
           $message = [
                      'status' => 'Success',
                      'message'=> "File  saved successfully",
                      'data' => $new_web_location
                   ];

        }else {
            $message = [
                      'status' => 'failed',
                      'message'=> "File uploading Failed",
                      'data' => "no location",
                      'msg'=>$_FILES
                   ];

        }
     
         $this->response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
    
    public function index_put()
    {     
    
        // for sending raw
    }
   
}

?>