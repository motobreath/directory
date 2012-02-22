<?php

class DepartmentsController extends Zend_Controller_Action
{
    /**
     *
     * @var Application_Model_DepartmentMapper
     */
    public $departmentMapper;
    public $departments = null;
    public $flashMessenger;

    public function init()
    {
        //get all depts
        $mapper=$this->getDepartmentMapper();
        $this->departments=$mapper->fetchAll();
        $this->view->page="dept";

        $this->flashMessenger=$this->getHelper("FlashMessenger");

        $this->getHelper("layout")->getView()->headTitle("UC Merced Directory - Departments");
    }

    public function indexAction()
    {
        $options=array();
        foreach($this->departments as $department){
            $options[$department->getName()]=$department->getName();
        }
        $form=new Application_Form_SearchDepartments(array(
            "departments"=>$options
        ));
        $form->setAction("/site/departments/results");
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
        $form=new Application_Form_SearchDepartments(array(
            "departments"=>$options
        ));
        $form->populate(array("department"=>$dept));

        $this->view->form=$form;
        $this->view->department=$dept;
        $this->view->people=$this->getHelper("SearchPeople")->search("dept1",$dept->getName());

    }

    public function getDepartmentMapper() {
        if(null===$this->departmentMapper){
            $this->departmentMapper=new Application_Model_DirectoryDepartmentMapper();
        }
        return $this->departmentMapper;
    }

}





