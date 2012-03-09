<?php

/**
 * Loads forms from cache when available
 *
 * @author Chris
 */
class App_Controller_Action_Helper_FormLoader
    extends Zend_Controller_Action_Helper_Abstract
{

    /**
     *
     * @var Zend_Cache
     */
    private $cache;

    /**
     *
     * @var Zend_Form
     */
    private $form;

    /**
     * Action helper to send form to controller
     * Will use cache when possible.
     * Pass file name of form and optionally options for form construct
     * 
     * @param String $formName
     * @param Array $options
     * @return Zend_Form
     */
    public function load($formName, $options=null){
        //get from cache
        $this->form=$this->getCache()->load($formName."Form");
        if(!$this->form){

            $formName="Application_Form_" . $formName;
            $this->form=new $formName($options);
            $this->getCache()->save($this->form,$formName."Form");

        }
        return $this->form;

    }

    public function getCache() {
        if($this->cache===null){
            $this->cache=Zend_Registry::get("cache");
        }
        return $this->cache;

    }

    public function setCache($cache) {
        $this->cache = $cache;
    }
    public function getForm() {
        return $this->form;
    }

    public function setForm($form) {
        $this->form = $form;
    }



}

?>
