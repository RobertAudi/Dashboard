<?php

require_once './Dashboard/bootstrap.php';

$sandboxFiles = new Listing( 'Sandbox/php' );
$list = $sandboxFiles->getFiles(12);

$pics = new Listing('/Users/aziz/Music/iTunes/iTunes Music/Music/Compilations/Gilles Peterson Digs America Vol.2');
$list2 = $pics->getFiles(3, 'size');

// fo($list, '<br />SandboxFiles'); // DEBUG <-
// fo($list2, '<br />Pics'); // DEBUG <-

$siteStatus = new ServerStatus( 'http://www.google.com' );
// $siteStatus2 = new ServerStatus( 'php.net' );

// foo($siteStatus->status, '$siteStatus->status'); // DEBUG <-

// foo( $siteStatus->getTitle('http://www.google.com.lb/') );


// foo( $siteStatus->check() ); // DEBUG <-