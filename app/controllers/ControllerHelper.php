<?php

class ControllerHelper {
    public static function validateAllInputAgainst($rules)
    {
        return Validator::make(Input::all(), $rules);
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