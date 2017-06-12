<?php
// Routes


$app->get('/[{name}]', function ($request, $response, $args) {

	$this->logger->info("Slim-Skeleton '/' route");

	$result = [];

	$params = $request->getQueryParams();

	$response = $response->withHeader('Content-type', 'application/json');

	if (isset($params['lat']) && isset($params['lng'])) {
		$center_lat = $params['lat'];
		$center_lng = $params['lng'];
		$radius = isset($params['radius']) ? $params['radius'] : 25;
		
		$db = new PDO("mysql:host=localhost;dbname=maps", 'root', '123@cms');
		$query = sprintf("SELECT *, ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM resellers HAVING distance < '%s' ORDER BY distance LIMIT 0 , 5",
			  ($center_lat),
			  ($center_lng),
			  ($center_lat),
			  ($radius));
		$t = $db->query($query);

		foreach ($t->fetchAll() as $key => $value) {
			$result[] = [
				'Company' => $value['Company'],
				'Address' => $value['Address'],
				'Suburb' => $value['Suburb'],
				'Locality' => $value['Locality'],
				'PostCode' => $value['PostCode'],
				'Email' => $value['Email'],
				'Phone' => $value['Phone'],
				'Lat' => $value['Lat'],
				'Lng' => $value['Lng'],
				'Website' => $value['Website']
			];
		}
	}

	return $response->withJson($result);

	// $db = new PDO("sqlite:..//db.sqlite");
	// $sql = "Select * from mytable";

	// $result = [];
	// foreach ($db->query($sql) as $key => $value) {
	// 	$result[] = [
	// 		'Company' => $value['Company'],
	// 		'Address' => $value['Address'],
	// 		'Suburb' => $value['Suburb'],
	// 		'Locality' => $value['Locality'],
	// 		'PostCode' => $value['PostCode'],
	// 		'Email' => $value['Email'],
	// 		'Phone' => $value['Phone'],
	// 		'Lat' => $value['Lat'],
	// 		'Lng' => $value['Lng']
	// 	];
	// }
	// $response->withJson($result);

	// return $response;
});
