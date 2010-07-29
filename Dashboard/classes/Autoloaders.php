<?php if ( ! defined( 'APPPATH' ) ) exit( 'Direct access to the individual scripts is not allowed!' );

/**
* Widget autoloader
*/
class WidgetAutoloader
{
	private static $extensions;
	
	public static function autoloader( $widget = '' )
	{
		self::get_extensions();
		$widget_path = WIDGETDIR . '/' . $widget . self::$extensions['widget'];
		if ( is_readable( $widget_path ) )
		{
			include_once $widget_path;
			return true;
		}
		else
		{
			return false;
		}
	} // End of public static function autoload
	
	private static function get_extensions()
	{
		if ( isset( self::$extensions ) )
		{
			return self::$extensions;
		}
		else
		{
			self::$extensions = explode( ',', spl_autoload_extensions() );
			$extensions = array();
			for ( $i = 0, $max = count( self::$extensions ); $i  < $max; $i ++ )
			{
				if ( preg_match( '/^[^.].+=>\..+$/', self::$extensions[$i] ) )
				{
					$e = explode( '=>', self::$extensions[$i] );
					$label = $e[0];
					$extension = $e[1];
					$extensions[$label] = $extension;
					unset( $extension, $label, $e );
				}
				elseif ( preg_match( '/^\..+$/', self::$extensions[$i] ) )
				{
					$extensions[] = self::$extensions[$i];
				}
			}
			self::$extensions = $extensions;
			return $extensions;
		}
	} // End of private static function get_extensions
	
} // End of class WidgetAutoloader

/* End of file Autoloaders.php */
/* Location: ./Dashboard/classes/Autoloaders.php */