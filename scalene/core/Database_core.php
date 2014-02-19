<?php

class Database
{
	private $con;
	private $scalene;

	public function __construct($scalene)
	{
		$this->scalene = $scalene;

		foreach ($this->scalene->config["database"] as $var => $value)
			${$var} = $value;

		try
		{
			$this->con = new PDO("mysql:host=$server;dbname=$database;charset=utf8", $user, $pass);
		}
		catch(PDOException $e)
		{
			die("Could not connect to database. Message given: ".$e->getMessage());
		}
	}

	public function get($table, $where = 1)
	{
		$query = "SELECT * FROM `$table` WHERE $where";
		$stmt = $this->con->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function insert($table, $array)
	{
		$plainKeys = array_keys($array);
		foreach($array as $key => $value)
			$newArray[":$key"] = $value;
		$newKeys = array_keys($newArray);

		$query = "INSERT INTO `$table` ";
		$query .= '(`'.implode($plainKeys, '`,`').'`) ';
		$query .= 'VALUES ('.implode($newKeys, ', ').')';

		$stmt = $this->con->prepare($query);
		$stmt->execute($newArray);
	}

	public function update($table, $array, $where = 1)
	{
		$plainKeys = array_keys($array);
		foreach($array as $key => $value)
			$newArray[":$key"] = $value;
		$newKeys = array_keys($newArray);

		$query = "UPDATE `$table` SET ";
		foreach ($plainKeys as $k)
			$fields[] = "`$k`=:$k";
		$query .= implode($fields, ", ");
		$query .= " WHERE $where";

		$stmt = $this->con->prepare($query);
		$stmt->execute($newArray);
	}

	public function delete($table, $where)
	{
		$query = "DELETE FROM `$table` WHERE $where";
		$stmt = $this->con->prepare($query);
		$stmt->execute();
	}

	public function query($query)
	{
		$stmt = $this->con->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
}