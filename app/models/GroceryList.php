<?php

use Illuminate\Database\Eloquent\Model;

class GroceryList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('user_id', 'name', 'for_store');

    protected $hidden = array('user_id');

    public function scopeByLoggedInUser($query)
    {
        $id = GlobalUserToken::get()->payload->id;
        return $query->where('user_id', '=', $id);
    }
}
