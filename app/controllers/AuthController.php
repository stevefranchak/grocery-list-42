<?php

class AuthController extends \BaseController {

    public function register()
    {
        $validationResults = $this->validateCredentials(array(
            'failIfEmailExists' => True
        ));
        if (!$validationResults['isValid']) {
            return $validationResults['errorResponse'];
        }

        User::create(array(
            'email' => $validationResults['email'],
            'password' => Hash::make($validationResults['password'])
        ));

        return Response::json(array(
            'isSuccessful' => True
        ), 201);
    }

    public function login()
    {
        $validationResults = $this->validateCredentials();
        if (!$validationResults['isValid']) {
            return $validationResults['errorResponse'];
        }
        
        if (!Auth::attempt(array('email' => $validationResults['email'], 'password' => $validationResults['password']))) {
            return \ControllerHelper::respondWithErrors(array(
                'Invalid credentials.'
            ), 401);
        }

        try {
            $token = \Token::createByEmail($validationResults['email']);
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

    public function ping()
    {
        return array(
            'loggedIn' => True
        );
    }

    private function validateCredentials($options = array())
    {
        $results = array(
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'isValid' => True,
            'errorResponse' => NULL
        );
        
        $validator = \ControllerHelper::validateInputsAgainst(
            array(
                'email' => 'required|email' . (array_get($options, 'failIfEmailExists') ? '|unique:users,email' : ''),
                'password' => 'required|string|max:64'
            ),
            array(
                'email' => $results['email'],
                'password' => $results['password']
            )
        );

        if ($validator->fails()) {
            $results['isValid'] = False;
            $results['errorResponse'] = \ControllerHelper::respondWithValidationErrors($validator);
        }

        return $results;
    }

}
