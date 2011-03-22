<?php
require_once __DIR__ . '/Dashboard/bootstrap.php';

$sandboxFiles = new Listing($_SERVER['DOCUMENT_ROOT']);
$list = $sandboxFiles->get_files(8, array('recursive_size' => true));
?>

<ul class="files">
	<?php foreach ($list as $file): ?>
		<li class="file"><a href="<?php echo $file['url']; ?>"><?php echo $file['filename'] ?></a> <small>(<?php echo $file['size']; ?>) <?php echo $file['modification_date'] ?></small></li>
	<?php endforeach ?>
</ul>

<!-- ------------------------------------------------------------------------ -->
<hr />
<!-- ------------------------------------------------------------------------ -->

<?php

$reader = new Logfile('php');
// $reader = new Logfile('/Users/aziz/Sites/samples/logfile.log');
$logs = $reader->getLogs(20);

?>

<ul class="logs">
	<?php foreach ($logs as $log): ?>
		<!-- TODO: Remove pre and code tags and add css styling -->
		<li><pre><code><?php echo $log; ?></code></pre></li>
	<?php endforeach ?>
</ul>

<!-- ------------------------------------------------------------------------ -->
<hr />
<!-- ------------------------------------------------------------------------ -->

<?php $site = new ServerStatus('http://azizlight.me/', 'Aziz, Light!'); ?>
<?php if ($site->status): ?>
	<p><?php echo $site->title . ' is up!' ?></p>
<?php else: ?>
	<p><?php echo $site->title . ' is down!' ?></p>
<?php endif ?>