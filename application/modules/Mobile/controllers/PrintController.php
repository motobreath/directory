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
        $view = $this->getHelper('ViewRenderer')->view;
        $email=$this->_getParam("email");
        $view->person=$this->getHelper("SearchPeople")->getPerson($email);
        $msg = $view->render('print/contact.phtml');
        $filename=$this->view->person->getLastName() . "_" . $this->view->person->getFirstName();

        $mail=new Zend_Mail();

        //create vcard attachment
        $at=$mail->createAttachment($msg);
        $at->type        = 'text/vcard';
        $at->disposition = Zend_Mime::DISPOSITION_INLINE;
        $at->encoding    = Zend_Mime::ENCODING_BASE64;
        $at->filename    = $filename . ".vcf";

        //building wierd body message required to send email
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

        $message ="Contact VCard for " . $view->person->getFullName();

        $mail->setBodyText($message);

        //send email from us, to you. nice i know.
        $mail->addTo($email);
        $mail->setFrom("directoy@ucmerced.edu","UC Merced Directory");
        $mail->setSubject("Contact");
        $mail->send();

        //
    }

}





