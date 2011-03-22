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