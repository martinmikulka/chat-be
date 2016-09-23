<?php

namespace App\Model\Api;

/**
 * ErrorResult
 */
class ErrorResult extends BaseResult
{
	public $errors;


	public function __construct () {
		parent::__construct();
		$this->errors = array();
	}


	public function addError ($message) {
		$this->errors[] = $message;
	}


}
