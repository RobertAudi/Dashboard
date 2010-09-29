<?php if (! defined('APPPATH')) exit('Direct access to the individual scripts is not allowed!');

/**
* Server Status Widget
*/
class ServerStatus
{
	/**
	 * Contains information about the url:
	 * - Full url
	 * - Protocol
	 * - Domain extension
	 * - Domain name (without the extension)
	 * - etc.
	 *
	 * @access private
	 * @var array
	 */
	private $url;
	
	/**
	 * The status of the server.
	 *
	 * @access private
	 * @var bool
	 */
	private $status;
	
// ------------------------------------------------------------------------
	
	/**
	 * El Constructor!
	 *
	 * @access public
	 * @author Aziz Light
	 */
	public function __construct($url = '', $title = '')
	{
		if (($url = self::setup_url($url)) === false)
		{
			exit;
		}
		else
		{
			$this->url = $url;
			$this->url['status'] = $this->check();
			$this->url['title'] = (empty($title)) ? $this->getTitle($this->url['full_url']) : $title;
		}
	} // End of __construct
	
// ------------------------------------------------------------------------
	
	/**
	 * Setup getters.
	 *
	 * @author Aziz Light
	 */
	public function __get($property)
	{
		$getter = 'get' . ucfirst(strtolower($property));
		
		if (method_exists($this, $getter))
		{
			return $this->$getter();
		}
	} // End of __get
	
// ------------------------------------------------------------------------
	
	/**
	 * Check the status of the url.
	 *
	 * @access public
	 * @param int $port : The port number.
	 * @param int $timeout : The time after which it will be assumed that the url's status is not "active".
	 * @param int $_min_check_interval : time (in seconds) between every status check. Used to avoid excessive amounts of requests.
	 * @return bool : The satus.
	 * @author Aziz Light
	 */
	public function check($port = 80, $timeout = 15, $_min_check_interval = 1800)
	{
		if ((! is_int($port) && $port < 0) || (! is_int($timeout) && $timeout < 1))
			return false;
		
		$this->status = (bool) @fsockopen($this->url['full_domain'],  $port, $errno, $errstr, $timeout);
		return $this->status;
	} // End of public function check
	
// ------------------------------------------------------------------------
	
	/**
	 * Wrapper for the check method so that $this->status work.
	 *
	 * @access public
	 * @param int $port : The port number.
	 * @param int $timeout : The time after which it will be assumed that the url's status is not "active".
	 * @param int $_min_check_interval : time (in seconds) between every status check. Used to avoid excessive amounts of requests.
	 * @return bool : The satus.
	 * @author Aziz Light
	 */
	private function getStatus($port = 80, $timeout = 15, $_min_check_interval = 1800)
	{
		return $this->check($port, $timeout, $_min_check_interval);
	} // End of public function getStatus
	
// ------------------------------------------------------------------------
	
	/**
	 * - Verify that the url provided is valid
	 * - Seperate the valid url into chuncks
	 * - Create a clean version of the url
	 *
	 * @access private
	 * @static
	 * @param string $url : The url.
	 * @return array : The url array containing url chuncks and other potentially useful stuff.
	 * @author Aziz Light
	 */
	private static function setup_url($url)
	{
		if (! is_string($url))
			return false;
		
		if ((bool) preg_match('/^(?P<url>(?P<protocol>[a-zA-Z]{3,6}:\/\/)?(?P<subdomain>[a-zA-Z\d-]{1,}\.)?(?P<domain>[a-zA-Z\d-]{2,}?){1}(?P<extension>(?:\.[a-zA-Z]{2,4}){1,2})(?:\/)?)$/', $url, $url_chunks))
		{
			// clean the matches array.
			unset($url_chunks[0], $url_chunks[1], $url_chunks[2], $url_chunks[3], $url_chunks[4], $url_chunks[5], $url_chunks[6]);
			
			// create a version of the subdomain without the trailing dot
			$url_chunks['clean_subdomain'] = rtrim($url_chunks['subdomain'], '.');
			
			// create a key that contains the domain and the extension
			$url_chunks['full_domain'] = $url_chunks['domain'] . $url_chunks['extension'];
			
			// create a clean url that will work properly with fsockopen
			$url_chunks['clean_url'] = $url_chunks['subdomain'] . $url_chunks['domain'] . $url_chunks['extension'];
			
			// create an full url link usable in an html anchor.
			$url_chunks['full_url']  = (empty($url_chunks['protocol'])) ? 'http://' : $url_chunks['protocol'];
			$url_chunks['full_url'] .= $url_chunks['clean_url'] . '/';
			
			// create a url-safe version of the domain name
			$url_chunks['safe_domain'] = str_replace('.', '_dot_', $url_chunks['full_domain']);
			
			return $url_chunks;
		}
		else
		{
			return false;
		}
	} // End of private static function setup_url
	
// ------------------------------------------------------------------------
	
	/**
	 * Get the title element from a URL or just return the url title if it has
	 * already been set.
	 * 
	 * @access public
	 * @param string $url : The url from where you want to retrieve the title.
	 * @return $string : The title.
	 * @author Danny Battison - gabehabe@gmail.com modified by Aziz Light
	 */
	public function getTitle($url = '')
	{
		if (isset($this->url['title']))
		{
			return $this->url['title'];
		}
		else
		{
			// we can't treat it as an XML document because some sites aren't valid XHTML
			// so, we have to use the classic file reading functions and parse the page manually.
			$fh = fopen($url, 'r');
			
			// read the first 7500 characters, it's gonna be near the top.
			$str = fread($fh, 7500);
			fclose($fh);
			
			// Extract the title.
			$str2 = strtolower($str);
			$start = strpos($str2, '<title>')+7;
			$len   = strpos($str2, '</title>') - $start;
			
			$title = substr($str, $start, $len);
			
			return $title;
		}
	} // End of public function getTitle
	
} // End of class ServerStatus

/* End of file ServerStatus.php */
/* Location: ./Dashboard/Widgets/ServerStatus.php */