<?php
namespace buildok\logger;

use buildok\logger\Logger;

/**
 * ErrorLogger class
 */
class ErrorLogger
{
	/**
	 * Errors buffer
	 * @var array
	 */
	private $errors = [];

	/**
	 * Init
	 * Set error and exception handlers
	 */
	public function __construct()
	{
		set_exception_handler([$this, 'exceptionHandler']);
		set_error_handler([$this, 'errorHandler']);
	}

	/**
	 * Restore default handlers
	 */
	public function __destruct()
	{
		restore_error_handler();
		restore_exception_handler();
	}

	/**
	 * Exception handler
	 * @see http://php.net/manual/ru/function.set-exception-handler.php
	 */
	public function exceptionHandler(\Throwable $exception)
	{
		if (is_a($exception, 'ErrorException') || is_a($exception, 'ParseError')) {
			$this->errorHandler($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		$traces = [];
		foreach ($exception->getTrace() as $key => $item) {
			$arguments = [];
			foreach ($item['args'] as $key => $value) {
				$arguments[] = $this->explaine($value);
			}

			$trace_item = [
				'file' => $item['file'],
				'line' => $item['line'],
				'function' => $item['function'],
				'args' => $arguments
			];

			$traces[] = $trace_item;
		}

		$this->errors[] = [
			'direct' => 'php',
			'time' => microtime(true),
			'type' => 'uncaught exception',
			'message' => $exception->getMessage(),
			'file' => $exception->getFile(),
			'line' => $exception->getLine(),
			'trace' => $traces
		];

		exit(1);
	}

	/**
	 * Error handler
	 * @see http://php.net/manual/ru/function.set-error-handler.php
	 */
	public function errorHandler($errno, $errst, $errfile = null, $errline = null, $errcontext = null)
	{
		$type = $this->mapErrors($errno);

		$context = [];
		if ($errcontext) {
			foreach ($errcontext as $name => $value) {
				$context[] = $this->explaine($value, $name);
			}
		}

		$this->errors[] = [
			'direct' => 'php',
			'time' => microtime(true),
			'type' => $type,
			'message' => $errst,
			'file' => $errfile,
			'line' => $errline,
			'context' => $context
		];

		exit(1);
	}

	/**
	 * Get errors
	 * @return array
	 */
	public function errors()
	{
		return $this->errors;
	}

	/**
	 * Returns data of variable type
	 * @param  mixed $value Variable value
	 * @param  string $name  Variable name
	 * @return array
	 */
	private function explaine($value, $name = null)
	{
		$classname = null;
		$type = gettype($value);

		switch ($type) {
			case 'boolean':
				$value = ($value) ? 'true' : 'false';
				break;
			case 'double':
				$type = 'float';
				$value = $value;
				break;
			case 'NULL':
				$value = 'null';
				break;
			case 'resource':
				$value = get_resource_type($value);
				break;
			case 'object':
				$classname = get_class($value);
			case 'array':
				$items = $value;
				$value = [];
				foreach ($items as $ind => $item_value) {
					$value[] = $this->explaine($item_value, $ind);
				}
				break;

			case 'unknown type':
				$value = '';
				break;
		}

		$item = [
			'type' => $type,
			'value' => $value
		];
		is_null($classname) || $item['classname'] = $classname;
		is_null($name) || $item['name'] = $name;

		return $item;
	}

	/**
	 * Returns error type
	 * @param  int $code Error code
	 * @return string
	 */
	private function mapErrors($code)
	{
	    $type = null;

	    switch ($code) {
	        case \E_PARSE:
	        case \E_ERROR:
	        case \E_CORE_ERROR:
	        case \E_COMPILE_ERROR:
	        case \E_USER_ERROR:
	            $type = 'fatal';
	            break;
	        case \E_WARNING:
	        case \E_USER_WARNING:
	        case \E_COMPILE_WARNING:
	        case \E_RECOVERABLE_ERROR:
	            $type = 'warning';
	            break;
	        case \E_NOTICE:
	        case \E_USER_NOTICE:
	            $type = 'notice';
	            break;
	        case \E_STRICT:
	            $type = 'strict';
	            break;
	        case \E_DEPRECATED:
	        case \E_USER_DEPRECATED:
	            $type = 'deprecated';
	            break;
	        default :
	        	$type = 'Error Exception';
	            break;
	    }

	    return $type;
	}
}