<?php

class IndexController extends Zend_Controller_Action
{

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     *
     *
     */
    public $flashMessenger = null;

    public $_namespace="UCMDirectory";

    /**
     *
     * @var Zend_Session
     */
    public $session;

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
        $form->setAction("/site/index/results");
        $this->view->searchForm=$form;
        unset($this->getSession()->searchFor);
        unset($this->getSession()->searchBy);

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
        $this->view->searchFor=$searchFor;
        $this->view->searchBy=$searchBy;

        $this->getSession()->searchFor=$searchFor;
        $this->getSession()->searchBy=$searchBy;
    }

    public function detailsAction()
    {
        $email=$this->_getParam("email");
        $person=$this->getHelper("SearchPeople")->getPerson($email);
        $form=new Application_Form_SMS();
        if($this->getRequest()->isPost()){
            $this->view->submitted=true;
            if($form->isValid($this->_getAllParams())){
                $msg=$this->getHelper("SendSMS")->getTextSMS($person);
                $this->getHelper("SendSMS")->send($this->_getParam("number"),$this->_getParam("carrier"),$msg);
                $this->view->successSMS=true;
            }
        }
        $this->view->person=$person;
        $this->view->form=$form;
        $this->view->searchFor=$this->getSession()->searchFor;
        $this->view->searchBy=$this->getSession()->searchBy;

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

    public function getSession(){
        if(null===$this->session){
            $this->session=new Zend_Session_Namespace($this->_namespace);
        }
        return $this->session;
    }


}









