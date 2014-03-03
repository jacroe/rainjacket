<?php

/**
 * A Twilio helper class
 *
 * This makes it stupid easy to send text messages.
 *
 * @author Jacob Roeland
 */

class Twilio
{
	/**
	 * Stored instance of the Scalene class
	 * @var Scalene()
	 */
	private $_scalene;

	/**
	 * Instance of the Twilio class
	 * @var Twilio()
	 */
	private $_twilio;

	/**
	 * Class constructor
	 * @param Scalene() $scalene Reference to scalene class
	 */
	public function __construct($scalene)
	{
		require_once SCALENE_PATH."extlib/Twilio/Twilio.php";

		$this->_scalene = $scalene;

		$this->_twilio = new Services_Twilio($scalene->config["twilio"]["account_sid"], $scalene->config["twilio"]["auth_token"]);
	}

	/**
	 * Sends a text message
	 * @param int    $num The phone number to text
	 * @param string $msg The message to send
	 */
	public function SendText($num, $msg)
	{
		$this->_twilio->account->messages->create(array(
			"To" => $num,
			"From" => $this->_scalene->config["twilio"]["number"],
			"Body" => $msg,
		));
	}
}