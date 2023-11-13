<?php
namespace App\Models;
class Location{
    public $id;
    public $city;
    public $address;
    public $phone_number;
    public $zip_code;
    public $country_code;

  
    public static function validateString($string, $min, $max) {
        $length = strlen($string);
        if ($length < $min || $length > $max) {
            throw new \Exception("the string must have a length between $min and $max.");
        }
        return $string;
    }

    public static function createLocation(Array $data) {
        $location = new Location();
        if (isset($data['id'])) {
            $location->id = $data['id'];
        }
        $location->city = htmlspecialchars(self::validateString($data['city'], 1, 50));
        $location->address = htmlspecialchars(self::validateString($data['address'], 1, 255));
        $location->zip_code = htmlspecialchars(self::validateString($data['zip_code'], 3, 12));
        $location->country_code = htmlspecialchars(self::validateString($data['country_code'], 2, 2));
        if (isset($data['phone_number'])) {
            $location->phone_number = htmlspecialchars(self::validateString($data['phone_number'], 5, 15));
        }
        return $location;
    }
 
}