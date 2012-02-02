<?php

class Application_Form_Search extends Zend_Form
{

    public function init()
    {
        $searchBy = new Zend_Form_Element_Select('searchBy');
        $searchBy->addMultiOptions(array(
                'lastName' => 'Last Name (automatic wildcard at the end)',
                'firstName' => 'First Name (automatic wildcard at the end)',
                'fullName' => 'Full Name',
                'department' => 'Department',
                'email'=>"Email",
                'telephone'=>"Telephone Number"
                ))
                ->setLabel("Search By:")
                ->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"<br />"))));
        $this->addElement($searchBy);

        $searchFor=new Zend_Form_Element_Text("searchFor");
        $searchFor->setLabel("Search By:")
                ->setAttrib("style","width:192px")
                ->setDecorators(array(
                    "ViewHelper",
                    "Label"
                    ));
        $this->addElement($searchFor);

        $submit=new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Search")
                ->setAttrib("class","submit")
                ->setDecorators(array("ViewHelper"));
        $this->addElement($submit);

    }


}

