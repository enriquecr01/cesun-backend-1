<?php
include_once("../config/global.php");
class MySQLConnection
{
	//return a MySQL connection object
	public static function getConnection()
	{
		try {
			$server = DB_HOST;
			$database = DB_NAME;
			$user = DB_USERNAME;
			$password = DB_PASSWORD;
			$encode = DB_ENCODE;
			//create connection
			$connection = mysqli_connect($server, $user, $password, $database);
			//character set
			$connection->set_charset($encode);
			//check connection
			if (!$connection) {
				echo 'Could not connect to MySql';
				die;
			}
			//return connection
			return $connection;
		} catch (Exception $ex) {
			echo 'Configuration error';
			die;
		}
	}
}
