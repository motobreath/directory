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
        $config=new Zend_Config_Ini(APPLICATION_PATH . "/configs/email.ini", APPLICATION_ENV);
        $host=$config->email->host;
        $emailConfig = array(
                    'auth' => $config->email->config->auth,
                    'username' => $config->email->config->username,
                    'password' => $config->email->config->password,
                    z'ssl' => $config->email->config->ssl,
                    'port' => $config->email->config->port
        );

        $tr= new Zend_Mail_Transport_Smtp($host,$emailConfig);
        Zend_Mail::setDefaultTransport($tr);

    }

    protected function _initDb(){
        $config=new Zend_Config_Ini(APPLICATION_PATH . "/configs/db.ini",APPLICATION_ENV);
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
        Zend_Registry::set("cache", Zend_Cache::factory("Core", "File", $frontOptions, $backOptions));
    }

}

