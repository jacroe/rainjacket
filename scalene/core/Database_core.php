<?php

class Database extends Core
{
	private $con;

	public function __construct()
	{
		foreach ($this->config["database"] as $var => $value)
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

	public function numRows($table, $where = 1)
	{
		return count($this->get($table, $where));
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

	public function put($table, $array)
	{
		$structure = $this->query("SHOW INDEXES FROM $table WHERE Key_name = 'PRIMARY'");
		$primaryColumn = $structure[0]['Column_name'];

		if (array_key_exists($primaryColumn, $array))
		{
			if ($this->numRows($table, "`$primaryColumn` = '{$array[$primaryColumn]}'"))
				$this->update($table, $array, "`$primaryColumn` = '{$array[$primaryColumn]}'");
			else
				$this->insert($table, $array);
		}
		else
			$this->insert($table, $array);
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