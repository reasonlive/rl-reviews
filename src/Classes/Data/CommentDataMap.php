<?php

namespace Reviews\Classes\Data;

class CommentDataMap extends DataMap {

	public function __construct() {
		$this->setTableName('comments')
		->defineField('message', 'varchar(200)')
		->defineField('review', 'int')
		->defineField('user', 'int')
		->defineField('dateFrom', 'datetime');

		$this->setReference('review', ['reviews' => parent::ID])
		->setReference('user', ['users' => parent::ID]);
	}
}