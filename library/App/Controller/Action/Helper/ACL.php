<?php

/**
 * Uses ACL to validate if user has access
 *
 * @author Chris
 */
class App_Controller_Action_Helper_ACL extends Zend_Controller_Action_Helper_Abstract{

    public static function hasAccess($resource){
        $ACL=Zend_Registry::get("ACL");
        $roleHelper=new App_Controller_Action_Helper_GetUserRole();
        $role=$roleHelper->getUserRole();
        if($ACL->isAllowed($role, $resource)){
            return true;
        }

        return false;
    }

}

?>
