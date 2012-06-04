<?php

/**
 * View helper to display errors straight into a view, no controller needed
 *
 * @author Chris
 */
class App_View_Helper_DisplayMsg {

    public function displayMsg(){
        $flashMessenger=new Zend_Controller_Action_Helper_FlashMessenger();
        $msgs=$flashMessenger->setNamespace("directoryMsg")->getMessages();
        $output="";
        foreach($msgs as $msg){
            if(!empty($msg)){
                $output.= "<div class='success'>" . $msg . "</div>";
            }
        }
        return $output;
    }


}

?>
