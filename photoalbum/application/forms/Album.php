<?php

class Application_Form_Album extends Zend_Form
{
	public function init()
	{
		$this->setName('album');

		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int');
		
		$title = new Zend_Form_Element_Text('title');

		$title->setLabel('Album Title:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$title->setDecorators(array(
                   'ViewHelper',
                   'Description',
                   'Errors',
                   array(array('data'=>'HtmlTag'), array('tag' => 'td')),
                   array('Label', array('tag' => 'td')),
                   array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
           ));

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');

	     $submit->setDecorators(array(
               'ViewHelper',
               'Description',
               'Errors', array(array('data'=>'HtmlTag'), array('tag' => 'td',
               'colspan'=>'2','align'=>'center')),
               array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
       ));

		$this->addElements(array($title, $submit));
		
		 $this->setDecorators(array(
               'FormElements',
               array(array('data'=>'HtmlTag'),array('tag'=>'table')),
               'Form'
       ));

	}
}

?>