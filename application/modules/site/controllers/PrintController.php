<?php

require_once("dompdf/dompdf_config.inc.php");

class PrintController extends Zend_Controller_Action
{

    private $departmentMapper;

    public function init()
    {
        $this->_helper->layout->disableLayout();
    }

    public function indexAction()
    {
        // action body
    }

    public function pdfAction()
    {
        $front=Zend_Controller_Front::getInstance();
        $front->setParam("noViewRenderer", true);
        if($this->_getParam("department")){
            $searchFor=$this->_getParam("department");
            $searchBy="ucmercededuapptdeptname1";
            $this->view->searchFor=$searchFor;
            $this->view->searchBy="Department";
        }
        elseif($this->_getParam("people")){
            $searchBy=$this->_getParam("search");
            $searchFor=$this->_getParam("people");
            $this->view->searchFor=$searchFor;
            switch($searchBy){
                case "firstName":
                    $this->view->searchBy="First Name";
                    break;
                case "lastName":
                    $this->view->searchBy="Last Name";
                    break;
                case "fullName":
                    $this->view->searchBy="Full Name";
                    break;
                case "ucmercededuapptdeptname1":
                    $this->view->searchBy="Department";
                    break;
                case "email":
                    $this->view->searchBy="Email";
                    break;
                case "telephone":
                    $this->view->searchBy="Telephone";
                    break;
                case "ucmnetid":
                    $this->view->searchBy="UCMNetID";
                    break;
                case "sid":
                    $this->view->searchBy="Student ID";
                    break;
                case "eid":
                    $this->view->searchBy="Employee ID";
                    break;
                case "ccid":
                    $this->view->searchBy="Cat Card ID";
                    break;
                
                default:
                //    throw new Exception("Invalid search term for generating PDF",500);
                    $this->getHelper("FlashMessenger")->setNamespace("directoryErrors")->addMessage("Invalid Option for Printing PDF.<br />If you need assistance, please contact the Help Desk at (209) 228-HELP(4357)");
                    $this->_redirect("/");
                
            }

        }
        $people=$this->getHelper("SearchPeople")->search($searchBy,$searchFor);
        foreach($people as $key=>$person){
            //filter out some data based on search
            //if dept, only show staff not students
            if($searchBy=="ucmercededuapptdeptname1"){
                $affiliation=$person->getPrimaryAffiliation();
                if($affiliation=="student"){
                    unset($people[$key]);
                }
            }
            //add additional search options here, i.e if you would like to
            //filter out by addiotnal params put them here
        }
        if($searchBy)

        $this->view->searchResults=$people;
        $view=$this->view=Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        $html=$view->render("print/pdf.phtml");

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();

        $dompdf->stream("search-results.pdf");

    }

    public function getDepartmentMapper() {
        if(null===$this->departmentMapper){
            $this->departmentMapper=new Application_Model_DirectoryDepartmentMapper();
        }
        return $this->departmentMapper;
    }



}