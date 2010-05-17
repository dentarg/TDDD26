<?php

class PhotoController extends Zend_Controller_Action
{

    public function init()
    {
		
        /* Initialize action controller here */
    }

    public function indexAction()
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
			 }
		
			$this->view->userid = $user['id'];
			
			$id = $this->_getParam('aid');
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
		if ( $this->_hasParam('aid') )
		{
			$id = $this->_getParam('aid');
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
		$auth = Zend_Auth::getInstance();
		
		$albumid = $this->getRequest()->getParam('aid');
		
		if(!$albumid || !$auth->hasIdentity()) 
		{
			$this->_redirect("/index");
		}

		$album = new Application_Model_DbTable_Album();
		$user = new Application_Model_DbTable_User();
		$identity = $auth->getIdentity();
		$user = $user->getUserByEmail($identity);
		
		if(!$album->fetchRow("id=".$albumid." AND author=".$user['id']))
			$this->_redirect("/index");
		
		
		$this->view->title = "Upload Photos";
		$this->view->headTitle($this->view->title);

		$form = new Application_Form_Photo();
		$form->submit->setLabel('Upload');

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
				$uploadDir = APPLICATION_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'public'.
							 DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'gallery';
							 
				$upload->setDestination($uploadDir);
				$upload->addValidator('Extension', false, array('jpg','jpeg', 'png', 'gif'));

				$files = $upload->getFileInfo();

				foreach ($files as $file => $info) 
				{
					// file uploaded ?
					if (!$upload->isUploaded($file) )continue;
					// validators are ok ?
					if (!$upload->isValid($file)) 
					{
						$this->view->msg .=  "Sorry <strong><u>Photo ". ($count+1) ."</u></strong> is of wrong format. Upload only jpg, jpeg, gif and png.<br/>";
						continue;
					}


					$ext = $this->_findexts($info['name']);
					$fileName = 'img_'.$count.time().".".$ext;	
					$upload->addFilter('Rename',
									   array('target' => $uploadDir.DIRECTORY_SEPARATOR.$fileName,
											 'overwrite' => true));
						
					
					$upload->receive($info['name']);
					list ($picName, $extension) = explode('.',$info['name']);
					//$picName.','. $info['name'].','. $albumid;
					$modelPhoto->addPhoto($picName, $fileName, $albumid);

					$count++;
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
		$auth = Zend_Auth::getInstance();
		if(!$auth->hasIdentity()) 
		{
			$this->_redirect("/index");
		}

		$picid = $this->getRequest()->getParam('pic');
		$albumid = $this->getRequest()->getParam('album');
		if ($picid)
		{
			$photo = new Application_Model_DbTable_Photo();
			$photo->deletePhoto($picid);
			
		}
		$this->_redirect("album/show/aid/".$albumid);
		//$this->_helper->redirector('index');	
		//Deleting picture and redirecting to album
	}
	
	protected function _findexts($filename)
	{
		$filename = strtolower($filename) ;
		$exts = split("[/\\.]", $filename) ;
		$n = count($exts)-1;
		$exts = $exts[$n];
		return $exts;
	}
}

?>