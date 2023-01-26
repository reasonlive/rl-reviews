<?php

namespace Reviews\Models;

use Reviews\Classes\Database;
use Reviews\Classes\Data\DataMap;

/**
 * Base class for App Models
 */
abstract class Model
{
	/**
	 * Data for table structure
	 * !!! must be initiated in constructor !!!
	 * @var DataMap
	 */
	protected DataMap $data;

	private static Database $db;

	private array $input_fields;

	private ?int $_id;

	private bool $isNew;

	public function id(): int
	{
		return $this->_id;
	}

	private function _setId(int $id): void
	{
		$this->_id = $id; 
	}

	public function __construct()
	{
		if(!static::$db)
		{
			static::$db = Database::connect();	
		}

		$this->isNew = true;
	}

  public function __toString() {
      return static::class;
  }

	private static function getInstance(int $id, array $loaded_data): static {
		$model = new static();
		if(!isset($model->data)) {
			throw new \Exception("$model must initiate $data object in its constructor");
		}

		$model->isNew = false;
		$model->_setId($id);
		$this->input_fields = $loaded_data;

		return $model;
	}

	public function set(string $field, string $value): static {
		if(!array_key_exists($field, $this->data)) {
			throw new \Exception("$field doesn't exist");
		}

		$this->input_fields[$field] = $value;
		return $this;
	}

	public function get(string $field): array
	{
		if($value = $this->input_fields[$field] ?? null) {
			return $value;
		}

		return static::$db->select($data->getTableName(), [$field])
		->where(self::ID, $this->id())
		->query();
	}

	public static function load(int $id)
	{
		self::$db = Database::connect();

		$loaded_data = self::$db->select($this->data->getTableName())
		->where(self::ID, $id)
		->query();

		if($loaded_data)
		{
			return static::getInstance($id, $loaded_id);
		}	
	}

	public function save()
	{
		if($this->isNew)
		{
			$this->isNew = false;
			return static::$db->insert($data->getTableName(), $this->input_fields)->query();
		}
		else
		{
			return static::$db->update($data->getTableName(), $this->input_fields)->query();
		}
	}

	public function remove()
	{
		return static::$db->delete($data->getTableName())
		->where(self::ID, $this->id())
		->query();
	}

	public
	 function fields()
	{
		if(count($this->input_fields)) {
			return $this->input_fields;
		}

		return static::$db->select($data->getTableName())
		->where(self::ID, $this->id())
		->query();
	}
}