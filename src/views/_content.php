<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $type string Content type
 * @var $body string Content
 */

switch (strtolower($type)) {
	case 'json':
		if (is_string($body)) {
			$body = json_decode($body);
		}
		echo $this->renderPartial('_json', ['item' => $body]);
		break;
	case 'xml':
		$values = [];

		$p = xml_parser_create();
		xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($p, $body, $values);
		xml_parser_free($p);

		echo $this->renderPartial('_xml', ['values' => $values]);
		break;
	case 'x-www-form-urlencoded':

		break;

	default:
		echo htmlentities($body);
		break;
}