<?php
include_once __DIR__ ."/HelpersCrud.php";

function getOneRowById($db,$table,$col,$id){
    $query="SELECT * FROM `" . $table . "` WHERE `" . $col . "` = :id";
    $bind=[
        "id"=>$id
    ]; 
    return getQueryRecords($db,$query,$bind);
}


// all records with cursor pagination
function getAllFacilities($db,$next_page="",$prev_page=""){
    if($next_page){
        $id=base64_decode($next_page);
        $query = "SELECT * FROM `facilities` WHERE `id` > :id ORDER BY `id` LIMIT 2";
        $bind=[
            "id"=>$id
        ];
        return getQueryRecords($db,$query,$bind);

    }elseif($prev_page){
        var_dump('prev');
        $id=base64_decode($prev_page);
        var_dump($id);
        $query = "SELECT * FROM `facilities` WHERE `id` <= :id ORDER BY `id` DESC  LIMIT 2";
        $bind=[
            "id"=>$id
        ];
        $results= getQueryRecords($db,$query,$bind);
        return array_reverse($results);
        
    }else{
        $query="SELECT * FROM `facilities` ORDER BY `id` LIMIT 2";
        $bind = [];
        return getQueryRecords($db,$query,$bind);

    }
}

function getSearchQueryFacilities($db,$next_page="",$prev_page=""){
    if($next_page){
        $id=base64_decode($next_page);
        $user_search = $_GET['query'];
        $query = "SELECT DISTINCT `facilities`.`id` FROM `facilities` 
        LEFT JOIN `locations` ON `facilities`.`location_id` = `locations`.`id`
        LEFT JOIN `facility_tag` ON `facilities`.`id` = `facility_tag`.`facility_id`
        LEFT JOIN `tags` ON `facility_tag`.`tag_id` = `tags`.`id`
        WHERE `facilities`.`name` LIKE :search AND `id`>:id
        OR `tags`.`name` LIKE :search AND `id`>:id
        OR `locations`.`city` LIKE :search AND `id`>:id 
        ORDER BY `id` LIMIT 2";
        $bind = [
            'search' => "%$user_search%",
            'id'=>$id
        ];
        return  getQueryRecords($db,$query,$bind);
    }elseif($prev_page){
        $id=base64_decode($next_page);
        $user_search = $_GET['query'];
        $query = "SELECT DISTINCT `facilities`.`id` FROM `facilities` 
        LEFT JOIN `locations` ON `facilities`.`location_id` = `locations`.`id`
        LEFT JOIN `facility_tag` ON `facilities`.`id` = `facility_tag`.`facility_id`
        LEFT JOIN `tags` ON `facility_tag`.`tag_id` = `tags`.`id`
        WHERE `facilities`.`name` LIKE :search AND `id`<:id
        OR `tags`.`name` LIKE :search  AND `id`<:id
        OR `locations`.`city` LIKE :search  AND `id`<:id
        ORDER BY `id`DESC LIMIT 2";
        $bind = [
        'search' => "%$user_search%",
        'id'=>$id
    ];
      $results= getQueryRecords($db,$query,$bind);
      return array_reverse($results);
    }else{
        $user_search = $_GET['query'];
        $query = "SELECT DISTINCT `facilities`.`id` FROM `facilities` 
        LEFT JOIN `locations` ON `facilities`.`location_id` = `locations`.`id`
        LEFT JOIN `facility_tag` ON `facilities`.`id` = `facility_tag`.`facility_id`
        LEFT JOIN `tags` ON `facility_tag`.`tag_id` = `tags`.`id`
        WHERE `facilities`.`name` LIKE :search
        OR `tags`.`name` LIKE :search
        OR `locations`.`city` LIKE :search 
        ORDER BY `id` LIMIT 2";
        $bind = [
            'search' => "%$user_search%"
        ];
        return  getQueryRecords($db,$query,$bind);
    }
}

function getOneSingleRecord ($db,$table,$col,$id){
    $query="SELECT * FROM `" . $table . "` WHERE `" . $col . "` = :id";
    $bind=[
        "id"=>$id
    ];
  return getOneQueryRecord($db,$query,$bind);
}

