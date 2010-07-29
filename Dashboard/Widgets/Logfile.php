<?php if ( ! defined( 'APPPATH' ) ) exit( 'Direct access to the individual scripts is not allowed!' );

/**
* Logfile Widget
*/
class Logfile
{
	/**
	 * The logfile which logs will be displayed.
	 *
	 * @access private
	 * @var string
	 */
	private $logfile;
	
	/**
	 * The logs.
	 *
	 * @access private
	 * @var array
	 */
	private $logs;
	
// ------------------------------------------------------------------------
	
	/**
	 * El Constructor!
	 *
	 * @access public
	 * @author Aziz Light
	 */
	public function __construct( $logfile )
	{
		if ( ( $logfile = $this->setup_logfile( $logfile ) ) === false )
		{
			exit;
		}
		else
		{
			$this->logfile = $logfile;
			$this->logs = array();
		}
	} // End of __construct
	
// ------------------------------------------------------------------------
	
	/**
	 * Check and setup the provided logfile (path)
	 * to make sure it's valid.
	 *
	 * @access private
	 * @param string $logfile : The logfile (path).
	 * @return bool|string : The edited logfile path. False on error.
	 * @author Aziz Light
	 */
	private function setup_logfile( $logfile )
	{
		if ( ! is_string( $logfile ) )
			return false;
		
		// If the logfile path is absolute or relative to the current location, don't touch it.
		// Else, make it relative to the server root.
		if ( substr( $logfile, 0, 1 ) != self::$directory_seperator && substr( $logfile, 0, 2 ) != '.' . self::$directory_seperator )
			$logfile = $_SERVER['DOCUMENT_ROOT'] . self::$directory_seperator . $logfile;
		
		if ( is_readable( $logfile ) )
			return $logfile;
		
		return false;
	} // End of private function setup_logfile
	
} // End of class Logfile

/* End of file Logfile.php */
/* Location: ./Dashboard/Widgets/Logfile.php */