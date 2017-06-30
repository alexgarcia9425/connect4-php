<?php
/**
* This file pretends to simulate what in a real scenario
* would be a connect4/games RESTful resource
* Ideally, with more time, I would have used Laravle or Lumen
* to build the service.
*/
require '../vendor/autoload.php';
header('Content-Type: application/json');

//TODO: Refactor into objects if there's time

$request_method = $_SERVER['REQUEST_METHOD'];

// Mongo connection
$client = new MongoDB\Client("mongodb://localhost:27017");

// Fetch from games db gamestates collection
$collection = $client->games->gamestates;

if($request_method === "GET") {
	// Fin the game status
	$result = $collection->findOne( [ 'game_name' => 'the_only_one'] );
	// If there's no game create one
	if(!$result) {
		$board = [
				'1' => [],
				'2' => [],
				'3' => [],
				'4' => [],
				'5' => [],
				'6' => [],
				'7' => [],
	  		];
		$document = ['game_name' => 'the_only_one', 'game_state' => ['board' => $board, 'turn' => '1'] ];
		$collection->insertOne($document);
		$result = $collection->findOne( [ 'game_name' => 'the_only_one'] );
	}
	echo json_encode(['result' => $result]);
}

if($request_method === "PUT") {
	// Get PUT data
	$decoded_input = json_decode(file_get_contents("php://input"), true);
	// TODO: data validation before update
	$result = $collection->updateOne(['game_name' => 'the_only_one'], ['$set' => $decoded_input]);
	// return updated document
	echo json_encode(['result' => $result]);
}

