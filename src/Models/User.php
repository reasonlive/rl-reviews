<?php

namespace Reviews\Models;
use Reviews\Classes\Data\UserDataMap;

class User extends Model {

	public function __construct() {
		parent::__construct();
		$this->data = new UserDataMap();
	}
}