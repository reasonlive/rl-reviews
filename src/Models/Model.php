<?php

namespace Reviews\Models;

use Reviews\Classes\Database;

abstract class Model
{
	private const ID = 'id';
	protected const TABLENAME = null;

	private static ?Database $db = null;

	private array $inputFields = array();
	private array $outputFields;

	private ?int $_id = null;

	private bool $isNew = true;

	public function id()
	{
		return $this->_id;
	}

	private function _setId(int $id)
	{
		$this->_id = $id; 
	}

	public function __construct(array $data = array())
	{
		if(!static::$db)
		{
			static::$db = Database::connect();	
		}
		
		if(count($data))
		{
			$this->inputFields = $data;
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
		$this->inputFields[$field] = $value;
	}

	public function get(string $field): array
	{
		return static::$db->select(static::TABLENAME, $field)
		->where(self::ID, $this->id())
		->query();
	}

	public static function load(int $id)
	{
		self::$db = Database::connect();

		$loaded_id = self::$db->select(static::TABLENAME, self::ID)
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
			return static::$db->insert(static::TABLENAME, $this->inputFields)->query();
		}
		else
		{
			return static::$db->update(static::TABLENAME, $this->inputFields)->query();
		}
	}

	public function remove()
	{
		return static::$db->delete(static::TABLENAME)
		->where(self::ID, $this->id())
		->query();
	}

	private function fields()
	{
		return static::$db->select(static::TABLENAME)
		->where(self::ID, $this->id())
		->query();
	}
}