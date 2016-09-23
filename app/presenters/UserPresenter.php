<?php

namespace App\Presenters;

use Nette;
use App;
use App\Errors;
use App\Model\UserRepository;
use App\Model\IUserFactory;

/**
 * User presenter.
 */
class UserPresenter extends BasePresenter
{
	/** @var \App\Model\UserRepository */
	private $userRepository;

	/** @var \App\Model\IUserFactory */
	private $userFactory;


	/**
	 * Class constructor
	 * @param \App\Model\UserRepository $userRepository
	 */
	public function __construct (UserRepository $userRepository, IUserFactory $userFactory) {
		parent::__construct();
		$this->userRepository = $userRepository;
		$this->userFactory = $userFactory;
	}


	/**
	 * Default action (/user)
	 * @return void
	 * @throws Nette\Application\BadRequestException
	 */
	public function actionDefault () {
		if ($this->request->method == 'POST') {
			$user = $this->userFactory->create();
			$user->username = isset($this->payload->username) ? $this->payload->username : '';
			$user->password = isset($this->payload->password) ? $this->payload->password : '';
			$user->password_confirm = isset($this->payload->password_confirm) ? $this->payload->password_confirm : '';
			$item = $this->userRepository->create($user);

			if ($item instanceof App\Model\User) {
				$result = new App\Model\Api\SuccessResult;
				$result->data = $item->toApi();
				$this->sendJsonResponse($result);
			} else {
				$result = new App\Model\Api\ErrorResult;
				foreach ($item as $error) {
					$result->addError($error);
				}
				$this->sendJsonResponse($result, Nette\Http\Iresponse::S400_BAD_REQUEST);
			}
		} else {
			$this->error(Errors::E_API_METHOD_NOT_SUPPORTED, Nette\Http\Iresponse::S400_BAD_REQUEST);
		}
		$this->terminate();
	}


	/**
	 * User Login action (/user/login)
	 * @return void
	 * @throws Nette\Application\BadRequestException
	 */
	public function actionLogin () {
		if ($this->request->method == 'POST') {
			$credentials = array(
				isset($this->payload->username) ? $this->payload->username : '',
				isset($this->payload->password) ? $this->payload->password : ''
			);
			$item = $this->userRepository->authenticate($credentials);

			$result = new App\Model\Api\SuccessResult;
			$result->data = $item->getData();
			$this->sendJsonResponse($result);
		} else {
			$this->error(Errors::E_API_METHOD_NOT_SUPPORTED, Nette\Http\Iresponse::S400_BAD_REQUEST);
		}
		$this->terminate();
	}


}
