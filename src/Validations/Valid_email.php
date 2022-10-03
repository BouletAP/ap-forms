<?php namespace BouletAP\Forms\Validations;
  

  
class Valid_email extends \BouletAP\Forms\AbstractValidation {

    public function validate($value) {
        
        if( !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->has_error = true;
            return false;
        }
        return true;
    }
}
