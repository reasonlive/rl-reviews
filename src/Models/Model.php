<?php

namespace Reviews\Models;

use Reviews\Classes\Database;

abstract class Model
{
	protected static DataMap $data;

	private static Database $db;

	private ?int $_id;

	private bool $isNew;

	public static function getDataMap() {
		return static::$data;
	}

	public function id()
	{
		return $this->_id;
	}

	private function _setId(int $id)
	{
		$this->_id = $id; 
	}

	public static function getTableName() {
		return static::$tablename;
	}

	public static function getFieldsMap() {
		return self::$fields_map + static::$fields_map;
	}

	public function __construct(array $data = array())
	{
		if(!static::$db)
		{
			static::$db = Database::connect();	
		}
		
		if(count($data))
		{
			$this->input_fields = $data;
		}
	}

	private static function getInstance(int $id)
	{
		$model = new static();
		$model->isNew = false;
		$model->_id = $id;
		
		return $model;
	}

	public function set(string $field, string $value)
	{
		$this->input_fields[$field] = $value;
	}

	public function get(string $field): array
	{
		return static::$db->select(static::getTableName(), $field)
		->where(self::ID, $this->id())
		->query();
	}

	public static function load(int $id)
	{
		self::$db = Database::connect();

		$loaded_id = self::$db->select(static::getTableName(), self::ID)
		->where(self::ID, $id)
		->query();

		if($loaded_id)
		{
			return static::getInstance($loaded_id);
		}	
	}

	public function save()
	{
		if($this->isNew)
		{
			$this->isNew = false;
			return static::$db->insert(static::getTableName(), $this->input_fields)->query();
		}
		else
		{
			return static::$db->update(static::getTableName(), $this->input_fields)->query();
		}
	}

	public function remove()
	{
		return static::$db->delete(static::getTableName())
		->where(self::ID, $this->id())
		->query();
	}

	private function fields()
	{
		return static::$db->select(static::getTableName())
		->where(self::ID, $this->id())
		->query();
	}
}