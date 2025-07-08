<?php
require_once __DIR__ . '/../models/TypePretModel.php';
require_once __DIR__ . '/../helpers/Utils.php';


class TypePretController{
    public static function getAll(){
        $type_prets = TypePretModel::getAll();
        Flight::json($type_prets);
    }
}