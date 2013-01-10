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
		
	$ll = "36.060944,-115.133735"; // Default to the Venetian.
	if ( htmlspecialchars($_GET["ll"]) ) {
		$ll = htmlspecialchars($_GET["ll"]);
	}
	
	$item = 0; // Default to item 0.
	if ( htmlspecialchars($_GET["item"]) ) {
		$item = htmlspecialchars($_GET["item"]);
	}
	
	$venue = $fsObjUnAuth->get('/venues/explore',array('ll' => $ll, 'venuePhotos' => 1));
	
	$items = $venue->response->groups[0]->items;
	
	$default_reason = $venue->response->groups[0]->items[0]->reasons->items[0]->message;

	$first_item = true;

	$count = 0;

	$item = $items[$item];
	//foreach ($items as &$item) {	
	
		if (!$item) {
?>
			{
				"name" : "Nothing now. Keep looking!",
				"lat" : null,
				"lon" : null,
				"distance" : 0,
				"image" : null,
			}		
<?php
		} else { 
	
		$user_photo = $item->venue->likes->groups[0]->items[0]->photo;
		$name = $item->venue->name;
		$lat = $item->venue->location->lat;
		$lon = $item->venue->location->lng;
		$distance = $item->venue->location->distance;
		$image = $example_images[$count % 3];
		if ( $item->venue->photos->groups[0]->items[0]->url ) {
			$image = $item->venue->photos->groups[0]->items[0]->url;	
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
				"image" : "<?= $image ?>", 
				"reason" : "<?= $default_reason ?>",
				"user_photo" : "<?= $user_photo ?>"
			}	
<?php		
			$first_item = false;
			$count++;
			}
	//}
?>	



