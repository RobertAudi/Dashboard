<?php

require_once './Dashboard/bootstrap.php';

include_once '/Users/aziz/Sources/aziz/git/Foo/Foo.php';  // DEBUG <-

$sandboxFiles = new Listing('sandbox/php');
$list = $sandboxFiles->getFiles(8);

foc($list); // DEBUG <-
?>

<ul>
	<?php foreach ($list as $file): ?>
		<li><a href="<?php echo $file['url']; ?>"><?php echo $file['filename'] ?></a> <small><?php echo $file['modification_date'] ?></small></li>
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

<?php $site = new ServerStatus('http://blog.azizlight.me/', 'Aziz, Light!'); ?>
<?php if ($site->status): ?>
	<p><?php echo $site->title . ' is up!' ?></p>
<?php else: ?>
	<p><?php echo $site->title . ' is down!' ?></p>
<?php endif ?>