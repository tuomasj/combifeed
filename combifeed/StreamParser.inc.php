<?php
/**
 * 
 **/
class StreamParser
{
    public static function create($params)
    {
        if(is_array($params) && array_key_exists('type', $params) && array_key_exists('url', $params))
        {
            $key = $params['type'];
            $url = $params['url'];

            // load the data
            $func = 'DataReader::loadFromURL';

            switch( $key )
            {
            case 'github':
                return new GithubParser( $func, $url );
            case 'twitter':
                return new TwitterParser( $func, $url );
            case 'flickr':
                return new FlickrParser( $func, $url );
            }
        }

        return NULL;
    }
}
?>
