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
		echo 'asfdgsja';
    }

    public function showAction()
    {
		//If user is not logged in and he didn't specified id of the user he wants to view then he is redirected to
		//index page. It's temp. redirect page can be changed in the future or it can throw an error
		if (!isset($userNamespace) && !$this->_hasParam('id')) {
			$this->_helper->redirector('index');
			//$userNamespace = new Zend_Session_Namespace('Zend_Auth'); //For debugging reasons...
			//$userNamespace->id = 2;
		}
		
		// Use default value of current user if id is not set
		$id = $this->_getParam('id', $userNamespace->id);
		$user = new Application_Model_DbTable_User();
		$user = $user->getUser($id);
		$this->view->user = $user;
		
		//Title "user's profile" or "your profile"
		$this->view->title = ($id == $userNamespace->id ? 'Your' : $user['nickname']."'s")." profile";
		$this->view->headTitle($this->view->title);
		
		$albums = new Application_Model_DbTable_Album();
		$this->view->albums = $albums->fetchAll("author = ".$id)->toArray();
		
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper'); //Register jquery for the view
    }
	
	public function createAction()
	{
		//Sign up
	}
	
	public function loginAction()
	{
		//Login
	}
	
	public function updateAction()
	{
		//Updating user
	}
}

?>