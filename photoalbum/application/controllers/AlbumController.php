<?php
class AlbumController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$this->view->title = "Recently added albums";
		$this->view->headTitle($this->view->title);
		$album = new Application_Model_DbTable_Album();

		$select = $album->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
		$select->setIntegrityCheck(false)
			   ->joinLeft('photo', 'photo.id = album.cover', 'photo.picture')
			   ->order('album.date DESC')
			   ->limit(20, 0);
		
		$this->view->albums = $album->fetchAll($select);
	   
		//$rows = $table->fetchAll($select);
		
		
		
		//$album->select()
													 //->join('photo', 'photo.id = album.cover')
		//);
		//$this->view->albums = $album->fetchAll()->toArray();
		
		//$this->view->albums = $album->fetchAll_C();
		
		//Album page
    }
	
	public function createAction()
	{
		$auth = Zend_Auth::getInstance();
		if(!$auth->hasIdentity()) 
		{
			$this->_redirect("/album");
		}
		

		$this->view->title = "Add new album";
		$this->view->headTitle($this->view->title);

		$form = new Application_Form_Album();
		$form->submit->setLabel('Add');

		$this->view->form = $form;

		if ($this->getRequest()->isPost()) 
		{
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) 
			{
				$auth = Zend_Auth::getInstance();
				if($auth->hasIdentity()) 
				{
				   $identity = $auth->getIdentity();
				
				   // get user "object" by email
				   // because that's what stored in $auth->getIdentity()
				   $user = new Application_Model_DbTable_User();
				   $user = $user->getUserByEmail($identity);
				
				   // do stuff
				   //echo $user['nickname'];
				   //echo $user['id'];
				 }
				 
				$author = $user['id'];
				$title = $form->getValue('title');
				$albums = new Application_Model_DbTable_Album();
				$newAlbumId = $albums->addAlbum($author, $title);
				$this->_redirect('/photo/create/album/'.$newAlbumId);
			} 
			else 
			{
				$form->populate($formData);
			}
		}
	}
	
	public function showAction()
	{	
		if($this->_hasParam('id'))
		{
			$auth = Zend_Auth::getInstance();
			if($auth->hasIdentity()) 
			{
			   $identity = $auth->getIdentity();
			
			   // get user "object" by email
			   // because that's what stored in $auth->getIdentity()
			   $user = new Application_Model_DbTable_User();
			   $user = $user->getUserByEmail($identity);
			
			   // do stuff
			   //echo $user['nickname'];
			   //echo $user['id'];
			   $this->view->userid = $user['id'];
			 }
			
			
			
			$id = $this->_getParam('id');
			$album = new Application_Model_DbTable_Album();
			$album = $album->getAlbum($id);
			$this->view->album = $album;
			
			$user = new Application_Model_DbTable_User();
			$user = $user->getUser($album['author']);
			$this->view->title = '<a href="'.$this->view->url(array('controller'=>'user',
	'show'=>'create')).'?id='.$album['author'].'">'.$user['nickname'].'</a> > '.$album['name'];
	
		
			$albumPhoto = new Application_Model_DbTable_Photo();
			$this->view->albumPhoto = $albumPhoto->fetchAll("album = ".$id)->toArray();
	
		}
		else
			$this->view->title = 'Album id is not specified';
	}
	
	public function updateAction()
	{
		//Updating album
	}
	
	public function deleteAction()
	{
		$auth = Zend_Auth::getInstance();
		if(!$auth->hasIdentity()) 
		{
			$this->_redirect("/public/user/show");
		}
		
		$albumId = $this->getRequest()->getParam('album');
		if ($albumId)
		{
			$albumModel = new Application_Model_DbTable_Album();
			$albumModel->deleteAlbum($albumId);
			
		}	
		
		$this->_redirect("/public/user/show");
		//$this->_helper->redirector('index');
			//Deleting album and all pictures which belong to it
	}
}

?>