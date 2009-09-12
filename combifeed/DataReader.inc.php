<?php
define('TEMP_DIR', 'cache/');
define('CACHE_EXPIRES', 60*5); // 10 minutes
define('CACHE_PREFIX', 'cache_');

define('CACHE_OLD', 0);
define('CACHE_OK', 1);
define('CACHE_MISSING', 2);

class DataReader {

    private static $error_message;

    private static function readURL($url)
    {
        $timeout = 10;
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        $content = curl_exec( $ch );
        $response = curl_getinfo( $ch );
        curl_close ( $ch );
        if($response['http_code'] == 200)
        {
            return $content;
        }
        return NULL;
    }

    private static function isCached($filename)
    {
        if(file_exists($filename))
        {
            $age = time() - filemtime($filename);
            if($age < CACHE_EXPIRES)
            {
                return CACHE_OK;
            }
            else
                return CACHE_OLD;
        }
        else
            return CACHE_MISSING;
    }

    private static function writeCache($filename, $buffer)
    {
        if(!file_put_contents($filename, $buffer))
            return false;
        if(!chmod($filename, 0777))
            return false;
        return true;
    }

    private static function readCache($filename)
    {
        $buf = file_get_contents($filename) or die('Cannot read: '.$filename);
        return $buf;
    }

    private static function createFilename($url)
    {
        return CACHE_PREFIX.md5($url);
    }

    public static function loadFromURL($url)
    {
        $filename = TEMP_DIR.DataReader::createFilename($url);
        $cache = DataReader::isCached($filename);
        if( $cache == CACHE_MISSING)
        {
            $buffer = DataReader::readURL($url);
            if($buffer)
            {
                DataReader::writeCache( $filename, $buffer ) or die('No write access');

            }
            else
            {
                die('Unable to get content');
            }
            return $buffer;
        }
        else
        if( $cache == CACHE_OK)
        {
            $buf = DataReader::readCache($filename);
            return $buf;
        }
        else
        if( $cache == CACHE_OLD)
        {
            $buffer = DataReader::readURL($url);
            if($buffer)
            {
                DataReader::writeCache( $filename, $buffer);
                return $buffer;
            }
            else
            {
                return DataReader::readCache($filename);
            }
        }
    }
};
?>
