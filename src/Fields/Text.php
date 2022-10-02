<?php namespace BouletAP\Forms;


class Text extends \BouletAP\Forms\AbstractField {
    
    public function display() {
        
        return '<input type="text" name="'.$this->getName().'" value="'.$this->getValue().'" '.$this->displayAttributes().' />';
    }
}
