<?php

namespace App\Model;

use Nette;

/**
 * Validation.
 */
class Validation extends Nette\Object
{
	private $errors = array();


	public function add ($error) {
		$this->errors[] = $error;
	}


	public function OK () {
		return count($this->errors) ? false : true;
	}


	public function getErrors () {
		return $this->errors;
	}
}
