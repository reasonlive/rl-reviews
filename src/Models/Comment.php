<?php

namespace Reviews\Models;

class Comment extends Model {

	protected static string $tablename = 'comments';
	protected static array $fields_map = array(
		'message' => [],
		'review'  => []
	);
}