<?php

class IndexController extends Zend_Controller_Action
{
	public function init()
    {
        /* Initialize action controller here */
	}
	
	//create login form
	private function getLoginForm()
	{
		//create form
		$form = new Zend_Form();
		$form->setAction('index');
		$form->setMethod('post');
		
		//username
		$form->addElement('text','username');
		$usernameTextfield = $form->getElement('username');
		$usernameTextfield->setLabel('Username:');
		$usernameTextfield->size = 15;
		
		//password
		$form->addElement('password','password');
		$passwordTextfield = $form->getElement('password');
		$passwordTextfield->setLabel('Password: ');
		$passwordTextfield->size = 15;
		
		//submit button
		$form->addElement('submit','submit');
		$loginButton = $form->getElement('submit');
		$loginButton->setLabel('Login');
				
		return $form;
	}
	
	public function indexAction()
    {
        // action body
		try
		{	
			$this->view->title = "CMS :: Home";
			$form = $this->getLoginForm();
			
			if($_POST) //if form has been submitted
			{
				if($form->isValid($_POST)) //if submitted form's data is valid
				{
					require_once ("UserModel.php");
					$userModel = new UserModel();
			
					//get all data from form
					$username = $form->getValue("username");
					$password = $form->getValue("password");
					
					if ($username=="" || $password=="" ) 
					{
						$this->view->message = "Fillup both fields";
						$this->view->form = $form;
					}
					else if($userModel->isValidLogin($username,$password)==1)
					{
						session_start();
						$_SESSION['user'] = $username;
						//redirect if correct
						$this->_redirect('user/home');
					}	
					else if ($userModel->isValidLogin($username,$password)==0)
					{
						//show error msg
						$this->view->message = "Wrong username or password";
						$this->view->form = $form;
					}
					else if ($userModel->isValidLogin($username,$password)==-1)
					{
						//show error msg
						$this->view->message = "Profile not activated";
						$this->view->form = $form;
					}
				}
				else //if invalid data, then show submitted data + error msg.
				{
					$this->view->form = $form;
				}
			}
			else //no data has been submitted. that means user has just clicked on "User registration" for new registration
			{
				$this->view->form = $form;
			}		
			
			$this->view->frontPage=1;
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
    }
	
	private function getForgotPasswordForm()
	{
		//create form
		$form = new Zend_Form();
		$form->setAction('forgot');
		$form->setMethod('post');
		
		//username
		$form->addElement('text','username');
		$usernameTextfield = $form->getElement('username');
		$usernameTextfield->setLabel('Username: ');
		$usernameTextfield->size = 15;
		$usernameTextfield->addValidator(new Zend_Validate_StringLength(5,15));
		$usernameTextfield->setRequired(true);
		
		//send email button
		$form->addElement('submit','sendEmail');
		$sendEmailButton = $form->getElement('sendEmail');
		$sendEmailButton->setLabel('Send email');
				
		return $form;
	}
	
	//sends smtp mail
	private function sendMail($username,$fullname,$emailAddress,$currentActivationKey)
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
		$mail->setSubject("Children Monitoring System_Reset password");
			
		$resetPasswordURL = "http://childmonitoringbd.com/register/reset?username=".$username."&key=".$currentActivationKey;
			
		$mail->setBodyText("Hi ".$fullname.",\n\n".
						   "Visit the link to reset your CMS login password:\n".
						   $resetPasswordURL."\n\n".
						   "Regards,\nChildren Monitoring System developer team.");
		$mail->setFrom("services@childmonitoringbd.com","Children Monitoring System");
			
		//send it!
	   	$mail->send();
	}
	
	public function forgotAction()
    {
		// action body
		try
		{
			$this->view->title = "CMS :: Forgot password";
			
			require_once ("RegisterModel.php");
			$registerModel = new RegisterModel();
			$form = $this->getForgotPasswordForm();
			
			if ($_POST)
			{
				if($form->isValid($_POST)) //if submitted form's data is valid
				{
					$username = $form->getValue("username");
					
					if ($registerModel->isExistUser($username))
					{
						$userInfo = $registerModel->getUserInfomation($username);
						$current_activation_key = $registerModel->getCurrentActivationKey($username);
						$this->sendMail($username,$userInfo['full_name'],$userInfo['email'],$current_activation_key);
						
						$this->view->message = 'An email with new password setup link has been sent to your email address.<br>Check it!';
					}
					else
					{
						$this->view->form = $form;
						$this->view->message = 'Wrong username';
					}
				}
				else //if invalid data, then show submitted data + error msg.
				{	
					$this->view->form = $form;
					$this->view->message = 'Please re-enter your username';
				}	
			}
			else
			{
				$this->view->form = $form;
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
	}
	
    public function downloadAction()
    {
        // action body
		$this->view->title = "CMS :: Download application";
    }
	
	public function aboutAction()
    {
        // action body
		$this->view->title = "CMS :: About us";
    }
	
	//create feedback form
	private function getFeedbackForm()
	{
		//create form
		$form = new Zend_Form();
		$form->setAction('feedback');
		$form->setMethod('post');
		
		//name
		$form->addElement('text','name');
		$nameTextfield = $form->getElement('name');
		$nameTextfield->setLabel('Name:');
		$nameTextfield->size = 30;
		$nameTextfield->addValidator(new Zend_Validate_StringLength(3,30));
		$nameTextfield->setRequired(true);
		
		//email
		$form->addElement('text','email');
		$emailTextfield = $form->getElement('email');
		$emailTextfield->setLabel('Email:');
		$emailTextfield->size = 30;
		$emailTextfield->addValidator(new Zend_Validate_EmailAddress);
		$emailTextfield->addValidator(new Zend_Validate_StringLength(10,30));
		$emailTextfield->setRequired(true);
			
		//comment
		$form->addElement('textarea','comment');
		$commentTextarea = $form->getElement('comment');
		$commentTextarea->setLabel('Feedback:');
		$commentTextarea->rows = 3;
		$commentTextarea->cols = 23;
		$commentTextarea->addValidator(new Zend_Validate_StringLength(5,100));
		$commentTextarea->setRequired(true);
		
		//send feedback button
		$form->addElement('submit','send');
		$sendButton = $form->getElement('send');
		$sendButton->setLabel('Send feedback');

		return $form;
	}
	
	public function feedbackAction()
    {
        // action body
		try
		{
			require_once ("FeedbackModel.php");
			$feedbackModel = new FeedbackModel();
			
			$this->view->title = "CMS :: Feedback";
			$form = $this->getFeedbackForm();
			
			if ($_POST) //if form has been submitted
			{
				if($form->isValid($_POST)) //if submitted form's data is valid
				{
					//get all data from form
					$name = $form->getValue("name");
					$email = $form->getValue("email");
					$comment = $form->getValue("comment");
					
					$feedbackModel->insertIntoFeedback($name,$email,$comment);
				}
				else //if invalid data, then show submitted data + error msg.
				{
					$this->view->form = $form;
				}
			}
			else //no data has been submitted.user just opened that page
			{
				$this->view->form = $form;
			}
		}
		catch(Exception $e)
		{
			$this->_redirect('error');
		}
    }
	
}







