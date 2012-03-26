<?php

class IndexController extends Zend_Controller_Action
{

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     *
     *
     *
     *
     */
    public $flashMessenger = null;

    public $_namespace = 'UCMDirectory';

    /**
     * @var Zend_Session
     *
     *
     */
    public $session = null;

    public function init()
    {
        $this->flashMessenger=$this->getHelper("FlashMessenger");
        $this->getHelper("layout")->getView()->headTitle("UC Merced Directory - People Search");
        $this->view->page="people";
        $this->_helper->contextSwitch()
                ->addActionContext("index","mobile")
                ->addActionContext("results","mobile")
                ->initContext();
    }

    public function indexAction()
    {
        $this->resetSearchSession();
        $form=$this->getHelper("FormLoader")->load("Search");

        if($this->getSession()->isMobile){
            $form->setAction("/site/index/results/");
        }
        else{
            $form->setAction("/site/index/search/");
        }



        //$form->setAttrib("data-ajax","false");
        $this->view->searchForm=$form;

    }

    public function resultsAction()
    {
        $this->getHelper("layout")->disable();
        $searchBy = $this->_getParam("searchBy");
        $searchFor = trim($this->_getParam("searchFor"));

        if(empty($searchFor) || empty($searchBy)){
            $this->flashMessenger->setNamespace("directoryErrors")->addMessage("Invalid search options. Please search again.");
            $this->_redirect("/");
            return;
        }

        $form=$this->getHelper("FormLoader")->load("Search");
        $form->setAction("/site/index/search/");

        $this->view->searchResults=$this->getHelper("SearchPeople")->search($searchBy,$searchFor);

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
        if(!$person){
            $this->flashMessenger->setNamespace("directoryErrors")->addMessage("Person not found. Please search again.");
        }
        $form=$this->getHelper("FormLoader")->load("SMS");
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

        $this->getSession()->detailPerson=$person;

    }

    public function vcardAction()
    {
        $this->_helper->layout()->disableLayout();
        $email=$this->_getParam("email");
        $this->view->person=$this->getHelper("SearchPeople")->getPerson($email);
        $this->view->filename=$this->view->person->getLastName() . "_" . $this->view->person->getFirstName();
    }

    public function getSession()
    {
        if(null===$this->session){
            $this->session=new Zend_Session_Namespace($this->_namespace);
        }
        return $this->session;
    }

    public function searchAction()
    {
        $form=$this->getHelper("FormLoader")->load("Search");
        if ($form->isValid($this->_getAllParams())){
            $searchBy = $this->_getParam("searchBy");
            $searchFor = trim($this->_getParam("searchFor"));
            $this->_redirect("/site/index/results/searchBy/$searchBy/searchFor/$searchFor/");
        }
        else{
            foreach($form->getErrors() as $error){
                 $this->flashMessenger->setNamespace("directoryErrors")->addMessage($error[0]);
             }
             $this->_redirect("/");
        }

    }

    private function resetSearchSession()
    {
        unset($this->getSession()->searchFor);
        unset($this->getSession()->searchBy);
        unset($this->getSession()->detailPerson);

    }

    public function errorAction()
    {
        throw new Exception("This be the error check action.", 500);
    }


}













