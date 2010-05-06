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
		$userNamespace->id = 1;
	
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
				$users->addUser($email, $password, $nickname);
				$this->_helper->redirector('show');
			} 
			else 
			{
				$form->populate($formData);
			}
		}		
	}
	
	public function loginAction()
	{
		if(!isset($userNamespace) && $this->_postParam('login'))
		{
			//Implement login action here
			$this->_helper->redirector('show');
		}
		elseif(isset($userNamespace) && $this->_postParam('logout')) {}
			//Implement logout action here
		$this->_helper->redirector('index');
	}
	
	public function updateAction()
	{
		//Updating user
	}
}

?>