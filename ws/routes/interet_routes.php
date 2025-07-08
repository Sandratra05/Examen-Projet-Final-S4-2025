<?php
require_once __DIR__ . '/../controllers/InteretController.php';


Flight::route('GET /get-form', ['InteretController', 'getForm']);
Flight::route('GET /fonds', ['InteretController', 'getAll']);
Flight::route('POST /interets/list', ['InteretController', 'getInteretParPeriode']);
// Flight::route('GET /fonds/@id', ['InteretController', 'getById']);
// Flight::route('POST /fonds', ['InteretController', 'create']);
// Flight::route('PUT /fonds/@id', ['InteretController', 'update']);
// Flight::route('DELETE /fonds/@id', ['InteretController', 'delete']);
