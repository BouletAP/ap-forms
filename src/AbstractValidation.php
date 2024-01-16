<?php 

namespace BouletAP\Forms;
  
  
abstract class AbstractValidation {
    
    protected $has_error = false;
    protected $name;
    protected $invalid_message = "invalid";
    
    abstract function validate($value);
    
    
    
    public function hasError() {
        return $this->has_error;
    }    

    public function getName() {
        return $this->name;
    }  

    public function getMessage() {
        return $this->invalid_message;
    }  
}
