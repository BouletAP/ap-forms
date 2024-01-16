<?php namespace BouletAP\Forms\Validations;

  
class Required extends \BouletAP\Forms\AbstractValidation {

    public function __construct() {
        $this->name = "input-required";
        $this->invalid_message = "Ce champs est requis";
    }

    public function validate($value) {
        
        if( empty($value) ) {
            $this->has_error = true;
            return false;
        }
        return true;
    }
}
