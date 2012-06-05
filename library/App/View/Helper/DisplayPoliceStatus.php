<?php

/**
 * View helper to check if police is logged in, if so, display message
 * The beauty of view helpers, this can be called from view and not
 * Controller, and can be placed in any "block" in the view.
 * @author Chris
 */
class App_View_Helper_DisplayPoliceStatus {

    public function displayPoliceStatus(){
        $output="";
        $hasAccess=Zend_Controller_Action_HelperBroker::getStaticHelper("ACL")->hasAccess("AdditionalData");
        if($hasAccess){
           $output="<strong>Police Department User</strong><br />You are currently logged in as a Police Department user. Additional search options are available as well as additional user types will be displayed in your search results.<br /><a href='/help/logout'>Logout &raquo;</a>";
        }

        return $output;

    }

}

?>
