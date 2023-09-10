<?php
include_once("../classes/mysqlconnection.php");

class User
{
	private $id;
	private $name;
	private $username;
	private $password;
	private $role;
	private $active;

	public function getId()
	{
		return $this->id;
	}

	public function setName($value)
	{
		$this->name = $value;
	}

	public function setPassword($value)
	{
		$this->password = $value;
	}
	public function setUsername($value)
	{
		$this->username = $value;
	}
	public function setRole($value)
	{
		$this->role = $value;
	}
	public function setActive($value)
	{
		$this->active = $value;
	}



	function __construct()
	{
		if (func_num_args() == 0) {
			$this->id = 0;
			$this->name = '';
			$this->username = '';
			$this->role = '';
			$this->password = '';
			$this->active = 0;
		}
		if (func_num_args() == 1) {
			//get arguments
			$arguments = func_get_args();
			$id = $arguments[0];
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'select id, name, username, role, active 
						from users
						where id = ?';
			//command
			$command = $connection->prepare($query);
			//bind parameters
			$command->bind_param('i', $id);
			//execute
			$command->execute();
			//bind results
			$command->bind_result($id, $name, $username, $role, $active);
			//fetch data
			$found = $command->fetch();
			//close command
			mysqli_stmt_close($command);
			//close connection
			$connection->close();
			//throw exception if record not found
			if ($found) {
				$this->id = $id;
				$this->name = $name;
				$this->username = $username;
				$this->role = $role;
				$this->active = $active;
			} //if
			else {
				throw new RecordNotFoundException(); //throw exception if record not found
			} //else
		}
		if (func_num_args() == 5) {
			//get arguments
			$arguments = func_get_args();
			//pass arguments to attributes
			$this->id = $arguments[0];
			$this->name = $arguments[1];
			$this->username = $arguments[2];
			$this->role = $arguments[3];
			$this->active = $arguments[4];
			$this->password = '';
		}
	}

	public function toArray()
	{
		return array(
			'id' => $this->id,
			'name' => $this->name,
			'username' => $this->username,
			'role' => $this->role,
			'active' => $this->active,
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
