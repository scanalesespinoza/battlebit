<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_httprequest.php 6727 2011-06-14 04:35:10Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
class JSNISHTTPRequest 
{ 
    var $_fp;        // HTTP socket 
    var $_url;        // full URL 
    var $_host;        // HTTP host 
    var $_protocol;    // protocol (HTTP/HTTPS) 
    var $_uri;        // request URI 
    var $_port;        // port 
    
    // scan url 
    function _scanUrl() 
    { 
        $req = $this->_url; 
        
        $pos = strpos($req, '://'); 
        $this->_protocol = strtolower(substr($req, 0, $pos)); 
        
        $req = substr($req, $pos+3); 
        $pos = strpos($req, '/'); 
        if($pos === false) 
            $pos = strlen($req); 
        $host = substr($req, 0, $pos); 
        
        if(strpos($host, ':') !== false) 
        { 
            list($this->_host, $this->_port) = explode(':', $host); 
        } 
        else 
        { 
            $this->_host = $host; 
            $this->_port = ($this->_protocol == 'https') ? 443 : 80; 
        } 
        
        $this->_uri = substr($req, $pos); 
        if($this->_uri == '') 
            $this->_uri = '/'; 
    } 
    
    // constructor 
    function JSNISHTTPRequest($url) 
    { 
        $this->_url = $url; 
        $this->_scanUrl(); 
    } 
    
	function &getInstance()
	{
		static $instanceHTTPRequest;
		
		if ($instanceHTTPRequest == null)
		{
			$instanceHTTPRequest = new JSNISHTTPRequest();
		}
		return $instanceHTTPRequest;
	}
    
    // download URL to string 
    function DownloadToString() 
    { 
        if(!function_exists('fsockopen')) return false;
		
		$crlf = "\r\n"; 
        
        // generate request 
        $req = 'GET ' . $this->_uri . ' HTTP/1.0' . $crlf 
            .    'Host: ' . $this->_host . $crlf 
            .    $crlf; 
        
        // fetch 
        $this->_fp = @fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port); 
        @fwrite($this->_fp, $req); 
        while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp)) 
            @$response .= fread($this->_fp, 1024); 
        @fclose($this->_fp); 
        
        // split header and body 
        $pos = @strpos($response, $crlf . $crlf); 
        if($pos === false) 
            return(@$response); 
        $header = substr($response, 0, $pos); 
        $body 	= substr($response, $pos + 2 * strlen($crlf)); 
        
        // parse headers 
        $headers 	= array(); 
        $lines 		= explode($crlf, $header); 
        foreach($lines as $line) 
            if(($pos = strpos($line, ':')) !== false) 
                $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1)); 
        
        // redirection? 
        if(isset($headers['location'])) 
        { 
            $http = new JSNISHTTPRequest($headers['location']); 
            return($http->DownloadToString($http)); 
        } 
        else 
        { 
            return($body); 
        } 
    } 
} 
?>