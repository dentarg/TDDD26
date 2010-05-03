<?php

class PhotoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$album = $this->getRequest()->getParam('album');

		$this->view->title = "Album Photo";
		$this->view->headTitle($this->view->title);
		
		$albumPhoto = new Application_Model_DbTable_Photo();
		$this->view->albumPhoto = $albumPhoto->fetchAll("album = ".$album)->toArray();
		
		//print_r($this->view->albumPhoto);
		//Random public pictures to be displayed on the frontpage
    }
	
	public function showAction()
	{
		//Single picture view
	}
	
	public function createAction()
	{
		//Uploading pictures to existing albums
	}
	
	public function updateAction()
	{
		//Updating picture
	}
	
	public function deleteAction()
	{
		//Deleting picture and redirecting to album
	}
}

?>