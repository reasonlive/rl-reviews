<?php

namespace Reviews\Models;
use Reviews\Classes\Data\CommentDataMap;

class Comment extends Model {

	public function __construct() {
		parent::__construct();
		$this->data = new CommentDataMap();
	}
}