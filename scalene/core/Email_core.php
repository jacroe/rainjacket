<?php

class Email extends Library
{
	private $smtpServer;
	private $smtpPort;
	private $smtpUser;
	private $smtpPass;
	private $smtpFrom;

	public function __construct()
	{
		foreach ($this->config["email"] as $var => $value)
			$this->{"smtp".ucfirst($var)} = $value;
	}

	public function send($toName, $toEmail, $subject, $body, $attachment = null)
	{
		$transport = Swift_SmtpTransport::newInstance($this->smtpServer, $this->smtpPort, 'ssl')
		  ->setUsername($this->smtpUser)
		  ->setPassword($this->smtpPass);
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance('(no subject)')
		  ->setFrom(array($this->smtpFrom["email"] => $this->smtpFrom["name"]))
		  ->setTo(array($toEmail => $toName))
		  ->setSubject($subject)
		  ->setBody($body, 'text/html');
		if ($attachment) $message->attach(Swift_Attachment::fromPath($attachment));
		$result = $mailer->send($message);
	}
}