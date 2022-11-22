<?php

namespace Reviews\Classes;

class Database
{
	/**
	 * @var Object Database instance
	 */
  private static $db = null;

  /**
   * @var PDO instance
   */
  private $connector;

  /**
   * @var query string to database
   */
  private string $query = '';

  /**
   * @var string|null condition for WHERE clause
   */
  private ?string $whereCondition = null;

  /**
   * @var array Params as values for PDO substitution
   */
  private array $params = array();

  protected const settings = array(
  	'dbname'   => 'reviews',
  	'host'     => '127.0.0.1',
  	'username' => 'root',
  	'password' => 'mitoteam'
  );

  private function __construct(){}

  private static function getInstance()
  {
  	 if(self::$db === null){
  	 	self::$db = new self();
  	 }

  	 return self::$db;
  }

  public static function connect()
  {
		$db = self::getInstance();

		try{
		  	$db->connector = new \PDO(
		  		"mysql:dbname=" . self::settings['dbname'] . ";host=" . self::settings['host'],
		  		self::settings['username'],
		  		self::settings['password']
		  	);
		}
		catch(\PDOException $e){
			error_log($e->getMessage());
			exit('[PDO CONNECTION ERROR]');
		}

		return $db;
  }

  public function select(string $tablename, array $data = array())
  {
    $this->isInProcess = true;
  	$this->checkQuery(!strlen($this->query));

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

  public function insert(string $tablename, array $data)
  {
    $this->isInProcess = true;
  	$this->checkQuery(!strlen($this->query));

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

  public function update(string $tablename, array $data)
  {
    $this->isInProcess = true;
  	$this->checkQuery(!strlen($this->query));

  	$this->query = "UPDATE $tablename SET";

  	foreach(array_keys($data) as $key)
  	{
  		$this->query .= " $key = ? ";
  	}

  	$this->params = array_values($data);

  	return $this;
  }

  public function delete(string $tablename)
  {
    $this->isInProcess = true;
  	$this->checkQuery(!strlen($this->query));

  	$this->query = "DELETE FROM $tablename";

  	return $this;
  }

  public function or()
  {
  	$this->whereCondition = 'OR';
  	return $this;
  }

  public function and()
  {
  	$this->whereCondition = 'AND';
  	return $this;
  }

  public function where(string $field, mixed $value)
  {
  	if(str_contains($this->query, 'WHERE')){
  	  $this->query .= " ($this->whereCondition ?? 'AND') $field = ?";
  	}
  	else{
  	  $this->query .= " WHERE $field = ?";
  	}

  	$this->params[] = $value;

  	return $this;
  }

  public function query(bool $strict = false)
  {
  	$this->checkQuery(strlen($this->query));

  	$stmt = $this->connector->prepare($this->query);

  	$this->query = '';
    $this->isInProcess = false;

  	if(!$strict){
	  	$stmt->execute($this->params);
	  	$this->params = array();	
  	}

  	$r = $stmt->fetchAll($this->fetch_mode);
  	$stmt = null;
  	
  	return $r;
  }

  private function checkQuery(bool $condition, ?string $message = null)
  {
  	if(!$condition || !$this->isInProcess){
  	  throw new Exception($message ?? '[SQL QUERY ERROR]');
  	}

  	return null;
  }

  private $fetch_mode = \PDO::FETCH_ASSOC;

  public function setFetchMode(int $mode){
  	$this->fetch_mode = $mode;
  }

  private static bool $isInProcess = false;

  public static function inProcess(): bool
  {
    return self::isInProcess;
  }
}