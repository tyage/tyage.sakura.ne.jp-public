<?php
require_once("./lib.php");

$db = new Database();
$pdo = $db->connect();

function pushToDB($data, $pdo) {
	$sql = "insert into headsnaps (url, image_url, uploaded, created) ".
		"values (:url, :image_url, :uploaded, NOW())";
	$pdo->prepare($sql)->execute($data);
}

// picasa
$picasa_url = "https://picasaweb.google.com/data/feed/tiny/featured?".
	"alt=jsonm&kind=photo&slabel=featured&max-results=10";
$picasa_data = json_decode(file_get_contents($picasa_url));
foreach ($picasa_data->feed->entry as $photo) {
	$photo_data = json_decode(file_get_contents($photo->link[3]->href));
	pushToDB(array(
		":url" => $photo->link[2]->href,
		":image_url" => $photo->media->thumbnail[2]->url,
		":uploaded" => date("Y-m-d H:i:s",
			$photo_data->entry->featuredDate/1000)
	), $pdo);
}

// flickr
$flickr_api_key = "";
$flickr_rest_url = "http://api.flickr.com/services/rest/";
$flickr_url = $flickr_rest_url.
	"?method=flickr.interestingness.getList&extras=url_n,date_upload".
	"&per_page=10&format=json&nojsoncallback=1&api_key=".$flickr_api_key;
$flickr_data = json_decode(file_get_contents($flickr_url));
foreach ($flickr_data->photos->photo as $photo) {
	pushToDB(array(
		":url" => "http://www.flickr.com/photos/".$photo->owner."/".$photo->id,
		":image_url" => $photo->url_n,
		":uploaded" => date("Y-m-d H:i:s", $photo->dateupload)
	), $pdo);
}
