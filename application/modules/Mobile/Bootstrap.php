<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bootstrap
 *
 * @author Chris
 */
class Mobile_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected function _initPlugins(){
        $bootstrap = $this->getApplication();
        $bootstrap->bootstrap('frontcontroller');
        $front = $bootstrap->getResource('frontcontroller');

        $front->registerPlugin(new Mobile_Plugin_Layout());
    }
}

?>
