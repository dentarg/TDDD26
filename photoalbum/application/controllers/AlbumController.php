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
		
		$albums = $album->fetchAll($select)->toArray();
		$albums = $album->setCover( $albums );
		$this->view->albums = $albums;
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
			$this->_redirect("/index");
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
				$this->_redirect('/photo/create/aid/'.$newAlbumId);
			} 
			else 
			{
				$form->populate($formData);
			}
		}
	}
	
	public function showAction()
	{	
		if($this->_hasParam('aid'))
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
			
			
			
			$id = $this->_getParam('aid');
			$album = new Application_Model_DbTable_Album();
			$album = $album->getAlbum($id);
			$this->view->album = $album;
			
			$user = new Application_Model_DbTable_User();
			$user = $user->getUser($album['author']);
			
			$baseURL = new Zend_View_Helper_BaseUrl();
			$this->view->title = '<a href="'.$baseURL->baseUrl().'/user/show/id/'.$album['author'].'">'.$user['nickname'].'</a> > '.$album['name'];
	
		
			$albumPhoto = new Application_Model_DbTable_Photo();
			$this->view->albumPhoto = $albumPhoto->fetchAll("album = ".$id)->toArray();
	
		}
		else
			$this->view->title = 'Album id is not specified';
	}
	
	public function updateAction()
	{
		$auth = Zend_Auth::getInstance();
		if(!$auth->hasIdentity()) 
		{
			$this->_redirect("/index");
		}

		$id = $this->_getParam('aid', 0);
		
		if ( $id > 0 )
		{
			$form = new Application_Form_Album();
			$form->submit->setLabel('Update');

			$albumModel = new Application_Model_DbTable_Album();
			$album = $albumModel->getAlbum($id);

			$this->view->title = "Update album :: ".$album['name'];
			$this->view->headTitle($this->view->title);


			if ($this->getRequest()->isPost()) 
			{
				$formData = $this->getRequest()->getPost();
				if ($form->isValid($formData)) 
				{
					$title = $form->getValue('title');
					$cover = $album['cover'];
					$albumModel->updateAlbum($id, $title, $cover);
					$this->_redirect('/album/show/aid/'.$id);
				}
			}
			$this->view->album = $album;
			$this->view->form = $form;

			$data = array("id"=>$id, 'title' => $album['name']);
			$form->populate($data);
		}

	}
	
	public function deleteAction()
	{
		$auth = Zend_Auth::getInstance();
		if(!$auth->hasIdentity()) 
		{
			$this->_redirect("/index");
		}
		
		$albumId = $this->getRequest()->getParam('aid');
		if ($albumId)
		{
			$albumModel = new Application_Model_DbTable_Album();
			$albumModel->deleteAlbum($albumId);
			
		}	
		
		$this->_redirect("/user/show");
		//$this->_helper->redirector('index');
			//Deleting album and all pictures which belong to it
	}
}

?>