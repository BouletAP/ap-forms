<?php namespace BouletAP\Forms\Validations;

  
class Upload_size extends \BouletAP\Forms\AbstractValidation {

    public $max_file_size;

    public function __construct() {
        $this->name = "input-file-size";
        $this->invalid_message = "Le fichier est trop volumineux";
    }

    public function validate($value) {
        
        if( $value ) {
            if ($value["size"] > 2000000) { // 2Mo
                $this->has_error = true;
                return false;
            }        
        }
        
        return true;
    }
}
