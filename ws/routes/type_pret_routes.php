
    <?php
    require_once __DIR__ . '/../controllers/TypePretController.php';

    Flight::route('GET /typepret/form', ['TypePretController', 'form']);
    Flight::route('POST /typepret/create', ['TypePretController', 'create']);
    Flight::route('POST /typepret/list', ['TypePretController', 'list']);
    Flight::route('POST /typepret/update', ['TypePretController', 'update']);

Flight::route('GET /list-type-pret', ['TypePretController', 'getAll']);
// Flight::route('GET /prets/list', ['TypePretController', 'getAll']);

