<?php
include_once("../classes/mysqlconnection.php");

class User
{
	private $id;
	private $name;
	private $username;
	private $password;
	private $photo;
	private $active;

	public function getId() { return $this->id; }

	public function setName($value) { $this->name = $value; }

	public function setPassword($value) { $this->password = $value; }
	public function setUsername($value) { $this->username = $value; }
	public function setPhoto($value) { $this->photo = $value; }
	public function setActive($value) { $this->active = $value; }



	function __construct()
	{
		if (func_num_args() == 0) {
			$this->id = 0;
			$this->name = '';
			$this->username = '';
			$this->photo = '';
			$this->password = '';
			$this->active = 0;
		}
		if (func_num_args() == 5) {
			//get arguments
			$arguments = func_get_args();
			//pass arguments to attributes
			$this->id = $arguments[0];
			$this->name = $arguments[1];
			$this->username = $arguments[2];
			$this->photo = $arguments[3];
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
			'photo' => $this->photo,
			'active' => $this->active,
		);
	}



	public static function getAllUsers()
	{
		//list
		$list = array();
		$connection = MySQLConnection::getConnection();
		//query
		$query = 'SELECT id, name, username, photo, active
                      FROM users';
		//command
		$command = $connection->prepare($query);
		//execute
		$command->execute();
		//bind results
		$command->bind_result($id, $name, $username, $photo, $active);
		//fetch
		while ($command->fetch()) {
			array_push($list, new User($id, $name, $username, $photo, $active));
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
