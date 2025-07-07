<?php
require_once __DIR__ . '/../models/TypePretModel.php';
require_once __DIR__ . '/../helpers/Utils.php';



class EtudiantController {



    public static function form() {
    Flight::render('typepret-form.php', []);
    }

}
