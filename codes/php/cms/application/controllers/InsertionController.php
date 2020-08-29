<?php

class InsertionController extends Zend_Controller_Action
{
	//insert into locations
	public function addLocationAction()
	{
		// action body
		try
		{
			require_once ("LocationModel.php");
			$violationModel = new LocationModel();
			
			if ($this->getRequest())
			{
				$child_id = $this->getRequest()->getParam('id');
				$longitude = $this->getRequest()->getParam('longitude');
				$latitude = $this->getRequest()->getParam('latitude');
				$time = $this->getRequest()->getParam('time');
				
				if ($child_id && $longitude && $latitude && $time)
				{
					$violationModel->insertIntoLocations($child_id,$longitude,$latitude,$time);	
				}
				
				$this->_helper->viewRenderer->setNoRender(true);	
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
	}
	
	//insert into calls
	public function addCallAction()
	{
		// action body
		try
		{
			require_once ("PhonecallModel.php");
			$phonecallModel = new PhonecallModel();
			
			if ($this->getRequest())
			{
				$id = $this->getRequest()->getParam('id');
				$mobile = $this->getRequest()->getParam('mobile');
				$time = $this->getRequest()->getParam('time');
				$duration = $this->getRequest()->getParam('duration');
				$type = $this->getRequest()->getParam('type');
				
				if ($id && $mobile && $time && $duration && $type)
				{
					$phonecallModel->insertIntoCalls($id,$mobile,$time,$duration,$type);	
				}
				
				$this->_helper->viewRenderer->setNoRender(true);	
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
	}
	
	//insert into messages
	public function addMessageAction()
	{
		// action body
		try
		{
			require_once ("MessageModel.php");
			$messageModel = new MessageModel();
		
			if ($this->getRequest())
			{
				$id = $this->getRequest()->getParam('id');
				$mobile = $this->getRequest()->getParam('mobile');
				$data = $this->getRequest()->getParam('data');
				$time = $this->getRequest()->getParam('time');
				$type = $this->getRequest()->getParam('type'); 
				
				if ($id && $mobile && $data && $time && $type)
				{
					$messageModel->insertIntoMessages($id,$mobile,$data,$time,$type);	
				}
				
				$this->_helper->viewRenderer->setNoRender(true);	
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
	}
	
	//insert into violations
	public function addViolationAction()
	{
		// action body
		try
		{
			require_once ("ViolationModel.php");
			$violationModel = new ViolationModel();
			
			if ($this->getRequest())
			{
				$child_id = $this->getRequest()->getParam('id');
				$violation_type = $this->getRequest()->getParam('type');
				$time = $this->getRequest()->getParam('time');
				
				if ($child_id && $violation_type && $time)
				{
					$violationModel->insertIntoViolations($child_id,$violation_type,$time);	
				}
				
				$this->_helper->viewRenderer->setNoRender(true);	
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
	}
}



