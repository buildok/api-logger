<?php
namespace buildok\logger;

use buildok\logger\Logger;

/**
 *
 */
class ErrorLogger
{
	private $errors = [];

	public function __construct()
	{
		set_exception_handler([$this, 'exceptionHandler']);
		set_error_handler([$this, 'errorHandler']);
	}

	public function __destruct()
	{
		restore_error_handler();
		restore_exception_handler();
	}

	public function exceptionHandler($exception)
	{
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

			// !isset($item[''])

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
	}

	/**
	 * [errorHandler description]
	 * @param  int    $errno      [description]
	 * @param  string $errst      [description]
	 * @param  string $errfile    [description]
	 * @param  int    $errline    [description]
	 * @param  array  $errcontext [description]
	 * @return bool
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

		if ($type == 'Fatal Error') {
			exit(1);
		}

		return true;
	}

	public function errors()
	{
		return $this->errors;
	}

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
	            break;
	    }

	    return $type;
	}
}