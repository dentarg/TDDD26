<?php

class Zend_View_Helper_BaseUrl
{
    function baseUrl()  {
        return Zend_Controller_Front::getInstance()->getBaseUrl();
    }
}


?>