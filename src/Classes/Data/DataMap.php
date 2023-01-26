<?php

namespace Reviews\Classes\Data;

/**
 * Base class for database table structure
 */
abstract class DataMap {

	protected const ID = 'id';

	/** @var $fields Fields and its types for tables */
	private array $fields = [self::ID => array('type' => 'int')];

	/** @var $options Additional keys for table field */
	private array $options = [self::ID => array('AUTO_INCREMENT', 'PRIMARY_KEY')];

	/**
	 * Gets all fields for table like Model properties
	 * @return array 
	 */
	public function getFields(): array {
		return $this->fields + ['options' => $this->options];
	}

	/** @var $name Name of a database table */
	private string $name;
 
	/**
	 * Gets database table name
	 * @return string
	 */
	public function getTableName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return DataMap
	 */
	public function setTableName($name): static {
		$this->name = name;
		return $this;
	}

	/**
	 * Set foreign key referenced to other table
	 * @param string $field Field as a foreign key
	 * @param array $ref Key-value pair of referenced field [tablename => field]
	 * @return DataMap
	 */
	public function setReference($field, array $ref): static {
		$this->options[$field]['ref'] = $ref;
		return $this;
	}

	/**
	 * Additional options for field
	 * @param string $field
	 * @param string[] $options
	 * @return DataMap
	 */
	public function setOptions($field, array $options) {
		$this->options[$field] = $options;
		return $this;
	}

	/**
	 * Setter for single option
	 * @param string $field Field name
	 * @param string $option Table option
	 * @return DataMap
	 */
	public function setOption($field, string $option) {
		$this->options[$field][] = $option;
		return $this;
	}

	/**
	 * Defines table field and its type
	 * @param string $name Field name
	 * @param string $type Field type
	 * @return DataMap
	 */
	public function defineField($name, $type): static {
		$this->fields[$name] = ['type' => $type];
		return $this;
	}
} 
