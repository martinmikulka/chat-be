<?php

namespace App\Presenters;

use Nette;
use App;
use App\Errors;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	/** @var \stdClass */
	protected $payload;

	/** @var \App\Model\Api @inject */
	public $api;

	/**
	 * Startup function.
	 * @return void
	 * @throws Nette\Application\BadRequestException
	 */
	public function startup () {
		parent::startup();

		$request = $this->getHttpRequest();

		/**
		 * Check apiKey from Authorization header
		 */
		$apiKey = $request->getHeader('Authorization');
		if (!$apiKey) {
			$this->error(Errors::E_API_KEY_MISSING, Nette\Http\IResponse::S400_BAD_REQUEST);
		} else if ($this->api->getApiKey() !== $apiKey) {
			$this->error(Errors::E_API_KEY_INVALID, Nette\Http\IResponse::S400_BAD_REQUEST);
		}

		/**
		 * Check incoming Content-Type header
		 */
		$contentType = $request->getHeader('Content-Type');
		if ($contentType !== 'application/json') {
			$this->error(Errors::E_API_CONTENT_TYPE_HEADER_INVALID, Nette\Http\IResponse::S415_UNSUPPORTED_MEDIA_TYPE);
		}

		/**
		 * By default, presenter has request available in $this->request variable.
		 * It is an instance of \Nette\Application\Request which is unable to get raw data from the request body.
		 * That's why we get instance of \Nette\Http\Request from the container and get raw body from it.
		 */
		$this->payload = json_decode($request->getRawBody());
	}


	/**
	 * Send JSON response with properly set http status code.
	 * @return void
	 * @throws Nette\Application\BadRequestException
	 */
	protected function sendJsonResponse ($data, $code = Nette\Http\Iresponse::S200_OK) {
		$httpResponse = $this->getHttpResponse();
		$httpResponse->setCode($code);

		$response = new Nette\Application\Responses\JsonResponse($data);
		$response->send($this->getHttpRequest(), $httpResponse);
	}
}
