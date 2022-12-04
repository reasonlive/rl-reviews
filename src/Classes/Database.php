<?php

namespace Reviews\Classes;

use \PDO;

class Database {
	/**
	 * @var Database Instance
	 */
  private static Database|null $db = null;

  /**
   * @var PDO instance
   */
  protected static PDO $connector;

  /**
   * @var string  Request query to database
   */
  protected string $query = '';

  /**
   * @var string|null Condition for WHERE clause
   */
  private string|null $whereCondition = null;

  /**
   * @var array Params as values for table fields
   */
  private array $params = array();

  /**
   * @var array<string, bool> States of database query procedure 
   */
  protected static array $states = array(
    'insert' => false,
    'select' => false,
    'update' => false,
    'delete' => false,
    'create' => false,
    'alter'  => false,
    'drop'   => false,
  );

  /**
   * @var array<string, string> DB connection settings 
   */
  private static array $settings = array();

  /**
   * Set connection settings and check if there is necessary fields 
   * @param array<string, string> settings
   */
  public static function setConnectionSettings(array $settings): void {
  	if (
  		!array_key_exists('dbname', $settings)
  		|| !array_key_exists('host', $settings)
  		|| !array_key_exists('username', $settings)
  		|| !array_key_exists('password', $settings)
  	) {
  		throw new \Exception('Incorrect database connection settings');
  	} else {
  		self::$settings = $settings;
  	}
  }

  private function __construct() {}

  /**
   * @return Database instance
   */
  private static function getInstance(): self {
  	 if (self::$db === null) {
  	 	self::$db = new self();
  	 }

  	 return self::$db;
  }

  /**
   * @return Database instance after connection 
   */
  public static function connect(): self {
		try{
		  	static::$connector = new PDO(
		  		"mysql:dbname=" . self::$settings['dbname'] . ";host=" . self::$settings['host'],
		  		self::$settings['username'],
		  		self::$settings['password']
		  	);
		}
		catch(\PDOException $e){
			error_log($e->getMessage());
			exit('[PDO CONNECTION ERROR]');
		}

		return self::getInstance();
  }

  /**
   * Remove all tables in database 
   */
  public static function truncate(): void {
  	self::connect();
  	self::$connector->exec("DROP DATABASE " . self::$settings['dbname']);
  	self::$connector->exec("CREATE DATABASE " . self::$settings['dbname']);
  }

  /**
   * Select rows from a table
   * @param string $tablename Table
   * @param string[] $data Field names to be taken
   * @return Database
   */
  public function select(string $tablename, array $data = array()): self {
    $this->check(!self::inProcess());
    self::$states['select'] = true;

  	$this->query = 'SELECT ';

  	if(count($data)){
  	  $first = true;
      foreach($data as $val){
      	if($first){
      	  $this->query .= $val;	
      	}
      	else{
      		$this->query .= ", $val";
      	}

      	$first = false;
  	  }
  	}
  	else{
  	  $this->query .= ' * ';
  	}

  	$this->query .= " FROM $tablename";
  	
  	return $this;
  }

  /**
   * Insert rows into a table
   * @param string $tablename Table
   * @param array<string, string> $data Fields and values
   * @return Database
   */
  public function insert(string $tablename, array $data): self {
    $this->check(!strlen(self::inProcess()));
    self::$states['insert'] = true;

  	$this->query = "INSERT INTO $tablename";
  	$keys = $vals = '';

  	$first = true;
  	foreach(array_keys($data) as $key){
      if(!$first){
      	$keys .= ", $key";
      	$vals .= ", ?";
      }
      else{
        $keys .= $key;
        $vals .= "?";	
      }

      $first = false;
  	}

  	$this->query .= " ($keys) VALUES ($vals)";

  	$this->params = array_values($data);

  	return $this;
  }

  /**
   * Update rows in a table
   * @param string $tablename Table
   * @param array<string, string> $data Fields and values
   * @return Database
   */
  public function update(string $tablename, array $data): self {
  	$this->check(!self::inProcess());
  	self::$states['update'] = true;

  	$this->query = "UPDATE $tablename SET";

  	foreach(array_keys($data) as $key)
  	{
  		$this->query .= " $key = ? ";
  	}

  	$this->params = array_values($data);

  	return $this;
  }

  /**
   * Delete one row in a table
   * @param string $tablename Table
   * @return Database
   */
  public function delete(string $tablename): self {
  	$this->check(!self::inProcess());
  	self::$states['delete'] = true;

  	$this->query = "DELETE FROM $tablename";

  	return $this;
  }

  /**
   * Sets OR condition in a query
   * @return Database 
   */
  public function or(): self {
  	$this->whereCondition = 'OR';
  	return $this;
  }

  /**
   * Sets AND condition in a query
   * @return Database 
   */
  public function and(): self {
  	$this->whereCondition = 'AND';
  	return $this;
  }

  /**
   * Sets WHERE condition in a query
   * @param string $field Field name
   * @param mixed $value Field value
   * @return Database 
   */
  public function where(string $field, mixed $value): self {
  	$this->check(self::inProcess());

  	if(str_contains($this->query, 'WHERE')){
  	  $this->query .= " ($this->whereCondition ?? 'AND') $field = ?";
  	}
  	else{
  	  $this->query .= " WHERE $field = ?";
  	}

  	$this->params[] = $value;

  	return $this;
  }

  /**
   * Request to database with prepared query
   * @param bool $strict If it false, params will be passed as is, without PDO binding
   * @return array<string, int|string>|int  Result from database
   */
  public function query(bool $strict = false): array|int {
    $this->check(self::inProcess());

    if (self::$states['create'] || self::$states['alter'] || self::$states['drop']) {
    	$r = static::$connector->exec($this->query);
    	
    	$this->check($r !== false, '[SQL EXECUTION ERROR]');
    	$this->query = '';
    	$this->resetState();

    	return $r;
    }

  	$stmt = static::$connector->prepare($this->query);
  	$this->query = '';

  	if(!$strict){
      $this->check($stmt->execute($this->params), '[SQL EXECUTION ERROR]');
	  	$this->params = array();	
  	}

    if(self::$states['select'])
    {
      $r = $stmt->fetchAll($this->fetch_mode);  
    }
    else
    {
      $r = $stmt->rowCount();
    }
  	
  	$stmt = null;
  	$this->resetState();
  	
  	return $r;
  }

  /**
   * Check if condition is true, throw Exception otherwise
   * @param bool $condition Any expression
   * @param string|null $message Exception message
   * @return bool True if condition is true 
   */
  private function check(bool $condition, ?string $message = null): bool {
  	if (!$condition) {
  	  throw new \Exception($message ?? '[SQL STATE ERROR]');
  	}

  	return true;
  }

  /** @var int Mode for PDO query result */
  private int $fetch_mode = PDO::FETCH_ASSOC;

  /**
   * Sets mode setting for PDO query result
   * @param int $mode  
   */
  public function setFetchMode(int $mode): void {
  	$this->fetch_mode = $mode;
  }

  /**
   * Check Database procedure state
   * @return bool true if Database is in process
   */
  public static function inProcess(): bool {
    return in_array(true, array_values(self::$states));
  }

  /**
   * Reset Database state
   */
  private function resetState(): void {
  	foreach(self::$states as $in_process)
  	{
  		$in_process = false;
  	}
  }
}