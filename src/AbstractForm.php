<?php 

namespace BouletAP\Forms;

use BouletAP\Forms\Fields\Blank;

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

    public function getValues() {
        $values = array();
        foreach( $this->fields as $field ) {
            $values[$field->getName()] = $field->getValue();
        }
        return $values;
    }

    public function getErrors($format = false) {
        $errors = $this->errors;
        if( $format && !empty($this->errors) ) {
            $errors = [];
            foreach( $this->errors as $field => $error ) {
                $errors[$field] = reset($error);
            }
        }
        return $errors;
    }
    

    public function hasErrors() {
        return !empty($this->errors);
    }
}