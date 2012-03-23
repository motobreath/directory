<?php

class RedirectController extends Zend_Controller_Action
{

    /**
     *
     * @var String
     */
    public $_namespace="UCMDirectory";
    /**
     *
     * @return Zend_Session_Namespace
     */
    public $_session;

    /**
     * Sets object namespace
     * @return Zend_Session
     */
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

    public function indexAction()
    {
        // action body
    }

    public function mobileAction()
    {
        $this->getNamespace()->isMobile=true;
        $this->_redirect("/");
    }


}





