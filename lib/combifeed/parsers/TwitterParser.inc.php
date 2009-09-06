<?php
/**
 * 
 **/
class TwitterParser extends AbstractParser
{
    public function getStream()
    {
        $buf = $this->fetchStream();
        $xml = new SimpleXmlElement($buf);

        $result = array();
        foreach($xml->channel->item as $entry) {  
            $item = TimelineStream::createItem($this->getMeta(), array('timestamp' => strtotime($entry->pubDate), 'title' => (string)$entry->title, 'link' => (string)$entry->link));
            $result[] = $item;
        }  
        return $result;
    }

    public function getMeta()
    {
        return AbstractParser::createMeta('twitter', 'http://www.twitter.com');
    }
}
?>
