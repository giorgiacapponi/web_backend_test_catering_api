<?php
function getQueryRecords($db, $query, $bind){
    $db->executeQuery($query, $bind);
    $stmt = $db->getStatement();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $records;
}
 
function getOneQueryRecord($db,$query,$bind){
    $db->executeQuery($query, $bind);
    $stmt = $db->getStatement();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $record;
}