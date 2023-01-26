<?php

namespace Reviews\Classes\Data;

/**
 * Data map for Review model
 */
class ReviewDataMap extends DataMap {

	public function __construct() {
		$this->setTableName('reviews')
		->defineField('user', 'int')
		->defineField('dateFrom', 'datetime')
		->defineField('title', 'varchar(200)')
		->defineField('message', 'text')
		->defineField('rate', 'int')
		->defineField('status', 'tinyint')
		->defineField('target', 'int');

		$this->setReference('user', ['users' => parent::ID])
		->setReference('target', ['companies' => parent::ID]);
	}
}