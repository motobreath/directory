<?php

include "CAS/CAS.php";

class HelpController extends Zend_Controller_Action
{

    public $_namespace = 'UCMDirectory';

    /**
     * @var Zend_Session
     *
     */
    public $session = null;

    /**
     *
     * @var Application_Model_DirectoryPerson
     */
    public $person;


    public function init()
    {
        $this->person=$this->getSession()->detailPerson;
    }

    public function indexAction()
    {

    }

    public function contactAction()
    {
    }

    public function updateAction()
    {
        if(!$this->getSession()->username){
            $this->getCAS();
        }
        $options=array(
            "firstName"=>$this->person->getFirstName(),
            "lastName"=>$this->person->getLastName(),
            "title"=>$this->person->getJobTitle(),
            "department"=>$this->person->getDepartment(),
            "phone"=>$this->person->getPhone(),
            "fax"=>$this->person->getFax(),
            "mobile"=>$this->person->getCellPhone()
        );
        $form=new Application_Form_Update();
        $form->populate($options);

        if($this->getRequest()->isPost()){

            $mail=new Zend_Mail();
            $msg="<p>This is generated from the directory page and is requested by " . $this->getSession()->username . "<br />";
            $msg.="The user selected the following MSO: ";
            $mso=$this->_getParam("mso");
            if($mso=="na"){
                $msg.= "No MSO selected";
            }
            else{
                $msg.=$mso;
            }
            $msg.="</p>";
            $msg.="<p>MSOs, please login to <a href='http://idm.ucmerced.edu'>http://idm.ucmerced.edu</a> and make the changes to the user's record.</p>";
            $msg.="First Name:<br />
                   Currently: " . $this->person->getFirstName() . "<br />
                   Requested: " . $this->_getParam("firstName") . "<br /><br />";
            $msg.="Last Name:<br />
                   Currently: " . $this->person->getLastName() . "<br />
                   Requested: " . $this->_getParam("lastName") . "<br /><br />";
            $msg.="Title:<br />
                   Currently: " . $this->person->getJobTitle() . "<br />
                   Requested: " . $this->_getParam("title") . "<br /><br />";
            $msg.="Department:<br />
                   Currently: " . $this->person->getDepartment() . "<br />
                   Requested: " . $this->_getParam("department") . "<br /><br />";
            $msg.="Telephone:<br />
                   Currently: " . $this->person->getPhone() . "<br />
                   Requested: " . $this->_getParam("phone") . "<br /><br />";
            $msg.="Fax:<br />
                   Currently: " . $this->person->getFax() . "<br />
                   Requested: " . $this->_getParam("fax") . "<br /><br />";
            $msg.="Mobile:<br />
                   Currently: " . $this->person->getCellPhone() . "<br />
                   Requested: " . $this->_getParam("mobile") . "<br /><br />";
            $msg.="Comments:" . $this->_getParam("comments");

            $mail->setBodyHtml($msg);

            $mail->addTo("cmitchell@ucmerced.edu");
            $mail->setSubject("Request to change information for:" . $this->person->getEmail());
            $mail->setFrom("directory@ucmerced.edu");

            $mail->send();

            $this->view->sent=true;

            $form->populate($this->_getAllParams());

        }

        $this->view->form=$form;

    }

    public function logoutAction(){
        Zend_Session_Namespace::resetSingleInstance($this->_namespace);
        phpCAS::client(SAML_VERSION_1_1,"cas.ucmerced.edu",443,"/cas",false);
        phpCAS::logoutWithRedirectService("/");
        $this->_redirect("/");
    }

    public function getSession()
    {
        if(null===$this->session){
            $this->session=new Zend_Session_Namespace($this->_namespace);
        }
        return $this->session;
    }

    private function getCAS(){
        //this action is cassified, only view after login

        phpCAS::client(SAML_VERSION_1_1,"cas.ucmerced.edu",443,"/cas",false);
        phpCAS::setNoCasServerValidation();
        phpCAS::forceAuthentication();

        $this->getSession()->username=phpCAS::getUser();
    }




}







