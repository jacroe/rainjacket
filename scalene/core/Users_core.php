<?php
session_start();
session_regenerate_id(true);

class Users extends Core
{
	private $scalene;
	private $phpass;
	private $dbtable;
	public $errors;

	public function __construct()
	{
		$this->load->helper("validator");
		$this->dbtable = $this->config["users"]["dbtable"];

		require_once SCALENE_PATH."extlib/PasswordHash.php";

		$this->phpass = new PasswordHash(8, FALSE);
	}

	public function userLoggedIn()
	{
		$user = $_SESSION["user"];
		if (!empty($user))
			return $user;
		return false;
	}

	public function getUser()
	{
		if ($this->userLoggedIn())
		{
			$user = $this->database->get($this->dbtable, "`username` = '{$_SESSION["user"]}'");
			unset($user[0]["password"]);
			return $user[0];
		}
		
		else
			return false;
	}

	public function register($username, $email, $password, $other = array())
	{
		$username = strtolower($username);
		if (!$this->isUsernameFree($username))
			$errors[] = "UsernameTaken";
		if (!$this->isEmailFree($email))
			$errors[] = "EmailTaken";
		if (!validate_email($email))
			$errors[] = "EmailBad";
		if (empty($password))
			$errors[] = "PasswordEmpty";

		if (empty($errors))
		{
			$main = array(
				"username"=>$username,
				"email"=>$email,
				"password"=>$this->phpass->HashPassword($password)
			);
			$this->database->insert($this->dbtable, array_merge($main, $other));
			$this->login($username, $password);
			return true;
		}
		else
		{
			$this->errors = $errors;
			return false;
		}
			
	}

	public function login($username, $password)
	{
		$userRows = $this->database->get($this->dbtable, "`username` = '$username'");
		$user = $userRows[0];
		if ($this->phpass->CheckPassword($password, $user["password"]))
		{
			$_SESSION["user"] = $username;
			return true;
		}
		else
			return false;
	}

	public function logout()
	{
		$_SESSION = array();
		if (ini_get("session.use_cookies"))
		{
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		session_destroy();
	}

	private function isUsernameFree($username)
	{
		$rows = $this->database->get($this->dbtable, "`username` = '$username'");
		if (empty($rows))
			return true;
		else
			return false;
	}

	private function isEmailFree($email)
	{
		$rows = $this->database->get($this->dbtable, "`email` = '$email'");
		if (empty($rows))
			return true;
		else
			return false;
	}
}