<?php if ( ! defined( 'APPPATH' ) ) exit( 'Direct access to the individual scripts is not allowed!' );

/**
* Listing Widget
* 
* Requires the Helpers class (DashboardHelpers):
* - DashboardHelpers::timespan
* - DashboardHelpers::formatSize
*/
class Listing
{
	/**
	 * The directory seperator; so that Windows user don't start whinning.
	 *
	 * @access private
	 * @static
	 * @var	string
	 */
	private static $directory_seperator = '/';
	
	/**
	 * List of files excluded from the files list that will be generated.
	 *
	 * @access private
	 * @static
	 * @var array
	 */
	private static $excluded_files = array(
		'.',
		'..',
		'.htaccess',
		'.localized',
		'index.php',
		'index.html',
	);
	
	/**
	 * Supported sorting types and their corresponding
	 * comparision method (that will be used with uksort).
	 *
	 * @access private
	 * @static
	 * @var array
	 */
	private $valid_sort_type;
	
// ------------------------------------------------------------------------
	
	/**
	 * The folder which files will be listed.
	 *
	 * @access private
	 * @var string
	 */
	private $folder;
	
	/**
	 * The list of files.
	 *
	 * @access private
	 * @var array
	 */
	private $files;
	
// ------------------------------------------------------------------------
	
	/**
	 * El Constructor!
	 * 
	 * The folder path can be of one of the following forms:
	 * - an absolute path (starting with /)
	 * - a path relative to the server root (starting with the folder name)
	 * - a path relative to the index.php file (starting with ./)
	 * 
	 * @access public
	 * @param string $folder : The folder which files will be listed.
	 */
	public function __construct( $folder )
	{
		if ( ( $folder = $this->setup_folder( $folder ) ) === false )
		{
			exit;
		}
		else
		{
			$this->folder = $folder;
			$this->files = array();
			
			$this->valid_sort_type = array(
				'lastmodified' => array( $this, 'compare_file_modification_date' ), // <- line 44
				'alphabetical' => 'strnatcasecmp',
				'size' => array( $this, 'compare_file_size' )
			);
		}
	} // End of __construct
	
// ------------------------------------------------------------------------
	
	/**
	 * Get a list of files.
	 *
	 * @access public
	 * @param int $num : The number of files to list. 0 means all the files.
	 * @param bool $new_list : If the user want to get two lists from the same instance, he can ask to use the list previously generated instead of generating a new list.
	 * @return bool|array : List of files. False on error.
	 * @author Aziz Light
	 */
	public function getFiles( $num = 0, $order = 'lastmodified', $new_list = true )
	{
		// What the fuck does return false mean?
		// FIXME: throw an exception, or log an error message or something...
		if ( ! is_int( $num ) || ! is_bool( $new_list ) )
			return false;
		
		if ( $new_list === false && isset( $this->files ) && count( $this->files >= $num ) )
			return array_slice( $this->files, 0, $num );
		
		if ( $handle = opendir( $this->folder ) )
		{
			while ( ( $file = readdir( $handle ) ) !== false )
			{
				if ( ! in_array( $file, self::$excluded_files ) && ! is_dir( $this->folder . $file ) )
				{
					$this->files[$file] = array(
						'filename'          => $file,
						'filepath'          => $this->folder,
						'fullpath'          => $this->folder . $file,
						'modification_date' => DashboardHelpers::timespan( filemtime( $this->folder . $file ) ),
						'size'              => DashboardHelpers::formatSize( filesize( $this->folder . $file ) ),
					);
				}
			}
			closedir( $handle );
		}
		
		if ( ! $this->order_files( $order ) )
		{
			// TODO: Do something to notify the user that the sort operation failed.
		}
		return array_slice( $this->files, 0, $num );
	} // End of public function getFiles
	
// ------------------------------------------------------------------------
	
	/**
	 * Check and setup the provided folder (path)
	 * to make sure it's valid.
	 *
	 * @access private
	 * @param string $folder : The folder (path).
	 * @return bool|string : The edited folder path. False on error.
	 * @author Aziz Light
	 */
	private function setup_folder( $folder )
	{
		if ( ! is_string( $folder ) )
			return false;
		
		// Add a trailing slash if there isn't one.
		if ( substr( $folder, -1 ) != self::$directory_seperator )
			$folder .= self::$directory_seperator;
		
		// If the folder path is absolute or relative to the current location, don't touch it.
		// Else, make it relative to the server root.
		if ( substr( $folder, 0, 1 ) != self::$directory_seperator && substr( $folder, 0, 2 ) != '.' . self::$directory_seperator )
			$folder = $_SERVER['DOCUMENT_ROOT'] . self::$directory_seperator . $folder;
		
		if ( is_dir( $folder ) )
			return $folder;
		
		return false;
	} // End of private function setup_folder
	
// ------------------------------------------------------------------------
	
	/**
	 * Order the files array using one of the comparision methods.
	 *
	 * @access private
	 * @param string $sort_type : Crieria by which the array will be sorted. Needs to be a valid sort type.
	 * @param bool $reverse_order : Reverse the sort order.
	 * @return bool : Boolean relative to the success of the operation.
	 * @author Aziz Light
	 */
	private function order_files( $sort_type = 'lastmodified', $reverse_order = false )
	{
		if ( ! array_key_exists( $sort_type, $this->valid_sort_type ) )
			return false;
		
		if ( uksort( $this->files, $this->valid_sort_type[$sort_type] ) )
		{
			if ( $reverse_order === true )
				$this->files = array_reverse( $this->files );
			return true;
		}
		else
		{
			return false;
		}
	} // End of private function order_files
	
// ------------------------------------------------------------------------
	
	/**
	 * Comparision method.
	 * Used to sort an array of files by modification date.
	 *
	 * @access private
	 * @author Aziz Light
	 */
	private function compare_file_modification_date( $a, $b )
	{
		$a_modification_date = filemtime( $this->folder . $a );
		$b_modification_date = filemtime( $this->folder . $b );
		
		if ( $a_modification_date == $b_modification_date )
			return 0;
		return ( $a_modification_date < $b_modification_date ) ? 1: -1;
	} // End of private function compare_file_modification_date
	
// ------------------------------------------------------------------------
	
	/**
	 * Comparision method.
	 * Used to sort an array of files by size.
	 * This method should also work for big files (between 2GB and 4GB).
	 *
	 * From php.net:
	 * 
	 * Because PHP's integer type is signed and many
	 * platforms use 32bit integers, filesize() may
	 * return unexpected results for files which are
	 * larger than 2GB. For files between 2GB and 4GB
	 * in size this can usually be overcome by using.
	 * 
	 *     sprintf("%u", filesize($file)).
	 * 
	 * @access private
	 * @author Aziz Light
	 */
	private function compare_file_size( $a, $b )
	{
		$a_size = sprintf( "%u", filesize( $this->folder . $a ) );
		$b_size = sprintf( "%u", filesize( $this->folder . $b ) );
		
		if ( $a_size == $b_size )
			return 0;
		
		return ( $a_size > $b_size ) ? -1: 1;
	} // End of private function compare_file_size
	
} // End of class Listing

/* End of file Listing.php */
/* Location: ./Dashboard/Widgets/Listing.php */