<?php
namespace models\email;

if (file_exists('vendor/swiftmailer/swiftmailer/lib/swift_required.php')) {
	require_once 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';
} else {
	require_once '../vendor/swiftmailer/swiftmailer/lib/swift_required.php';
}

class Mailer
{
	private static $host = 'ssl://smtp.yandex.com';
	private static $port = 465;
	private static $username = 'support@lime-office.com';
	private static $password = 'g7_9dHr533';

	public static function sendEmail($subject, $body, $to)
	{
		$transport = new \Swift_SmtpTransport(static::$host, static::$port);
		$transport->setUsername(static::$username)
				  ->setPassword(static::$password);

		$mailer = new \Swift_Mailer($transport);

		$message = new \Swift_Message($subject);
		$message->setFrom(static::$username)
				  ->setTo($to)
				  ->setBody($body);

		return $mailer->send($message);
	}
}

