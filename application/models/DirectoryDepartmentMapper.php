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

    public function __construct(){
        $this->db=Zend_Db_Table_Abstract::getDefaultAdapter();
    }

    /**
     * Fetch all departments, return an array
     * @return Application_Model_DirectoryDepartment
     */
    public function fetchAll(){
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
            $results[]=new Application_Model_DirectoryDepartment($options);
        }

        return $results;

    }

    /**
     * Find a single department by name
     * @param String $name
     * @return Application_Model_DirectoryDepartment
     */
    public function find($name){
        $sql=$this->db->select()->from("IDMV7.UCMDEPARTMENT")->where("NAME=?",$name);
        $rs=$this->db->fetchRow($sql);
        if($rs){
            return new Application_Model_DirectoryDepartment($rs);
        }
        return false;
    }



}

?>
