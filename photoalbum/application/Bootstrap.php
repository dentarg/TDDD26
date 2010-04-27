<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	private $view;
	
	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$this->view = $this->getResource('view');
		$this->view->doctype('XHTML1_TRANSITIONAL');
		
	}
	
	protected function _initViewHelpers()
	{
		
		$this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
		$this->view->jQuery()->addStylesheet('js/jquery/css/ui-lightness/jquery-ui-1.7.2.custom.css')
			->setLocalPath('js/jquery/js/jquery-1.3.2.min.js')
			->setUiLocalPath('js/jquery/js/jquery-ui-1.7.2.custom.min.js');
	
		$this->view->jQuery()->enable()->uiEnable();
	}

}

