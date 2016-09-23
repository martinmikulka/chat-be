<?php

namespace App;

/**
 * Errors
 */
abstract class Errors
{
	const E_API_CONTENT_TYPE_HEADER_INVALID = 'E_API_CONTENT_TYPE_HEADER_INVALID';
	const E_API_KEY_MISSING = 'E_API_KEY_MISSING';
	const E_API_KEY_INVALID = 'E_API_KEY_INVALID';
	const E_API_METHOD_NOT_SUPPORTED = 'E_API_METHOD_NOT_SUPPORTED';
	const E_API_RESOURCE_NOT_FOUND = 'E_API_RESOURCE_NOT_FOUND';

	const E_USERNAME_EMPTY = 'E_USERNAME_EMPTY';
	const E_USERNAME_EXISTS = 'E_USERNAME_EXISTS';
	const E_USER_ACCOUNT_DOES_NOT_EXIST = 'E_USER_ACCOUNT_DOES_NOT_EXIST';
	const E_PASSWORD_EMPTY = 'E_PASSWORD_EMPTY';
	const E_PASSWORD_CONFIRM_EMPTY = 'E_PASSWORD_CONFIRM_EMPTY';
	const E_PASSWORD_MISMATCH = 'E_PASSWORD_MISMATCH';
	const E_AUTHENTICATION_FAILED = 'E_AUTHENTICATION_FAILED';
}