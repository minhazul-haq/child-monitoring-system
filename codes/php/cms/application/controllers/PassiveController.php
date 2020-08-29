<?php

class PassiveController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
	
	//save location data to database
	public function dataFromPhoneAction()
	{
		if($this->_request->isPost())
		{
			$childId=$this->_request->getPost("childId");
			$latitude=$this->_request->getPost("latitude");
			$longitude=$this->_request->getPost("longitude");
			
			require_once "PassiveModel.php";
			
			try
			{
				$passiveModel=new PassiveModel();
				$passiveModel->insertData($childId,$latitude,$longitude);
			}
			catch(Zend_Db_Exception $e)
			{
				echo $e->getMessage();
			}
		}
		else
		{
			echo 'no data found';
		}
		
		$this->_helper->viewRenderer->setNoRender();
	}
	
	//verify child ID of a certain phone
	public function childIdVerificationAction()
	{
		if($this->_request->isPost())
		{
			$user_name=$this->_request->getPost("user_name");
			$password=$this->_request->getPost("password");
			$child_name=$this->_request->getPost("child_name");
			
			require_once "PassiveModel.php";
			try
			{
				$passiveModel=new PassiveModel();
				$response=$passiveModel->getValidity($user_name,$password,$child_name);
			}
			catch(Zend_Db_Exception $e)
			{
				echo $e->getMessage();
			}
		}
		else
		{
			echo 'no data found';
		}
		echo $response;
		
		$this->_helper->viewRenderer->setNoRender();
	}
	
	// save incoming and outgoing call number to database
	public function callFromPhoneAction()
	{
		echo "hello world";
		
		if($this->_request->isPost())
		{
			$childId=$this->_request->getPost("childId");
			$phoneNumber=$this->_request->getPost("phoneNumber");
			$phoneState=$this->_request->getPost("phoneState");
			
			require_once "PassiveModel.php";
			try
			{
				$passiveModel=new PassiveModel();
				$passiveModel->savePhoneState($childId,$phoneNumber,$phoneState);
			}
			catch(Zend_Db_Exception $e)
			{
				echo $e->getMessage();
			}
		}
		$this->_helper->viewRenderer->setNoRender();
	}
	
	// save phone number of sms to database
	public function smsToPhoneAction()
	{
		if($this->_request->isPost())
		{
			$phoneNumber=$this->_request->getPost("PhoneNumber");
			$childId=$this->_request->getPost("childId");
			
			require_once "PassiveModel.php";
			try
			{
				$passiveModel=new PassiveModel();
				$passiveModel->saveSMSNumber($phoneNumber,$childId);
			}
			catch(Zend_Db_Exception $e)
			{
				echo $e->getMessage();
			}
		}
		
		$this->_helper->viewRenderer->setNoRender();
	}
}

