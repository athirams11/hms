<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	*
 	* Class ApiModel using to create and Maintain items
 	*
 	*
 	*/
	class ApiModel extends CI_Model {
	
		/**
		*
	 	* Function for add or update items
	 	*
	 	* @param   input post parameters 
	 	* @return   bool
	 	*
	 	*/
	 	
		public function update_number_stat($post_data)
		{
		 
 			$this->delete_number_from_api($post_data); 
			$newStat = $this->ManageModel->get_service_data_value('WORK_ORDER_TYPE',$post_data['OpType'],false);
			if($newStat!=''){				
					$this->db->trans_start();
					$this->db->where("PHONE_NUMBER", $post_data['msisdn']);	
					$data = array("PHONE_NUMBERS_STATUS" => $newStat);		
					$this->db->update("PHONE_NUMBERS",$data);	
					 //      echo $this->db->last_query();			 
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === FALSE)
					{
					    return false;
					}
					return true;
			}
			return false;
		}
	 	
	 	public function delete_number_from_api_old($param_data)
	 	{
	 		if($param_data['OpType'] == '8'){
	 				$this->SimRequestModel->log_number_history($param_data);
	 				$this->SimRequestModel->update_terminate_sim_provison($param_data);
        			return true;
        		}        		
        	return false;
	 	}
	 	
	 	public function delete_number_from_api($param_data)
	 	{
	 		if($param_data['OpType'] == '8'){
	 				$this->SimRequestModel->log_number_history($param_data);
        			$subscriber_arr = $this->ManageModel->get_client($param_data["msisdn"] );        
        			if($subscriber_arr['data_row']['PaymentMode'] =='0'){
        					log_activity('delete','subscriber delete ',$subscriber_arr['data_row'] );    
        					$this->CronJobModel->call_delete_provison_ema( $subscriber_arr['data_row'] );
        					$this->CronJobModel->call_deactivation_provison_cbs($subscriber_arr['data_row'] );
        			} 
        			$this->SimRequestModel->update_terminate_sim_provison($param_data);
        			return true;
        		}        		
        	return false;
	 	}
		 
		function getNumberId($number) 
		{
			$this->db->where("PHONE_NUMBER",$number);		
			$query = $this->db->get("PHONE_NUMBERS");
			if ($query->num_rows() > 0)
				return $query->row();
			else
				return false;
		}
		
	#public function parce_xml_array($raw_post_xml){
	public function parce_xml_to_array($raw_post_xml, $part='Body')
	{
		$bodyparams =  $this->xmlparse->get_xml_part($raw_post_xml,'//'.$part.'/*');
		$bodyparams = $this->xmlparse->delete_empty_nodes($bodyparams);				    	   	  																
		$xmlObject = simplexml_load_string($bodyparams);					
		$inputArr =  $this->xml2json->xmlToArray($xmlObject,'root');
		return $inputArr;
	}
	
	 #public function response($data = NULL, $http_code = NULL)	
	 public function response($data = NULL, $http_code = NULL)
    {
		ob_start();
        // If the HTTP status is not NULL, then cast as an integer
        if ($http_code !== NULL)
        {
            // So as to be safe later on in the process
            $http_code = (int) $http_code;
        }

        // Set the output as NULL by default
        $output = NULL;

        // If data is NULL and no HTTP status code provided, then display, error and exit
        if ($data === NULL && $http_code === NULL)
        {
            $http_code = self::HTTP_NOT_FOUND;
        }

        // If data is not NULL and a HTTP status code provided, then continue
        elseif ($data !== NULL)
        {      
        	if(is_array($data)){
        		ini_set('memory_limit', '-1');
				$output = json_encode($data);		
				$this->output->set_content_type('application/json');
        	}else{
				$output = $data;		
				$this->output->set_content_type('application/xhtml+xml');				        		
        	}          					 
        }
      
        // Output the data
        $this->output->set_output($output);

        ob_end_flush();

        // Otherwise dump the output automatically
    }
    
    public function replace_xml_tag($key){    	
    	 return '<'.$key.'>'.$key.'</'.$key.'>';
    }
    
    
    #public function mandatory_check($chkarr){
    public function mandatory_check($identifier_array, $checkarr){
    	$ret='';
    			foreach($checkarr as $iden) {						
				   if(empty($identifier_array[$iden])){
								$ret = $iden;
				   }
				}
		return $ret;
    }
}
?>