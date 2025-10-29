<?php


try {
    $db = new PDO(
        'mysql:host=database;dbname=draftoDB;charset=utf8',
        'root',
        'root123'
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
