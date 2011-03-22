<?php if (! defined('APPPATH')) exit('Direct access to the individual scripts is not allowed!');

/**
* Helpers
*/
class DashboardHelpers
{
    // Prevent the user from instantiating helpers.
    private function __construct()
    {
        // Blow up!
        throw new RuntimeException("Can't instantiate helpers !!!");
        exit;
    }
    
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
    public static function timespan($date = null, $old_dates_format = '\o\n F jS, Y')
    {
        if (empty($date))
            return 'just now';
        elseif (! is_int($date))
            $date = strtotime($date);
        
        // get the time difference...
        $time = time() - $date;
        
        // ...and seperate it in minutes, hours, etc.
        $minutes = floor($time / 60);
        $hours = floor($minutes / 60);
        $days = floor($hours / 24);
        $weeks = floor($days / 7);
        $months = floor($weeks / 4);
        
        $nice_time = '';
        
        if ($time < 60)
        {
                $nice_time = 'just now';
        }
        elseif ($minutes < 60)
        {
            $nice_time .= ($minutes == 1) ? 'a minute ago' : $minutes . ' minutes ago';
        }
        elseif ($hours < 24)
        {
            $nice_time .= ($hours == 1) ? 'an hour ago' : $hours . ' hours ago';
        }
        elseif ($days < 7)
        {
            $nice_time .= ($days) == 1 ? '1 day ago' : $days . ' days ago';
        }
        elseif ($weeks < 5)
        {
            $nice_time .= ($weeks == 1) ? '1 week ago' : $weeks . ' weeks ago';
        }
        elseif ($months < 12)
        {
            $nice_time .= ($months == 1) ? '1 month ago' : $months .' months ago';
        }
        else
        {
            $nice_time = date($old_dates_format, $date);
        }
        
        return $nice_time;
    } // End of public static function timespan
    
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
    public static function format_file_size($size)
    {
         $sizes = array(' bytes', ' kb', ' mb', ' gb');
        return ($size == 0) ? 'n/a' : round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i];
    }
    
    /**
     * get_all_files and get_highest_file_timestamp are used to get replicate a recursive filemtime function.
     *
     * @author info at daniel-marschall dot de modified by Aziz Light
     * @link http://www.php.net/manual/en/function.filemtime.php#91665
     */
    private static function get_all_files($directory, $recursive = true)
    {
        $result = array();
        $handle =  opendir($directory);
        while ($datei = readdir($handle))
        {
           if (($datei != '.') && ($datei != '..'))
           {
               $file = $directory.'/'.$datei;
               if (is_dir($file))
               {
                   if ($recursive)
                   {
                       $result = array_merge($result, self::get_all_files($file.'/'));
                   }
               }
               else
               {
                   $result[] = $file;
               }
           }
        }
        closedir($handle);
        return $result;
    } // End of get_all_files
    
    /**
     * get_all_files and get_highest_file_timestamp are used to get replicate a recursive filemtime function.
     *
     * @author info at daniel-marschall dot de modified by Aziz Light
     * @link http://www.php.net/manual/en/function.filemtime.php#91665
     */
    public static function get_highest_file_timestamp($directory, $recursive = true)
    {
        $allFiles = self::get_all_files($directory, $recursive);
        $highestKnown = 0;
        foreach ($allFiles as $val)
        {
            $currentValue = filemtime($val);
            if ($currentValue > $highestKnown)
            {
                $highestKnown = $currentValue;
            }
        }
        
        return $highestKnown;
    } // End of get_highest_file_timestamp
    
    /**
     * Check if dir is empty
     *
     * @return bool
     * @author jasoneisen at gee mail modified by Aziz Light
     * @link http://www.php.net/manual/en/function.is-dir.php#85428
     */
    public static function is_empty_dir($dir)
    {
        return (($files = @scandir($dir)) && count($files) <= 2);
    } // End of is_empty_dir
    
    /**
     * Get Filesize of a directory recursively
     *
     * @return int
     * @author lixlpixel
     * @link http://lixlpixel.org/recursive_function/php/get_size_recursively/
     */
    public static function recursive_directory_size($directory)
    {
        $size = 0;
        if(substr($directory,-1) == '/')
        {
            $directory = substr($directory,0,-1);
        }
        if(!file_exists($directory) || !is_dir($directory) || !is_readable($directory))
        {
            return -1;
        }
        if($handle = opendir($directory))
        {
            while(($file = readdir($handle)) !== false)
            {
                $path = $directory.'/'.$file;
                if($file != '.' && $file != '..')
                {
                    if(is_file($path))
                    {
                        $size += filesize($path);
                    }elseif(is_dir($path))
                    {
                        $handlesize = self::recursive_directory_size($path);
                        if($handlesize >= 0)
                        {
                            $size += $handlesize;
                        }else{
                            return -1;
                        }
                    }
                }
            }
            closedir($handle);
        }
        
        return $size;
    } // End of recursive_directory_size
    
} // End of class DashboardHelpers

/* End of file Helpers.php */
/* Location: ./Dashboard/classes/Helpers.php */
