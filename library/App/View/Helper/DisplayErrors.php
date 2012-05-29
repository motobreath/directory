<?php

/**
 * View helper to display errors straight into a view, no controller needed
 *
 * @author Chris
 */
class App_View_Helper_DisplayErrors {

    public function displayErrors(){
        $flashMessenger=new Zend_Controller_Action_Helper_FlashMessenger();
        $errors=$flashMessenger->setNamespace("directoryErrors")->getMessages();
        $output="";
        foreach($errors as $error){
            if(!empty($error)){
                $output.= "<div class='errorDiv'>" . $error . "</div>";
            }
        }
        return $output;
    }


}

?>
