<?php

class Application_Model_DirectoryPerson
{
    public $idmId;
    public $ucmNetId;
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $fax;
    public $jobTitle;
    public $jobTitle2;
    public $department;
    public $location;
    public $ferpaFlag;
    public $directoryFlag;
    public $publishCellFlag;
    public $cellPhone;
    public $primaryAffiliation;
    public $subAffiliation;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid directory property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid directory property');
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function toString()
    {
      $result = "Application_Model_Directory{\n";
      $methods = get_class_methods($this);
      foreach ($methods as $method) {
        if(preg_match("/^get/", $method)) {
          $value = (string) $this->$method();
          $result .= "  " . $method . " = " . $value . "\n";
        }
      }
      $result .= "}";
      return $result;
    }

    public function setIDMId($idmId)
    {
      $this->idmId = (string) $idmId;
      return $this;
    }
    public function getIDMId()
    {
      return $this->idmId;
    }

    public function setUCMNetId($ucmNetId)
    {
      $this->ucmNetId = (string) $ucmNetId;
      return $this;
    }
    public function getUCMNetId()
    {
      return $this->ucmNetId;
    }

    public function setFirstName($firstName)
    {
      $this->firstName = (string) $firstName;
      return $this;
    }
    public function getFirstName()
    {
      return $this->firstName;
    }

    public function setLastName($lastName)
    {
      $this->lastName = (string) $lastName;
      return $this;
    }
    public function getLastName()
    {
      return $this->lastName;
    }

    public function setEmail($email)
    {
      $this->email = (string) $email;
      return $this;
    }
    public function getEmail()
    {
      return $this->email;
    }

    public function setPhone($phone)
    {
      $this->phone = (string) $phone;
      return $this;
    }
    public function getPhone()
    {
      return $this->phone;
    }

    public function setFax($fax)
    {
      $this->fax = (string) $fax;
      return $this;
    }
    public function getFax()
    {
      return $this->fax;
    }

    public function setJobTitle($jobTitle)
    {
      $this->jobTitle = (string) $jobTitle;
      return $this;
    }
    public function getJobTitle()
    {
      return $this->jobTitle;
    }

    public function setDepartment($department)
    {
      $this->department = (string) $department;
      return $this;
    }
    public function getDepartment()
    {
      return $this->department;
    }

    public function setLocation($location)
    {
      $this->location = (string) $location;
      return $this;
    }
    public function getLocation()
    {
      return $this->location;
    }

    public function setFERPAFlag($ferpaFlag)
    {
      $this->ferpaFlag = (string) $ferpaFlag;
      return $this;
    }
    public function getFERPAFlag()
    {
      return $this->ferpaFlag;
    }

    public function setDirectoryFlag($directoryFlag)
    {
      $this->directoryFlag = (string) $directoryFlag;
      return $this;
    }
    public function getDirectoryFlag()
    {
      return $this->directoryFlag;
    }

    public function setPublishCellFlag($publishCellFlag)
    {
      $this->publishCellFlag = (string) $publishCellFlag;
      return $this;
    }
    public function getPublishCellFlag()
    {
      return $this->publishCellFlag;
    }

    public function setCellPhone($cellPhone)
    {
      $this->cellPhone = (string) $cellPhone;
      return $this;
    }
    public function getCellPhone()
    {
      return $this->cellPhone;
    }

    public function setPrimaryAffiliation($primaryAffiliation)
    {
      $this->primaryAffiliation = (string) $primaryAffiliation;
      return $this;
    }
    public function getPrimaryAffiliation()
    {
      return $this->primaryAffiliation;
    }

    public function getSubAffiliation() {
        return $this->subAffiliation;
    }

    public function setSubAffiliation($subAffiliation) {
        $this->subAffiliation = $subAffiliation;
    }

    public function getJobTitle2() {
        return $this->jobTitle2;
    }

    public function setJobTitle2($jobTitle2) {
        $this->jobTitle2 = $jobTitle2;
        return $this;
    }

    public function getLastNameFirst(){
        return $this->getLastName() . ", " . $this->getFirstName();
    }

    public function getFullName(){
        return $this->getFirstName() . " " . $this->getLastName();
    }

}
