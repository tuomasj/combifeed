<?php
/**
 * 
 **/
class TimelineStream
{
    
    function __construct()
    {
        // code...
        $this->sorted = false;
        $this->feed = array();
    }

    function add($array)
    {
        $this->feed = array_merge((array)$this->feed, (array) $array);
        $this->sorted = false;
    }

    static function cmp($a, $b)
    {
        if($a->timestamp == $b->timestamp)
            return 0;
        return ($a->timestamp < $b->timestamp) ? 1 : -1;
    }

    function sort()
    {
        usort( $this->feed, array('TimelineStream', 'cmp'));
        $this->sorted = true;
    }

    function getLatest($num)
    {
        if(!$this->sorted)
            $this->sort();
        return array_slice($this->feed, 0 ,$num);
    }

    public static function createItem($meta, $array = NULL)
    {
        $defaults = array('body' => null, 'timestamp' => null, 'link' => null, 'origin' => null, 'type' => $meta->name);
        if($array && array_key_exists('timestamp', $array))
        {
            unset($defaults['timestamp']);
            return (object) array_merge($defaults, $array);
        }
        return (object) $defaults; 
    }
}
?>
