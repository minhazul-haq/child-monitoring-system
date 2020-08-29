<?php

require_once ("dbConnector.php");

class PassiveModel extends dbConnector
{
	//insert data into locations
	public function insertData($childId,$latitude,$longitude)
	{
		$db = $this->connectToDatabase();
		
		//inserting data in the table 
		$db->insert('locations',array("child_id"=>$childId,
									  "longitude"=>$longitude,
									  "latitude"=>$latitude,
									  "time"=>new Zend_Db_Expr("NOW()")));
									 
		$db->closeConnection();
	}
	
	//return if the username,password and childname is valid
	public function getValidity($user_name,$password,$child_name)
	{
		$db = $this->connectToDatabase();
			
		$sqlString = "select * from `login` where user_name='".$user_name."'";
		$result = $db->fetchRow($sqlString);	
		 
		if (strcmp($result['password'],sha1($password))==0) //username,password correct
		{
			$sqlString = "select * from `child` where user_name='".$user_name."' and child_name='".$child_name."'";
			
			$result = $db->fetchRow($sqlString);
			
			if ($result)
			{
				return $result['child_id'];
			}
			else
			{				
				return 0; 
			}
		 }
		 else //username,password wrong
		 {
			 $db->closeConnection();
			 return 0;
		 }
	}
		
	//save incoming and outgoing SMS number to database
	public function saveSMSNumber($phoneNumber,$childId)
	{
		$db = $this->connectToDatabase();
		
		$db->insert('messages',array("child_id"=>$childId,
									 "mobile_no"=>$phoneNumber,
									 "message_data"=>'abc',
									 "time"=>new Zend_Db_Expr("NOW()"),
									 "sent_or_received"=>0));
		$db->closeConnection();
	}
	
	// save incoming and outgoing call number to database
	public function savePhoneState($childId,$phoneNumber,$phoneState)
	{
		$db = $this->connectToDatabase();
		
		$db->insert('calls',array("child_id"=>$childId,
								  "mobile_no"=>$phoneNumber,
								  "time"=>new Zend_Db_Expr("NOW()"),
								  "duration"=>0,
								  "dialed_or_received"=>$phoneState));
		$db->closeConnection();
	}
}

?>