<?php if ( ! defined( 'APPPATH' ) ) exit( 'Direct access to the individual scripts is not allowed!' );

/**
* Helpers
*/
class DashboardHelpers
{
	private function __construct() {}
	
// ------------------------------------------------------------------------
	
	/**
	 * Converts a date to a relative timespan.
	 * Examples:
	 *      just now
	 *      a couple seconds ago
	 *      42 minutes ago
	 *      3 hours ago
	 *      4 days ago
	 * 
	 * FIXME: Unrelated to the project. The doc Textmate command returned an error. It's probably due to the default value of $old_dates_format.
	 *
	 * @access public
	 * @static
	 * @param $date : The date to relativise. Can be either a timestamp or a string recognized by the strtotime PHP function.
	 * @param $old_dates_format : The format string that will be used for dates older than a year.
	 * @return string
	 * @author Yamil Urbina modified by Aziz Light
	 */
	public static function timespan( $date = null, $old_dates_format = '\o\n F jS, Y' )
	{
		if ( empty( $date ) )
			return 'just now';
		elseif ( ! is_int( $date ) )
			$date = strtotime( $date );
		
		// get the time difference...
		$time = time() - $date;
		
		// ...and seperate it in minutes, hours, etc.
		$minutes = floor( $time / 60 );
		$hours = floor( $minutes / 60 );
		$days = floor( $hours / 24 );
		$weeks = floor( $days / 7 );
		$months = floor( $weeks / 4 );
		
		$nice_time = 'about ';
		
		if ( $time < 60 )
		{
				$nice_time = 'a couple seconds ago';
		}
		elseif ( $minutes < 60 )
		{
			$nice_time .= ( $minutes == 1 ) ? 'a minute ago' : $minutes . ' minutes ago';
		}
		elseif ( $hours < 24 )
		{
			$nice_time .= ( $hours == 1 ) ? 'an hour ago' : $hours . ' hours ago';
		}
		elseif ( $days < 7 )
		{
			$nice_time .= ( $days ) == 1 ? '1 day ago' : $days . ' days ago';
		}
		elseif ( $weeks < 5 )
		{
			$nice_time .= ( $weeks == 1 ) ? '1 week ago' : $weeks . ' weeks ago';
		}
		elseif ( $months < 12 )
		{
			$nice_time .= ( $months == 1 ) ? '1 month ago' : $months .' months ago';
		}
		else
		{
			$nice_time = date( $old_dates_format, $date );
		}
		
		return $nice_time;
	} // End of public static function timespan
	
// ------------------------------------------------------------------------
	
	/**
	 * Format the size of a file.
	 *
	 * @access public
	 * @static
	 * @param int $size : The file size in bytes.
	 * @return string : The formated file size.
	 * @author biolanor at googlemail dot com modified by Aziz Light
	 * @link http://www.php.net/manual/en/function.filesize.php#98981
	 */
	public static function formatSize( $size )
	{
 		$sizes = array( ' bytes', ' kb', ' mb', ' gb' );
		return ( $size == 0 ) ? 'n/a' : round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . $sizes[$i];
	}
	
} // End of class DashboardHelpers

/* End of file Helpers.php */
/* Location: ./Dashboard/classes/Helpers.php */