<?php
include_once __DIR__ ."/HelpersCrud.php";

function deleteAll($db,$table,$col,$id){
    $query="DELETE FROM `" . $table . "` WHERE `" . $col . "` = :id";
    $bind = [
        'id' => $id
    ];
    return $db->executeQuery($query, $bind);
}