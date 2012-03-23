<?php

class EmailController extends Zend_Controller_Action
{

    public $flashMessenger = null;

    public $_namespace = 'UCMDirectory';

    /**
     * @var Zend_Session
     *
     */
    public $session = null;

    public function getSession()
    {
        if(null===$this->session){
            $this->session=new Zend_Session_Namespace($this->_namespace);
        }
        return $this->session;
    }

    public function init()
    {
        $this->flashMessenger=$this->getHelper("FlashMessenger");
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
	$ajaxContext->addActionContext('sendcontact', 'json')
                    ->initContext();
    }

    public function indexAction()
    {
        $this->view->searchFor=$this->getSession()->searchFor;
        $this->view->searchBy=$this->getSession()->searchBy;
    }

    public function sendcontactAction()
    {

        $this->_helper->layout->disableLayout();
        $mail=new Zend_Mail();
        $view = $this->getHelper('ViewRenderer')->view;
        $format=$this->_getParam("format");

        try{
            $sendTo=$this->_getParam("sendTo");
            $email=$this->_getParam("email");
            if(empty($sendTo) || empty($email)){
                $this->flashMessenger->setNamespace("directoryErrors")->addMessage("Invalid Email. Please go back and try again");
                throw new Exception("Empty Email");
            }
            $validate=new Zend_Validate_EmailAddress();
            if(!$validate->isValid($email) || !$validate->isValid($sendTo)){
                $this->flashMessenger->setNamespace("directoryErrors")->addMessage("Invalid Email. Please go back and try again");
                throw new Exception("bad Email");
            }

            $view->person=$this->getHelper("SearchPeople")->getPerson($email);
            $msg = $view->render('email/sendcontact.phtml');
            $filename=$this->view->person->getLastName() . "_" . $this->view->person->getFirstName();

            //create vcard attachment
            $at=new Zend_Mime_Part($msg);
            $at->disposition = Zend_Mime::DISPOSITION_INLINE;
            $at->encoding    = Zend_Mime::ENCODING_BASE64;
            $at->filename    = $filename . ".vcf";
            $mail->addAttachment($at);

            $message ="Contact VCard for " . $view->person->getFullName() . " - This message was auto generated from UC Merced Directory, please do not reply.";

            $mail->setBodyText($message);

            //send email from us, to you. nice i know.
            $mail->addTo($sendTo);
            $mail->setFrom("directoy@ucmerced.edu","UC Merced Directory");
            $mail->setSubject("Contact");
            $mail->send();

            if($format=="json"){
                $this->getResponse()->setHttpResponseCode(200);
            }
            else{
               $this->_redirect("/site/email");
            }
        }
        catch(Exception $e){
            if($format=="json"){
                $this->getResponse()->setException($e)->setHttpResponseCode(500);
            }
            else{
                $this->flashMessenger->setNamespace("directoryErrors")->addMessage("Could not send email: " . $e);
               $this->_redirect("/site/email");
            }
        }


    }


}





