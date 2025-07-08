<?php
require_once __DIR__ . '/../controllers/PretController.php';

Flight::route('GET /prets', ['PretController', 'showFormPret']);
Flight::route('POST /prets/create', ['PretController', 'createPret']);
Flight::route('GET /prets/liste', ['PretController', 'listePret']);
Flight::route('GET /prets/simulation/@id', ['PretController', 'simulerPret']);
Flight::route('GET /prets/pdf/@id', ['PretController', 'afficherPdfPret']);
Flight::route('POST /prets/comparaison', 'PretController::comparaisonPrets');



// Flight::route('GET /prets/@id', ['EtudiantController', 'getById']);
// Flight::route('POST /prets', ['EtudiantController', 'create']);
// Flight::route('PUT /prets/@id', ['EtudiantController', 'update']);
// Flight::route('DELETE /prets/@id', ['EtudiantController', 'delete']);
