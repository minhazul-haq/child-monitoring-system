<?php

class RegisterController extends Zend_Controller_Action
{
	public function init()
    {
        /* Initialize action controller here */
    }

	//create registration form
	private function getRegistrationForm()
	{
		//create form
		$form = new Zend_Form();
		$form->setAction('register');
		$form->setMethod('post');
		
		//fullname
		$form->addElement('text','fullname');
		$fullnameTextfield = $form->getElement('fullname');
		$fullnameTextfield->setLabel('Fullname: ');
		$fullnameTextfield->size = 50;
		$fullnameTextfield->addValidator(new Zend_Validate_StringLength(5,50));
		$fullnameTextfield->setRequired(true);
		
		//email
		$form->addElement('text','email');
		$emailTextfield = $form->getElement('email');
		$emailTextfield->setLabel('Email address: ');
		$emailTextfield->size = 50;
		$emailTextfield->addValidator(new Zend_Validate_EmailAddress);
		$emailTextfield->addValidator(new Zend_Validate_StringLength(10,50));
		$emailTextfield->setRequired(true);
		
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
		
		//username
		$form->addElement('text','username');
		$usernameTextfield = $form->getElement('username');
		$usernameTextfield->setLabel('Username: ');
		$usernameTextfield->size = 15;
		$usernameTextfield->addValidator(new Zend_Validate_Alnum());
		$usernameTextfield->addValidator(new Zend_Validate_StringLength(5,15));
		$usernameTextfield->setRequired(true);
		
		//password
		$form->addElement('password','password');
		$passwordTextfield = $form->getElement('password');
		$passwordTextfield->setAttrib('id','givenPassword');
		$passwordTextfield->setLabel('Password: ');
		$passwordTextfield->size = 15;
		$passwordTextfield->addValidator(new Zend_Validate_StringLength(5,15));
		$passwordTextfield->setRequired(true);
		
		//retype password
		$form->addElement('password','repassword');
		$repasswordTextfield = $form->getElement('repassword');
		$repasswordTextfield->setLabel('Retype password: ');
		$repasswordTextfield->size = 15;
		$repasswordTextfield->addValidator(new Zend_Validate_StringLength(5,15));
		$repasswordTextfield->setRequired(true);	
		
		//profile picture file upload
		$fileUploadElement = new Zend_Form_Element_File
							('Picture', array('label' => 'Select profile picture:',
											  'required' => true,
            								  'MaxFileSize' => 1048576, //1MB
            								  'validators' => array(array('Count',false,1),
                													array('Size', false, 1048576),
                													array('Extension',false,'bmp,jpg,png'),
                 													array('ImageSize',false,array('minwidth' => 100,
 																								  'minheight' => 100,
 																								  'maxwidth' => 1000,
																								  'maxheight' => 1000)))));
		$fileUploadElement->setDestination('media/profile_pictures/');
		$form->addElement($fileUploadElement);
				
		//captcha
		$captchaElement = new Zend_Form_Element_Captcha
						('captchaElement', array('captcha' => array('captcha' => 'Image',
																 'wordLen' => 5,
																 'required' => true,
																 'width' => 150,
    															 'height' => 50,
																 'imgDir' => 'media/captcha/',
																 'imgUrl' => '/media/captcha/',
				        										 'timeout' => 600,
																 'font' => 'media/arial.ttf')));				
		$captchaElement->setLabel('Prove you\'re human: ');
		$form->addElement($captchaElement); 	
		
		//submit button
		$form->addElement('submit','submit');
		$submitButton = $form->getElement('submit');
		$submitButton->setLabel('Regiser');

		return $form;
	}
			
	//sends smtp mail
	private function sendMail($emailAddress,$fullname,$username,$activationKey)
	{
		//config
		$config = array('auth' => 'login',
           				'username' => 'services+childmonitoringbd.com',
           				'password' => 'childmon_rhythm',
           				'port' => 25);

        $mailTransport = new Zend_Mail_Transport_Smtp('mail.childmonitoringbd.com',$config);
        Zend_Mail::setDefaultTransport($mailTransport);
    	
		//prepare email
		$mail = new Zend_Mail();
		$mail->addTo($emailAddress);
		$mail->setSubject("Child Monitoring System_Account activation link");
		$activationURL = "http://www.childmonitoringbd.com/register/activate?username=".$username."&key=".$activationKey;
		$mail->setBodyText("Hi ".$fullname.
							",\n\nVisit the link to activate your CMS account:\n"
							.$activationURL.
							"\n\nRegards,\nChild Monitoring System developer team.");
		$mail->setFrom("services@childmonitoringbd.com","Child Monitoring System");
			
		//send it!
	   	$mail->send();
	}
	
