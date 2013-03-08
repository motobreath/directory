<?php
/**
 * Helper file to find MSO email
 *
 * @author Chris
 */
class App_Controller_Action_Helper_GetMSO
    extends Zend_Controller_Action_Helper_Abstract
{
    public function direct($department){
        return $this->getMSOInfo($department);
    }

    private function getMSOInfo($department){
        $db=  Zend_Db_Table_Abstract::getDefaultAdapter();
        $sql=$db->select()
                ->from("IDMV7.UCMDEPARTMENT")
                ->where("UCMDEPARTMENT.NAME=?",$department);
        $result=$db->fetchRow($sql);
        $mso=array();
        if(!empty($result["MSO_NAME"])){
            $mso["name"]=$result["MSO_NAME"];
            $mso["email"]=$result["MSO_EMAIL"];
        }
        else{
            $mso["name"]="Default IDM Contact";
            $mso["email"]="idm@ucmerced.edu";
        }
        return $mso;
    }

}

?>
