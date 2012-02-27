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
                case "department":
                    $this->view->searchBy="Last Name";
                    break;
                case "email":
                    $this->view->searchBy="Email";
                    break;
                case "telephone":
                    $this->view->searchBy="Telephone";
                    break;
                default:
                    throw new Exception("Invalid search term for generating PDF",500);
            }

        }
        $this->view->searchResults=$this->getHelper("SearchPeople")->search($searchBy,$searchFor);
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