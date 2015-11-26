<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	public static function getAllRoles()
	{
		return ['owner', 'admin', 'manager', 'author', 'viewer'];
	}
}
