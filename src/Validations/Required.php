<?php namespace BouletAP\Forms;

  
class Required extends \BouletAP\Forms\AbstractValidation {

    public function validate($value) {
        
        if( empty($value) ) {
            $this->has_error = true;
            return false;
        }
        return true;
    }
}
