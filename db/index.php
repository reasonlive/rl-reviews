<?php 



class Database {

	private $connector;

	public function __construct($host, $login, $pass, $dbname){
		$this->connector = mysqli_connect($host,$login,$pass,$dbname);

		if($this->connector == false){
			printf("Connect failed: %s" , mysqli_connect_error());
			exit;
		}
		else
		mysqli_set_charset($this->connector, "utf8");
	}

	//makes any operations by setting query
	public function execute($query){

		$res = mysqli_query($this->connector, $query);

		if(!$res){
			$j = mysqli_warning_count($this->connector);
			if ($j > 0) {
			    $e = mysqli_get_warnings($this->connector);
			    for ($i = 0; $i < $j; $i++) {
			        var_dump($e);
			        $e->next();
			    }
			}
		}
		return $res;

	}

	//gets one row by the custom query 
	//and returns assoc array
	public function get($query){
		$res = mysqli_query($this->connector, $query);
		if(!$res)return null;
		$row = $res->fetch_assoc();
		$res->free();

		return $row;
	}

	//gets all rows in the table and
	//returns array of assoc arrays
	public function get_all($table){
		$res = mysqli_query($this->connector, "SELECT * FROM $table");
		if(!$res)return null;
		$result_arr = array();

		for($i = 0; $i < $res->num_rows; $i++)
			$result_arr[] = $res->fetch_assoc();


		
		$res->free();

		return $result_arr;
	}

	//gets particular amount of rows in the table
	//returns array of assoc rows
	public function get_some($table, $limit, $offset=0){
		$res = mysqli_query($this->connector, "SELECT * FROM $table WHERE allowed = 1 LIMIT $limit OFFSET $offset");
		if(!$res)return null;
		$result_arr = array();

		for($i = 0; $i < $res->num_rows; $i++)
			$result_arr[] = $res->fetch_assoc();
		
		$res->free();

		return $result_arr;

	}

	function get_allowed($table){
		$res = mysqli_query($this->connector, "SELECT * FROM $table WHERE allowed = 1");
		if(!$res)return null;
		$result_arr = array();

		for($i = 0; $i < $res->num_rows; $i++)
			$result_arr[] = $res->fetch_assoc();
		
		$res->free();

		return $result_arr;
	}

	//gets all rows which suits to condition: $column = $value
	//returns array of assoc rows or null
	public function get_all_by_condition($table, $column, $value){
		$res = mysqli_query($this->connector, "SELECT * FROM $table WHERE $column = $value");

		if($res->num_rows < 1)return null;

		$result_arr = array();

		for($i = 0; $i < $res->num_rows; $i++)
			$result_arr[] = $res->fetch_assoc();
		
		$res->free();

		return $result_arr;


	}


	public function add_review($title, $message, $username, $phone, $email){

		$date = date('Y-m-d h:i:s');

		$stmt = mysqli_stmt_init($this->connector);
		$prepared = mysqli_stmt_prepare($stmt, "INSERT INTO reviews (title, message, username, phone, email,created) VALUES (?,?,?,?,?,'$date') ");

		if($prepared){
			mysqli_stmt_bind_param($stmt, "sssss", $title,$message,$username,$phone,$email);

			$success = mysqli_stmt_execute($stmt);

			mysqli_stmt_close($stmt);
		}else{
			//print some error
			mysqli_stmt_close($stmt);
			return false;
		}


		$j = mysqli_warning_count($this->connector);
		if ($j > 0) {
		    $e = mysqli_get_warnings($this->connector);
		    for ($i = 0; $i < $j; $i++) {
		        var_dump($e);
		        $e->next();
		    }
		}

		return $success;

	}

	public function update_review($id, $column, $value){

		$stmt = mysqli_stmt_init($this->connector);

		$types = $column === "message" ? "si" : "ii"; 

		$prepared = mysqli_stmt_prepare($stmt, "UPDATE reviews SET $column = ? WHERE id = ? ");

		if($prepared){
			mysqli_stmt_bind_param($stmt, $types, $value, $id);
			$success = mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}else{
			mysqli_stmt_close($stmt);
			return false;
		}

		return $success;
	}

	public function delete_review($id){

		$deleted_from_answers = mysqli_query($this->connector, "DELETE FROM answers WHERE review = $id ");
		if($deleted_from_answers){
			return mysqli_query($this->connector, "DELETE FROM reviews WHERE id = $id ");
		}
		else return null;
		
	}


	public function add_answer($id, $message){

		$stmt = mysqli_stmt_init($this->connector);

		$prepared = mysqli_stmt_prepare($stmt, "INSERT INTO answers (message, review)
			VALUES (?,?) ");

		if($prepared){
			mysqli_stmt_bind_param($stmt, "si", $message, $id);

			$success = mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}else{
			mysqli_stmt_close($stmt);
			return false;
		}

		
		return $success;
	}





	



	public function prepare_str($string){
		$without_tags = strip_tags($string);
		if ($this->connector) 
			return mysqli_real_escape_string ($this->connector, $without_tags);
		
	}



	public function __destruct(){
		mysqli_close($this->connector);
		
	}

}



?>