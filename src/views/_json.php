<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $item stdClass Content object
 */


foreach ($item as $key => $value):
	$type = gettype($value);
	$v = ($type == 'object') ? (array)$value : $value;
	$empty = ((bool)$v ? false : 'empty'); ?>
	<div class="json-item <?= $type ?> <?= $empty ?>">
		<div class="name"><span><?= $key ?></span></div>
		<div class="value">
			<?php
			if (is_scalar($value) || is_null($value)): ?>
				<span><?= $value ?></span>
			<?php else:
				echo $this->renderPartial('_json', ['item' => $value]);
			endif; ?>
		</div>
	</div>
<?php endforeach; ?>


