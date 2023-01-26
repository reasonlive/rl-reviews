<?php

namespace Reviews\Classes;

class DatabaseTableBuilder extends Database {

	private static string|null $auto_column = null;
	private static array $foreign_keys = array();

  public function __construct() {
  	return parent::connect();
  }

  public static function create(string|array $classname) {
  	parent::$states['create'] = true;
  	
  	$entity_list = is_array($classname) ? $classname : [$classname];

  	foreach ($entity_list as $classname) {
  		$table = new self();
  		$table->init($classname::getTableName(), $classname::getFieldsMap());

	  	if (self::$auto_column) {
	  		$table->setPrimaryKey(self::$auto_column);
	  	}

	  	if (count(self::$foreign_keys)) {
	  		foreach (self::$foreign_keys as $field => [$ref_tablename, $ref_field]) {
	  			$table->setForeignKey($field, $ref_tablename, $ref_field);
	  		}
	  	}

	  	$table->setAutoIncrement()
	  		->setCharset()
	  		->setCollate()
	  		->run();
	  }	
  }

  // key => fieldname, value => [datatype, default, option, ref]
	public function init(string $tablename, array $data) {

		$this->query = "CREATE TABLE IF NOT EXISTS $tablename ()";

		foreach ($data as $field => $options) {

			$type = $options['datatype'] ?? '';
			$default = $options['default'] ?? 'DEFAULT NULL';
			$option = $options['option'] ?? '';

			if ($ref_field = $options['ref'] ?? false) {
				self::$foreign_keys[$field] = $ref_field;
			}

			if ($option == 'AUTO_INCREMENT') {
				self::$auto_column = $field;
			}

			$this->appendInsteadOfLastChar("$field $type $default $option, ");
		}

		$this->query = trim($this->query);
		$this->appendInsteadOfLastChar(')');

    return $this;
	}

	private function appendInsteadOfLastChar(string $statement) {
		$this->query = substr_replace($this->query, $statement, -1);
	}

  public function run() {
  	$this->query .= ';';
    $this->query($this->query);
  }

	public function setPrimaryKey(string $field) {
		$str = ", PRIMARY KEY ($field))";
		$this->appendInsteadOfLastChar($str);
		return $this;
	}

	public function setUniqueKey(string $field) {
    $str = ", UNIQUE ($field))";
    $this->appendInsteadOfLastChar($str);
    return $this;
	}

	public function setForeignKey(string $field, string $ref_tablename, string $ref_field) {
		$str = ", FOREIGN KEY ($field) REFERENCES $ref_tablename ($ref_field))";
		$this->appendInsteadOfLastChar($str);
		return $this;
	}

  // after fields initialization
  public function setAutoIncrement(int $default_value = 1) {
    $this->query .= " AUTO_INCREMENT=$default_value";
    return $this;
  }

	public function setCharset(string $value = 'utf8mb4') {
    $this->query .= " DEFAULT CHARSET=$value";
    return $this;
	}

	public function setCollate(string $value = 'utf8mb4_unicode_ci') {
    $this->query .= " COLLATE=$value";
    return $this;
	}
}