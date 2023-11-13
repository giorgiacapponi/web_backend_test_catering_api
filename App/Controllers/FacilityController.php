<?php
namespace App\Controllers;


include_once __DIR__ ."../../Helpers/HelpersRead.php";
include_once __DIR__ ."../../Helpers/HelpersDelete.php";
include_once __DIR__ ."../../Helpers/HelpersCreate.php";
include_once __DIR__ ."../../Helpers/HelpersUpdate.php";

use App\Models\Facility;
use App\Models\Location;
use App\Models\Tag;
use App\Plugins\Http\Response as Status;
use App\Plugins\Http\Exceptions;


use PDO;

class FacilityController extends BaseController {
    
    
    public function read(){
       
        $results=[];
        $records_facilities=null;
        $next_page="";
        $prev_page="";
        

  
     
           
           // Check if 'id' is set in the GET parameters and retrive facility by id
           if (isset($_GET['id'])) {
               if (is_numeric($_GET['id'])) {
                   $records_facilities = getOneRowById($this->db, 'facilities', 'id', $_GET['id']);
                   
                   if (!$records_facilities) {
                       (new Status\NotFound(['success' => false, 'message' => 'No records found!']))->send();
                    exit;
                }
            } else {
                // If 'id' is not a number
                (new Status\BadRequest(['success' => false, 'message' => 'Entered data is not valid']))->send();
                exit;
            }
            
            // If the GET parameters are empty, retrieve all facilities
        } elseif ((empty($_GET)) || (isset($_GET['next_page']))||(isset($_GET['prev_page']))){
           if(isset($_GET['next_page'])){
            $next_page=$_GET['next_page'];
            $prev_page=$next_page;
           }elseif(isset($_GET['prev_page'])){
            $next_page="";
            $prev_page=$_GET['prev_page'];
           }
            $records_facilities = getAllFacilities($this->db,$next_page,$prev_page);
            
            if (!$records_facilities) {
                (new Status\NotFound(['success' => false, 'message' => 'No records found!']))->send();
                exit;
            }
            
            // If a search query is set in the GET parameters
        } elseif (isset($_GET['query']) && isset($_GET['next_page'])||isset($_GET['query']) && isset($_GET['prev_page'])||isset($_GET['query'])) {
            if(isset($_GET['next_page'])){
                $next_page=$_GET['next_page'];
                $prev_page=$next_page;
               }elseif(isset($_GET['prev_page'])){
                $next_page="";
                $prev_page=$_GET['prev_page'];
               }
               
            $records = getSearchQueryFacilities($this->db,$next_page,$prev_page);
            
            // Retrieve the facility records that match the search query
            if ($records && count($records) > 0) {
                foreach ($records as $record) {
                    $records_facilities[] = getOneSingleRecord($this->db, 'facilities', 'id', $record['id']);
                }
            }
            if (!$records_facilities) {
                (new Status\NoContent(['success' => true, 'message' => 'No records found!']))->send();
                exit;
            }
        }
        
        // If there are records found
        if ($records_facilities && count($records_facilities) > 0) {
            foreach ($records_facilities as $record) {
                
                // Retrieve the corresponding location record from the database
                $locationRecord = getOneSingleRecord($this->db, 'locations', 'id', $record['location_id']);
                $new_location = Location::createLocation($locationRecord);
                
                //retrieve corresponding tag and create an array of Tag objects
                $tagRecords = getOneRowById($this->db, 'facility_tag', 'facility_id', $record['id']);
                $new_tags = array_map(function($tagRecord) {
                    $tagDetailRecord = getOneSingleRecord($this->db, 'tags', 'id', $tagRecord['tag_id']);
                    $new_tag = Tag::createTag($tagDetailRecord);
                    return $new_tag;
                }, $tagRecords);

                // facility with all data
                $new_facility = Facility::createFacility($record);
                $new_facility->location = $new_location;
                $new_facility->tags = $new_tags;

             
                $results[] = $new_facility;
            }
        }
        //  take last id to implement cursor pagination
        if(!isset($_GET['next_page'])|| $next_page ||$prev_page){

            $last_result=$results[count($results)-1]->id;
            $next_page=base64_encode($last_result);
      }
         //  response
        (new Status\Ok(['success' => true, 'data' => $results,'next_page'=>$next_page,'prev_page'=>$prev_page]))->send();
                
    }


    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);

        // Retrieve the facility record from the database
        $facility = getOneSingleRecord($this->db, 'facilities', 'id', $data['id']);

        if (!$facility) {
            (new Status\NotFound(['success' => false, 'message' => 'No records found']))->send();
            exit;
        }
        if (!$data || !isset($data['id'])) {
            (new Status\BadRequest(['success' => false, 'message' => 'Entered data are not valid']))->send();
            exit;
        }  

        // Remove  tags with facility_id  and facility
        $facility = new Facility();
        $facility->id = $data['id'];
        deleteAll($this->db, 'facility_tag', 'facility_id', $facility->id);
        $deleteFacilityResult = deleteAll($this->db, 'facilities', 'id', $facility->id);

        // response
        if (!$deleteFacilityResult) {
            (new Status\InternalServerError(['success' => false, 'message' => 'Internal server errors']))->send();
            exit;
        }else{
            (new Status\Created(['success' => true, 'message' => 'removed']))->send();
        }
       }
    }
    


   
    public function create() {
            $data = json_decode(file_get_contents('php://input'), true);
    
        // new record in location
        if(!isset($data['location'])|| empty($data['location'])){
            throw new \Exception('location data are required');
        }
        $new_location = Location::createLocation($data['location']);
        $new_location_row = newRecordLocation($this->db, $new_location);
    
        $new_location->id = $this->db->getLastInsertedId();
    
        // new record in facilities with location
        $new_facility = Facility::createFacility($data);
        $new_facility->location = $new_location;
        $new_facility_row = newRecordFacility($this->db, $new_facility);
    
   
        $new_facility->id = $this->db->getLastInsertedId();
    
        // new record in facility_tag
        if (isset($data['tag_name']) && $data['tag_name']) {
         
            foreach ($data['tag_name'] as $tag_name) {
                // Check if the tag already exists
                  $tag = findByName($this->db, $tag_name);
                  $old_tag=TAG::createTag($tag);
                  if (!$tag) {
                      // If the tag doesn't exist, create a new one
                      $new_tag = Tag::createTag(['name' => $tag_name]);
  
                      $new_tag_row = newRecordTag($this->db,$new_tag);
                      $new_tag->id = $this->db->getLastInsertedId();
                      $new_facility_tag_row = newRecordFacilityTag($this->db, $new_facility, $new_tag);
                   
                  }else {
                  //   if the tag exists, only create a new record in facility_tag
                      $new_facility_tag_row = newRecordFacilityTag($this->db, $new_facility,$old_tag);
                  }
            }
        }
        // response
        if ($new_facility_row && $new_location_row) {
            (new Status\Created(['success' => true, 'message' => 'Record created successfully']))->send();
        } else {
            (new Status\InternalServerError(['success' => false, 'message' => 'Internal server errors']))->send();
        }
    }

    
