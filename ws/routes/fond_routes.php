<?php
require_once __DIR__ . '/../controllers/FondController.php';


Flight::route('GET /get-form', ['FondController', 'getForm']);
Flight::route('GET /fonds', ['FondController', 'getAll']);
Flight::route('GET /fonds/@id', ['FondController', 'getById']);
Flight::route('POST /fonds', ['FondController', 'create']);
// Flight::route('PUT /fonds/@id', ['FondController', 'update']);
Flight::route('DELETE /fonds/@id', ['FondController', 'delete']);
