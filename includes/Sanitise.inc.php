<?php
class Sanitise {

	/**
	 * Sanitise data for HTML output
	 */
	static function html($string, $charset = 'UTF-8')
		{
		return self::__html($string, false, $charset);
		}
		
	/**
	 * Sanitise get/post/request/cookie data for HTML output
	 */
	static function html_gpc($string, $charset = 'UTF-8')
		{
		return self::__html($string, true, $charset);
		}

	private static function __html($string, $stripslashes, $charset)
		{
		if (is_array($string))
			{
			$returnArray = array();
			foreach ($string as $key=>$item) $returnArray[$key] = self::__html($item, $stripslashes = true, $charset);
			return $returnArray;
			}
		else
			{
			// Adjust for magic_quotes
			if($stripslashes && get_magic_quotes_gpc()) $string = stripslashes($string);
			// trim whitespace and convert to HTML entities
			return htmlentities(trim($string), ENT_QUOTES, $charset); 
			}
		}
	
	/**
	 * Reverse magic quotes if set
	 */
	static function reverse_magic_quotes($string)
		{
		if (is_array($string))
			{
			$returnArray = array();
			foreach ($string as $key=>$item) $returnArray[$key] = self::reverse_magic_quotes($item);
			return $returnArray;
			}
		else
			{
			return get_magic_quotes_gpc() ? stripslashes($string) : $string;
			}
		}

    /**
     * Clean HTML e.g. of MS Word formatting
     */
    function clean_html($html)
        {
        // Strip comments
        $html = preg_replace('/\<\!--(.*?)--\>/su', '', $html);
        // Convert b to strong and i to em
        $html = preg_replace('/(\<\/?)i(\s+|\>)/su', '$1em$2', $html);
        $html = preg_replace('/(\<\/?)b(\s+|\>)/su', '$1strong$2', $html);
        // Strip unwanted tags
        $html = strip_tags($html, '<strong><em><u><p><br><a><ul><ol><li><img>');
        // Strip style attributes
        return preg_replace('/(\<[^>]+)\sstyle\="[^"]*"([^>]*\>)/su', '$1$2', $html);
        }
	}

