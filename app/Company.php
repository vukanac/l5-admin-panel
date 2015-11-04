<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	/**
	 * Get the user that controls the company.
	 */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
