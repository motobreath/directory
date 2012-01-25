<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here*/
    }

    public function indexAction()
    {
        $checkMobile=$this->getHelper("MobileRedirect");
        $checkMobile->mobileRedirect();
    }


}



