<?php

/**
 * Use this plugin to create custom contexts.
 * By using Zend context action helper, new contexts will be available
 * to the controller automatically
 *
 * @author Chris
 */
class App_Controller_Plugin_CreateContexts
    extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        //create mobile context
        $contextSwitch=Zend_Controller_Action_HelperBroker::getStaticHelper("ContextSwitch");
        $contextSwitch->setContext("mobile",array(
                    "suffix"=>"mobile",
                    "headers"=>array("Content-Type"=>"text/html; Charset=UTF-8")
                ))->setAutoDisableLayout(false)
                ->initContext();

        /***** Add additional Context here ****/
    }
}

?>
