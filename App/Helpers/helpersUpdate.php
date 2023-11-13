<?php
include_once __DIR__ ."/HelpersCrud.php";


function updateLocation($db,$data){
    $query = "UPDATE `locations` SET `city` = :city, `address` = :address, `phone_number` = :phone_number, `zip_code` = :zip_code, `country_code` = :country_code WHERE `id` = :id";
    $bind = [
        'id' => $data->id,
        'city' => $data->city,
        'address' => $data->address,
        'phone_number' => $data->phone_number,
        'zip_code' => $data->zip_code,
        'country_code' => $data->country_code
    ];
   return $db->executeQuery($query, $bind);
}


function updateFacility($db,$data){
    $query = "UPDATE `facilities` SET `name` = :name, `creation_date` = :creation_date, `location_id` = :location_id WHERE `id` = :id";
    $bind = [
        'id' => $data->id,
        'name' => $data->name,
        'creation_date' => $data->creation_date,
        'location_id' => $data->location->id
    ];
   return $db->executeQuery($query, $bind);
}