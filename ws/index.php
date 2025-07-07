<?php
require 'vendor/autoload.php';
require 'db.php';
require 'routes/etudiant_routes.php';
// require 'routes/fond_routes.php';
require_once __DIR__ . '/routes/fond_routes.php';

Flight::set('flight.views.path', __DIR__ . '/../');

Flight::start();