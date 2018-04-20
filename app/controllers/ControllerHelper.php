<?php

class ControllerHelper {
    public static function validateInputsAgainst($rules, $inputs = NULL)
    {
        if ($inputs === NULL)
        {
            $inputs = Input::all();
        }
        return Validator::make($inputs, $rules);
    }

    public static function respondWithValidationErrors(Illuminate\Validation\Validator $validator)
    {
        return self::respondWithErrors($validator->messages()->all(), 400);
    }

    public static function respondWithErrors($errorMessages, $statusCode = 400)
    {
        return Response::json(array(
            'errors' => $errorMessages
        ), $statusCode);
    }

}