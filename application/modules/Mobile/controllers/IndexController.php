<?php

class Mobile_IndexController extends Zend_Controller_Action
{

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     *
     */
    public $flashMessenger = null;

    public function init()
    {
        $this->flashMessenger=$this->getHelper("FlashMessenger");
        $this->getHelper("layout")->getView()->headTitle("UC Merced Directory - People Search");
    }

    public function indexAction()
    {
        $form=new Application_Form_Search();
        $form->setAction("/mobile/index/results");
        $form->getElement("searchBy")->removeDecorator("HtmlTag");
        $form->getElement("submit")->addDecorator("HtmlTag",array("tag"=>"div","class"=>"submit"));
        $this->view->searchForm=$form;
    }

    public function resultsAction()
    {
        $searchFor=$this->_getParam("searchFor");
        $searchBy=$this->_getParam("searchBy");
        if(empty($searchFor) || empty($searchBy)){
            $this->flashMessenger->setNamespace("directoryErrors")->addMessage("Invalid Search. Please search again.");
            $this->_redirect("/mobile");
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
             $this->_redirect("/mobile");
        }

    }

    public function detailsAction()
    {
        // action body
    }


}







