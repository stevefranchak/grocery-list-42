<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface {

    use UserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $fillable = array('email', 'password', 'accountId');

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'id', 'created_at', 'updated_at');

    public function scopeByEmail($query, $email)
    {
        return $query->where('email', '=', $email);
    }

    public static function getByEmail($email)
    {
        return User::byEmail($email)->first();
    }

    public static function getLoggedInUserId()
    {
        return GlobalUserToken::get()->payload->id;
    }

}
