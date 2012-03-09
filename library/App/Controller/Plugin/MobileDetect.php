<?php

/**
 * checks for mobile, if mobile use context switch to use mobile files
 * Example, if mobile, will use index.mobile.phtml
 *
 * @author Chris
 */
class App_Controller_Plugin_MobileDetect
    extends Zend_Controller_Plugin_Abstract
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

    /**
     * Sets context before dispatch.
     * Note: If format is sent, do not set context
     * Example, Ajax will send format=json, so json files need to be served
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        $defaultFormat=$request->getParam("format");
        if(!empty($defaultFormat)){
            return;
        }
        $layout=Zend_Controller_Action_HelperBroker::getStaticHelper("Layout");
        $contextSwitch=Zend_Controller_Action_HelperBroker::getStaticHelper("ContextSwitch");
        $contextSwitch->setContext("mobile",array(
                    "suffix"=>"mobile",
                    "headers"=>array("Content-Type"=>"text/html; Charset=UTF-8")
                ))->setAutoDisableLayout(false)
                ->initContext();
        if(!isset($this->getNamespace()->isMobile)){
            $mobileHelper=Zend_Controller_Action_HelperBroker::getStaticHelper("GetIsMobile");
            if($mobileHelper->getIsMobile()){
                $request->setParam("format","mobile");
                $layout->setLayout("mobile");
                $this->getNamespace()->isMobile=true;
            }
        }
        elseif($this->getNamespace()->isMobile){
            $request->setParam("format","mobile");
            $layout->setLayout("mobile");
        }

    }

}

?>
