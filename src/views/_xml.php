<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $item stdClass Content object
 */


// var_dump($item);

for ($item->rewind(); $item->valid(); $item->next()):
	$hasChild = $item->hasChildren();
	$empty = (!$hasChild && !strlen(strval($item->current())) ? 'empty' : ''); ?>
	<div class="xml-item <?= ($hasChild ? 'node ' : 'leaf ') . $empty ?>">
		<div class="name"><span><?= $item->key() ?></span></div>
		<div class="value">
			<?php
			if ($hasChild):
				echo $this->renderPartial('_xml', ['item' => $item->current()]);
			else:
				if ($empty) {
					$value = $empty;
				} else {
					$value = strval($item->current());
				} ?>
				<span><?= $value ?></span>
			<?php endif; ?>
		</div>
		<div class="name back"><span><?= $item->key() ?></span></div>
	</div>
<?php endfor; ?>

