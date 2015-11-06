<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
    
	/**
	 * Get the user that controls the company.
	 */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
