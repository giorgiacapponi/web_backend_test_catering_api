<?php
namespace App\Models;
class Tag{
    public $id;
    public $name;
   

    public static function validateString($string, $min, $max) {
        $length = strlen($string);
        if ($length < $min || $length > $max) {
            throw new \Exception("the string must have a length between $min and $max.");
        }
        return $string;
    }
    
    public static function createTag(Array $data) {
        $tag = new Tag();
        if (isset($data['id'])) {
            $tag->id = $data['id'];
        }
        $tag->name = htmlspecialchars(self::validateString($data['name'], 1, 50));
        return $tag;
    }
}