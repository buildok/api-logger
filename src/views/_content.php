<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $type string Content type
 * @var $body string Content
 */

$s = '<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/"><S:Body><ns2:addWithdrawEntry xmlns:ns2="http://ws.platform.commersite.com/"><accountEntryPlatformRequest><clientPid>65496</clientPid><serverPid>50092</serverPid><callerContextId>61800408</callerContextId><accountEntryDetailed><accountEntry><account>0</account><accountEntryType>SRTB</accountEntryType><amount>-80</amount><codice>0</codice><creationDate>2017-06-15T16:00:22.490+02:00</creationDate><currency>TRY</currency><description>{&quot;gameId&quot;:&quot;mOracle41&quot;}</description><extraCodice>0</extraCodice></accountEntry></accountEntryDetailed><contextId>6603692</contextId><kind>2</kind><originatingPid>65496</originatingPid><partyOriginatingUid>20523</partyOriginatingUid><sourceContextId>61800408</sourceContextId><transactionUid>20933967832</transactionUid><relatedTransUid>0</relatedTransUid><sessionToken>8f5a9a5b-adb3-454b-99ae-ac4960717bb4</sessionToken></accountEntryPlatformRequest></ns2:addWithdrawEntry></S:Body></S:Envelope>';

switch (strtolower($type)) {
	case 'json':
		echo $this->renderPartial('_json', ['item' => json_decode($body)]);
		break;
	case 'xml':


		// var_dump($xml);
		// list ($hdr, ) = explode("\n", $body);
		// $body = str_ireplace($hdr, '', $body);
		// var_dump($body);
		$xml = new SimpleXMLIterator($s);
		// echo '<div class="xml-hdr">', htmlentities($hdr), '</div>';
		echo $this->renderPartial('_xml', ['item' => $xml]);
		break;

	default:
		echo htmlentities($body);
		break;
}

?>


