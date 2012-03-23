<?php

class RedirectController extends Zend_Controller_Action
{

    public $_namespace="UCMDirectory";
    public $_session;

    public function getNamespace(){
       if (null === $this->_session) {
            $this->_session =
                new Zend_Session_Namespace($this->_namespace);
        }
        return $this->_session;
    }

    public function init()
    {
        /* Initialize action controller here */
    }

    public function mobileAction(){
        $this->getNamespace()->isMobile=true;
        $this->_redirect("/");
    }

    public function leavemobileAction()
    {
        $this->getNamespace()->isMobile=false;
        $this->_redirect("/");
    }


}



