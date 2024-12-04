<?php namespace BouletAP\Forms\Validations;

  
class Upload_mimetype extends \BouletAP\Forms\AbstractValidation {

    public $max_file_size;
    public $allowed_extension;

    public function __construct($ext) {
        $this->name = "input-file-size";
        $this->invalid_message = "Ce type de fichier n'est pas accepté";
        $this->allowed_extension = $ext;
    }

    public function validate($value) {

        if( $value ) {
            $target_file = basename($value["full_path"]);
            $file_type = strtolower( pathinfo($target_file, PATHINFO_EXTENSION) );

            if( !in_array($file_type, $this->allowed_extension) ) {
                $this->has_error = true;
                return false;
            }
        }     
        
        return true;
    }
}
