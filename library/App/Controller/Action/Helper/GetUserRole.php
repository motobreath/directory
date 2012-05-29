<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IsPD
 *
 * @author Chris
 */
class App_Controller_Action_Helper_GetUserRole
    extends Zend_Controller_Action_Helper_Abstract
{

    public $_namespace = 'UCMDirectory';

    /**
     * @var Zend_Session
     *
     */
    public $session = null;

    public function direct(){
        return $this->getUserRole();
    }

    public function getUserRole(){
        return $this->getSession()->userRole;
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
