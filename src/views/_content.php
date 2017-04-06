<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $type string Content type
 * @var $body string Content
 */


switch (strtolower($type)) {
	case 'json':
		$content = json_decode($body);
		echo $this->renderPartial('_json', ['item' => $content]);
		break;


	default:
		echo htmlentities($body);
		break;
}

?>


