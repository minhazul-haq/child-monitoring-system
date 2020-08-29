<?php

require_once ("dbConnector.php");

class FeedbackModel extends dbConnector
{
	//insert a feedback data
	public function insertIntoFeedback($name,$email,$comment)
	{	
		$db = $this->connectToDatabase();
		
		date_default_timezone_set('Etc/GMT-6'); //dhaka time

		$db->insert('feedback',array('name' => $name,
						             'email' => $email,
						   			 'comment' => $comment,
						   			 'time' => date('Y-m-d H:i:s'),
						   			 'is_checked' => 0));

		$db->closeConnection();
	}
}

?>

