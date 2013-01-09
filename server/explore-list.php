<?php
	ob_start();
	require_once 'EpiCurl.php';
	require_once 'EpiFoursquare.php';
	$clientId = 'USAVZNQTYR1CKIJDJXM0BFYV3EYOC5GMECOOAPWSSWG3BOHS';
	$clientSecret = 'RKSAEP0QMFMBO4XSXFWIJECDCWY3VGY4K54KIP2L21UP5WW2';
	//$code = 'BFVH1JK5404ZUCI4GUTHGPWO3BUIUTEG3V3TKQ0IHVRVGVHS';
	//$accessToken = 'DT32251AY1ED34V5ADCTNURTGSNHWXCNTOMTQM5ANJLBLO2O';
	$redirectUri = 'http://server.neatocode.com/spaceneedle/simpleTest.php';
	//$userId = '5763863';
	$fsObj = new EpiFoursquare($clientId, $clientSecret, $accessToken);
	$fsObjUnAuth = new EpiFoursquare($clientId, $clientSecret);
?>
<?php
	$ll = htmlspecialchars($_GET["ll"]);
	$item = 0;
	if ( htmlspecialchars($_GET["item"]) ) {
		$item = htmlspecialchars($_GET["item"]);
	}
	
	$example_images = array(
		'http://server.neatocode.com/spaceneedle/example_desert.png',
		'http://server.neatocode.com/spaceneedle/example_drinks.png',
		'http://server.neatocode.com/spaceneedle/example_food.png'
	);
	
	$venue = $fsObjUnAuth->get('/venues/search',array('ll' => $ll, 'venuePhotos' => 1));
	$first_item = true;
	$count = 0;
	foreach ($venue->response->groups[0]->items as &$item) {
		$name = $item->name;
		$lat = $item->location->lat;
		$lon = $item->location->lng;
		$distance = $item->location->distance;
		$image = $example_images[$count % 3];
		if ( $item->photos->groups[0]->items[0] ) {
			$image = $item->photos->groups[0]->items[0]->prefix
				. 'original'
				. $item->photos->groups[0]->items[0]->suffix;	
		}
		if (!$first_item) {
			?>,<?php
		}
?>	
			{
				"name" : "<?= $name ?>",
				"lat" : <?= $lat ?>,
				"lon" : <?= $lon ?>,
				"distance" : <?= $distance ?>,
				"image" : "<?= $image ?>"
			}	
<?php		
			$first_item = false;
			$count++;
	}
?>	