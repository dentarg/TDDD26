<?php

class AlbumController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$this->view->title = "Albums";
		$this->view->headTitle($this->view->title);
		$album = new Application_Model_DbTable_Album();
		$this->view->albums = $album->fetchAll()->toArray();
		
		//Album page
    }
	
	public function createAction()
	{
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
				$artist = $form->getValue('artist');
				$title = $form->getValue('title');
				$albums = new Application_Model_DbTable_Album();
				$albums->addAlbum($artist, $title);
				$this->_helper->redirector('index');
			} 
			else 
			{
				$form->populate($formData);
			}
		}
	}
	
	public function updateAction()
	{
		//Updating album
	}
	
	public function deleteAction()
	{
		//Deleting album and all pictures which belong to it
	}
}

?>