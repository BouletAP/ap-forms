<?php 

namespace BouletAP\Forms;

abstract class AbstractForm {
    
    protected $action;
    protected $fields = array();
    
    protected $errors = array();
    
    
    
    public function __construct($action = '') {
        $this->action = $action;        
        $this->fields();       
    }
    
    public function validate() {                
                
        $result = true;                
        foreach( $this->fields as $field ) {
            if( !$field->validate() ) {
                $result = false;
                $this->errors[$field->getName()] = $field->getErrors();
            }
        }
        
        return $result;
    }
    
    
    // Permet de remplir le formulaire de valeur par défault
    public function fill($values) {
        if( !is_array($values) ) return false;
        foreach( $values as $key => $value) {
            if( isset($this->fields[$key]) ) {
                $this->fields[$key]->setValue($value);
            }
        }
    }
    
    // Permet de changer // enlever un champ^s
    public function setField() {}
    
    
    public function addFields() {
        $args = func_get_args();
        foreach($args as $arg) {
            $this->fields[$arg->getName()] = $arg;
        }
    }
    
    public function getField($name) {
        if(isset($this->fields[$name])) {
            $field = $this->fields[$name];
        }
        else {
            $field = new Blank;
        }        
        
        return $field;
    }

    public function getErrors() {
        return $this->errors;
    }
    
}