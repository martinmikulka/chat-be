<?php

namespace App\Presenters;

use Nette;
use App;
use App\Errors;
use Tracy\Debugger;

/**
 * Error presenter.
 */
class ErrorPresenter extends BasePresenter
{
	/**
	 * @param \Nette\Application\Request $request
	 * @return \Nette\Application\IResponse
	 */
	public function run (Nette\Application\Request $request) {
		$exception = $request->getParameter('exception');

		$code = $exception->getCode();
		$message = $code === 404 ? Errors::E_API_RESOURCE_NOT_FOUND : $exception->getMessage();

		/**
		 * Prepare response content
		 */
		$result = new App\Model\Api\ErrorResult;
		$result->addError($message);

		/**
		 * Set HTTP status code properly
		 */
		$httpResponse = $this->getHttpResponse();
		$httpResponse->setCode($code ? $code : 500);

		return new Nette\Application\Responses\JsonResponse($result);
	}


}
