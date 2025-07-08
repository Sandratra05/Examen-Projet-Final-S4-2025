<?php
require_once __DIR__ . '/../db.php';

class TypePretModel{
    public static function getAll(){
        $db = getDB();
        $sql = 'select * from ef_type_pret';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public static function ($id){
}