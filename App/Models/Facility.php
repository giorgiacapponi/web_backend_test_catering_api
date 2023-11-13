<?php
namespace App\Models;
class Facility{
    public $id;
    public $name;
    public $creation_date;
    public $location;
    public $tags;
    

    public static function validateString($string, $min, $max) {
        $length = strlen($string);
        if ($length < $min || $length > $max) {
            throw new \Exception("the string must have a length between $min and $max.");
        }
        return $string;
    }
    
    public static function createFacility(Array $data) {
        $facility = new Facility();
        if (isset($data['id'])) {
            $facility->id = $data['id'];
        }
        $facility->name = htmlspecialchars(self::validateString($data['name'], 1, 255));
        if (isset($data['creation_date'])) {
            $facility->creation_date = $data['creation_date'];
        }
        
    
        return $facility;
    }
    

}

?>