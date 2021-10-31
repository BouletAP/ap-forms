<?php 

namespace BouletAP\Forms;
  
  
abstract class AbstractValidation {
    
    protected $has_error = false;
    
    abstract function validate($value);
    
    
    
    public function hasError() {
        return $this->has_error;
    }    
}
