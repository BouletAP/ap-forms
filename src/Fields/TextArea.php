<?php namespace BouletAP\Forms\Fields;


class TextArea extends \BouletAP\Forms\AbstractField {
    
    public function display() {
        
        return '<textarea name="'.$this->getName().'" '.$this->displayAttributes().'>'.$this->getValue().'</textarea>';
    }
}
