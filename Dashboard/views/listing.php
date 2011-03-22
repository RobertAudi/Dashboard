<ul class="files">
	<?php foreach ($list as $file): ?>
		<li class="file"><a href="<?php echo $file['url']; ?>"><?php echo $file['filename'] ?></a> <small>(<?php echo $file['size']; ?>) <?php echo $file['modification_date'] ?></small></li>
	<?php endforeach ?>
</ul>
