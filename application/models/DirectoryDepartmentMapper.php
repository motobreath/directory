<?php

/**
 * Maps Dept data from db to local model
 *
 * @author Chris
 */
class Application_Model_DirectoryDepartmentMapper {

    /**
     *
     * @var Zend_Db
     */
    private $db;
    /**
     *
     * @var Array
     */
    private $departments;
    /**
     *
     * @var String
     */
    private $_namespace="UCMDirectory";
    /**
     *
     * @var Zend_Session
     */
    private $session;

    /**
     *
     * @var Zend_Cache
     */
    private $cache;

    public function __construct(){
        $this->db=Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->cache=Zend_Registry::get("cache");
    }

    /**
     * Fetch all departments, return an array
     * Added session caching
     * Added disk caching, will expire every 24 hours
     * @return Application_Model_DirectoryDepartment
     */
    public function fetchAll(){
        return $this->getDepartments();
    }

    /**
     * Find a single department by name
     * @param String $name
     * @return Application_Model_DirectoryDepartment
     */
    public function find($name){
        $departments=$this->getDepartments();
        if(isset($departments[$name])){
            return $departments[$name];
        }
        else{
            throw new Exception("Department not found", 500);
        }
    }

    /**
     * Get Departments from session->cache->db
     * Returns array of departments
     * @return Array
     */
    private function getDepartments(){
        if(null===$this->departments){
            //get from session
            $this->departments=$this->getSession()->departments;
            echo "loaded from session";

        }
        if(!$this->departments){
            //get from cache
            $this->departments=$this->cache->load("departments");
            //save these in session
            if($this->departments){
                $this->getSession()->departments=$this->departments;
            }
            echo "loaded from cache";

        }
        if(!$this->departments){
            //get from db
            $sql=$this->db->select()->from("IDMV7.UCMDEPARTMENT")->order("NAME ASC");
            $rs=$this->db->fetchAll($sql);
            $results=array();
            foreach($rs as $row){
                $options=array(
                    "name"=>$row["NAME"],
                    "phone"=>$row["PHONENUMBER"],
                    "fax"=>$row["FAXNUMBER"],
                    "description"=>$row["DESCRIPTION"],
                    "url"=>$row["URL"],

                );
                $results[$options["name"]]=new Application_Model_DirectoryDepartment($options);
            }
            //cache these
            $this->cache->save($results,"departments");
            $this->departments=$results;
            echo "loaded from db";
        }
        return $this->departments;
    }

    /**
     *
     * @return Zend_Session
     */
    private function getSession(){
        if(null===$this->session){
            $this->session=new Zend_Session_Namespace($this->_namespace);
        }
        return $this->session;
    }

}

?>
