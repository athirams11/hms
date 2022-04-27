<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomerModel extends CI_Model 
{
	
	function deletedata($post_data = array())
	{
		$data_id = $post_data["data_id"];
		//$data_id = 1;
		if ($data_id > 0)
		{
			
				$this->db->start_cache();
		
				$this->db->where("CC_ID",$data_id);
				//$this->db->check_execute();
				$rata = $this->db->update("COMPANY_CUSTOMER",array("CC_CUSTOMER_ACTIVE" => 0));
				
				$this->db->stop_cache();
				$this->db->flush_cache();
				
				//$this->db->error();
				
				$updated_rows = $this->db->affected_rows();			    
			if($updated_rows==0)
			{
				$result 	= array('data'=> array('updated_rows'=>$updated_rows),
									'message'=>'Failed to delete Customer',
									'status'=>'Failed'
									);
				return $result;
			}
			else
			{
				$result 	= array('data'=> array('updated_rows'=>$updated_rows),
									'message'=>'Customer deleted Successfully',
									'status'=>'Success'
									);
				return $result;	
			}
		}
		else
		{
			$result 	= array('data'=> array('updated_rows'=>$updated_rows),
									'message'=>'Failed to delete Customer',
									'status'=>'Success'
									);
		  return $result ;	
		}
		
	}
} 
?>