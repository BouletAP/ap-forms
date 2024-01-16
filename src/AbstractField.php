<?php 

namespace BouletAP\Forms;
  
  
abstract class AbstractField {
    
    protected $name;
    protected $value;
    protected $attributes = array();
    protected $rules = array();
    
    protected $errors = array();
    
    
    abstract function display();
        
    
    public function __construct($name, $attributes = array()) {
        $this->name = $name;
        $this->attributes = $attributes;

        $this->attributes['class'] = "formfield-".$name;
    }
    
    
    public function validate() {
        $result = true;        
        
        foreach( $this->getRules() as $rule ) {
            if( !$rule->validate($this->getValue()) ) {
                $result = false;

                $this->errors[$rule->getName()] = $rule->getMessage();
            }
        }
        
        return $result;
    }
    
    
    public function show($type = 'input') {
        
        if( $type == 'error' ) {
            foreach( $this->getRules() as $rule ) {
                if( $rule->hasError() ) {
                    $classname = str_replace('Apform\\Validations\\', '', get_class($rule));
                    return '<span class="input-validation-error">'.__('apform.'.strtolower($classname)).'</span>';
                }
            }
        }
        elseif( $type == 'input' ) {
            return $this->display();
        }
    }
    
    public function addAttribute($attr, $value) {
        $this->attributes[$attr] = $value;
        return $this;
    }
    
    public function addValidation($rule) {
        $this->rules []= $rule;
        return $this;
    }
    
    
    public function displayAttributes() {
        $output = "";
        $keys = array_keys($this->attributes);
        foreach($keys as $key) {
            $output .= $key.'="'.$this->attributes[$key].'" ';
        }
        return $output;
    }
    
    
    
    public function setValue($value) {
        $this->value = $value;
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    // Getters..
    public function getName() {
        return $this->name;
    }
    public function getRules() {
        return $this->rules;
    }
    public function getValue() {  
        
        if( !empty($_POST) && isset($_POST[$this->name]) ) {
            $val = $_POST[$this->name];

            $val = urldecode($val);
            $val = strip_tags($val);
            $val = htmlspecialchars($val);
            
            $this->setValue($val);
        }
        return $this->value;
    }

    
}
