<?php

/**
 * Sends email to specified carriers text message email addresses
 *
 * @author Chris
 */
class App_Controller_Action_Helper_SendSMS
    extends Zend_Controller_Action_Helper_Abstract
{

    private $carriers;
    /**
     *
     * @var Zend_Mail
     */
    private $mail;

    private function getCarriers() {
        if(null===$this->carriers){
            $config=new Zend_Config_Ini(APPLICATION_PATH . "/configs/sms.ini", APPLICATION_ENV);
            $carriers=$config->toArray();
            $this->carriers=$carriers["carrier"];
        }
        return $this->carriers;
    }

    /**
     * Gets instance of mail object, uses default transport set in bootstrap
     * @return Zend_Mail
     */
    private function getMail(){
        if(null===$this->mail){
            $this->mail=new Zend_Mail();
        }
        return $this->mail;
    }

    /**
     * Sends requested info to number
     * @param String $number
     * @param String $carrier
     */
    public function send($number,$requestedCarrier,$msg){
        $number=str_replace("-","",$number);
        $carriers=$this->getCarriers();
        $mail=$this->getMail();

        //if none selected or they dn't know carrier, send to all
        //Only correct carrier will respond with correct number
        if(empty($requestedCarrier) || $requestedCarrier=='none'){
            $mail->setBodyText($msg);
            $mail->setFrom("directory@ucmerced.edu","UC Merced Directory");
            $mail->setBodyText($msg);
            $sentTo="";
            foreach($carriers as $carrier=>$carrierName){
                $mail->addTo($number . "@" . $carrier);
                $sentTo.=$number . "@" . $carrier;
            }
            try{
                $mail->send();
            }
            catch(Exception $e){
                
            }

        }
        else{
            $mail->addTo($number . "@" . $requestedCarrier);
            $mail->setBodyText($msg);
            $mail->setFrom("directory@ucmerced.edu","UC Merced Directory");
            try{
                $mail->send();
            }
            catch(Exception $e){
                var_dump($e);
                die();
            }
        }

        unset($this->mail);
        return;

    }

    public function getVcardSMS(Application_Model_DirectoryPerson $person){
        //if student, only send name and email
        if($person->getPrimaryAffiliation()=="student"){
            $msg="BEGIN:VCARD\n
                VERSION:3.0\n
                FN: " .  $person->getFullName() . " \n
                N;charset=iso-8859-1:" . $person->getLastName() . ";" . $person->getFirstName() . "\n
                EMAIL;type=WORK:\n
                URL;type=HOME:\n
                TITLE;charset=iso-8859-1:\n
                ORG;charset=iso-8859-1:University of California, Merced\n
                ADR;type=WORK;charset=iso-8859-1:;;5200 North Lake Road;Merced;CA;95343;USA\n
                TEL;type=WORK:\n
                TEL;WORK;FAX:\n
                TEL;type=CELL:\n
                URL;type=WORK:\n
                END:VCARD";
        }
        else{
            $msg="BEGIN:VCARD
                VERSION:3.0
                FN: " . $person->getFullName() . " \n
                N;charset=iso-8859-1:" .  $person->getLastName() . ";" . $person->getFirstName() . "\n
                EMAIL;type=WORK:" . $person->getEmail() . "\n
                URL;type=HOME:\n
                TITLE;charset=iso-8859-1:\n
                ORG;charset=iso-8859-1:University of California, Merced\n
                ADR;type=WORK;charset=iso-8859-1:;;5200 North Lake Road;Merced;CA;95343;USA\n
                TEL;type=WORK:" . $person->getPhone() . "\n
                TEL;WORK;FAX:" . $person->getFax() . "\n
                TEL;type=CELL:" . $person->getCellPhone() . "\n
                URL;type=WORK:\n
                END:VCARD";
        }
        return $msg;
    }

    public function getTextSMS(Application_Model_DirectoryPerson $person){
        //if student, only send name and email
        if($person->getPrimaryAffiliation()=="student"){
            $msg=$person->getFullName() . "\n" . $person->getEmail();
        }
        else{
            $msg="";
            if(!empty($person->phone)){
                $msg.="W:" . $person->getPhone() . "\n";
            }
            if(!empty($person->cellPhone)){
                $msg.="M:" . $person->getCellPhone() . "\n";
            }
            if(!empty($person->fax)){
                $msg.="M:" . $person->getFax() . "\n";
            }
            $msg.=$person->getEmail();
        }

        return $msg;

    }
}

?>
