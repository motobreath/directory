<?php

class Application_Form_Update extends Zend_Form
{

    public function init()
    {

        $view=$this->getView();
        //$view->headScript()->appendFile("/js/applicationForm.js");
        $view->headLink()->appendStylesheet("/css/updateForm.css");

        $this->setAttrib("id","updateForm");

        $requestingUser=new Zend_Form_Element_Hidden("requestingUser");
        $requestingUser->setDecorators(array("ViewHelper"));
        $this->addElement($requestingUser);

        $userEmail=new Zend_Form_Element_Hidden("userEmail");
        $userEmail->setDecorators(array("ViewHelper"));
        $this->addElement($userEmail);

        $firstName=new Zend_Form_Element_Text("firstName");
        $firstName->setLabel("First Name: ")->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        $this->addElement($firstName);

        $lastName=new Zend_Form_Element_Text("lastName");
        $lastName->setLabel("Last Name: ")->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        $this->addElement($lastName);

        $title=new Zend_Form_Element_Text("title");
        $title->setLabel("Title: ")->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        $this->addElement($title);

        $department=new Zend_Form_Element_Text("department");
        $department->setLabel("Department: ")->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        $this->addElement($department);

        $title2=new Zend_Form_Element_Text("title2");
        $title2->setLabel("Title: ")->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        //$this->addElement($title2);

        $department2=new Zend_Form_Element_Text("department2");
        $department2->setLabel("Working Department2: ")->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        //$this->addElement($department2);

        $phone=new Zend_Form_Element_Text("phone");
        $phone->setLabel("Telephone: ")->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        $this->addElement($phone);

        $fax=new Zend_Form_Element_Text("fax");
        $fax->setLabel("Fax: ")->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        $this->addElement($fax);

        $mobile=new Zend_Form_Element_Text("mobile");
        $mobile->setLabel("Mobile: ")->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        $this->addElement($mobile);

        $mso=new Zend_Form_Element_Select("mso");
        $mso->setMultiOptions($this->getMSO())
                ->setLabel("MSO:")
                ->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        $this->addElement($mso);


        $comments=new Zend_Form_Element_Textarea("comments");
        $comments->setLabel("Comments:")->setDecorators(array("ViewHelper","Label",array("HtmlTag",array("tag"=>"br","openOnly"=>true))));
        $this->addElement($comments);



        $submit=new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Send Update");
        $this->addElement($submit);

    }

    private function getMSO(){
        $options=array(
            "na"=>"Don't know or select one...",
            "pjohnston@ucmerced.edu"=>"Academic Affairs - Phil Johnston",
            "speterson@ucmerced.edu"=>"Academic Resource Center - Stephanie Peterson",
            "speterson@ucmerced.edu"=>"Academic Personnel - Stephanie Peterson",
            "pjohnston@ucmerced.edu"=>"Academic Senate - Phil Johnston",
            "jcisneros4@ucmerced.edu"=>"Accounting (BFS) - Jennifer Cisneros",
            "kunruh@ucmerced.edu"=>"Administration - Katie Unruh",
            "kbonilla@ucmerced.edu"=>"Admissions - Karen Bonilla",
            "edarrah@ucmerced.edu"=>"Bookstore - Elaine Darrah",
            "kunruh@ucmerced.edu"=>"Budget Office - Katie Unruh",
            "kbonilla@ucmerced.edu"=>"Campus Card - Karen Bonilla",
            "tsmullen@ucmerced.edu"=>"Campus Enterprises - Tony Smullen",
            "raoki@ucmerced.edu"=>"Center for Educational Partnerships (Fresno) - Randy Aoki",
            "mwidger@ucmerced.edu"=>"Chancellor - Mare Widger",
            "jdumagon@ucmerced.edu"=>"College One - Juana Dumagan",
            "kbonilla@ucmerced.edu"=>"Counceling - Karen Bonilla",
            "edarrah@ucmerced.edu"=>"Dining - Elaine Darrah",
            "kbonilla@ucmerced.edu"=>"Disability Services - Karen Bonilla",
            "kunruh@ucmerced.edu"=>"Early Childhood Development Center (ECEC) - Katie Unruh",
            "kunruh@ucmerced.edu"=>"Environmental Health and Safety - Katie Unruh",
            "cchristensen@ucmerced.edu"=>"Engineering - Christina Christensen",
            "speterson@ucmerced.edu"=>"Exec. Vice Chancellor and Provost - Stephanie Peterson",
            "tsmullen@ucmerced.edu"=>"Facilities - Tony Smullen",
            "kbonilla@ucmerced.edu"=>"Financial Aid - Karen Bonilla",
            "syoshimi@ucmerced.edu"=>"Graduate Division - Sandy Yoshimi",
            "harno@ucmerced.edu"=>"Great Valley Center (Modesto) - Heidi Arno",
            "kunruh@ucmerced.edu"=>"Human Resources - Katie Unruh",
            "mangel@ucmerced.edu"=>"Information Technology - Marisela Angel",
            "speterson@ucmerced.edu"=>"Institutional Planning and Analysis - Stephanie Peterson",
            "kbonilla@ucmerced.edu"=>"International Programs - Karen Bonilla",
            "jparham@ucmerced.edu"=>"Library - Joy Parham",
            "msmith29@ucmerced.edu"=>"Natural Sciences - Mireille Smith",
            "kunruh@ucmerced.edu"=>"Operations - Katie Unruh",
            "dcaton@ucmerced.edu"=>"Physical Planning - Diane Caton",
            "afarr-matthew@ucmerced.edu"=>"Police and Public Safety - Ann Farr-Matthew",
            "kbonilla@ucmerced.edu"=>"Recreation - Karen Bonilla",
            "kbonilla@ucmerced.edu"=>"Registrar - Karen Bonilla",
            "syoshimi@ucmerced.edu"=>"Research - Sandy Yoshimi",
            "syoshimi@ucmerced.edu"=>"Sierra Nevada Research Institute (SNRI) - Sandy Yoshimi",
            "jhansen@ucmerced.edu"=>"Social Sciences, Humanities and Arts - Janet Hansen",
            "syoshimi@ucmerced.edu"=>"Sponsored Projects - Sandy Yoshimi",
            "kbonilla@ucmerced.edu"=>"Student Advising - Karen Bonilla",
            "kbonilla@ucmerced.edu"=>"Students First Center - Karen Bonilla",
            "kbonilla@ucmerced.edu"=>"Student Affairs - Karen Bonilla",
            "kbonilla@ucmerced.edu"=>"Student Health - Karen Bonilla",
            "edarrah@ucmerced.edu"=>"Student Housing - Elaine Darrah",
            "cmcbride3@ucmerced.edu"=>"Student Life - Connie McBride",
            "srunyon@ucmerced.edu"=>"University Relations - Shannon Runyon",
            "jhansen@ucmerced.edu"=>"Writing Program - Janet Hansen",
        );
        return $options;
    }


}

