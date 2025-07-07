<?php
function getDB() {

    // $host = 'localhost';
    // $dbname = 'db_s2_ETU003197';
    // $username = 'root';
    // $password = '';

    $host = 'localhost';
    $dbname = 'db_s2_ETU003197';
    $username = 'root';
    $password = '';

    try {
        return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die(json_encode(['error' => $e->getMessage()]));
    }
}
