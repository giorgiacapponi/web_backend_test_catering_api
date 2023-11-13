<?php
namespace App\Models;

class Employee {
    public $id;
    public $name;
    public $last_name;
    public $date_of_birth;
    public $facility;

    public static function validateString($string, $min, $max) {
        $length = strlen($string);
        if ($length < $min || $length > $max) {
            throw new \Exception("the string must have a length between $min and $max.");
        }
        return $string;
    }

    public static function createEmployee(Array $data) {
        $employee = new Employee();
        if (isset($data['id'])) {
            $employee->id = $data['id'];
        }
        $employee->name = htmlspecialchars(self::validateString($data['name'], 1, 50));
        $employee->last_name = htmlspecialchars(self::validateString($data['last_name'], 1, 50));
        $employee->date_of_birth = $data['date_of_birth'];
        if (isset($data['facility_id'])) {
            $employee->facility = $data['facility_id'];
        }

        return $employee;
    }
}