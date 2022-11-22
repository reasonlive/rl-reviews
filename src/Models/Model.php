<?php

namespace Reviews\Models;

use Reviews\Database;

abstract class Model
{
	private const ID = 'id';
	private const TABLENAME = null;

	private Database $db = null;
	private array $fieldsData = array();

	private id = null;

	private bool isNew = true;

	public function id()
	{
		return $this->id;
	}

	public function __construct(array $data = array())
	{
		$this->db = Database::connect();

		if(count($data))
		{
			$this->fieldsData = $data;
		}
	}

	public function set(string $field, string $value)
	{
		$this->fieldsData[$field] = $value;
	}

	public function get(string $field): array
	{
		return $this->db->select(self::TABLENAME, $field)
		->where(self::ID, $this->id())
		->query();
	}

	public static function load(int $id)
	{
		if(!$this->db)
		{
			$this->db = Database::connect();
		}

		
	}

	public function save()
	{
		if($this->isNew)
		{
			$this->db->insert(self::TABLENAME, $this->fieldsData)->query();
			$this->isNew = false;
		}
		else
		{
			$this->db->update(self::TABLENAME, $this->fieldsData)->query();
		}
	}

	public function remove()
	{
		$this->db->delete(self::TABLENAME)
		->where(self::ID, $this->id())
		->query();
	}

	private function fields()
	{
		return $this->select(self::TABLENAME)
		->where(self::ID, $this->id())
		->query();
	}
}