<?php
/**
 * @var $this Object of buildok\logger\View
 * @var $item Object of stdClass
 */

?>

<div class="general">
	<div class="url">
		<span class="caption">request url</span>
		<span class="value"><?= $item->uri; ?></span>
	</div>

	<div class="method">
		<span class="caption">method</span>
		<span class="value"><?= $item->method; ?></span>

	</div>

	<?php
	if (property_exists($item, 'ajax') && $item->ajax): ?>
		<div class="ajax">
			<span>ajax</span>
		</div>
	<?php endif; ?>


	<?php
	if ($item->direct == 'income'): ?>
		<div class="remote-addr">
			<span class="caption">remote address</span>
			<span class="value"><?= (property_exists($item, 'port') ? implode(':', [$item->ip, $item->port]) : $item->ip); ?></span>
		</div>
	<?php endif; ?>
</div>

<?php
if ($item->param): ?>
	<ul class="list params">
		<span class="caption"><?= 'parameters'; ?></span>
		<?php
		foreach($item->param as $name => $value): ?>
			<li class="item">
				<span class="caption"><?= $name; ?></span>
				<span class="value"><?= $value; ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<ul class="list headers">
	<span class="caption"><?= 'headers'; ?></span>
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
	<div class="content-type"><span class="caption"><?= $c_type; ?></span></div>
	<div class="content-wrapper">
		<?= $this->renderPartial('_content', ['type' => $c_type, 'body' => $item->body]); ?>
	</div>
<?php endif; ?>