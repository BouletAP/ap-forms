<?php namespace BouletAP\Forms\Fields;


class Password extends \BouletAP\Forms\AbstractField {
    
    public function display() {
        
        return '<input type="password" name="'.$this->getName().'" value="'.$this->getValue().'" '.$this->displayAttributes().' />';
    }
}
