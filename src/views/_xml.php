<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $values array XML source
 */

foreach ($values as $key => $item) {
	switch ($item['type']) {
		case 'open':
			$tag = $item['tag'];
			if (isset($item['attributes'])) {
				$attrs = '';
				foreach ($item['attributes'] as $key => $value) {
					$attrs .= " $key:$value";
				}

				$tag .= $attrs;
			}
			?>
			<div class="xml-item node">
				<div class="name"><span><?= $tag ?></span></div>
					<div class="value">
			<?php
			break;
		case 'complete':
			$tag = $item['tag'];
			if (isset($item['attributes'])) {
				$attrs = '';
				foreach ($item['attributes'] as $key => $value) {
					$attrs .= " $key:$value";
				}

				$tag .= $attrs;
			}
			$value = isset($item['value']) ? $item['value'] : ''; ?>
						<div class="xml-item leaf <?= (strlen($value) ? '' : 'empty') ?>">
							<div class="name"><span><?= $tag ?></span></div>
							<div class="value"><span><?= $value ?></span></div>
							<div class="name back"><span><?= $item['tag'] ?></span></div>
						</div>
			<?php
			break;
		case 'close': ?>
					</div>
				<div class="name back"><span><?= $item['tag'] ?></span></div>
			</div>
			<?php
			break;
	}
}