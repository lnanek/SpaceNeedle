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

	$example_images = array(
		'http://server.neatocode.com/spaceneedle/example_desert.png',
		'http://server.neatocode.com/spaceneedle/example_drinks.png',
		'http://server.neatocode.com/spaceneedle/example_food.png'
	);
		
	$ll = htmlspecialchars($_GET["ll"]);
	$item = 0;
	if ( htmlspecialchars($_GET["item"]) ) {
		$item = htmlspecialchars($_GET["item"]);
	}
	$venue = $fsObjUnAuth->get('/venues/search',array('ll' => $ll, 'venuePhotos' => 1));
	if ( $venue->response->groups[0]->items[$item]->name ) {
		$name = $venue->response->groups[0]->items[$item]->name;
		$lat = $venue->response->groups[0]->items[$item]->location->lat;
		$lon = $venue->response->groups[0]->items[$item]->location->lng;
		$distance = $venue->response->groups[0]->items[$item]->location->distance;
		$image = $example_images[$item % 3];
		if ( $venue->response->groups[0]->items[$item]->photos->groups[0]->items[0] ) {
			$image = $venue->response->groups[0]->items[$item]->photos->groups[0]->items[0]->prefix
				. 'original'
				. $venue->response->groups[0]->items[$item]->photos->groups[0]->items[0]->suffix;	
		}
?>	
			{
				"name" : "<?= $name ?>",
				"lat" : <?= $lat ?>,
				"lon" : <?= $lon ?>,
				"distance" : <?= $distance ?>,
				"image" : "<?= $image ?>"
			}
<?php } else { ?>
			{
				"name" : "Nothing now. Keep looking!",
				"lat" : null,
				"lon" : null,
				"distance" : 0,
				"image" : "https://www.google.com/images/srpr/logo3w.png"
			}
<?php } ?>
	}

