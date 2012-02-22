<?php

/**
 * Description of Layout
 *
 * @author Chris
 */
class Mobile_Plugin_Layout
    extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if ('mobile' != strtolower($request->getModuleName())) {
            // If not in this module, return early
            return;
        }

        // Change layout
        Zend_Layout::getMvcInstance()->setLayout('mobile');
    }

}

?>
