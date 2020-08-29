<?php

require_once ("dbConnector.php");

class UserModel extends dbConnector
{
	//get user's information
	public function getUserInfomation($username)
	{
		$db = $this->connectToDatabase();
		
		$sqlString = "select * from `user` where user_name='".$username."'";
		$result = $db->fetchRow($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//get children's name of a user
	public function getChildren($username)
	{
		$db = $this->connectToDatabase();
		
		$sqlString = "select child_id,child_name from `child` where user_name='".$username."'";
		$result = $db->fetchAll($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//get user's password
	public function getUserPassword($username)
	{
		$db = $this->connectToDatabase();
		
		$sqlString = "select password from `login` where user_name='".$username."'";
		$result = $db->fetchOne($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//insert a child into 'children' table 
	public function insertIntoChildren($user_name,$child_name)
	{	
		$db = $this->connectToDatabase();
		
		$totalChild = 0;
		$sqlString = "select count(*) from `child`";
		$totalChild = $db->fetchOne($sqlString);
		
		$sqlString = "select count(*)
					  from `child` 
					  where user_name='".$user_name."' and
					  child_name='".$child_name."'";
		$isExistChild = $db->fetchOne($sqlString);
		
		if ($isExistChild)
		{
			$db->closeConnection();
			return 0;
		} 
				
		$db->insert('child',array("user_name" => $user_name,
								 "child_id"=> 'ch'.($totalChild+1),
								 "child_name"=> $child_name));
		$db->closeConnection();
		return 'ch'.($totalChild+1);
	}
	
	//update user information
	public function updateUserInformation($user_name,$full_name,$address,$mobile_no)
	{	
		$db = $this->connectToDatabase();
		
		$conditions[] = "user_name='".$user_name."'";
		
		$newValues = array("full_name"=>$full_name,
						   "address"=>$address,
						   "mobile_no"=>$mobile_no);
		
		$db->update('user',$newValues,$conditions);
		$db->closeConnection();
	} 	
	
	//check whether username & password is correct
	public function isValidLogin($user_name,$password)
	{
		$db = $this->connectToDatabase();
		
		$sqlString = "select * from `login` where user_name='".$user_name."'";
		$result = $db->fetchRow($sqlString);
		
		if (strcmp($result['password'],sha1($password))==0)
		{
			$conditions[] = "user_name='".$user_name."'";
			$newValues = array("is_logged"=>1);
			
			if ($result['is_activated']==0)
			{
				$db->closeConnection();
				return -1; //not activated
			}
			else
			{
				$db->update('login',$newValues,$conditions);			
				$db->closeConnection();
				return 1; //OK
			}
		}
	
		$db->closeConnection();
		return 0; //wrong username or password
	}
	
	//logout user
	public function logoutUser($user_name)
	{
		$db = $this->connectToDatabase();
		
		$conditions[] = "user_name='".$user_name."'";
		$newValues = array("is_logged"=>0);
		
		$db->update('login',$newValues,$conditions);
		$db->closeConnection();
	}
}

