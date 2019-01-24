<?php
namespace buildok\logger\wrappers\base;

use buildok\logger\exceptions\LoggerException;
use buildok\logger\Logger;

/**
 * StreamWrapper class
 * @see http://php.net/manual/ru/class.streamwrapper.php
 */
abstract class StreamWrapper
{
	/**
	 * Stream context
	 */
	public $context;

	/**
	 * Resource descriptor
	 * @var Resource
	 */
	protected $resource;

	/**
	 * Resource content
	 * @var string
	 */
	protected $read_content = null;
	protected $write_content = null;

	/**
	 * Time of open stream
	 * @var float
	 */
	protected $open_time;


	public function __construct()
	{
		stream_wrapper_restore($this->scheme());
	}

	public function __destruct()
	{
		stream_wrapper_unregister($this->scheme());
		stream_wrapper_register($this->scheme(), get_class($this));
	}

	public function getMetadata()
	{
		return stream_get_meta_data($this->resource);
	}

	abstract public function scheme();

	public function __call($name, $arguments)
	{
		$ret = call_user_func_array([$this, $name], $arguments);

		return $ret;
	}

	/**
	 * [stream_open description]
	 * @param  string $path         [description]
	 * @param  string $mode         [description]
	 * @param  int    $options      [description]
	 * @param  string &$opened_path [description]
	 * @return bool
	 */
	protected function stream_open($path, $mode, $options, &$opened_path)
	{
		if (!$this->resource = fopen($path, $mode, false, $this->context)) {
			throw new LoggerException('Failed open stream');
		}

		$this->open_time = microtime(true);

		return (bool)$this->resource;
	}

	/**
	 * [stream_close description]
	 * @return void
	 */
	protected function stream_close()
	{
		$meta = stream_get_meta_data($this->resource);
		$params = stream_context_get_params($this->resource);

		fclose($this->resource);

		list($protocol, $code, $message) = explode(' ', $meta['wrapper_data'][0], 3);
		unset($meta['wrapper_data'][0]);

		// $headers = $this->parseHeaders($params['options']['http']['header']);

		$query_params = [];
		if (array_key_exists('content', $params['options']['http'])) {
			$content = $params['options']['http']['content'];

			$body = is_array($content) ? $content : json_decode($params['options']['http']['content'], true);
			// if (is_array($content)) {
			// 	$body = $content;
			// } else {
			// 	$body = json_decode($params['options']['http']['content'], true);
			// 	// if (!$body = json_decode($params['options']['http']['content'], true)) {
			// 	// 	parse_str($params['options']['http']['content'], $query_params);
			// 	// }
			// }
		} else {
			$body = false;
		}

		switch (strtolower($params['options']['http']['method'])) {
			case 'get':
				parse_str(parse_url($meta['uri'], \PHP_URL_QUERY), $query_params);
				break;
			case 'post':
				if (!$body) {
					parse_str($params['options']['http']['content'], $query_params);
				}
				break;

			default:
				# code...
				break;
		}



		$stream = [
			'request' => [
				'time' => $this->open_time,
				'direct' => 'outcome',
				'method' => $params['options']['http']['method'],
				'headers' => $this->parseHeaders($params['options']['http']['header']),
				'uri' => $meta['uri'],
				'body' => $body,
				'param' => $query_params,
			],
			'response' => [
				'time' => microtime(true),
				'direct' => 'income',
				'protocol' => $protocol,
				'code' => $code,
				'message' => $message,
				'headers' => $this->parseHeaders($meta['wrapper_data']),
				'body' => $this->read_content
			],
		];

		Logger::addStream($stream);
	}

	private function parseHeaders($headers)
	{
		$ret = [];
		foreach ($headers as $key => $header) {
			list ($name, $val) = explode(':', $header, 2);
			$ret[$name] = trim($val);
		}

		return $ret;
	}

	/**
	 * [stream_read description]
	 * @param  int    $count [description]
	 * @return string
	 */
	protected function stream_read($count)
	{
		$content = fread($this->resource, $count);
		$this->read_content .= $content;

		return $content;
	}

	/**
	 * @return bool
	 */
	protected function stream_eof()
	{
		return feof($this->resource);
	}

	/**
	 * [stream_cast description]
	 * @param  int    $cast_as [description]
	 * @return resource
	 */
	protected function stream_cast($cast_as)
	{
		return false;
	}

