<?php

namespace Reviews\Models\Users;

class Admin extends User {

	protected static string $tablename = 'admins';
	private static array $additional_fields = array(
		'securityMode' => ['datatype' => 'int', 'default' => 'DEFAULT (3)']
	);

	public static function getFieldsMap() {
		return parent::getFieldsMap() + static::$additional_fields;
	}
}