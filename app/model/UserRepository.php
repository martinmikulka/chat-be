<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;
use App;
use App\Errors;
use App\Model\Validation;

/**
 * Users management
 */
class UserRepository extends BaseRepository implements Nette\Security\IAuthenticator
{
	const TABLE = 'user';
	const COLUMN_ID = 'id';
	const COLUMN_USERNAME = 'username';
	const COLUMN_PASSWORD = 'password';


	/** @var \App\Model\IUserFactory */
	private $userFactory;


	/**
	 * Constructor.
	 * @param \Nette\Database\Context $database
	 */
	public function __construct(Nette\Database\Context $database, IUserFactory $userFactory)
	{
		$this->table = self::TABLE;
		parent::__construct($database);
		$this->userFactory = $userFactory;
	}


	/**
	 * Performs an authentication.
	 * @param array $credentials
	 * @return \Nette\Security\Identity
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$user = $this->getOneBy(array('username' => $username));
		if (!$user) {
			throw new Nette\Security\AuthenticationException(Errors::E_USER_ACCOUNT_DOES_NOT_EXIST, Nette\Http\Iresponse::S400_BAD_REQUEST);
		} else if (!Passwords::verify($password, $user->password)) {
			throw new Nette\Security\AuthenticationException(Errors::E_AUTHENTICATION_FAILED, Nette\Http\Iresponse::S400_BAD_REQUEST);
		} else if (Passwords::needsRehash($user->password)) {
			$this->update($user->id, array(
				self::COLUMN_PASSWORD => Passwords::hash($password),
			));
		}

		return new Nette\Security\Identity($user->id, null, $user->toApi());
	}


	/**
	 * Convert ActiveRow to entity.
	 * @param \Nette\Database\Table\ActiveRow $data
	 * @return \App\Model\User
	 */
	protected function toEntity(Nette\Database\Table\ActiveRow $data) {
		$entity = $this->userFactory->create();
		$entity->loadData($data);
		return $entity;
	}


	/**
	 * Adds new user.
	 * @param \App\Model\User
	 * @return \Nette\Database\Table\IRow|integer|boolean
	 * @throws \Nette\InvalidStateException
	 */
	public function create(User $entity) {
		$vld = $this->validate($entity);
		if ($vld->OK()) {
			try {
				$item = parent::insert(array(
						self::COLUMN_USERNAME => $entity->username,
						self::COLUMN_PASSWORD => Passwords::hash($entity->password),
				));

				return $item;
			}
			catch (App\Exceptions\DuplicateEntryException $e) {
				$vld->add(Errors::E_USERNAME_EXISTS);
			}
		}

		return $vld->getErrors();
	}


	/**
	 * Validate data.
	 * @param \App\Model\User $entity
	 * @param boolean $isInsert
	 * @return \App\Model\Validation
	 */
	private function validate(User $entity, $isInsert = true) {
		$vld = new Validation;
		if ($isInsert) {
			if (!$entity->username) {
				$vld->add(Errors::E_USERNAME_EMPTY);
			}
			if (!$entity->password) {
				$vld->add(Errors::E_PASSWORD_EMPTY);
			}
			if (!$entity->password_confirm) {
				$vld->add(Errors::E_PASSWORD_CONFIRM_EMPTY);
			}
			if ($entity->password && $entity->password_confirm && $entity->password !== $entity->password_confirm) {
				$vld->add(Errors::E_PASSWORD_MISMATCH);
			}
		}

		return $vld;
	}


}
