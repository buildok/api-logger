<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $item stdClass object of variable data
 */

?>



<div class="variable-data <?= $item->type ?>">
	<?php
	if (property_exists($item, 'name')): ?>
		<span class="name"><?= $item->name ?></span>
	<?php endif; ?>
	<span class="value <?= $item->type ?>">
		<?php
		if (property_exists($item, 'classname')): ?>
			<span class="class-name"><?= $item->classname ?></span>
		<?php endif; ?>

		<span class="type"><?= $item->type ?></span>


		<?php
		if ($item->type == 'object' || $item->type == 'array'):
			$values = $item->value;
			$str_value = '';
			foreach ($values as $key => $value) {
				$str_value .= $this->renderPartial('_variable', ['item' => $value]);
			} ?>
			<span class="var-value"><?= $str_value ?></span>
		<?php else: ?>
			<span class="var-value"><?= $item->value ?></span>
		<?php endif; ?>
	</span>

</div>

