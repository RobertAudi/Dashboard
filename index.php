<?php
require_once __DIR__ . '/Dashboard/bootstrap.php';

$sandboxFiles = new Listing($_SERVER['DOCUMENT_ROOT']);
$list = $sandboxFiles->get_files(8, array('recursive_size' => true));

include_once "./Dashboard/views/main.php";