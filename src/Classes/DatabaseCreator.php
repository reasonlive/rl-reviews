<?php

namespace Reviews\Classes;

use Reviews\Classes\Database;

DatabaseCreator extends Database{
	
	public function create(string $tablename, array $data = array())
  {
  	$this->query = "CREATE TABLE IF NOT EXISTS `$tablename`";

  	if(count($data) && !array_is_list($data))
  	{
  		$this->query .= '( ';

  		foreach($data as $field => $datatype)
  		{
  			$this->query .= "$field $datatype ,"
  		}
  	}
  }

  public function set(string $field, string $datatype)
  {

  }

  public function setPrimaryKey(string $field)
  {

  }

  public function setAutoIncrement(string $field, int $default_value = 1)
  {

  }

  public function setUniqueKey(string $field)
  {

  }

  public function setForeignKey(string $field, string $ref_tablename)
  {

  }

  public function setCharset(string $value)
  {

  }

  public function setCollate(string $value)
  {

  }



}