<?php namespace BouletAP\Forms\Fields;


class Email extends \BouletAP\Forms\AbstractField {
    
    public function __construct($name, $attributes = array()) {
        parent::__construct($name, $attributes);
        $this->addValidation( new Valid_email );
    }
    
    public function display() {
        
        return '<input type="text" name="'.$this->getName().'" value="'.$this->getValue().'" '.$this->displayAttributes().' />';
    }
}
