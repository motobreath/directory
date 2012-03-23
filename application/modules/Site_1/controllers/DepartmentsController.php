<?php

class DepartmentsController extends Zend_Controller_Action
{

    /**
     * @var Application_Model_DepartmentMapper
     *
     *
     */
    public $departmentMapper = null;

    public $departments = null;

    public $flashMessenger = null;

    public function init()
    {
        //get all depts
        $mapper=$this->getDepartmentMapper();
        $this->departments=$mapper->fetchAll();
        $this->view->page="dept";

        $this->flashMessenger=$this->getHelper("FlashMessenger");

        $this->getHelper("layout")->getView()->headTitle("UC Merced Directory - Departments");

        //adding mobile, just like that
        $this->_helper->contextSwitch()
                ->addActionContext("index","mobile")
                ->addActionContext("results","mobile")
                ->addActionContext("details","mobile")
                ->initContext();

    }

    public function indexAction()
    {
        $options=array();

        foreach($this->departments as $department){
            $options[$department->getName()]=$department->getName();
        }
        $formOptions=array(
            "departments"=>$options
        );
        $form=$this->getHelper("FormLoader")->load("SearchDepartments",$formOptions);
        $form->setAction("/site/departments/search");
        $this->view->form=$form;
        $this->view->departments=$this->departments;
    }

    public function resultsAction()
    {
        $dept=$this->_getParam("department");
        if(empty($dept) || $dept=="0"){
            $this->flashMessenger->setNamespace("directoryErrors")->addMessage("Invalid search options. Empty search dept. Please search again.");
            $this->_redirect("/site/departments");
            return;
        }
        $dept=$this->getDepartmentMapper()->find($dept);

        if(!$dept){
            $this->flashMessenger->setNamespace("directoryErrors")->addMessage("Invalid search options. Please search again.");
            $this->_redirect("/site/departments");
            return;
        }
        foreach($this->departments as $department){
            $options[$department->getName()]=$department->getName();
        }
        $formOptions=array(
            "departments"=>$options
        );
        $form=$this->getHelper("FormLoader")->load("SearchDepartments",$formOptions);
        $form->populate(array("department"=>$dept));

        $this->view->form=$form;
        $this->view->department=$dept;
        $this->view->searchResults=$this->getHelper("SearchPeople")->search("ucmercededuapptdeptname1",$dept->getName());

    }


    public function getDepartmentMapper()
    {
        if(null===$this->departmentMapper){
            $this->departmentMapper=new Application_Model_DirectoryDepartmentMapper();
        }
        return $this->departmentMapper;
    }

    public function searchAction()
    {
        $this->_redirect("/site/departments/results/department/" . $this->_getParam("department"));
    }




}









