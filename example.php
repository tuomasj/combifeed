<?php
include 'combifeed/combifeed.php';

$STREAMS = array(
    array('type' => 'github', 'url' => 'http://github.com/tuomasj.atom'),
    array('type' => 'twitter', 'url' => 'http://twitter.com/statuses/user_timeline/41119069.rss'),
    array('type' => 'flickr', 'url' => 'http://api.flickr.com/services/feeds/photos_public.gne?id=38646842@N07&lang=en-us&format=rss_200'),
);

$feed = new Combifeed($STREAMS);
if($feed->build() == false)
{
    die('Unable to parse feeds');
}
$xml = $feed->getLatest(20);
?>
<html>
<head>
</head>
<body>
<ul>
<?php foreach($xml as $item) { ?>
    <li><pre><?php print_r($item);?></pre></li>
<?php } ?>
</ul>
</body>
</html>
