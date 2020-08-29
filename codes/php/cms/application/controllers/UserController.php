<?php

class UserController extends Zend_Controller_Action
{
	public function init()
    {
        /* Initialize action controller here */
    }
	
	//create selectChild form
	public function getSelectChildForm()
	{	
		require_once ("UserModel.php");
		$userModel = new UserModel();
		session_start();
		
		//create form
		$form = new Zend_Form();
		$form->setAction('home');
		$form->setMethod('post');
		
		//childname
		$form->addElement('select','childname');
		$childnameSelectfield = $form->getElement('childname');
		$childnameSelectfield->setLabel('Select ');
		$childnameSelectfield->setRequired(true);
		
		$childList = $userModel->getChildren($_SESSION['user']);
		
		if (count($childList)==0) return 0;
		
		foreach ($childList as $child)
		{
			$childnameSelectfield->addMultiOptions(array($child[child_id]=>$child[child_name]));
		}
		
		//submit button
		$form->addElement('submit','select');
		$selectButton = $form->getElement('select');
		$selectButton->setLabel('Select child');
				
		return $form;
	}
	
	//get latest location
	private function getLatestLocationData()
	{
		require_once ("LocationModel.php");
		$locationModel = new LocationModel();		
		
		session_start();
		return $locationModel->getLatestData($_SESSION['child_id']);
	}
	
	//get 3 phonecall data
	private function getShortPhonecallData()
	{
		require_once ("PhonecallModel.php");
		$phonecallModel = new PhonecallModel();		
		
		session_start();
		return $phonecallModel->getShortData($_SESSION['child_id']);
	}
	
	//get 3 message data
	private function getShortMessageData()
	{
		require_once ("MessageModel.php");
		$messageModel = new MessageModel();		
		
		session_start();
		return $messageModel->getShortData($_SESSION['child_id']);
	}
	
	//get 3 violation data
	private function getShortViolationData()
	{
		require_once ("ViolationModel.php");
		$violationModel = new ViolationModel();		
		
		session_start();
		return $violationModel->getShortData($_SESSION['child_id']);
	}
	
