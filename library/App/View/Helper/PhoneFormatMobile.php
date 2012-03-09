<?php

/**
 * Formats phone numbers to look all pretty like
 *
 * @author Chris
 */
class App_View_Helper_PhoneFormatMobile
    extends Zend_View_Helper_Abstract
{

    public function phoneFormatMobile($phone){

        $phone = preg_replace("/[^0-9]/", "", $phone);

        if(strlen($phone) == 7)
            return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
        elseif(strlen($phone) == 10)
            return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
        else
            return $phone;

    }

}

?>
