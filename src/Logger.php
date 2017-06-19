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
	/**
	 * Stream wrappers
	 */
	const WRAPPERS = ['http', 'https'];

	/**
	 * Ignore request static
	 */
	const IGNORE = ['ico'];

	/**
	 * Log buffer
	 * @var array
	 */
	private static $app_log = [];

	/**
	 * Error logger object
	 * @var ErrorLogger
	 */
	private $errorLogger;

	/**
	 * DB logger object
	 * @var SQLiteStorage
	 */
	private $log;

	/**
	 * Show log object
	 * @var View
	 */
	private $view;

	/**
	 * Flag turn on/off log
	 * @var boolean
	 */
	private $on = true;

	/**
	 * Constructor
	 * Save income data to log
	 * or render log view
	 * @param string $logFile Log filename
	 * @param string $anchor  URI for log view
	 */
	public function __construct($logFile = 'logger.db', $anchor = 'api-logger')
	{
		ob_start();

		$this->log = new SQLiteStorage($logFile);
		$this->view = new View;

		$path = parse_url($_SERVER['REQUEST_URI'], \PHP_URL_PATH);
		if ($this->isIgnore($path)) {
			$this->on = false;
			exit(0);
		}

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

	/**
	 * Add stream processing data to log
	 * @param array $stream
	 */
	public static function addStream($stream)
	{
		foreach ($stream as $item) {
			self::$app_log[] = $item;
		}
	}

	/**
	 * Destructor
	 * Save outcome data to log
	 */
	public function __destruct()
	{
		$out = ob_get_contents();
		ob_end_clean();

		if ($this->on) {
			$errors = $this->errorLogger->errors();
			foreach ($errors as $key => $error) {
				self::$app_log[] = $error;
			}

			if (!$errors) {
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
			}

			$this->log->save(self::$app_log);
		}

		file_put_contents('php://output', $out);
	}

	/**
	 * Check request path
	 * @param  string  $path
	 * @return boolean
	 */
	private function isIgnore($path)
	{
		$parts = explode('/', $path);
		$last_paire = end($parts);

		$pairs = explode('.', $last_paire);
		if (count($pairs) > 1) {
			$ext = end($pairs);

			return in_array($ext, self::IGNORE);
		}

		return false;
	}

	/**
	 * Returns request data
	 * @return array
	 */
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

	/**
	 * Register available stream wrappers
	 */
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

	/**
	 * Returns request headers
	 * @return array
	 */
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
}

