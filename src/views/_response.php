<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $item Object of stdClass
 */

?>

<div class="general">
	<span class="protocol"><?= $item->protocol; ?></span>
	<span class="code"><?= $item->code; ?></span>
	<span class="message"><?= $item->message; ?></span>
</div>

<ul class="list headers">
	<span class="caption">headers</span>
	<?php
	$c_type = 'Undefined content type';
	foreach($item->headers as $header => $value):
		if (strcasecmp($header, 'Content-Type') == 0) {
			list ($c_type) = explode(';', $value);
			list (, $c_type) = explode('/', $c_type);
		} ?>
		<li class="item">
			<span class="caption"><?= $header; ?></span>
			<span class="value"><?= $value; ?></span>
		</li>
	<?php endforeach; ?>
</ul>

<?php
if ($item->body): ?>
	<div class="content-wrapper">
		<div class="content-type">
			<span class="caption">content</span>
			<span class="value"><?= $c_type; ?></span>
		</div>
		<div class="content-body">
			<?= $this->renderPartial('_content', ['type' => $c_type, 'body' => $item->body]); ?>
		</div>
	</div>
<?php endif;
