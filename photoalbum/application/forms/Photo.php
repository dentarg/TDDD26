<?php

class Application_Form_Photo extends Zend_Form
{
	public function init()
	{
		//parent::__construct($options);
		$this->setName('upload');
		
		$this->setMethod('post');
		$this->setAttrib('enctype', 'multipart/form-data');
		
		$file1= $this->createElement('file','file1');
		$file1->setLabel('Photo 1:');
		
		$file2= $this->createElement('file','file2');
		$file2->setLabel('Photo 2:');
		
		$file3= $this->createElement('file','file3');
		$file3->setLabel('Photo 3:');
		
		$file4= $this->createElement('file','file4');
		$file4->setLabel('Photo 4:');
		
		$file5= $this->createElement('file','file5');
		$file5->setLabel('Photo 5:');

		$albumid = new Zend_Form_Element_Hidden('albumid');
		
		$submit=$this->createElement('submit','submit');
		
		$this->addElements(array(
		$file1,
		$file2,
		$file3,
		$file4,
		$file5,
		$albumid,
		$submit
		));
			
	}
}

?>