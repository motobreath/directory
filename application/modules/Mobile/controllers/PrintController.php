<?php

class Mobile_PrintController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function contactAction()
    {
        $this->_helper->layout->disableLayout();
        $mail=new Zend_Mail();
        $view = $this->getHelper('ViewRenderer')->view;
        $email=$this->_getParam("email");
        $view->person=$this->getHelper("SearchPeople")->getPerson($email);
        $msg = $view->render('print/contact.phtml');
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
        $mail->addTo($email);
        $mail->setFrom("directoy@ucmerced.edu","UC Merced Directory");
        $mail->setSubject("Contact");
        $mail->send();

        //
    }

}





