<?php

namespace Reviews\Models;

class Review extends Model {

	protected static string $tablename = 'reviews';
	protected static array $fields_map = array(
		'username' => ['datatype' => 'varchar(100)'],
		'email'    => ['datatype' => 'varchar(100)', 'option' => 'UNIQUE KEY'],
		'dateFrom' => ['datatype' => 'datetime'],
		'title'    => ['datatype' => 'varchar(200)'],
		'message'  => ['datatype' => 'text'],
		'rate'     => ['datatype' => 'int'],
		'status'   => ['datatype' => 'tinyint(1)'],
		'target'   => ['datatype' => 'int', 'ref' => ['companies', parent::ID]]
	);
}