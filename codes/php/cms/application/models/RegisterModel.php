<?php

require_once ("dbConnector.php");

class RegisterModel extends dbConnector
{
	//returns whether there exists an user with username=$username
	public function isExistUser($username)
	{
		$db = $this->connectToDatabase();
		
		$sqlString = "select count(*) from `user` where user_name='".$username."'";
		$result = $db->fetchOne($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//returns whether there exists any email address equal to the $email
	public function isExistEmail($email)
	{
		$db = $this->connectToDatabase();
		
		$sqlString = "select count(*) from `user` where email='".$email."'";
		$result = $db->fetchOne($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//insert an user into 'user' table 
	public function insertIntoUser($user_name,$full_name,$email,$address,$mobile_no)
	{	
		$db = $this->connectToDatabase();
		$db->insert('user',array("user_name" => $user_name,
								 "full_name"=> $full_name,
								 "email"=> $email,
								 "address"=> $address,
								 "mobile_no"=> $mobile_no));
		$db->closeConnection();
	}

	//insert user's username & password into 'login' table
	public function insertIntoLogin($user_name,$password,$activation_key)
	{	
		$db = $this->connectToDatabase();
		$db->insert('login',array("user_name" => $user_name,
								  "password"=> sha1($password),
								  "activation_key"=>$activation_key,
								  "is_activated"=>0,
								  "is_logged"=>0));
		$db->closeConnection();
	}
	
	//check if username and activation key are valid
	public function isValidUserKey($user_name,$activation_key)
	{
		$db = $this->connectToDatabase();
		
		$sqlString = "select * from `login` where user_name='".$user_name."'";
		$result = $db->fetchRow($sqlString);
		
		if ($result && $result['activation_key']==$activation_key)
		{
			$db->closeConnection();
			return 1;
		}
		else
		{
			$db->closeConnection();
			return 0;
		}
	}
	
	//get user's information
	public function getUserInfomation($user_name)
	{
		$db = $this->connectToDatabase();
		
		$sqlString = "select * from `user` where user_name='".$user_name."'";
		$result = $db->fetchRow($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//get user's current activation key
	public function getCurrentActivationKey($user_name)
	{
		$db = $this->connectToDatabase();
		
		$sqlString = "select activation_key from `login` where user_name='".$user_name."'";
		$result = $db->fetchOne($sqlString);
			
		$db->closeConnection();
		
		return $result;
	}
	
	//activate an account
	public function activateUser($user_name)
	{
		$db = $this->connectToDatabase();
		
		$conditions[] = "user_name='".$user_name."'";
		
		//create an unique  activation code
		$new_activation_key = md5(uniqid(rand(),true));
		
		$newValues = array("is_activated"=>1,
						   "activation_key"=>$new_activation_key);
						   
		$db->update('login',$newValues,$conditions);
		$db->closeConnection();
	}
	
	//reset user password
	public function resetPassword($user_name,$password)
	{
		$db = $this->connectToDatabase();
		
		$conditions[] = "user_name='".$user_name."'";
		
		//create an unique  activation code
        $new_activation_key = md5(uniqid(rand(),true));
		
		$newValues = array("password"=>sha1($password),
						   "activation_key"=>$new_activation_key);
		
		$db->update('login',$newValues,$conditions);
		$db->closeConnection();
	}
	
}

