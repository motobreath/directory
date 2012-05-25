<?php

/**
 * Description of CAS
 *
 * @author Chris
 */

include "CAS/CAS.php";

class App_Controller_Action_Helper_CAS
    extends Zend_Controller_Action_Helper_Abstract
{
    public $_namespace = 'UCMDirectory';

    /**
     * @var Zend_Session
     *
     */
    public $session = null;

    public function direct(){
        phpCAS::client(SAML_VERSION_1_1,"cas.ucmerced.edu",443,"/cas",false);
        phpCAS::setNoCasServerValidation();
        phpCAS::forceAuthentication();

        $this->getSession()->username=phpCAS::getUser();
    }

    private function getSession()
    {
        if(null===$this->session){
            $this->session=new Zend_Session_Namespace($this->_namespace);
        }
        return $this->session;
    }
}

?>
