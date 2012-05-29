<?php

class PoliceController extends Zend_Controller_Action
{

    public $_namespace = 'UCMDirectory';

    /**
     * @var Zend_Session
     *
     */
    public $session = null;

    public function init()
    {
        $this->session=new Zend_Session_Namespace($this->_namespace);
    }

    public function indexAction()
    {
        $username=$this->_helper->CAS();
        $config=new Zend_Config_Json(APPLICATION_PATH . "/configs/pdUsers.json","users");
        if(in_array($username,$config->toArray())){
            $this->session->userRole="PD";
            $this->getHelper("FlashMessenger")->setNamespace("directoryMsg")->addMessage("Successfully logged in as Police Department User");
        }
        else{
            $this->getHelper("FlashMessenger")->setNamespace("directoryErrors")->addMessage("You are not authorized to view that page");
        }

        $this->_redirect("/");
    }


}

