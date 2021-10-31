<?php namespace BouletAP\Forms;


class Hidden extends \BouletAP\Forms\AbstractField {
    
    public function display() {
        return '<input type="hidden" name="'.$this->getName().'" value="'.$this->getValue().'" '.$this->displayAttributes().' />';
    }
}
