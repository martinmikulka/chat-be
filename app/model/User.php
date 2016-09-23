<?php

namespace App\Model;

/**
 * Description of User entity
 */
class User extends BaseEntity
{
	public $id;
	public $username;
	public $password;
	public $password_confirm;

	public function toApi() {
		$obj = new \stdClass;
		$obj->id = $this->id;
		$obj->username = $this->username;

		return $obj;
	}
}


interface IUserFactory
{
	/** @return \App\Model\User */
	public function create();
}