	//show registration form & then insert data into database when submitted
    public function registerAction()
    {
        // action body		
		try
		{
			require_once ("RegisterModel.php");
			$registerModel = new RegisterModel();
			
			$this->view->title = "CMS :: User registration";
			$form = $this->getRegistrationForm();
			
			if ($_POST) //if form has been submitted
			{
				if($form->isValid($_POST)) //if submitted form's data is valid
				{
					//get all data from form
					$username = $form->getValue("username");
					$fullname = $form->getValue("fullname");
					$email = $form->getValue("email");
					$address = $form->getValue("address");
					$mobile = $form->getValue("mobile");
					$password = $form->getValue("password");
					$repassword = $form->getValue("repassword");
					
					if ($registerModel->isExistUser($username))
					{
						$this->view->form = $form;
						$this->view->message = "Please try again with another username";
					}
					else if ($registerModel->isExistEmail($email))
					{
						$this->view->form = $form;
						$this->view->message = "Please try again with another email address";
					}
					else if (strcmp($password,$repassword)!=0)
					{
						$this->view->form = $form;
						$this->view->message = "Both of the passwords must be same";
					}
					else
					{	
						//create an unique  activation code
						$activationKey = md5(uniqid(rand(),true));
						
						//save profile picture
						$form->Picture->receive();
						
						//rename the profile picture to username
						$oldFilename = $form->Picture->getFileName(); 
						$fileExtension = pathinfo($oldFilename,PATHINFO_EXTENSION);
						$newPathFilename = 'media/profile_pictures/'.$username.'.'.$fileExtension;
										
						$filterRenameFile = new Zend_Filter_File_Rename(array('target' => $newPathFilename, 'overwrite' => true));
						$filterRenameFile->filter($oldFilename); 
						
						//insert data
						$registerModel->insertIntoUser($username,$fullname,$email,$address,$mobile);
						$registerModel->insertIntoLogin($username,$password,$activationKey);
					
						//send email
						$this->sendMail($email,$fullname,$username,$activationKey);
						
						$this->view->message = 'An activation email has been sent to your email address.<br>Please click on the activation link to activate your account.';
					}		
				}
				else //if invalid data, then show submitted data + error msg.
				{
					$this->view->form = $form;
				}
			}
			else //no data has been submitted. that means user just opened the page
			{
				$this->view->form = $form;
			}
		}
		catch(Exception $e)
		{
			echo $e;
			//$this->_redirect('error');
		}
	}
	
	//activate an user when he visits the activation link
    public function activateAction()
    {
		try
		{
			$this->view->title = "CMS :: Activate account";
			
			if ($_GET)
			{
				require_once ("RegisterModel.php");
				$registerModel = new RegisterModel();
						
				$userName = $this->_request->getQuery("username");
				$activationKey = $this->_request->getQuery("key");
				
				if ($registerModel->isValidUserKey($userName,$activationKey))
				{
					$registerModel->activateUser($userName);
					$this->view->imageIcon = "ok.png";
					$this->view->message = "Congratulations! Your account has been activated now";
				}
				else
				{
					$this->view->imageIcon = "error.png";
					$this->view->message = "This is a dead link";
				}
			}
			else
			{
				$this->view->imageIcon = "error.png";
				$this->view->message = "This page was loaded incorrectly";
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
	}
	
	//create password reset form
	private function getPasswordResetForm()
	{
		//create form
		$form = new Zend_Form();
		$form->setAction('reset');
		$form->setMethod('post');
		
		//password
		$form->addElement('password','password');
		$passwordTextfield = $form->getElement('password');
		$passwordTextfield->setLabel('New password:');
		$passwordTextfield->size = 15;
		$passwordTextfield->addValidator(new Zend_Validate_StringLength(5,15));
		$passwordTextfield->setRequired(true);
		
		//retype password
		$form->addElement('password','repassword');
		$repasswordTextfield = $form->getElement('repassword');
		$repasswordTextfield->setLabel('Retype password:');
		$repasswordTextfield->size = 15;
		$repasswordTextfield->addValidator(new Zend_Validate_StringLength(5,15));
		$repasswordTextfield->setRequired(true);		
		
		//submit button
		$form->addElement('submit','change');
		$changeButton = $form->getElement('change');
		$changeButton->setLabel('Change password');

		return $form;
	}
	
	// reset an user's password
    public function resetAction()
    {
		try
		{
			$this->view->title = "CMS :: Reset password";
			
			if ($_GET)
			{
				require_once ("RegisterModel.php");
				$registerModel = new RegisterModel();
			
				$userName = $this->_request->getQuery("username");
				$activationKey = $this->_request->getQuery("key");
				
				if ($registerModel->isValidUserKey($userName,$activationKey))
				{
					//create a form
					$form = $this->getPasswordResetForm();
					$form->setAction('/register/reset?username='.$userName.'&key='.$activationKey);
					
					if($_POST) //if form has been submitted
					{	
						if($form->isValid($_POST)) //if submitted form's data is valid
						{
							//if form is submitted then process it
							$password = $form->getValue("password");
							$rePassword = $form->getValue("repassword");
							
							if ($password==$rePassword)
							{
								$registerModel->resetPassword($userName,$password);
								$this->view->imageIcon = "ok.png";
								$this->view->message = "Your password has been updated";
							}
							else
							{
								$this->view->message = "Both fields must be same";
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
						$this->view->form = $form;
					}
				}
				else
				{
					$this->view->imageIcon = "error.png";
					$this->view->message = "This is a dead link";
				}
			}
			else
			{
				$this->view->imageIcon = "error.png";
				$this->view->message = "This page was loaded incorrectly";
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
	}
}





