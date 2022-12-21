<?php

namespace Reviews\Classes\Data;

class UserDataMap extends DataMap {

	public function __construct() {
		$this->setName('users')
		->defineField('firstname', 'varchar(100)')
		->defineField('lastname', 'varchar(100)')
		->defineField('email', 'varchar(100)')
		->defineField('dateFrom', 'datetime')
		->defineField('role', 'int')
		->defineField('login', 'int')
		->defineField('password', 'int');
	}
}