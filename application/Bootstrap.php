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
        $config = array(
            'auth' => 'login',
            'username' => 'stest',
            'password' => 'Merced$9',
            'ssl' => 'tls',
            'port' => 25
        );

        $tr= new Zend_Mail_Transport_Smtp("smtptest.ucmerced.edu",$config);
        Zend_Mail::setDefaultTransport($tr);

    }

    protected function _initDb(){
        $config=new Zend_Config_Ini(APPLICATION_PATH . "/configs/db.ini");
        $adapter=new Zend_Db_Adapter_Oracle($config);  
        Zend_Db_Table_Abstract::setDefaultAdapter($adapter);
    }

}

