<?php

namespace Reviews\Models\Users;
use Reviews\Models\Model;

class User extends Model {

	protected static string $tablename = 'users';

	protected static array $fields_map = array(
		'firstname' => ['datatype' => 'varchar(100)'],
		'lastname'  => ['datatype' => 'varchar(100)'],
		'email'     => ['datatype' => 'varchar(100)'],
		'dateFrom'  => ['datatype' => 'datetime'],
		'role'      => ['datatype' => 'int'],
		'login'     => ['datatype' => 'varchar(20)'],
		'password'  => ['datatype' => 'varchar(100)']
	);
}