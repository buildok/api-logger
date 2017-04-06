<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $item stdClass Content object
 */


foreach ($item as $key => $value): ?>
	<div class="json-item <?= gettype($value) ?>">
		<div class="name"><span><?= $key ?></span></div>
		<div class="value">
			<?php
			if (is_scalar($value)): ?>
				<span><?= $value ?></span>
			<?php else:
				echo $this->renderPartial('_json', ['item' => $value]);
			endif; ?>
		</div>
	</div>
<?php endforeach; ?>


