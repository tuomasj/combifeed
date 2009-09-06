<?php
/**
 * 
 **/
abstract class AbstractParser
{
    protected $buffer;

    public function __construct( $func, $url )
    {
        $this->url = $url;
        $this->func = $func;
    }

    private function load()
    {
        $func = $this->func;
        return call_user_func($this->func,  $this->url );
    }

    public function fetchStream()
    {
        return $this->load();
    }

    public abstract function getStream();

    public abstract function getMeta();

    public static function createMeta($name, $url)
    {
        return (object) array('name' => $name, 'url' => $url);
    }

}
?>
