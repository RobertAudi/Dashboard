<?php

date_default_timezone_set('Europe/Paris');

// -Dubug-Mode-------------------------------------------------------------
include_once '/Users/aziz/Sources/aziz/git/Foo/Foo.php';  // DEBUG <-
error_reporting( -1 );
// ------------------------------------------------------------------------

// -Application-Constants--------------------------------------------------
define( 'APPPATH', dirname( __FILE__ ) );
define( 'WIDGETDIR', APPPATH . '/Widgets' );
// ------------------------------------------------------------------------

// -Autoloader-------------------------------------------------------------
require_once APPPATH . '/classes/Autoloaders.php';
include_once APPPATH . '/classes/Helpers.php';
spl_autoload_extensions( 'widget=>.php,class=>.class.php' );
spl_autoload_register( array( 'WidgetAutoloader', 'autoloader' ) );
// ------------------------------------------------------------------------

/* End of file bootstrap.php */
/* Location: ./Dashboard/bootstrap.php */