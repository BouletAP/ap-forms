<?php namespace BouletAP\Forms\Fields;

use \BouletAP\Forms\Validations\Upload_size;
use \BouletAP\Forms\Validations\Upload_mimetype;



// Ajouter un AbstractUpload object
// Quand le formulaire est envoté, si le champs n'a pas d'error: transfert le fichier
// Ajouter un hidden field avec la valeur pour empêcher les upload multiples si d'autres champs sont invalides
// Ajouter un script sur la page pour supprimer les fichiers "hidden" si on quitte sans finir le formulaire
// Ajouter un preview pour montrer que le fichier est bien présent sur le serveur quand on resoumet le formulaire


class ImageUpload extends \BouletAP\Forms\AbstractField {

    //public $ratio;
    public $upload_dir;
    public $upload_url;
    public $upload_errors;
    
    public function __construct($name, $dir, $path, $attributes = array()) {
        parent::__construct($name, $attributes);
        
        $this->upload_dir = $dir;
        $this->upload_url = $path;
        
        $this->addValidation( new Upload_size );
        $this->addValidation( new Upload_mimetype(["jpg", "png", "jpeg", "gif"]) );
    }
    
    public function display() {

        $current_image = $this->getValue();


        if( !empty($this->value) ) {
            $field = '<div class="image-preview"><a onclick="bouletap_killImage(\''.$this->getName().'\');" href="javascript:;"><img src="/uploads/'.$this->value.'" /></a>'.PHP_EOL;
            $field .= '<input type="hidden" name="'.$this->getName().'" '.$this->displayAttributes().' value="'.$this->value.'" /></div>';
        }
        else {
            $field = '<input type="file" name="'.$this->getName().'" '.$this->displayAttributes().' />';
        }

        //echo '<pre>'; print_r($this); echo '</pre>ImageUploadDisplay'; 
        return $field;
    }


    public function validate() {
        $result = true;      

        // value from hidden field should skip validation and skip upload

        
        $uploaded_file = $this->getUploadFile();  
        foreach( $this->getRules() as $rule ) {
            if( !$rule->validate($uploaded_file) ) {
                $result = false;
                $this->errors[$rule->getName()] = $rule->getMessage();
            }
        }

        $this->delete_pending_image();

        // no validation error? attempt to upload the file
        if( $result && $uploaded_file) {
            $result = $this->uploadfunction();
        }
                        
        return $result;
    }


    public function getValue() {  
        parent::getValue();

        $suffix = "_delete_pending";        
        if( isset($_POST[$this->name.$suffix]) ) {
            $this->value = '';
        }

        // never return upload_url here
        //return !empty($this->value) ? $this->upload_url . $this->value : '';
        return $this->value;
    }


    public function getUploadFile() {
        $file = false;
        $name = $this->getName();        
        if( !empty($_FILES) && isset($_FILES[$name]) ) {
            if( !empty($_FILES[$name]) && !empty($_FILES[$name]['size']) ) {
                $file = $_FILES[$name];
            }
        }
        return $file;
    }

    
    public function uploadfunction() {

        
        $uploadError = false;
        $success = false;
        $upload_field = $this->getName();


        // rename file with slug name (clean url) but keep extension
        $upload_name = basename($_FILES[$upload_field]["name"]);
        $file_extension = pathinfo($upload_name, PATHINFO_EXTENSION);
        $upload_name = str_replace(".{$file_extension}", '', $upload_name);
        $upload_name_slug = self::createSlug( $upload_name );
        $upload_name_slug .= ".{$file_extension}";

        $target_file = $this->upload_dir . $this->upload_url . $upload_name_slug; 

        $target_file = $this->rename_unique($target_file);
        $upload_name_slug = basename($target_file);

        if (!$uploadError && move_uploaded_file($_FILES[$upload_field]["tmp_name"], $target_file)) {
            $this->setValue($this->upload_url . $upload_name_slug);
            //$this->delete_pending_image();
            return true;
        } 
        else {
            $uploadError = "Sorry, there was an error uploading your file.";
        }
        
        $this->upload_errors = $uploadError;
        return false;
    }


    // From namespace BouletAP/Tools/Stringz
    static public function createSlug($str, $delimiter = '-') {
        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    
    } 


    // @todo: refactor but currently working
    public function delete_pending_image() {
        $suffix = "_delete_pending";
        
        if( !empty($_POST) && isset($_POST[$this->name.$suffix]) ) {
            $val = $_POST[$this->name.$suffix];

            $val = urldecode($val);
            $val = strip_tags($val);
            $val = htmlspecialchars($val);

            $target_file = basename($val);
            $file_type = strtolower( pathinfo($target_file, PATHINFO_EXTENSION) );

            $allowed_extension = array("jpg", "png", "jpeg", "gif");
            if( in_array($file_type, $allowed_extension) ) {

                $full_path = $this->upload_dir . $val;
                
                if( file_exists($full_path) ) {
                    unlink($full_path);
                    return true;
                }
            }
        }
        return false;
    }


    public function rename_unique($target_file, $i = 1) {
        
        // check if file already exists
        if (file_exists($target_file)) {
            
            // remove extension from filename   
            $filename = basename($target_file, '.' . pathinfo($target_file, PATHINFO_EXTENSION));
            $extension = pathinfo($target_file, PATHINFO_EXTENSION);   
            $target_file = str_replace(".{$extension}", '', $target_file);

            $suffix = (int)strrev(substr(strrev($target_file), 0, strpos(strrev($target_file), '-')));

            if( $suffix <= 0 ) {
                $suffix = $i;
            }
            else {
                $target_file = str_replace("-{$suffix}", '', $target_file);
                $suffix = $suffix + 1;
            }
            
            // append number to filename
            $target_file = $target_file . '-' . $suffix . ".{$extension}";
                        

            if (file_exists($target_file)) {
                $target_file = $this->rename_unique($target_file, ++$i);
            }

        }
        
        return $target_file;
    }


    public function kill_image_script() {
        ob_start();
        ?>
        <script>
            // ajax pour delete
            // ajax pour upload d'une image (fresh id how?)
            function bouletap_killImage(input_name) {
                if( confirm('Êtes-vous sur de vouloir remplacer l\'image ?') ) {
                    var parent = document.querySelector('input[name="'+input_name+'"]').closest('.image-preview');            

                    var current_image = document.querySelector('input[name="'+input_name+'"]').value;

                    var new_file_input = document.createElement('input');
                    new_file_input.setAttribute('type', 'file');
                    new_file_input.setAttribute('name', input_name);

                    // creer un hidden_field old_image pour la suppression
                    var input_to_delete = document.createElement('input');
                    input_to_delete.setAttribute('type', 'hidden');
                    input_to_delete.setAttribute('name', input_name + "_delete_pending");     
                    input_to_delete.setAttribute('value', current_image);     

                    parent.after(new_file_input);
                    parent.after(input_to_delete);            
                    parent.remove();
                }
            }
        </script>
        <?php
        $output = ob_get_clean();
        return $output;
    }

}
