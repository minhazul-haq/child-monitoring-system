<?php

class dbConnector
{
	//connect to database
	protected function connectToDatabase()
	{
		$connParams=array( "host" => "localhost",
						   "username" => "childmon_rhythm",
						   "password" => "childmon_rhythm",
						   "dbname" => "childmon_cms" );
		
		return new Zend_Db_Adapter_Pdo_Mysql($connParams);
	}
}

?>

