<?php

class AuthController extends \BaseController {

	public function login()
	{
		$validator = \ControllerHelper::validateAllInputAgainst(array(
			'email' => 'required|email',
			'password' => 'required|string|max:64'
		));

		if ($validator->fails()) {
			return \ControllerHelper::respondWithValidationErrors($validator);
		}
		
		$email = Input::get('email');

		if (!Auth::attempt(array('email' => $email, 'password' => Input::get('password')))) {
			return \ControllerHelper::respondWithErrors(array(
				'Invalid credentials.'
			), 401);
		}

		try {
			$token = \Token::createByEmail($email);
			$token->store();
		} catch (Exception $exception) {
			return \ControllerHelper::respondWithErrors(array(
				"Failed to create and store your access token."
			), 500);
		}

		return array(
			'token' => $token->key
		);
	}

}
