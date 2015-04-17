<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BuildVCard
 *
 * @author Chris
 */
class App_View_Helper_BuildVCard {

    public function buildVCard(Application_Model_DirectoryPerson $person){
        $output="BEGIN:VCARD\n";
        $output.="VERSION:3.0\n";
        $output.="FN: " .  $person->getFullName() . " \n";
        $output.="N;charset=iso-8859-1:" .  $person->getFullName() . "\n";
        $output.="EMAIL;type=WORK:" .  $person->getEmail() . "\n";
        $output.="URL;type=HOME:\n";
        $output.="TITLE;charset=iso-8859-1:\n";
        $output.="ORG;charset=iso-8859-1:University of California, Merced\n";
        $output.="ADR;type=WORK;charset=iso-8859-1:;{$person->getLocation()};5200 North Lake Road;Merced;CA;95343;USA\n";
        if($person->getPrimaryAffiliation()!="student"){
            $output.="TEL;type=WORK:" .  $person->getPhone() . "\n";
            $output.="TEL;type=FAX:" .  $person->getFax() . "\n";
            $output.="TEL;type=CELL:" .  $person->getCellPhone() . "\n";
        }
        $output.="URL;type=WORK:\n";
        $output.="END:VCARD";

        return $output;
    }

}

?>
