<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		//User list
    }

    public function showAction() 
    {
		$userNamespace = new Zend_Session_Namespace('Zend_Auth'); //For debugging reasons...
		$userNamespace->id = 2;
	
		//If user is not logged in and he didn't specified id of the user he wants to view then he is redirected to
		//index page. It's temp. redirect page can be changed in the future or it can throw an error
		if (!isset($userNamespace) && !$this->_hasParam('id')) {
			$this->_helper->redirector('index');
		}
		
		// Use default value of current user if id is not set
		$id = $this->_getParam('id', $userNamespace->id);
		$user = new Application_Model_DbTable_User();
		$user = $user->getUser($id);
		$this->view->user = $user;
		
		$my_profile = $id == $userNamespace->id;
		$this->view->my_profile = $my_profile;
		
		//Title "user's profile" or "your profile"
		$this->view->title = ($my_profile ? 'Your' : $user['nickname']."'s")." profile";
		$this->view->headTitle($this->view->title);
		
		
		$albumModel = new Application_Model_DbTable_Album();
		$covers = new Application_Model_DbTable_Photo();
		
		$albums = $albumModel->fetchAll("author = ".$id)->toArray();
		/*$condition = "album = ".$albums[0];
		$i = 0;
		foreach($albums as $album)
		{
			if($i++ == 0) continue;
			$condition .= ' and album = '.$album['id'];
		}
		$cover[] = $covers->fetchAll($condition)->toArray();
		
		$this->view->covers = $covers;*/
		$this->view->albums = $albums;
		
		$this->view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper'); //Register jquery for the view
    }
	
	public function createAction()
	{
		//Sign up
		$this->view->title = "User registration";
		$this->view->headTitle($this->view->title);

		$form = new Application_Form_User();
		$form->submit->setLabel('Add');

		$this->view->form = $form;

		if ($this->getRequest()->isPost()) 
		{
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) 
			{
				$email = $form->getValue('email');
				$nickname = $form->getValue('nickname');
				$password = $form->getValue('password');
				$users = new Application_Model_DbTable_User();
				$users->addUser($email, $password, $nickname);
				$this->_helper->redirector('show');
			} 
			else 
			{
				//$form->populate($formData); // unnecessary
			}
		}		
	}
	
	public function loginAction()
	{
	   // from http://framework.zend.com/manual/en/zend.db.adapter.html
      $db = new Zend_Db_Adapter_Pdo_Mysql(array(
         'host'     => '127.0.0.1',
         'username' => 'tddd26',
         'password' => '123123',
         'dbname'   => 'tddd26'));

	   // from http://framework.zend.com/manual/en/zend.auth.adapter.dbtable.html
      $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
      $authAdapter
         ->setTableName('user')
         ->setIdentityColumn('email')
         ->setCredentialColumn('password');
         

      $email = $this->_getParam('email');
      $password = $this->_getParam('password');
         
      // Set the input credential values (e.g., from a login form)
      $authAdapter
          ->setIdentity($email)
          ->setCredential($password);

       // from http://framework.zend.com/manual/en/zend.auth.introduction.html
       // Attempt authentication, saving the result
       $result = $auth->authenticate($authAdapter);
       if (!$result->isValid()) {
          // Authentication failed; print the reasons why
          foreach ($result->getMessages() as $message) {
             echo "$message\n";
          }
       } else {
          $this->_helper->redirector('show');
          // Authentication succeeded; the identity ($username) is stored
          // in the session
          // $result->getIdentity() === $auth->getIdentity()
          // $result->getIdentity() === $username
       }

/*      
       // from http://framework.zend.com/manual/en/zend.auth.introduction.html
       // inside of AuthController / loginAction
       $result = $this->_auth->authenticate($adapter);
       switch ($result->getCode()) {
          case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
          // do stuff for nonexistent identity
          break;
          case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
          // do stuff for invalid credential
          break;
          case Zend_Auth_Result::SUCCESS:
          // do stuff for successful authentication
          break;
          default:
          // do stuff for other failure
          break;
       }
*/  

/*	   
		if(!isset($userNamespace) && $this->_getParam('login'))
		{
			//Implement login action here
			$this->_helper->redirector('show');
		}
		elseif(isset($userNamespace) && $this->_getParam('logout')) {}
			//Implement logout action here
		$this->_helper->redirector('index');
*/

	}
	
	public function updateAction()
	{
		//Updating user
	}
}

?>