<?php
include_once __DIR__ ."/HelpersCrud.php";


function newRecordLocation($db,$data){
    $query = "INSERT INTO `locations` (`city`, `address`, `phone_number`, `zip_code`, `country_code`) VALUES (:city, :address, :phone_number, :zip_code, :country_code)";
        $bind = [
            'city' => $data->city,
            'address' => $data->address,
            'phone_number' => $data->phone_number,
            'zip_code' => $data->zip_code,
            'country_code' => $data->country_code
        ];
      return  $db->executeQuery($query, $bind);
}

 function newRecordFacility($db,$data){
    $query = "INSERT INTO `facilities` (`name`,`creation_date`, `location_id`) VALUES (:name,:creation_date, :location_id)";
    $bind = [
        'name' => $data->name,
        'creation_date' => $data->creation_date,
        'location_id' => $data->location->id
    ];
    return $db->executeQuery($query, $bind);
}

function newRecordFacilityTag($db,$facility,$tag){
    $query = "INSERT INTO `facility_tag` (`facility_id`,`tag_id`) VALUES (:facility_id, :tag_id)";
    $bind = [
        'facility_id' => $facility->id,
        'tag_id' => $tag->id
    ];
    return $db->executeQuery($query, $bind);
}
function findByName($db, $name) {
    // Questa è una query SQL di esempio. Dovresti sostituirla con la tua query effettiva.
    $query = "SELECT * FROM `tags` WHERE `name` = :name";
    $bind = ['name' => $name];
    $result = getQueryRecords($db, $query, $bind);
    if (empty($result)) {
        return null;
    }
    return $result[0];  // Restituisce il primo record corrispondente
    // return $result;
}


function newRecordTag($db,$data){
    $query = "INSERT INTO `tags` (`name`) VALUES (:name)";
    $bind = ['name' => $data->name];
    return $db->executeQuery( $query, $bind);
 

}