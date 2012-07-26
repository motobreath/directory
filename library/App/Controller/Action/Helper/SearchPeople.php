<?php


/**
 * Searches Ldap for people
 *
 * @author Chris
 */
class App_Controller_Action_Helper_SearchPeople
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     *
     * @var App_Controller_Action_Helper_Ldap
     */
    private $ldap;

    private $searchByFieldToLDAPAttribute = array(
      "lastName"	=> "sn",
      "firstName"	=> "givenName",
      "fullName"	=> "cn",
      "email"		=> "mail",
      "telephone"	=> "telephoneNumber",
      "ucmercededuapptdeptname1"=> "ucmercededuapptdeptname1",
      "ucmnetid" => "uid",
      "sid" => "ucmercededubannersid",
      "eid" => "employeenumber",
      "ccid" => "ucMercedEduCampusCardID"
    );

    private $appendWildcardAttributes = array(
            "sn",
            "givenName",
            "mail",
            "ucmercededuapptdeptname1",
            "uid",
            "cn"
    );

    private $additionalData=false;

    public function __construct(){
        $this->ldap=new App_Controller_Action_Helper_Ldap();
        $this->additionalData=Zend_Controller_Action_HelperBroker::getStaticHelper("ACL")->hasAccess("AdditionalData");
    }

    public function search($searchBy,$searchFor){
        $ldapAttribute = $this->searchByFieldToLDAPAttribute[$searchBy];
        if(!$ldapAttribute) {
            throw new Exception("Invalid LDAP searchBy param. This should have been escaped from the search form");
        }
        $searchFor=$this->ldap->escapeValue($searchFor);


        if(in_array($ldapAttribute,$this->appendWildcardAttributes)){
            $searchFor.="*";
        }

        //special search options here. right now just use if statements,
        //could technically use ACL here too to sort what search option gets what
        //filter. oh well go with if then else for now

        //start with telephone, its special, then do the rest
        if($ldapAttribute=="telephoneNumber"){
            $searchFor=str_replace("-"," ", $searchFor);
            if($this->additionalData){
                $filter="(telephonenumber=*$searchFor*)";
            }
            else{
                $filter="(&(|(telephonenumber=*$searchFor*)(&(ucmercededupublishcellphonenumber=1)(mobile=*$searchFor*)))(ucmercededuonlinedir=1)(!(ucmercededuferpa=0))(|(edupersonprimaryaffiliation=staff)(edupersonprimaryaffiliation=generic)(edupersonprimaryaffiliation=affiliate)(edupersonprimaryaffiliation=faculty)))";
            }

        }
        else{
            //if additional data, i.e. police user is searching
            //don't limit ldap with filters
            if($this->additionalData){
                $filter="($ldapAttribute=$searchFor)";
            }
            else{
                //not police search
                //limit by filters
                if($searchBy=="ucmercededuapptdeptname1"){
                    //this filter removes students from the list
                    //also adds dept. Name 2
                    $filter = "(&(|(ucmercededuapptdeptname1=$searchFor)(ucmercededuapptdeptname2=$searchFor))(ucmercededuonlinedir=1)(|(edupersonprimaryaffiliation=staff)(edupersonprimaryaffiliation=generic)(edupersonprimaryaffiliation=affiliate)(edupersonprimaryaffiliation=faculty)))";
                }
                else{
                    //default filter
                    $filter = "(&($ldapAttribute=$searchFor)(ucmercededuonlinedir=1)(|(edupersonprimaryaffiliation=staff)(edupersonprimaryaffiliation=affiliate)(edupersonprimaryaffiliation=generic)(edupersonprimaryaffiliation=faculty)(edupersonprimaryaffiliation=student)))";
                }

            }

        }

        $entries=$this->ldap->search($filter,null);
        $entries=$entries->toArray();
        $results=array();
        foreach($entries as $entry){
            if($this->ldap->getItem($entry, "ucmercededuferpa")!="1"){

                $person = new Application_Model_DirectoryPerson();
                $this->populatePerson($person,$entry);
                $results[]=$person;

            }

        }

        //do special sorting
        //Sort application side lastName, firstName
        //ldap only allows for one sort attribue (php limitation)
        //sort here
        usort($results,function($a,$b){
            return strcmp(strtolower($a->getLastName()).", " .strtolower($a->getFirstName()), strtolower($b->getLastName()).", " .strtolower($b->getFirstName()));
        });

        //remove students if departmental search but not if pd, they want more data
        if($ldapAttribute=="ucMercedEduApptDeptName1" && !$this->additionalData){
            foreach($results as $key=>$person){
                $affiliation=$person->getPrimaryAffiliation();
                if($affiliation=="student"){
                    unset($results[$key]);
                }
            }
        }

        return $results;

    }

    /**
     * Get single person from ldap, return single person object
     * @param String $email
     * @return Application_Model_DirectoryPerson
     */
    public function getPerson($email){
        $email=$this->ldap->escapeValue($email);
        $filter="(&(ucmercededuonlinedir=1)(mail=$email))";
        $entry=$this->ldap->search($filter);
        $entry=$entry->toArray();
        if(count($entry)==0){
            return new Application_Model_DirectoryPerson();
        }
        $entry=$entry[0];
        $person=new Application_Model_DirectoryPerson();
        $this->populatePerson($person, $entry);
        return $person;
    }

    /**
     * Similar to find person by email, use IDM ID instead
     * @param String $IDMID
     * @return Application_Model_DirectoryPerson
     */
    public function getPersonFromIDMID($IDMID){
        if(!App_Controller_Action_Helper_ACL::hasAccess("AdditionalData")){
            return;
        }
        $IDMID=$this->ldap->escapeValue($IDMID);
        $filter="(ucmercededuidmid=$IDMID)";
        $entry=$this->ldap->search($filter);
        $entry=$entry->toArray();
        if(count($entry)==0){
            return new Application_Model_DirectoryPerson();
        }
        $entry=$entry[0];
        $person=new Application_Model_DirectoryPerson();
        $this->populatePerson($person, $entry);
        return $person;
    }

    private function populatePerson(Application_Model_DirectoryPerson $person, $entry){
        $person->setIDMId($this->ldap->getItem($entry, "ucmercededuidmid"))
               ->setUCMNetId($this->ldap->getItem($entry, "uid"))
               ->setFirstName($this->ldap->getItem($entry, "givenname"))
               ->setLastName($this->ldap->getItem($entry, "sn"))
               ->setEmail($this->ldap->getItem($entry, "mail"))
               ->setPhone($this->ldap->getItem($entry, "telephonenumber"))
               ->setFax($this->ldap->getItem($entry, "facsimiletelephonenumber"))
               ->setJobTitle($this->ldap->getItem($entry, "ucmercededuappttitle1"))
               ->setJobTitle2($this->ldap->getItem($entry, "ucmercededuappttitle2"))
               ->setDepartment($this->ldap->getItem($entry, "ucmercededuapptdeptname1"))
               ->setDepartment2($this->ldap->getItem($entry, "ucmercededuapptdeptname2"))
               ->setLocation($this->ldap->getItem($entry, "roomnumber"))
               ->setCellPhone($this->ldap->getItem($entry, "ucmercededupublishcellphonenumber") == "1" ? $this->ldap->getItem($entry, "mobile") : "")
               ->setPrimaryAffiliation($this->ldap->getItem($entry, "edupersonprimaryaffiliation"))
               ->setSubAffiliation($this->ldap->getItem($entry, "ucmercededuaffiliationsubtype"))
               ->setOrganizationalStatus($this->ldap->getItem($entry,"organizationalstatus"));
        $affiliation=$person->getPrimaryAffiliation();
        if($affiliation=="student"){
            $person->setPhone("");
            $person->setCellPhone("");
            $person->setFax("");
        }


    }


}

?>
