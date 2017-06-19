<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $records Array of log rows
 */

foreach ($records as $key => $row):
	if ($item = json_decode($row['json'])):
		if ($item->direct == 'php') {
			$action = 'error';
		} else {
			$action = (property_exists($item, 'method')) ? 'request' : 'response';
		} ?>

		<div id="<?= $row['id'] ?>" class="log-item on <?= implode(' ', [$item->direct, $action]); ?>">
			<div class="item-caption">
				<span class="direct"><?= $item->direct; ?></span>
				<span class="action"><?= $action; ?></span>
			</div>

       		<?php
       		switch ($action) {
       			case 'error':
       				$part = $this->renderPartial('_error', ['item' => $item]);
       				break;
       			case 'request':
       				$part = $this->renderPartial('_request', ['item' => $item]);
       				break;
   				case 'response':
       				$part = $this->renderPartial('_response', ['item' => $item]);
       				break;
       		}

       		echo $part;

			$dt = \DateTime::createFromFormat('U.u', $item->time);
			?>

			<div class="item-date">
				<span class="date-day"><?= $dt->format('D, d M Y'); ?></span>
				<span class="date-time"><?= $dt->format('H:i:s.u'); ?></span>
			</div>
		</div>
	<?php endif; ?>
<?php endforeach; ?>