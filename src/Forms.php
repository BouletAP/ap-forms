<?php


namespace BouletAP\Forms;


abstract class Forms extends \BouletAP\Forms\AbstractForm {
	
	public $name;

    abstract function fields();
	

	public function success() {
		if( isset($_POST['btn-'.$this->name]) && $this->validate() ) {
			return true;
		}
		return false;
	}


	public function error() {
		return !empty($_POST) && !$this->success();
	}
	

}