<?php
include_once("../classes/mysqlconnection.php");

class Medicine
{
	private $id;
	private $formula;
	private $commercialName;
	private $units;
	private $laboratoy;
	private $price;
	private $presentation;

	public function getId()
	{
		return $this->id;
	}

	public function setFormula($value)
	{
		$this->formula = $value;
	}

	public function setCommercialName($value)
	{
		$this->commercialName = $value;
	}
	public function setUnits($value)
	{
		$this->units = $value;
	}
	public function setLaboratory($value)
	{
		$this->laboratoy = $value;
	}
	public function setPrice($value)
	{
		$this->price = $value;
	}

	public function setPresentation($value)
	{
		$this->presentation = $value;
	}



	function __construct()
	{
		if (func_num_args() == 0) {
			$this->id = 0;
			$this->formula = "";
			$this->commercialName = "";
			$this->units = "";
			$this->laboratoy = "";
			$this->price = 0;
			$this->presentation = 0;
		}
		if (func_num_args() == 1) {
			//get arguments
			$arguments = func_get_args();
			$id = $arguments[0];
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'SELECT id, formula, commercial_name, units, presentation, laboratoy, price 
                    FROM medicines 
                    WHERE ?';
			//command
			$command = $connection->prepare($query);
			//bind parameters
			$command->bind_param('i', $id);
			//execute
			$command->execute();
			//bind results
			$command->bind_result($id, $formula, $commercial_name, $units, $presentation, $laboratoy, $price);
			//fetch data
			$found = $command->fetch();
			//close command
			mysqli_stmt_close($command);
			//close connection
			$connection->close();
			//throw exception if record not found
			if ($found) {
				$this->id = $id;
				$this->formula = $formula;
				$this->commercialName = $commercial_name;
				$this->units = $units;
				$this->laboratoy = $presentation;
				$this->price = $price;
				$this->presentation = $presentation;
			} //if
			else {
				throw new RecordNotFoundException(); //throw exception if record not found
			} //else
		}
		if (func_num_args() == 7) {
			//get arguments
			$arguments = func_get_args();
			//pass arguments to attributes
			$this->id = $arguments[0];
			$this->formula = $arguments[1];
			$this->commercialName = $arguments[2];
			$this->units = $arguments[3];
			$this->laboratoy = $arguments[4];
			$this->price = $arguments[5];
			$this->presentation = $arguments[6];
		}
	}

	public function toArray()
	{
		return array(
			'id' => $this->id,
			"formula" => $this->formula,
			"commercialName" => $this->commercialName,
			"units" => $this->units,
			"laboratoy" => $this->presentation,
			"presentation" => $this->presentation,
		);
	}


	public function add()
	{
		//get connection
		$connection = MySQLConnection::getConnection();
		//query
		$query = 'INSERT INTO users(name, username, password, role) VALUES (?, ?, sha1(?), ?);';
		//command
		$command = $connection->prepare($query);
		//parameters
		$command->bind_param('ssss', $this->name, $this->username, $this->password, $this->role);
		//execute
		$result = $command->execute();
		//close statement
		mysqli_stmt_close($command);
		//close connection
		$connection->close();
		//retunr result
		return $result;
	} //add



	public static function getAllUsers()
	{
		//list
		$list = array();
		$connection = MySQLConnection::getConnection();
		//query
		$query = 'SELECT id, name, username, role, active
                      FROM users';
		//command
		$command = $connection->prepare($query);
		//execute
		$command->execute();
		//bind results
		$command->bind_result($id, $name, $username, $role, $active);
		//fetch
		while ($command->fetch()) {
			array_push($list, new User($id, $name, $username, $role, $active));
		}
		//close statement
		mysqli_stmt_close($command);
		//close connection
		$connection->close();
		//return array
		return $list;
	}

	public static function getAllUsersArray()
	{
		//list
		$list = array();
		//encode to json
		foreach (self::getAllUsers() as $item) {
			array_push($list, $item->toArray());
		} //foreach
		return $list;
	}
}