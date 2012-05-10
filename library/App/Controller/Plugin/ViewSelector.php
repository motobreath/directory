<?php

/**
 * Checks view context. Currently checking for mobile and portal
 * Will display correct layout and set context accordingly
 * Context example, if mobile, will use index.mobile.phtml
 *
 * @author Chris
 */
class App_Controller_Plugin_ViewSelector
    extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @var String
     */
    public $_namespace="UCMDirectory";
    /**
     *
     * @var Zend_Session_Namespace
     */
    public $_session;

    public $_layout;

    /**
     * Sets object namespace
     * @return Zend_Session
     */
    public function getSession(){
       if (null === $this->_session) {
            $this->_session =
                new Zend_Session_Namespace($this->_namespace);
        }
        return $this->_session;
    }

    public function getLayout(){
        if(null===$this->_layout){
            $this->_layout=Zend_Controller_Action_HelperBroker::getStaticHelper("Layout");
        }
        return $this->_layout;
    }

    /**
     * Sets context before dispatch.
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        //If format is sent, do not set context
        //Example, Ajax will send format=json, so json files need to be served
        $format=$request->getParam("format");
        if(!empty($format)){
            return;
        }

        //begin checks.
        //first check if sessions exist, and if they do, forward on
        //haven't figurd this part out yet...
        if($this->getSession()->isPortal || !is_null($request->getParam("portal"))){
            $this->setPortal($request);
            return;
        }
        if($this->getSession()->isDesktop){
            $this->setDesktop($request);
            return;
        }
        if($this->getSession()->isMobile){
            $this->setMobile($request);
            return;
        }


        //Now all sessions are checked. now check to see if we
        //need to set sessions and redirect accordingly.

        //Begin mobile check.
        if(!isset($this->getSession()->isMobile)){
            $mobileHelper=Zend_Controller_Action_HelperBroker::getStaticHelper("GetIsMobile");
            if($mobileHelper->getIsMobile()){
                $this->setMobile($request);
                return;
            }
        }

        //now set default layout, would have returned with different view by now
        $this->getSession()->isDesktop=true;
        $this->getLayout()->setLayout("layout");

    }

    /**
     *
     */
    private function setPortal(){
        $this->getLayout()->setLayout("portal");
        $this->getSession()->isPortal=true;
        return;
    }

    /**
     * Called to set mobile view
     * @param Zend_Controller_Request_Abstract $request
     */
    private function setMobile(Zend_Controller_Request_Abstract $request){
        //add mobile context.
        //Allows for index.mobile.phtml files to be accessed
        $request->setParam("format","mobile");
        $this->getLayout()->setLayout("mobile");
        $this->getSession()->isMobile=true;
        return;
    }

    private function setDesktop($request){
        $this->getLayout()->setLayout("layout");
        return;
    }


}

?>