	public function homeAction()
    {
        // action body
		try
		{
			require_once ("UserModel.php");
			$userModel = new UserModel();
			
			session_start();
			
			if (isset($_SESSION['user']))
			{
				$this->view->datas = $userModel->getUserInfomation($_SESSION['user']);	
				$this->view->title = "User :: Home";
				$this->view->isHomePage = 1;
				$form = $this->getSelectChildForm();
				
				if ($fp = fopen('media/profile_pictures/'.$_SESSION['user'].'.jpg','r'))
					$this->view->proPicLocation = '/media/profile_pictures/'.$_SESSION['user'].'.jpg';
				else if ($fp = fopen('media/profile_pictures/'.$_SESSION['user'].'.png','r'))
					$this->view->proPicLocation = '/media/profile_pictures/'.$_SESSION['user'].'.png';
				else if ($fp = fopen('media/profile_pictures/'.$_SESSION['user'].'.bmp','r'))
					$this->view->proPicLocation = '/media/profile_pictures/'.$_SESSION['user'].'.bmp';
				else
					$this->view->proPicLocation = '/media/profile_pictures/blank.jpg';	
					
				if ($form)
					$childListOptions = $form->getElement('childname')->getMultiOptions();	
					
				if ($_POST)
				{
					if($form->isValid($_POST))
					{
						$child_id = $form->getValue('childname');
						$_SESSION['child_id'] = $child_id;
						$_SESSION['child_name'] = $childListOptions[$form->getValue('childname')];
						
						$this->view->form = $form;
					}
				}
				else
				{
					$this->view->form = $form;	
				}
				
				$this->view->locationData = $this->getLatestLocationData();
				$this->view->phonecallData = $this->getShortPhonecallData();
				$this->view->messageData = $this->getShortMessageData();	
				$this->view->violationData = $this->getShortViolationData();
			}
			else
			{
				$this->_redirect('/index/index');
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
	}
	
	//get all location data for a child
	public function locationAction()
	{
	    // action body
		try
		{
			require_once ("LocationModel.php");
			$locationModel = new LocationModel();	
			session_start();
			
			if (isset($_SESSION['user']))
			{
				$this->view->title = "User :: Location archive";
				
				//pagination
				$paginator = Zend_Paginator::factory($locationModel->getArchiveData($_SESSION['child_id']));
				$currentPage=1;
				
				$i = $this->_request->getQuery('i');
				if (!empty($i))
				{
					$currentPage = $this->_request->getQuery('i');
				}
				
				$paginator->setItemCountPerPage(20);
				$paginator->setPageRange(10);
				$paginator->setCurrentPageNumber($currentPage);
				
				$this->view->paginator = $paginator;
			}
			else
			{
				$this->_redirect('index/index');
			}	
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
    }
	
	//get all phonecall data for a child
	public function phonecallAction()
	{
		try
		{
			require_once ("PhonecallModel.php");
			$phonecallModel = new PhonecallModel();	
			session_start();
			
			if (isset($_SESSION['user']))
			{
				$this->view->title = "User :: Phonecall archive";
				
				//pagination
				$paginator = Zend_Paginator::factory($phonecallModel->getArchiveData($_SESSION['child_id']));
				$currentPage=1;
				
				$i = $this->_request->getQuery('i');
				if (!empty($i))
				{
					$currentPage = $this->_request->getQuery('i');
				}
				
				$paginator->setItemCountPerPage(20);
				$paginator->setPageRange(10);
				$paginator->setCurrentPageNumber($currentPage);
				
				$this->view->paginator = $paginator;
			}
			else
			{
				$this->_redirect('index/index');
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}	
	}
	
	//get all message data for a child
	public function messageAction()
    {
        // action body
		try
		{
			require_once ("MessageModel.php");
			$messageModel = new MessageModel();	
			session_start();
			
			if (isset($_SESSION['user']))
			{
				$this->view->title = "User :: Message archive";
				
				//pagination
				$paginator = Zend_Paginator::factory($messageModel->getArchiveData($_SESSION['child_id']));
				$currentPage=1;
				
				$i = $this->_request->getQuery('i');
				if (!empty($i))
				{
					$currentPage = $this->_request->getQuery('i');
				}
				
				$paginator->setItemCountPerPage(20);
				$paginator->setPageRange(10);
				$paginator->setCurrentPageNumber($currentPage);
				
				$this->view->paginator = $paginator;
			}
			else
			{
				$this->_redirect('index/index');
			}	
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
    }
	
	//get all violation data for a child
    public function violationAction()
    {
        // action body
		try
		{
			require_once ("ViolationModel.php");
			$violationModel = new ViolationModel();
			session_start();
			
			if (isset($_SESSION['user']))
			{
				$this->view->title = "User :: Violation tracker";
				
				//pagination
				$paginator = Zend_Paginator::factory($violationModel->getArchiveData($_SESSION['child_id']));
				$currentPage=1;
				
				$i = $this->_request->getQuery('i');
				if (!empty($i))
				{
					$currentPage = $this->_request->getQuery('i');
				}
				
				$paginator->setItemCountPerPage(20);
				$paginator->setPageRange(10);
				$paginator->setCurrentPageNumber($currentPage);
				
				$this->view->paginator = $paginator;
			}
			else
			{
				$this->_redirect('index/index');
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
    }
	
	//create add blocked number form
	private function getAddForm()
	{
		//create form
		$form = new Zend_Form();
		$form->setAction('blocking');
		$form->setMethod('post');
		
		//mobile number
		$form->addElement('text','mobile_no');
		$mobileTextfield = $form->getElement('mobile_no');
		$mobileTextfield->setLabel('Mobile no:');
		$mobileTextfield->size = 15;
		$mobileTextfield->addValidator(new Zend_Validate_Digits);
		$mobileTextfield->addValidator(new Zend_Validate_StringLength(11,15));
		$mobileTextfield->setRequired(true);
		
		//submit button
		$form->addElement('submit','submit');
		$loginButton = $form->getElement('submit');
		$loginButton->setLabel('Add');
				
		return $form;
	}
	
	public function blockingAction()
    {
        // action body
		try
		{
			require_once ("PhonecallModel.php");
			$phonecallModel = new PhonecallModel();
				
			session_start();
				
			if (isset($_SESSION['user']))
			{
				$form1 = $this->getAddForm();
				$this->view->title = "User :: Phonecall blocking";
				$this->view->isBlockingPage = 1;
					
				if ($_POST)
				{
					if($form1->isValid($_POST)) //if submitted form's data is valid
					{
						$mobile_no = $form1->getValue("mobile_no");
						$phonecallModel->addBlockedNumber($_SESSION['child_id'],$mobile_no);
								
						$this->view->form = $this->getAddForm();
					}
					else //if invalid data, then show submitted data + error msg.
					{
						$this->view->form = $form1;
					}
				}
				else //no data has been submitted. that means user has just clicked on "User registration" for new registration
				{
					$this->view->form = $form1;
				}
					
				$this->view->blockingData = $phonecallModel->getBlockedData($_SESSION['child_id']);
						
				if ($this->_request->getQuery("mobile"))
				{
					$phonecallModel->deleteBlockedNumber($_SESSION['child_id'],$this->_request->getQuery("mobile"));
					$this->_redirect('user/blocking');
				}			
			}
			else
			{
				$this->_redirect('index/index');
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
    }
	
	//create addChild form
	private function getAddChildForm()
	{
		//create form
		$form = new Zend_Form();
		$form->setAction('add-child');
		$form->setMethod('post');
		
		//childname
		$form->addElement('text','childname');
		$childnameTextfield = $form->getElement('childname');
		$childnameTextfield->setLabel('Child name: ');
		$childnameTextfield->size = 40;
		$childnameTextfield->addValidator(new Zend_Validate_StringLength(4,50));
		$childnameTextfield->setRequired(true);
		
		//submit button
		$form->addElement('submit','submit');
		$addButton = $form->getElement('submit');
		$addButton->setLabel('Add child');
				
		return $form;
	}
	
	public function addChildAction()
    {
        // action body
		try
		{
			require_once ("UserModel.php");
			$userModel = new UserModel();
			$form = $this->getAddChildForm();
				
			session_start();
			
			if (isset($_SESSION['user']))
			{
				$this->view->title = "User :: Add child";
				$this->view->isChildPage = 1;
				
				if ($_POST)
				{
					if($form->isValid($_POST)) //if submitted form's data is valid
					{
						$childname = $form->getValue("childname");
						$childId = $userModel->insertIntoChildren($_SESSION['user'],$childname);
						
						if($childId)
						{
							$this->view->message = 'Your child has been added';
						}
						else
						{
							$this->view->form = $form;
							$this->view->message = 'This child is already in database';
						}
					}
					else //if invalid data, then show submitted data + error msg.
					{	
						$this->view->form = $form;
					}	
				}
				else
				{
					$this->view->form = $form;
				}
			}
			else
			{
				$this->_redirect('index/index');
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
	}
	
	//create edit account form
	private function getEditAccountForm()
	{
		//create form
		$form = new Zend_Form();
		$form->setAction('edit-account');
		$form->setMethod('post');
		
		//fullname
		$form->addElement('text','fullname');
		$fullnameTextfield = $form->getElement('fullname');
		$fullnameTextfield->setLabel('Fullname: ');
		$fullnameTextfield->size = 50;
		$fullnameTextfield->addValidator(new Zend_Validate_StringLength(5,50));
		$fullnameTextfield->setRequired(true);
		
		//address
		$form->addElement('text','address');
		$addressTextfield = $form->getElement('address');
		$addressTextfield->setLabel('Contact address: ');
		$addressTextfield->size = 50;
		$addressTextfield->addValidator(new Zend_Validate_StringLength(10,100));
		$addressTextfield->setRequired(true);
		
		//mobile
		$form->addElement('text','mobile');
		$mobileTextfield = $form->getElement('mobile');
		$mobileTextfield->setLabel('Mobile no: ');
		$mobileTextfield->size = 20;
		$mobileTextfield->addValidator(new Zend_Validate_Digits);
		$mobileTextfield->addValidator(new Zend_Validate_StringLength(11,20));
		$mobileTextfield->setRequired(true);
		
		//submit button
		$form->addElement('submit','save');
		$saveButton = $form->getElement('save');
		$saveButton->setLabel('Save');

		return $form;
	}
	
    public function editAccountAction()
    {
		// action body
		try
		{
			require_once ("UserModel.php");
			$userModel = new UserModel();
		
			$form = $this->getEditAccountForm();
			
			session_start();
			
			if (isset($_SESSION['user']))
			{
				$this->view->title = "User :: Edit settings";
				$this->view->isEditPage = 1;
				
				$userInfo = $userModel->getUserInfomation($_SESSION['user']);
				
				if ($_POST)
				{
					if($form->isValid($_POST))
					{	
						$fullname = $form->getValue("fullname");
						$address = $form->getValue("address");
						$mobile = $form->getValue("mobile");
						
						$userModel->updateUserInformation($_SESSION['user'],$fullname,$address,$mobile);		
						$this->view->message = "Your account information has been updated";
					}
					else
					{
						$this->view->form = $form;
					}
				}
				else
				{
					$form->getElement("fullname")->setValue($userInfo['full_name']);
					$form->getElement("address")->setValue($userInfo['address']);
					$form->getElement("mobile")->setValue($userInfo['mobile_no']);
					$this->view->form = $form;
				}
			}
			else
			{
				$this->_redirect('index/index');
			}	
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
    }
	
	public function logoutAction()
    {
		// action body
		try
		{
			require_once ("UserModel.php");
			$userModel = new UserModel();
			session_start();
			
			if (isset($_SESSION['user']))
			{
				$userModel->logoutUser($_SESSION['user']);		
				unset($_SESSION['user']);
				session_destroy();
				$this->_redirect('index/index');
			}
			else
			{
				$this->_redirect('index/index');
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
    }	
}
?>