	/**
	 * @return bool
	 */
	protected function stream_flush()
	{
		return false;
	}

	/**
	 * [stream_lock description]
	 * @param  int    $operation [description]
	 * @return bool
	 */
	protected function stream_lock($operation)
	{
		return flock($this->resource, $operation);
	}

	/**
	 * [stream_metadata description]
	 * @param  string $path   [description]
	 * @param  int    $option [description]
	 * @param  mixed  $value  [description]
	 * @return bool
	 */
	protected function stream_metadata($path, $option, $value)
	{
		switch ($option) {
			case \STREAM_META_TOUCH:
				list($time, $atime) = extract($value);
				return touch($path, $time, $atime);

			case \STREAM_META_OWNER_NAME:
			case \STREAM_META_OWNER:
				return chown($path, $value);

			case \STREAM_META_GROUP_NAME:
			case \STREAM_META_GROUP:
				return chgrp($path, $value);

			case \STREAM_META_ACCESS:
				return chmod($path, $value);
		}

		return false;
	}

	/**
	 * [stream_seek description]
	 * @param  int    $offset [description]
	 * @param  int    $whence [description]
	 * @return bool
	 */
	protected function stream_seek($offset, $whence = SEEK_SET)
	{
		$res = fseek($this->resource, $offset, $whence);
		return !$res;
	}

	/**
	 * [stream_set_option description]
	 * @param  int    $option [description]
	 * @param  int    $arg1   [description]
	 * @param  int    $arg2   [description]
	 * @return bool
	 */
	protected function stream_set_option($option, $arg1, $arg2)
	{
		switch ($option) {
			case \STREAM_OPTION_BLOCKING:
				return stream_set_blocking($this->resource, (bool)$arg1);

			case \STREAM_OPTION_READ_TIMEOUT:
				return stream_set_timeout($this->resource, $arg1, $arg2);

			case \STREAM_OPTION_WRITE_BUFFER:
				return !stream_set_write_buffer($this->resource, $arg2);
		}

		return false;
	}

	/**
	 * @return array
	 */
	protected function stream_stat()
	{
		return fstat($this->resource);
	}

	/**
	 * @return int
	 */
	protected function stream_tell()
	{
		return ftell($this->resource);
	}

	/**
	 * [stream_truncate description]
	 * @param  int    $new_size [description]
	 * @return bool
	 */
	protected function stream_truncate($new_size)
	{
		return ftruncate($this->resource, $new_size);
	}

	/**
	 * [stream_write description]
	 * @param  string $data [description]
	 * @return int
	 */
	protected function stream_write($data)
	{
		$this->write_content .= $data;
		return fwrite($this->resource, $data);
	}



	/**
	 * @return bool
	 */
	protected function dir_closedir()
	{
		return closedir($this->resource);
	}

	/**
	 * [dir_opendir description]
	 * @param  string $path    [description]
	 * @param  int    $options [description]
	 * @return bool
	 */
	protected function dir_opendir($path, $options)
	{
		return (bool)opendir($path, $this->context);
	}

	/**
	 * @return string
	 */
	protected function dir_readdir()
	{
		return readdir($this->resource);
	}

	/**
	 * @return bool
	 */
	protected function dir_rewinddir()
	{
		rewinddir($this->resource);
		return true;
	}

	/**
	 * [mkdir description]
	 * @param  string $path    [description]
	 * @param  int    $mode    [description]
	 * @param  int    $options [description]
	 * @return bool
	 */
	protected function mkdir($path, $mode, $options)
	{
		return mkdir($path, $mode, (bool)$options, $this->resource);
	}

	/**
	 * [rename description]
	 * @param  string $path_from [description]
	 * @param  string $path_to   [description]
	 * @return bool
	 */
	protected function rename($path_from, $path_to)
	{
		return rename($path_from, $path_to, $this->resource);
	}

	/**
	 * [rmdir description]
	 * @param  string $path    [description]
	 * @param  int    $options [description]
	 * @return bool
	 */
	protected function rmdir($path, $options)
	{
		return rmdir($path, $this->resource);
	}

	/**
	 * [unlink description]
	 * @param  string $path [description]
	 * @return bool
	 */
	protected function unlink($path)
	{
		return unlink($path, $this->resource);
	}

	/**
	 * [url_stat description]
	 * @param  string $path  [description]
	 * @param  int    $flags [description]
	 * @return array
	 */
	protected function url_stat($path, $flags)
	{

	}

}