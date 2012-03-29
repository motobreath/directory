<?php

/**
 * Checks if student or not, returns appropriate title text
 *
 * @author Chris
 */
class Zend_View_Helper_GetJobTitle
    extends Zend_View_Helper_Abstract
{
    public function getJobTitle(Application_Model_DirectoryPerson $person){
        if($person->getPrimaryAffiliation()=="student"){
            return "<span style='text-transform:capitalize'>" . $person->getSubAffiliation() . "</span>" . " Student";
        }
        else{
            $output="";
            $jobTitle1=$person->getJobTitle();
            $dept=$person->getDepartment();
            $jobTitle2=$person->getJobTitle2();

            if(!empty($jobTitle1)){
                $output.= $jobTitle1;
                if(!empty($dept)) $output.=  " - ";
            }
            if(!empty($dept)) $output.=  "<a href='/site/departments/results/department/$dept/' data-prefetch>" . $dept . "</a>";
            if(!empty($jobTitle2)) $output.= "<br />" . $jobTitle2;

            if(empty($output)){
                return "No Specified Title";
            }
            else{
                return $output;
            }

        }

    }
}

?>
