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
		if (!isset($userNamespace) && !$this->_hasParam('id')) {
			$this->_helper->redirector('index');
		
		// Use default value of 1 if id is not set
		$id = $this->_getParam('id', $userNamespace->id);
		$user = new Application_Model_DbTable_User();
		$user = $user->getUser($id);
		$this->view->user = $user;
		
	
		$this->view->title = ($id == 2 ? 'Your' : $user['nickname']."'s")." profile";
		$this->view->headTitle($this->view->title);
		
		$albums = new Application_Model_DbTable_Album();
		$this->view->albums = $albums->fetchAll("author = ".$id)->toArray();
		
		
		

		
		//$id = 0;
		
		//$this->view->albums = $user->getUser($id);
		
		
		
		
		
		//$this->view->title = "Users";
		//$this->view->headTitle($this->view->title);
		//$album = new Application_Model_DbTable_User();
		//$this->view->users = $album->fetchAll()->toArray();
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