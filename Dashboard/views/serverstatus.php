<?php $site = new ServerStatus('http://azizlight.me/', 'Aziz, Light!'); ?>

<?php if ($site->status): ?>
	<p><?php echo $site->title . ' is up!' ?></p>
<?php else: ?>
	<p><?php echo $site->title . ' is down!' ?></p>
<?php endif ?>