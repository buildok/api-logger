<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $records Array of log rows
 */

$dt_0 = null;

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
			$dt_0 || $dt_0 = $dt;

			$mc = (float)$dt->format('s.u');
			$mc_0 = (float)$dt_0->format('s.u');
			$diff = number_format($mc - $mc_0, 6, '.', ' ');
			// var_dump($item);
			?>

			<div class="item-date">
				<span class="date-day"><?= $dt->format('D, d M Y'); ?></span>
				<span class="date-time"><?= $dt->format('H:i:s'); ?></span>
				<span class="date-diff"><?= $diff; ?></span>
			</div>
		</div>
		<?php //$dt_0 = $dt; ?>
	<?php endif; ?>
<?php endforeach; ?>