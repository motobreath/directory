<?php

class PoliceController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_helper->CAS;
        $this->_redirect("/");
    }


}

