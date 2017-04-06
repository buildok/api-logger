<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $item Object of stdClass
 */

?>
<div class="error-body">
	<div class="error-type <?= $item->type; ?>">
		<span><?= $item->type; ?></span>
	</div>
	<div class="info">
		<div class="message">
			<span class="caption">message</span>
			<span class="value"><?= $item->message; ?></span>
		</div>
		<div class="file">
			<span class="caption">file</span>
			<span class="value"><?= $item->file; ?></span>
		</div>
		<div class="line">
			<span class="caption">line</span>
			<span class="value"><?= $item->line; ?></span>
		</div>
	</div>

	<?php
	if (property_exists($item, 'trace')): ?>
		<div class="trace">
			<span class="caption">trace</span>

			<?php foreach ($item->trace as $key => $entry): ?>
				<div class="entry">
					<span class="step"><?= $key ?></span>
					<span class="value"><?= $entry->file; ?></span>
					<span class="value line"><?= $entry->line; ?></span>
					<span class="function">
						<span class="value"><?= $entry->function; ?></span>
						<span class="args">
							<?php
							if ($entry->args) {
								foreach ($entry->args as $val) {
									echo $this->renderPartial('_variable', ['item' => $val]);
								}
							} ?>
						</span>
					</span>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif;
	if (property_exists($item, 'context')):
		$context = '';
		foreach ($item->context as $key => $item) {
			$context .= $this->renderPartial('_variable', ['item' => $item]);
		}?>

		<div class="context">
			<span class="caption">local scope</span>
			<div><?= $context; ?></div>
		</div>
	<?php endif; ?>
</div>

<?php
// $scope = <<<SCOPE
// {
// 	"direct":"php",
// 	"time":1490003163.7169,
// 	"type":"notice",
// 	"message":"Undefined variable: misstake",
// 	"file":"\/source\/some.php",
// 	"line":5,

// 	"context":[
// 		{
// 			"name":"res",
// 			"type":"string",
// 			"value":"$context"
// 		},
// 		{
// 			"name":"bool",
// 			"type":"boolean",
// 			"value":"false"
// 		},
// 		{
// 			"name":"int",
// 			"type":"integer",
// 			"value":45
// 		},
// 		{
// 			"name":"double",
// 			"type":"float",
// 			"value":1.2323
// 		},
// 		{
// 			"name":"str",
// 			"type":"string",
// 			"value":"qwerty"
// 		},
// 		{
// 			"name":"arr",
// 			"type":"array",
// 			"value":[
// 				{
// 					"name":0,
// 					"type":"string",
// 					"value":"one"
// 				},
// 				{
// 					"name":1,
// 					"type":"string",
// 					"value":"two"
// 				},
// 				{
// 					"name":"ind",
// 					"type":"string",
// 					"value":"assoc"
// 				},
// 				{
// 					"name":"nest",
// 					"type":"array",
// 					"value":[
// 						{"name":0,"type":"integer","value":1},
// 						{"name":1,"type":"integer","value":2},
// 						{"name":2,"type":"integer","value":3},
// 						{"name":3,"type":"integer","value":4}
// 					]
// 				},
// 				{
// 					"name":"qq",
// 					"type":"array",
// 					"value":"empty"
// 				}
// 			]
// 		}
// 	]
// }
// SCOPE;

?>