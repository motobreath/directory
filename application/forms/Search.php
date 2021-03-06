<?php

class Application_Form_Search extends Zend_Form
{

    public function init()
    {
        $this->setAttrib("id","searchForm");
        $this->setDecorators(array('FormElements','Form'));
        //$this->getView()->headScript()->appendFile("/min/f=js/validate.js");

        $searchOptions=array(
            'lastName' => 'Last Name (automatic wildcard at the end)',
            'firstName' => 'First Name (automatic wildcard at the end)',
            'fullName' => 'Full Name',
            'title' => "Title",
            'ucmercededuapptdeptname1' => 'Department',
            'email'=>"Email",
            'telephone'=>"Telephone Number"
        );

        //if pd, allow for more search options
        if(Zend_Controller_Action_HelperBroker::getStaticHelper("ACL")->hasAccess("PDsearchForm")){
            $searchOptions["ucmnetid"]="UCMNetID";
            $searchOptions["sid"]="Student ID";
            $searchOptions["eid"]="Employee ID";
            $searchOptions["ccid"]="Cat Card ID";
        }

        $searchBy = new Zend_Form_Element_Select('searchBy');
        $searchBy->addMultiOptions($searchOptions)
                ->setLabel("Search By:")
                ->setRequired()
                ->addErrorMessage("Please select a search by parameter")
                ->setDecorators(array("Errors","ViewHelper","Label",array("HtmlTag",array("tag"=>"<img>","class"=>"loading","src"=>"/images/loading.gif","openOnly"=>true))));
        $this->addElement($searchBy);

        $searchFor=new Zend_Form_Element_Text("searchFor");
        $searchFor->setLabel("Search For:")
                ->addErrorMessage("Please enter a minimum of 2 characters")
                ->setRequired()
                ->addValidator(new Zend_Validate_StringLength(array("min"=>2)))
                ->setDecorators(array(
                    "ViewHelper",
                    "Label",
                    array(
                        "Errors",array("placement"=>"prepend","class"=>"errors")
                    ),
                    //array("HtmlTag",array("tag"=>"<div>","data-role"=>"fieldcontain"))
                    array("HtmlTag",array("tag"=>"<br />","placement"=>"prepend","openOnly"=>true))
                    ));
        $this->addElement($searchFor);

        $submit=new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Search the Directory")
                ->setAttribs(array("class"=>"submit","type"=>"submit"))
                ->setDecorators(array("ViewHelper",array("HtmlTag",array("tag"=>"<div>","class"=>"submit"))));
        $this->addElement($submit);

    }

    public function getSearchByFieldToLDAPAttribute() {
        return $this->searchByFieldToLDAPAttribute;
    }

    public function setSearchByFieldToLDAPAttribute($searchByFieldToLDAPAttribute) {
        $this->searchByFieldToLDAPAttribute = $searchByFieldToLDAPAttribute;
    }




}

