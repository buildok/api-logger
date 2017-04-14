<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $type string Content type
 * @var $body string Content
 */


switch (strtolower($type)) {
	case 'json':
		echo $this->renderPartial('_json', ['item' => json_decode($body)]);
		break;
	case 'xml':
		list ($hdr, ) = explode("\n", $body);
		echo '<div class="xml-hdr">', htmlentities($hdr), '</div>';
		echo $this->renderPartial('_xml', ['item' => new \SimpleXMLIterator($body)]);
		break;

	default:
		echo htmlentities($body);
		break;
}

?>


