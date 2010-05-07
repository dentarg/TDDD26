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
		 $base_url = substr($_SERVER['PHP_SELF'], 0, -9);  

		$this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
		$this->view->jQuery()->addStylesheet($base_url.'/js/jquery/css/ui-lightness/jquery-ui-1.7.2.custom.css')
			->setLocalPath($base_url.'/js/jquery/js/jquery-1.3.2.min.js')
			->setUiLocalPath($base_url.'/js/jquery/js/jquery-ui-1.7.2.custom.min.js');
	
		$this->view->jQuery()->enable()->uiEnable();
	}

}

