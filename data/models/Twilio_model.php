<?php

/**
 * A Twilio helper class
 *
 * This makes it stupid easy to send text messages.
 *
 * @author Jacob Roeland
 */

class Twilio extends Model
{
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
		parent::__construct($scalene);

		$this->_twilio = new Services_Twilio($this->config["twilio"]["account_sid"], $this->config["twilio"]["auth_token"]);
	}

	/**
	 * Sends a text message
	 * @param int    $num The phone number to text
	 * @param string $msg The message to send
	 */
	public function sendText($num, $msg)
	{
		$this->_twilio->account->messages->create(array(
			"To" => $num,
			"From" => $this->config["twilio"]["number"],
			"Body" => $msg,
		));
	}
}