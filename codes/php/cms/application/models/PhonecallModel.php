<?php

require_once ("dbConnector.php");

class PhonecallModel extends dbConnector
{
	//insert a phonecall data
	public function insertIntoCalls($child_id,$mobile_no,$time,$duration,$dialed_or_received)
	{	
		$db = $this->connectToDatabase();
		
		$db->insert('calls',array("child_id" => $child_id,
								  "mobile_no" => $mobile_no,
								  "time" => $time,
								  "duration" => $duration,
								  "dialed_or_received" => $dialed_or_received));
		$db->closeConnection();
	}
	
	//returns blocked numbers
	public function getBlockedData($child_id)
	{	
		$db = $this->connectToDatabase();
		
		$sqlString = "select blocked_no
					  from `blockednumbers`
					  where child_id='".$child_id."'";
					  
		$result = $db->fetchAll($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//delete a blocked number
	public function deleteBlockedNumber($child_id,$blocked_no)
	{	
		$db = $this->connectToDatabase();
		
		$conditions[] = "child_id='".$child_id."'";
		$conditions[] = "blocked_no='".$blocked_no."'";
		
		$db->delete('blockednumbers',$conditions);
		
		$db->closeConnection();
	}
	
	//add a blocked number
	public function addBlockedNumber($child_id,$blocked_no)
	{	
		$db = $this->connectToDatabase();
		
		$sqlString = "select count(*) 
					  from `blockednumbers`
					  where child_id='".$child_id."' and
					  blocked_no='".$blocked_no."'";
		$isThere = $db->fetchOne($sqlString);
		
		if ($isThere==0)
			$db->insert('blockednumbers',array("child_id" => $child_id,
								 		   "blocked_no" => $blocked_no));
		$db->closeConnection();
	}
	
	//returns top 5 phonecalls data
	public function getShortData($child_id)
	{	
		$db = $this->connectToDatabase();
		
		$sqlString = "select dialed_or_received,mobile_no,
					  date_format(time,'%d %b,%Y - %r') as formattedTime 
					  from `calls`
					  where child_id='".$child_id."'
					  order by time desc
					  limit 5";
					  
		$result = $db->fetchAll($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//returns archived phonecalls data
	public function getArchiveData($child_id)
	{	
		$db = $this->connectToDatabase();
		
		$sqlString = "select dialed_or_received,mobile_no,
					  date_format(time,'%d %b,%Y - %r') as formattedTime 
					  from `calls`
					  where child_id='".$child_id."'
					  order by time desc";
					  
		$result = $db->fetchAll($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
}

