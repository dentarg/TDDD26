<?php

class PhotoController extends Zend_Controller_Action
{

    public function init()
    {
		
        /* Initialize action controller here */
    }

    public function indexAction()
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
			 }
		
			$this->view->userid = $user['id'];
			
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
	
	public function showAction()
	{
		if ( $this->_hasParam('id') )
		{
			$id = $this->_getParam('id');
			$album = new Application_Model_DbTable_Album();
			$this->view->album = $album = $album->getAlbum($id);

			$user = new Application_Model_DbTable_User();
			$user = $user->getUser($album['author']);
			
			$photo = new Application_Model_DbTable_Photo();
			$this->view->albumPhoto = $photo->fetchAll("album = ".$id)->toArray();
		}
		//Single picture view
	}
	
	public function createAction()
	{
		$this->view->title = "Upload Photos";
		$this->view->headTitle($this->view->title);

		$form = new Application_Form_Photo();
		$form->submit->setLabel('Upload');

		$albumid = $this->getRequest()->getParam('album');
		$album = new Application_Model_DbTable_Album();
		$this->view->album = $album->getAlbum($albumid);

		$this->view->form = $form;
		
		$data = array("albumid"=>$albumid);
		$form->populate($data);
		
		
		$this->view->isUpload = false;
		if($this->getRequest()->isPost())
		{
			if($form->isValid($_POST)) 
			{
				$count = 0;
				$this->view->isUpload = true;
				
				$albumid = $this->getRequest()->getParam('albumid');
				$modelPhoto = new Application_Model_DbTable_Photo();
				
				$upload = new Zend_File_Transfer_Adapter_Http();
				$upload->setDestination('images/gallery/');
				$files = $upload->getFileInfo();
					foreach ($files as $file => $info) 
					{
						if($upload->isValid($file))
						{
							$count++;
							$upload->receive($file);
							
							list ($picName, $extension) = explode('.',$info['name']);
							//$picName.','. $info['name'].','. $albumid;
							
							$modelPhoto->addPhoto($picName, $info['name'], $albumid);
						}
					}
					
					$this->view->piccount = $count;
			}
		}		
		
		//Uploading pictures to existing albums
	}
	
	public function updateAction()
	{
		//Updating picture
	}
	
	public function downloadAction()
	{
		$this->_helper->layout->disableLayout();
		$this->view->imageFile = $this->getRequest()->getParam('imagefile');
	}
	
	public function deleteAction()
	{
		$picid = $this->getRequest()->getParam('pic');
		$albumid = $this->getRequest()->getParam('album');
		if ($picid)
		{
			$photo = new Application_Model_DbTable_Photo();
			$photo->deletePhoto($picid);
			
		}
		$this->_redirect('/photo/index/id/'.$albumid);
		//$this->_helper->redirector('index');	
		//Deleting picture and redirecting to album
	}
}

?>