<?php
class FlickrParser extends AbstractParser
{
    public function getStream()
    {
        $service = $this->getMeta()->name;
        $buf = $this->fetchStream();
        $xml = new SimpleXmlElement($buf);
        $result = array();
        foreach($xml->channel->item as $entry) {  
            $media = $entry->children('http://search.yahoo.com/mrss/');
            $params = array(
                    'timestamp' => strtotime($entry->pubDate), 
                    'title' => (string)$entry->title, 
                    'link' => (string)$entry->link,
                    'thumbnail' => (string) $media->thumbnail->attributes(),
                    'body' => (string) strip_tags($media->description),
                    'origin' => $service
                   );
            if(strstr($entry->id, "PushEvent"))
            {
                $content = substr($entry->content, strpos($entry->content, '<div class="message">'));
                $content = substr($content, 0, strpos($content,"</div>"));
                $params = array_merge($params, array('message' => trim(strip_tags($content))));
            }
            $item = TimelineStream::createItem($this->getMeta(), $params);

            $result[] = $item;
        }  
        
        return $result;

    }

    public function getMeta()
    {
        return AbstractParser::createMeta('Flickr', 'http://flickr.com');
    }
}
?>
