<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initialize helpers, both action and view
     */
    protected function _initHelpers(){
        Zend_Controller_Action_HelperBroker::addPrefix('App_Controller_Action_Helper');

        /**
         * @var Zend_View
         */
        $this->bootstrap("view");
        $view = $this->getResource('view');
        $view->addHelperPath(APPLICATION_PATH . "/../library/App/View/Helper/");

    }

    protected function _initEmailConfig() {
        $config=new Zend_Config_Ini(APPLICATION_PATH . "/configs/db.ini");
        $config=$config->toArray();
        $tr= new Zend_Mail_Transport_Smtp("smtptest.ucmerced.edu",$config);
        Zend_Mail::setDefaultTransport($tr);

    }

    protected function _initDb(){
        $config=new Zend_Config_Ini(APPLICATION_PATH . "/configs/db.ini");
        $adapter=new Zend_Db_Adapter_Oracle($config);
        Zend_Db_Table_Abstract::setDefaultAdapter($adapter);
    }

    protected function _initCaching() {
        $frontOptions = array(
            "lifetime" => 86400,
            "automatic_serialization" => true
        );

        $backOptions = array(
            "cache_dir" => APPLICATION_PATH . "/tmp/"
        );
        Zend_Registry::set("deptCache", Zend_Cache::factory("Core", "File", $frontOptions, $backOptions));
    }

}

