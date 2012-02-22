<?php

class IndexController extends Zend_Controller_Action
{

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     *
     *
     */
    public $flashMessenger = null;

    public function init()
    {
        $this->flashMessenger=$this->getHelper("FlashMessenger");
        $this->getHelper("layout")->getView()->headTitle("UC Merced Directory - People Search");
        $this->view->page="people";
    }

    public function indexAction()
    {
        $checkMobile=$this->getHelper("MobileRedirect");
        $checkMobile->mobileRedirect();

        $form=new Application_Form_Search();
        $this->view->searchForm=$form;
        if($this->getRequest()->isPost()){
            if ($form->isValid($this->_getAllParams())) {
                $searchBy = $this->_getParam("searchBy");
                $searchFor = trim($this->_getParam("searchFor"));
                $this->view->searchResults=$this->getHelper("SearchPeople")->search($searchBy,$searchFor);
                $this->view->searched=true;
            }
        }

    }

    public function resultsAction()
    {
        $searchFor=$this->_getParam("searchFor");
        $searchBy=$this->_getParam("searchBy");
        if(empty($searchFor) || empty($searchBy)){
            $this->flashMessenger->setNamespace("directoryErrors")->addMessage("Invalid search options. Please search again.");
            $this->_redirect("/");
            return;
        }
        $form=new Application_Form_Search();
        if ($form->isValid($this->_getAllParams())) {
            $searchBy = $this->_getParam("searchBy");
            $searchFor = trim($this->_getParam("searchFor"));
            $this->view->searchResults=$this->getHelper("SearchPeople")->search($searchBy,$searchFor);
        }
        else{
             foreach($form->getErrors() as $error){
                 $this->flashMessenger->setNamespace("directoryErrors")->addMessage($error[0]);
             }
             $this->_redirect("/");
        }
        $this->view->searchForm=$form;
    }

    public function detailsAction()
    {
        $email=$this->_getParam("email");
        $person=$this->getHelper("SearchPeople")->getPerson($email);
        $form=new Application_Form_SMS();
        if($this->getRequest()->isPost()){
            $this->view->submitted=true;
            if($form->isValid($this->_getAllParams())){
                if($this->_getParam("sendAs")=="text"){
                    $msg=$this->getHelper("SendSMS")->getTextSMS($person);
                }
                elseif($this->_getParam("sendAs")=="contact"){
                    $msg=$this->getHelper("SendSMS")->getVcardSMS($person);
                }

                $this->getHelper("SendSMS")->send($this->_getParam("number"),$this->_getParam("carrier"),$msg);
            }
        }
        $this->view->person=$person;
        $this->view->form=$form;

    }

    public function vcardAction()
    {
        $this->_helper->layout()->disableLayout();
        $email=$this->_getParam("email");
        $this->view->person=$this->getHelper("SearchPeople")->getPerson($email);
        $this->view->filename=$this->view->person->getLastName() . "_" . $this->view->person->getFirstName();
    }

    public function sendsmsAction(){
        $form=new Application_Form_SMS();
        if($form->isValid($this->_getAllParams())){

        }
    }


}









