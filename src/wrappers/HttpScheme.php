<?php
namespace buildok\logger\wrappers;

use buildok\logger\wrappers\base\StreamWrapper;

/**
 * HttpScheme class
 */
class HttpScheme extends StreamWrapper
{

	public function scheme()
	{
		return 'http';
	}



}