<?php

require_once ("dbConnector.php");

class LocationModel extends dbConnector
{
	//insert a location data
	public function insertIntoLocations($child_id,$longitude,$latitude,$time)
	{
		$db = $this->connectToDatabase();
		
		$db->insert('locations',array("child_id" => $child_id,
								 	  "longitude" => $longitude,
									  "latitude" => $latitude,
								 	  "time" => $time));
		$db->closeConnection();
	}
	
	//get latest data
	public function getLatestData($child_id)
	{	
		$db = $this->connectToDatabase();
		
		$sqlString = "select longitude,latitude
					  from `locations`
					  where child_id='".$child_id."' 
					  order by time desc";
					  
		$result = $db->fetchRow($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
	
	//returns all location data
	public function getArchiveData($child_id)
	{	
		$db = $this->connectToDatabase();
		
		$sqlString = "select longitude,latitude,
					  date_format(time,'%d %b,%Y - %r') as formattedTime
					  from `locations`
					  where child_id='".$child_id."'
					  order by time desc";
					  
		$result = $db->fetchAll($sqlString);
		
		$db->closeConnection();
		
		return $result;
	}
}

