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
    {/*
		// Use default value of 1 if id is not set
		$id = $this->_getParam('id', 1);
		$user = new Application_Model_DbTable_User();
		$user = $user->getUser($id);
		$this->view = 
		//die(print_r($user));
		//exit;
		print_r($user);
		
	
		$this->view->title = $user-> "Profile";
		$this->view->headTitle($this->view->title);
		
		
		

		
		//$id = 0;
		
		//$this->view->albums = $user->getUser($id);
		
		*/
		
		
		
		$this->view->title = "Users";
		$this->view->headTitle($this->view->title);
		$album = new Application_Model_DbTable_User();
		$this->view->users = $album->fetchAll()->toArray();
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