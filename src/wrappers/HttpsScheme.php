<?php
namespace buildok\logger\wrappers;

use buildok\logger\wrappers\base\StreamWrapper;

/**
 * HttpScheme class
 */
class HttpsScheme extends StreamWrapper
{

	public function scheme()
	{
		return 'https';
	}



}