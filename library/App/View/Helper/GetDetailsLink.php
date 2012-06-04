<?php

/**
 * Links to detail pages are different for users vs additional data users (PD)
 * Helper to return correct link
 *
 * @author Chris
 */
class App_View_Helper_GetDetailsLink
    extends Zend_View_Helper_Abstract
{
    public function getDetailsLink(Application_Model_DirectoryPerson $person){
        if(Zend_Controller_Action_HelperBroker::getStaticHelper("ACL")->hasAccess("AdditionalData")){
            $link="/site/index/details/IDM/" . $person->getIDMId();
        }
        else{
            $link="/site/index/details/email/" . $person->email;
        }

        return $link;
    }
}

?>
