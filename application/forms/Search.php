<?php

class Application_Form_Search extends Zend_Form
{

    public function init()
    {
        $this->setAttrib("id","searchForm");
        $this->setDecorators(array('FormElements','Form'));
        $this->getView()->headScript()->appendFile("/js/validate.js");

        $searchOptions=array(
            'lastName' => 'Last Name (automatic wildcard at the end)',
            'firstName' => 'First Name (automatic wildcard at the end)',
            'fullName' => 'Full Name',
            'department' => 'Department',
            'email'=>"Email",
            'telephone'=>"Telephone Number"
        );
        $searchBy = new Zend_Form_Element_Select('searchBy');
        $searchBy->addMultiOptions($searchOptions)
                ->setLabel("Search By:")
                ->setRequired()
                ->setDecorators(array("Errors","ViewHelper","Label",array("HtmlTag",array("tag"=>"<br />"))));
        $this->addElement($searchBy);

        $searchFor=new Zend_Form_Element_Text("searchFor");
        $searchFor->setLabel("Search For:")
                ->setAttrib("style","width:192px")
                ->addErrorMessage("Please enter a minimum of 2 characters")
                ->setRequired()
                ->addValidator(new Zend_Validate_StringLength(array("min"=>2)))
                ->setDecorators(array(
                    "ViewHelper",
                    "Label",
                    array(
                        "Errors",array("placement"=>"prepend","class"=>"errors")
                    )
                    ));
        $this->addElement($searchFor);

        $submit=new Zend_Form_Element_Button("submit");
        $submit->setLabel("Search")
                ->setAttribs(array("class"=>"submit","type"=>"submit"))
                ->setDecorators(array("ViewHelper"));
        $this->addElement($submit);

    }

    public function getSearchByFieldToLDAPAttribute() {
        return $this->searchByFieldToLDAPAttribute;
    }

    public function setSearchByFieldToLDAPAttribute($searchByFieldToLDAPAttribute) {
        $this->searchByFieldToLDAPAttribute = $searchByFieldToLDAPAttribute;
    }




}

