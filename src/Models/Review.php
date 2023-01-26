<?php

namespace Reviews\Models;

use Reviews\Classes\Data\ReviewDataMap;

class Review extends Model {

	public function __construct() {
		parent::__construct();
		$this->data = new ReviewDataMap();
	}
}