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
      "department"	=> "ucMercedEduApptDeptName1",
      "email"		=> "mail",
      "telephone"	=> "telephoneNumber",
      "ucmercededuapptdeptname1"=> "ucmercededuapptdeptname1"

    );

    public function __construct(){
        $this->ldap=new App_Controller_Action_Helper_Ldap();
    }

    public function search($searchBy,$searchFor){
        $ldapAttribute = $this->searchByFieldToLDAPAttribute[$searchBy];
        if(!$ldapAttribute) {
            throw new Exception("Invalid LDAP searchBy param. This should have been escaped from the search form");
        }
        $searchFor=$this->ldap->escapeValue($searchFor);

        //add wildcard for firstname, last name, email
        if($ldapAttribute=="sn" || $ldapAttribute=="givenName" || $ldapAttribute=="email"){
            $searchFor.="*";
        }

        //do sorting - sort by last name unless searching on last name, then
        //sorty on first name
        $order="sn";
        if($ldapAttribute=="sn"){
            $order="givenName";
        }
        //special search for telephone:
        //NOTE: replace "-" with " " for best results
        if($ldapAttribute=="telephoneNumber"){
            $searchFor=str_replace("-"," ", $searchFor);
            $filter="(&(|(telephonenumber=*$searchFor*)(mobile=*$searchFor*))(ucmercededuonlinedir=1)(|(edupersonprimaryaffiliation=staff)(edupersonprimaryaffiliation=generic)(edupersonprimaryaffiliation=affiliate)(edupersonprimaryaffiliation=faculty)))";
        }
        else{
            $filter = "(&($ldapAttribute=$searchFor)(ucmercededuonlinedir=1)(|(edupersonprimaryaffiliation=staff)(edupersonprimaryaffiliation=affiliate)(edupersonprimaryaffiliation=generic)(edupersonprimaryaffiliation=faculty)(edupersonprimaryaffiliation=student)))";
        }

        $entries=$this->ldap->search($filter,null,$order);
        $entries=$entries->toArray();
        $results=array();

        foreach($entries as $entry){

            $person = new Application_Model_DirectoryPerson();
            $this->populatePerson($person,$entry);
            $results[]=$person;

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
        if(!$entry){
            return;
        }
        $entry=$entry->toArray();
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
               ->setLocation($this->ldap->getItem($entry, "roomnumber"))
               ->setFERPAFlag(($this->ldap->getItem($entry, "ucmercededuferpa") == "1" ? true : false))
               ->setDirectoryFlag(($this->ldap->getItem($entry, "ucmercededuonlinedir") == "1" ? true : false))
               ->setPublishCellFlag(($this->ldap->getItem($entry, "ucmercededupublishcellphonenumber") == "1" ? true : false))
               ->setCellPhone($this->ldap->getItem($entry, "ucmercededupublishcellphonenumber") == "1" ? $this->ldap->getItem($entry, "mobile") : "")
               ->setPrimaryAffiliation($this->ldap->getItem($entry, "edupersonprimaryaffiliation"))
               ->setSubAffiliation($this->ldap->getItem($entry, "ucmercededuaffiliationsubtype"));

    }


}

?>
