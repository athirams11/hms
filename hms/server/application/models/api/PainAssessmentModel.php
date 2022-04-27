<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PainAssessmentModel extends CI_Model 
{

	
	public function savePainAssesment($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		if(isset($post_data["assessment_id"]))
		{
			$data = array(
				"ASSESSMENT_ID" => trim($post_data["assessment_id"]),
				"PAIN_SCORE" => trim($post_data["pain_score"]),
				"LOCATION" => trim($post_data["location"]),
				"PAIN_INTENSITY" => trim($post_data["pain_intensity"]),
				"PAIN_CHARACTER" => trim($post_data["pain_character"]),
				"FREQUENCY" => trim($post_data["frequency"]),
				"DURATION" => trim($post_data["duration"]),
				"RADIATION" => trim($post_data["radiation"])
			);
			$data_id = trim($post_data["pain_assessment_id"]);
			if ($data_id > 0)
			{
				$data["UPDATED_BY"] = $post_data["user_id"];
				$data["UPDATED_ON"] = date("Y-m-d H:i:s");
				$this->db->where("PAIN_ASSESSMENT_ID",$data_id);
				$this->db->update("PAIN_ASSESSMENT",$data);
				return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Pain assessment details saved successfully");
			}
			else
			{
				$data["DATE_TIME"] = toUtc(trim($post_data["date"]),$post_data["timeZone"]); //date("Y-m-d H:i:s");
				$data["CREATED_BY"] = $post_data["user_id"];
				$data["CREATED_ON"] = date("Y-m-d H:i:s");
				if($this->db->insert("PAIN_ASSESSMENT",$data))
				{
					$data_id = $this->db->insert_id();
					return array("status"=> "Success", "data_id"=>$data_id, "msg"=> "Pain assessment details saved successfully");
				}
			}
		}
		return $result;
	}


	public function getPainAssesmentpain($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		if(isset($post_data["assessment_id"]) && (int)($post_data["assessment_id"]) > 0 )
		{
			$this->db->start_cache();

			$this->db->select("P.*,U.USER_ACCESS_TYPE");
			$this->db->from("PAIN_ASSESSMENT P");
			$this->db->join("USERS U","U.USER_SPK = P.CREATED_BY","left");
			// $this->db->join("MASTER_DATA M","M.MASTER_DATA_ID = P.PAIN_INTENSITY","left");
			// $this->db->join("MASTER_DATA Ms","Ms.MASTER_DATA_ID = P.PAIN_CHARACTER","left");
			//$this->db->where("P.PAIN_ASSESSMENT_ID",$post_data["pain_assessment_id"]);
			$this->db->where("P.ASSESSMENT_ID",$post_data["assessment_id"]);
			//$this->db->where("P.CREATED_BY",$post_data["user_id"]);
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
		
			$this->db->stop_cache();
			$this->db->flush_cache();

			// $this->db->select("*");
			// $this->db->from("PAIN_ASSESSMENT P");
			// $this->db->where("P.ASSESSMENT_ID",$post_data["assessment_id"]);
			// $this->db->where("P.CREATED_BY !=",$post_data["user_id"]);
			// $querys = $this->db->get();
			// // echo $this->db->last_query();exit;
			

			// $this->db->stop_cache();
			// $this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = array("status"=> "Success", "data"=> $query->result_array());
			}

		}
		return $result;
	}


	public function getPainAssesment($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		if(isset($post_data["assessment_id"]) && (int)($post_data["assessment_id"]) > 0 )
		{
			$this->db->start_cache();

			$this->db->select("*");
			$this->db->from("PAIN_ASSESSMENT P");
			// $this->db->join("MASTER_DATA M","M.MASTER_DATA_ID = P.PAIN_INTENSITY","left");
			// $this->db->join("MASTER_DATA Ms","Ms.MASTER_DATA_ID = P.PAIN_CHARACTER","left");
			//$this->db->where("P.PAIN_ASSESSMENT_ID",$post_data["pain_assessment_id"]);
			$this->db->where("P.ASSESSMENT_ID",$post_data["assessment_id"]);
			//$this->db->where("P.CREATED_BY",$post_data["user_id"]);
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
		
			$this->db->stop_cache();
			$this->db->flush_cache();

			// $this->db->select("*");
			// $this->db->from("PAIN_ASSESSMENT P");
			// $this->db->where("P.ASSESSMENT_ID",$post_data["assessment_id"]);
			// $this->db->where("P.CREATED_BY !=",$post_data["user_id"]);
			// $querys = $this->db->get();
			// // echo $this->db->last_query();exit;
			

			// $this->db->stop_cache();
			// $this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = array("status"=> "Success", "data"=> $query->result_array());
			}

		}
		return $result;
	}
	public function getPainAssesments($post_data)
	{
		$result = array("status"=> "Failed", "data"=> array());
		if(isset($post_data["assessment_id"]) && (int)($post_data["assessment_id"]) > 0  && $post_data["user_id"] > 0)
		{
			
		
			$this->db->start_cache();

			$this->db->select("P.*,U.USER_ACCESS_TYPE");
			$this->db->from("PAIN_ASSESSMENT P");
			$this->db->join("USERS U","U.USER_SPK = P.CREATED_BY","left");
			$this->db->where("P.ASSESSMENT_ID",$post_data["assessment_id"]);
			$this->db->where("P.CREATED_BY !=",$post_data["user_id"]);
			// $this->db->where("U.USER_ACCESS_TYPE !=",$post_data["user_group"]);
			$query = $this->db->get();

			$this->db->stop_cache();
			$this->db->flush_cache();
			if($query->num_rows() > 0)
			{
				$result = array("status"=> "Success", "data"=> $query->result_array());
			}

		}
		return $result;
	}
} 
?>
