<?php

require_once ("dbConnector.php");

class ViolationModel extends dbConnector
{
	//insert a violation data
	public function insertIntoViolations($child_id,$violation_type,$time)
	{	
		$db = $this->connectToDatabase();
		
		$db->insert('violations',array("child_id" => $child_id,
								 	   "violation_type"=> $violation_type,
								 	   "time"=>$time));
		$db->closeConnection();
	}
	
	//returns top 3 violation data
	public function getShortData($child_id)
	{	
		$db = $this->connectToDatabase();
		
		$sqlString = "select violation_type,date_format(time,'%d %b,%Y - %r') as formattedTime 
					  from `violations`
					  where child_id='".$child_id."' 
					  order by time desc
					  limit 3";
					  
		$result = $db->fetchAll($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//returns all violation data
	public function getArchiveData($child_id)
	{	
		$db = $this->connectToDatabase();
		
		$sqlString = "select violation_type,date_format(time,'%d %b,%Y - %r') as formattedTime 
					  from `violations`
					  where child_id='".$child_id."' 
					  order by time desc";
					  
		$result = $db->fetchAll($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
}

?>

