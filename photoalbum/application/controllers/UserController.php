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
       $this->view->title = "User list";
       $this->view->headTitle($this->view->title);

       $user = new Application_Model_DbTable_User();
       $this->view->users = $user->fetchAll()->toArray();
    }

    public function showAction() 
    {
		// from http://framework.zend.com/manual/en/zend.auth.introduction.html
      $auth = Zend_Auth::getInstance();
      $id = $this->_getParam('id');
      $my_profile = false;
      $user = new Application_Model_DbTable_User();

      
	
      if(!isset($id) && !$auth->hasIdentity()) {
         // not logged in and no id supplied, show nothing
         die("Not logged in."); // should redirect or something nicer
      }
      else {
         // id set or logged in
         if(isset($id)) {
            $id = $this->_getParam('id');
            $user = $user->getUser($id);
         }
         // logged in and viewing own profile
         else if($auth->hasIdentity()) {
            // Identity exists; get it
            $identity = $auth->getIdentity();
            // look up logged in user by email
      		$user = $user->getUserByEmail($identity);
      		$this->view->user = $user;
            $id = $user['id'];
      		$my_profile = true;
			$this->view->my_profile = $my_profile;
         }
      }
		// Title "user's profile" or "your profile"
		$this->view->title = ($my_profile ? 'Your' : $user['nickname']."'s")." profile";
		$this->view->headTitle($this->view->title);

      // list albums of user
		$album = new Application_Model_DbTable_Album();
		$select = $album->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
		$select->setIntegrityCheck(false)
			   ->joinLeft('photo', 'photo.id = album.cover', 'photo.picture')
			   ->order('album.date DESC')
			   ->where("author = ".$id);
		$this->view->albums = $album->fetchAll($select)->toArray();

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

             // from Ivan 25036c5d28e0a5fb93602bcb4a163ed1969635f0
             $existing = count($users->fetchAll("email = '".$email."'")->toArray());
             if($existing)
                $this->view->form->email->addError("Email already exists");
             else
             {
                $users->addUser($email, $password, $nickname);

                /* This is exactly the same code used in loginAction()
                   Yeah, I know, it's REALLY UGLY, you should not duplicate code */

                // get database adapter from application.ini
                $dbAdapter = Zend_Registry::get('db');

                // from http://framework.zend.com/manual/en/zend.auth.adapter.dbtable.html
                $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
                $authAdapter
                   ->setTableName('user')
                   ->setIdentityColumn('email')
                   ->setCredentialColumn('password')
                   ->setCredentialTreatment('md5(?)');

                // Set the input credential values (e.g., from a login form)
                $authAdapter
                   ->setIdentity($email)
                   ->setCredential($password);

                // from http://framework.zend.com/manual/en/zend.auth.introduction.html
                // Get a reference to the singleton instance of Zend_Auth
                $auth = Zend_Auth::getInstance();
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

                // END OF UGLY HACK                
                
                $this->_helper->redirector('show');
             }
          }
       }		
    }
	
	public function loginAction()
	{
	   // get database adapter from application.ini
      $dbAdapter = Zend_Registry::get('db');
      
	   // from http://framework.zend.com/manual/en/zend.auth.adapter.dbtable.html
      $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
      $authAdapter
         ->setTableName('user')
         ->setIdentityColumn('email')
         ->setCredentialColumn('password')
         ->setCredentialTreatment('md5(?)');
      
      // read values from login form
      $email = $this->_getParam('email');
      $password = $this->_getParam('password');
         
      // Set the input credential values (e.g., from a login form)
      $authAdapter
          ->setIdentity($email)
          ->setCredential($password);

       // from http://framework.zend.com/manual/en/zend.auth.introduction.html
       // Get a reference to the singleton instance of Zend_Auth
       $auth = Zend_Auth::getInstance();
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
	}
	
	public function logoutAction()
	{
	   Zend_Auth::getInstance()->clearIdentity();
	   $this->_helper->redirector('index', 'album'); // should redirect to index
	}
	
	public function updateAction()
	{
		//Updating user
	}
}
?>