<?php

include_once dirname(__FILE__).'/StreamParser.inc.php';
include_once dirname(__FILE__).'/DataReader.inc.php';
include_once dirname(__FILE__).'/TimelineStream.inc.php';
include_once dirname(__FILE__).'/AbstractParser.inc.php';
include_once dirname(__FILE__).'/parsers/GithubParser.inc.php';
include_once dirname(__FILE__).'/parsers/TwitterParser.inc.php';
include_once dirname(__FILE__).'/parsers/FlickrParser.inc.php';
//
// combifeed
//
/**
 * 
 **/
class CombiFeed
{

    private $FEED;
    private $timeline;

    /**
     *
     * @param $array_of_feeds Array of feeds
     *
     */
    function __construct($array_of_feeds)
    {
        $this->FEED = $array_of_feeds;
        $this->timeline = NULL;
    }

    function build()
    {
        $timeline = new TimelineStream();
        foreach($this->FEED as $feed) {
            $parser = StreamParser::create($feed);
            if($parser != NULL) 
            {
                $timeline->add( $parser->getStream());
            }
            else
                return false;
        }
        $this->timeline = $timeline;
        return true;
    }

    function getLatest($num)
    {
        if($this->timeline != NULL)
            return $this->timeline->getLatest($num);
        else
            return NULL;
    }
}
?>
