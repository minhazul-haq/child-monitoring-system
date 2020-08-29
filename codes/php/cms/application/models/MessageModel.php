<?php

require_once ("dbConnector.php");

class MessageModel extends dbConnector
{
	//insert a message data
	public function insertIntoMessages($child_id,$mobile_no,$message_data,$time,$sent_or_received)
	{	
		$db = $this->connectToDatabase();
		
		$db->insert('messages',array("child_id" => $child_id,
								 	 "mobile_no"=> $mobile_no,
									 "message_data"=> $message_data,
									 "time"=> $time,
									 "sent_or_received"=> $sent_or_received));
		$db->closeConnection();
	}
	
	//returns top 5 archived message data
	public function getShortData($child_id)
	{	
		$db = $this->connectToDatabase();
		
		$sqlString = "select mobile_no,message_data,
					  date_format(time,'%d %b,%Y - %r') as formattedTime,sent_or_received 
					  from `messages`
					  where child_id='".$child_id."'
					  order by time desc
					  limit 5";
					  
		$result = $db->fetchAll($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//returns archived message data
	public function getArchiveData($child_id)
	{	
		$db = $this->connectToDatabase();
		
		$sqlString = "select mobile_no,message_data,
					  date_format(time,'%d %b,%Y - %r') as formattedTime,sent_or_received 
					  from `messages`
					  where child_id='".$child_id."'
					  order by time desc";
					  
		$result = $db->fetchAll($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
}

