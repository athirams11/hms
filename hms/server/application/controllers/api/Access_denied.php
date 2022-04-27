<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Controller: access_denied to create and maintain access_denied.
Date created: 27-12-2016
*/

class Access_denied extends CI_Controller {
	
	public function __construct() {
		parent::__construct();		
		
					
   }
	

 
	
	public function index()
	{		         
			$this->load->view('access_denied');		 
	}
	
}

?>