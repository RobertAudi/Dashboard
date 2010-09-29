<?php if (! defined('APPPATH')) exit('Direct access to the individual scripts is not allowed!');

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
	public function __construct($logfile)
	{
		if (($logfile = $this->setup_logfile($logfile)) === false)
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
	
	// Modified version of: http://mydebian.blogdns.org/?p=197
	public function getLogs($lines = 10)
	{
		if ((int) $lines <= 0)
		{
			unset($lines);
			$lines = 10;
		}
		
		$handle = @fopen($this->logfile, "r");
		if (!$handle)
		{
			throw new Exception('Failed to open the logfile (' . $this->logfile . ')');
			exit;
		}
		
		flock($handle, LOCK_SH);
		
		// get the file size with a trick
		fseek($handle, 0, SEEK_END);
		$filesize = ftell($handle);
		
		$bufferlength = 5000;
		$position = - min($bufferlength, $filesize); // don't get past the start-of-file
		
		while ($lines > 0)
		{
			if ($err = fseek($handle, $position, SEEK_END))
			{
				throw new Exception('Something went wrong while reading the log file');
				
				flock($handle, LOCK_UN);
				fclose($handle);
				
				return $this->logs;
			}
			
			// get some line from the logfile
			unset($buffer, $temp_lines);
			$buffer = fread($handle, $bufferlength);
			$temp_lines = explode("\n", $buffer);
			
			if (isset($first) && $first != '')
				$temp_lines[count($temp_lines) - 1] .= $first;
			
			// extract the first retrieved line because it might not be complete.
			unset($first, $num_lines);
			$first = array_shift($temp_lines);
			
			$num_lines = count($temp_lines); // store the number of lines retrieved
			
			if (empty($temp_lines[$num_lines - 1]))
			{
				array_pop($temp_lines);
				$num_lines--;
			}
			
			if ($num_lines >= $lines)
			{
				$this->logs = array_merge(array_slice($temp_lines, $num_lines - $lines + 1), $this->logs);
				$lines -= $num_lines;
			}
			elseif (-$position >= $filesize)
			{
				array_unshift($temp_lines, $first);
				$this->logs = array_merge($temp_lines, $this->logs);
				$lines = $num_lines - $lines;
			}
			else
			{
				$this->logs = array_merge($temp_lines, $this->logs);
				
				$lines    -= $num_lines;
				$position = - min($position - $bufferlength, $filesize);
			}
		}
		
		flock($handle, LOCK_UN);
		fclose($handle);
		
		return $this->logs;
	} // End of getLogs
	
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
	private function setup_logfile($logfile)
	{
		if (! is_string($logfile))
			return false;
		
		$bookmarks_file = APPPATH . '/data/logfiles.ini';
		if (file_exists($bookmarks_file))
			$bookmarks = parse_ini_file($bookmarks_file);
		
		if (array_key_exists($logfile, $bookmarks))
			return $bookmarks[$logfile];
		
		// If the logfile path is absolute or relative to the current location, don't touch it.
		// Else, make it relative to the server root.
		if (substr($logfile, 0, 1) != '/' && substr($logfile, 0, 2) != './')
			$logfile = $_SERVER['DOCUMENT_ROOT'] . '/' . $logfile;
		
		if (is_readable($logfile))
			return $logfile;
		
		return false;
	} // End of private function setup_logfile
	
} // End of class Logfile

/* End of file Logfile.php */
/* Location: ./Dashboard/Widgets/Logfile.php */