<?php

namespace App\Model\Api;

/**
 * SuccessResult
 */
class SuccessResult extends BaseResult
{
	public $data;


	public function __construct () {
		parent::__construct();
		$this->data = null;
	}


}
