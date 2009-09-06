<?php
class GithubParser extends AbstractParser
{
    public function getStream()
    {
        $service = $this->getMeta()->name;
        $buf = $this->fetchStream();
        $xml = new SimpleXmlElement($buf);
        $result = array();
        foreach($xml->entry as $entry) {  
            $params = array(
                    'type' => 'github',
                    'timestamp' => strtotime($entry->updated), 
                    'title' => (string)$entry->title, 
                    'link' => (string)$entry->link['href'],
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
        return AbstractParser::createMeta('Github', 'http://github.com');
    }
}
?>
