<?php
define('TEMP_DIR', 'cache/');
define('CACHE_EXPIRES', 60*10); // 10 minutes
define('CACHE_PREFIX', 'cache_');

class DataReader {

    private static $error_message;

    private static function readURL($url)
    {
        $timeout = 5;
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
            return $content;
        return NULL;
    }

    private static function isCached($filename)
    {
        if(file_exists($filename))
        {
            $age = filemtime($filename) - time() - CACHE_EXPIRES;
            if($age < 0)
            {
                return true;
            }
        }
        return false;
    }

    private static function writeCache($filename, $buffer)
    {
        @file_put_contents($filename, $buffer) or die('Cannot write '.$filename);
    }

    private static function readCache($filename)
    {
        return @file_get_contents($filename) or die('Cannot read: '.$filename);
    }

    private static function createFilename($url)
    {
        return CACHE_PREFIX.md5($url);
    }

    public static function loadFromURL($url)
    {
        $filename = TEMP_DIR.DataReader::createFilename($url);
        if( ($cache = DataReader::isCached($filename)) == FALSE)
        {
            $buffer = DataReader::readURL($url);
            if($buffer)
                DataReader::writeCache( $filename, $buffer );
            return $buffer;
        }
        else
            return DataReader::readCache($filename);

    }


};
?>
