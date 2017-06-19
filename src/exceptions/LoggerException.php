<?php
namespace buildok\logger\exceptions;

class LoggerException extends \Exception
{
	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		restore_error_handler();
		restore_exception_handler();

		error_reporting(E_ALL | E_STRICT);
    	ini_set('display_errors', 1);

    	parent::__construct("Logger Exception: $message", $code, $previous);
	}
}