public function update() {
   
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);

        // Check if the facility to be updated exists in the database
        $facility = getOneSingleRecord($this->db, 'facilities', 'id', $data['id']);

        if (!$facility) {
            (new Status\NotFound(['success' => false, 'message' => 'No records found']))->send();
            exit;
        }

        // Check if the received location ID matches the existing facility's location ID
        if ($facility['location_id'] != $data['location']['id']) {
            (new Status\BadRequest(['success' => false, 'message' => 'Entered data are not valid']))->send();
            exit;
        }
        // update the location records
        $location = Location::createLocation($data['location']);
        $update_location = updateLocation($this->db, $location);

        // update facility records
        $facility = Facility::createFacility($data);
        $facility->location = $location;
        $update_facility = updateFacility($this->db, $facility);

  // If there are tags, first remove all existing tag records for the structure
        if (!isset($data['tag_name']) || empty($data['tag_name'])) {
            deleteAll($this->db, 'facility_tag', 'facility_id', $facility->id);
        } else {
        if (isset($data['tag_name']) && $data['tag_name']) {
        deleteAll($this->db, 'facility_tag', 'facility_id', $facility->id);

            foreach ($data['tag_name'] as $tag_name) {
              // Check if the tag already exists
                $tag = findByName($this->db, $tag_name);
                $old_tag=TAG::createTag($tag);
                if (!$tag) {
                    // If the tag doesn't exist, create a new one
                    $new_tag = Tag::createTag(['name' => $tag_name]);

                    $new_tag_row = newRecordTag($this->db,$new_tag);
                    $new_tag->id = $this->db->getLastInsertedId();
                    $new_facility_tag_row = newRecordFacilityTag($this->db, $facility, $new_tag);
                 
                }else {
                //   if the tag exists, only create a new record in facility_tag
                    $new_facility_tag_row = newRecordFacilityTag($this->db, $facility,$old_tag);
                }
        
        }
        
    }
}

        // response
        if ($update_facility && $update_location) {
            (new Status\Created(['success' => true, 'message' => 'Record updated successfully']))->send();
        } else {
            (new Status\InternalServerError(['success' => false, 'message' => 'Internal server errors']))->send();
        }
    }
}

 

 
}