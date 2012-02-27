<?php

/**
 * Send SMS from detail page
 *
 * @author Chris
 */
class Application_Form_SMS
    extends Zend_Form
{

    private $carriers;

    public function getCarriers() {
        if(null===$this->carriers){
            $config=new Zend_Config_Ini(APPLICATION_PATH . "/configs/sms.ini", APPLICATION_ENV);
            $carriers=$config->toArray();
            $this->carriers=$carriers["carrier"];

        }
        return $this->carriers;
    }

    public function init(){
        $this->setAttrib("id","smsForm");
        $this->setDecorators(array('FormElements','Form'));
        $this->getView()->headScript()->appendFile("/js/validate.js");

        $validate=new Zend_Validate_Regex(array(
           "pattern"=>"/^[2-9]\d{2}-\d{3}-\d{4}$/",
           "message"=>"Please enter your Cellphone Number in the format xxx-xxx-xxxx"
        ));
        $number=new Zend_Form_Element_Text("number");
        $number->setLabel("Cellphone Number:")
                ->setDescription("<em>(xxx-xxx-xxxx)</em>")
                ->setRequired()
                ->addValidator($validate)
                ->addErrorMessage("Please enter your Cellphone Number in the format xxx-xxx-xxxx")
                ->setDecorators(array("Label","ViewHelper",array("Description",array("tag"=>"span")),array("Errors",array("placement"=>"prepend"))));
        $number->getDecorator("Description")->setEscape(false);
        $this->addElement($number);

        $carriers=$this->getCarriers();

        $carriers[""]=" - Select - ";
        array_multisort($carriers);
        $carriers["none"]="Other / Not Sure";


        $carrier=new Zend_Form_Element_Select("carrier");
        $carrier->addMultiOptions($carriers)
                ->setLabel("Select your provider:")
                ->setDescription("<em>Mobile provider not in the list? <a href='mailto:cmitchell@ucmerced.edu'>Email Us</a></em>")
                ->setDecorators(array("Errors","Label","ViewHelper",array("Description",array("tag"=>"span")),"HtmlTag"))
                ->getDecorator("Description")->setEscape(false);
        $this->addElement($carrier);

        $submit=new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Send")->setDecorators(array("ViewHelper"))->setAttrib("class","submit");
        $this->addElement($submit);

        $this->addElement('hash', 'no_csrf_foo', array('salt' => 'unique'));

    }

}

?>
