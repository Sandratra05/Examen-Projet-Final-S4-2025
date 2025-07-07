<?php
require_once __DIR__ . '/../controllers/TypePretController.php';

Flight::route('GET /typepret/form', ['TypePretController', 'form']);
Flight::route('POST /typepret/create', ['TypePretController', 'create']);
Flight::route('GET /typepret/success', ['TypePretController', 'success']);