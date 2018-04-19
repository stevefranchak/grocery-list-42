<?php

class ControllerHelper {
    public static function validateAllInputAgainst($rules)
    {
        return Validator::make(Input::all(), $rules);
    }

    public static function respondWithValidationErrors(Illuminate\Validation\Validator $validator)
    {
        return Response::json(array(
            'errors' => $validator->messages()->all()
        ), 400);
    }

    public static function respondWithOwnErrors($errorMessages)
    {
        return Response::json(array(
            'errors' => $errorMessages
        ), 401);
    }
    
}