<?php

namespace Reviews\Models\Users;
use Reviews\Classes\Data\UserDataMap;

class User extends Model {

	protected static DataMap $data = new UserDataMap();
	
	public function __construct() {
	
	}
}