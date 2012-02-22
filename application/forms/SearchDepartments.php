<?php


/**
 * form for searching departments
 *
 * @author Chris
 */
class Application_Form_SearchDepartments
    extends Zend_Form
{
    /**
     * Array of departments, set from either construct or
     * passed in using setters when creating new form
     * @var Array
     */
    public $departments;

    public function getDepartments() {
        //fail safe for depts. should pass in to construct
        //but if none, get from db
        if(null==$this->departments){
            $mapper=new Application_Model_DirectoryDepartmentMapper();
            $departments=$mapper->fetchAll();
            $options=array();
            foreach($departments as $department){
                $options[]=$department->getName();
            }
            $this->departments=$options;
        }
        return $this->departments;
    }

    public function setDepartments($departments) {
        $this->departments = $departments;
    }

    /**
     * Init Form! Remember to use array($departments)
     * when creating new form
     */
    public function init(){

        $this->setAttrib("id","searchDepartments");

        $options=array();
         $options["0"]="Choose a name from the pulldown or select a name below...";
        foreach($this->getDepartments() as $key=>$value){
            $options[$key]=$value;
        }

        $department=new Zend_Form_Element_Select("department");
        $department->setLabel("Department Name:")
                ->addMultiOptions($options)
                ->setDecorators(array("Label","ViewHelper"));

        $submit=new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Search the Directory")->setDecorators(array("ViewHelper"));

        $this->addElements(array($department,$submit));

    }
}

?>
