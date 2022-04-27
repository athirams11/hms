<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utility {
	var $CI;
	public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->helper('url');
        $this->CI->config->item('base_url');
        $this->CI->load->database();
       
    }
	public function is_Duplicate($tablename,$fieldname,$value,$idfield="",$idvalue=0)
	{
		$this->CI->db->start_cache();
		if(is_array($fieldname))
		{
			$len = count($fieldname);
			$this->CI->db->group_start();
			for($i=0;$i<$len;$i++)
			{
				$this->CI->db->where("UPPER(".$fieldname[$i].")",trim(strtoupper($value[$i])));
			}
			$this->CI->db->group_end();
		}
		else
		{
			$this->CI->db->where($fieldname,$value);
		}
		
		if($idvalue !="" && $idfield !="")
		{
			$this->CI->db->where($idfield .' !=',$idvalue);
		}
		$this->CI->db->from($tablename);
		$query = $this->CI->db->get();
		
		$this->CI->db->stop_cache();
		$this->CI->db->flush_cache();
		//echo $this->CI->db->last_query();
		if ($query->num_rows() > 0)
		{
			return true; 
		}
		else
			return false;
		
	}
		
}
?>