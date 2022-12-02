<?php

namespace Reviews\Classes;

DatabaseCreator extends Database {
	
	public function create(string $tablename, array $data = array()) {
		$this->query = "CREATE TABLE IF NOT EXISTS $tablename";

		if (count($data) && !array_is_list($data)) {
			$this->query .= '( ';

			$first = true;
			foreach ($data as $field => $datatype) {
				if (!$first) {
					$this->query .= ', ';
				}
				else {
					$first = false;
				}

				$this->query .= "$field $datatype";
			}
		}
	}

	public function set(string $field, string $datatype) {

	}

	public function setPrimaryKey(string $field) {
		$this->query .= ", PRIMARY KEY ($field)";
		return $this;
	}

	public function setAutoIncrement(string $field, int $default_value = 1) {

	}

	public function setUniqueKey(string $field) {

	}

	public function setForeignKey(string $field, string $ref_tablename, string $ref_field) {
		$this->query .= ", FOREIGN KEY ($field) REFERENCES $ref_tablename ($ref_field)";
		return $this;
	}

	public function setCharset(string $value) {

	}

	public function setCollate(string $value) {

	}
}