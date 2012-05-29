<?php


/**
 * Performs searches of ldap
 *
 * @author Chris
 */
class App_Controller_Action_Helper_Ldap
    extends Zend_Ldap
{

    /**
     *
     * @var Zend_Ldap
     */
    private $ldapConnection;
    private $basedn;

    public function __construct() {

        $appConfig = new Zend_Config_Ini(APPLICATION_PATH . "/configs/ldap.ini",APPLICATION_ENV);
        if($appConfig->ldap && $appConfig->ldap->host){
            $ldap = $appConfig->ldap;
            $ldapOptions["host"] = $ldap->host;
        }
        else{
            throw new Exception("missing ldap.host");
        }

        //set base dn if in file
        if($ldap->basedn)
            $this->setBasedn($ldap->basedn);

        if($ldap->port)
            $ldapOptions["port"] = $ldap->port;
        if($ldap->useTLS)
            $ldapOptions["useStartTls"] = $ldap->useTLS;
        if($ldap->useSSL)
            $ldapOptions["useSsl"] = $ldap->useSSL;
        if($ldap->binddn)
            $ldapOptions["username"] = $ldap->binddn;
        if($ldap->password)
            $ldapOptions["password"] = $ldap->password;

        $this->ldapConnection= new Zend_Ldap($ldapOptions);

    }

    public function __destruct() {

      if(null !== $this->ldapConnection)
        $this->ldapConnection->disconnect();

    }

    /**
     * Perform Ldap search
     * BaseDN is loaded from .ini file by default
     * @param String $filter
     * @param String $basedn
     * @param String $order
     * @return type
     */
    public function search($filter, $basedn=null, $order=null)
    {
        if(null===$basedn){
            $basedn=$this->getBasedn();
        }
        //$sort=array("sn","givenName");
        // $collection is a Zend_Ldap_Collection
        $collection = $this->getLdapConnection()->search(
            $filter,	// filter
            $basedn, 				// basedn
            Zend_Ldap::SEARCH_SCOPE_ONE, 	// scope
            $this->getAttributes(),             // attributes
            $order                             //sort
        );
        return $collection;

    }

    /**
     * This escapes attribute values for an LDAP search filter.
     * e.g., if you're building a filter that looks like "(sn=<userinput>)"
     * then the <userinput> should be escaped first by this function.
     * @param String $value
     * @return String
     */
    public function escapeValue($value)
    {
      return Zend_Ldap_Filter_Abstract::escapeValue($value);
    }

    /**
     * helper function to pull attribute values out of a
     * Zend_Ldap_Collection (single-value attribute)
     * @param String $item
     * @param String $key
     * @return String
     */
    public function getItem($item, $key)
    {
      if(array_key_exists($key, $item))
      {
        $val = $item[$key];
        if($val !== null)
        {
          if(is_array($val))
            return $val[0];
          else
            return $val;
        }
      }
      return;
    }

    /**
     * Helper function to pull attribute values out of a
     * Zend_Ldap_Collection (multi-value attribute).
     * returns an array or null.
     * @param String $item
     * @param String $key
     * @return String
     */
    public function getItemArray($item, $key)
    {
      if(array_key_exists($key, $item))
      {
        $val = $item[$key];
        if($val !== null)
        {
          if(is_array($val))
            return $val;
          else
            return array($val);
        }
      }
      return;
    }

    public function getLdapConnection() {
        return $this->ldapConnection;
    }

    public function setLdapConnection($ldapConnection) {
        $this->ldapConnection = $ldapConnection;
    }

    public function getBasedn() {
        return $this->basedn;
    }

    public function setBasedn($basedn) {
        $this->basedn = $basedn;
    }

    private function getAttributes(){
        return array(
            "ucmercededuidmid",
            "uid",
            "givenname",
            "sn",
            "mail",
            "telephonenumber",
            "facsimiletelephonenumber",
            "ucmercededuappttitle1",
            "ucmercededuappttitle2",
            "ucmercededuapptdeptname1",
            "roomnumber",
            "ucmercededupublishcellphonenumber",
            "mobile",
            "edupersonprimaryaffiliation",
            "ucmercededuaffiliationsubtype",
            "organizationalstatus"
            );
    }

}

?>
