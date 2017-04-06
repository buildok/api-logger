<?php
namespace buildok\logger;

use buildok\logger\exceptions\LoggerException;
use buildok\logger\SQLiteStorage;
use buildok\logger\ErrorLogger;
use buildok\logger\View;

/**
 * Logger class
 */
class Logger
{
	const WRAPPERS = ['http'];

	private static $app_log = [];

	private $errorLogger;

	private $log;
	private $on = true;

	public function __construct($anchor = 'api-logger')
	{
		ob_start();

		$this->log = new SQLiteStorage();
		$this->view = new View;

		$path = parse_url($_SERVER['REQUEST_URI'], \PHP_URL_PATH);
		if (stripos($path, $anchor) === false) {
			$this->registerWrappers();
			$this->errorLogger = new ErrorLogger();

			self::$app_log[] = $this->getRequest();
		} else {

			$this->on = false;
			$this->view->render([
				'records' => $this->log->show()
			]);

			exit(0);
		}
	}

	public function __destruct()
	{
		$out = ob_get_contents();
		ob_end_clean();

		if ($this->on) {
			$errors = $this->errorLogger->errors();
			foreach ($errors as $key => $error) {
				self::$app_log[] = $error;
			}

			$parsed_hdr = [];
			foreach (headers_list() as $key => $header) {
				list($name, $val) = explode(':', $header, 2);
				$parsed_hdr[$name] = trim($val);
			}
			$response['headers'] = $parsed_hdr;
			$response['body'] = $out;
			$response['direct'] = 'outcome';
			$response['time'] = microtime(true);
			$response['code'] = http_response_code();
			$response['message'] = '';
			$response['protocol'] = $_SERVER['SERVER_PROTOCOL'];
			self::$app_log[] = $response;

			$this->log->save(self::$app_log);
		}

		file_put_contents('php://output', $out);
	}

	private function getRequest()
	{
		$request = [
			'time' => $_SERVER['REQUEST_TIME_FLOAT'],
			'direct' => 'income',
			'method' => $_SERVER['REQUEST_METHOD'],
			'headers' => $this->getRequestHeaders(),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'port' => $_SERVER['REMOTE_PORT'],
			'uri' => $_SERVER['REQUEST_URI'],
			'ajax' => array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && !strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'XmlHttpRequest')

		];

		$request['param'] = [];
		empty($_POST) || $request['param'] = $_POST;
		empty($_GET) || $request['param'] = $_GET;

		$request['body'] = file_get_contents('php://input');

		return $request;
	}

	private function registerWrappers()
	{
		foreach (self::WRAPPERS as $type) {
			$class_ns = 'buildok\\logger\\wrappers\\' . ucfirst($type) .'Scheme';

			if (!class_exists($class_ns)) {
				throw new LoggerException('Unsupported wrapper type:' . $type);
			}

			if (in_array($type, stream_get_wrappers())) {
			    stream_wrapper_unregister($type);
			}
			stream_wrapper_register($type, $class_ns, \STREAM_IS_URL);
		}
	}

	private function getRequestHeaders()
	{
	    $headers = [];
	    foreach ($_SERVER as $key => $value) {
	        if (substr($key, 0, 5) == 'HTTP_') {
	            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
	        	$headers[$header] = $value;
	        }
	    }

	    return $headers;
	}

	public static function addStream($stream)
	{
		foreach ($stream as $item) {
			self::$app_log[] = $item;
		}
	}
}

