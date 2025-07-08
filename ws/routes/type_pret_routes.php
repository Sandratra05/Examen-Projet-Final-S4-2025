<?php
require_once __DIR__ . '/../controllers/TypePretController.php';

Flight::route('GET /list-type-pret', ['TypePretController', 'getAll']);
// Flight::route('GET /prets/list', ['TypePretController', 'getAll']);
