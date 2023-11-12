<?php
namespace App\Service;

class UtilsService{
    /**
     * @param string $string
     * @return string
     */
    public static function cleanInput(string $input){
        // removed trim because of removing 0
        return htmlspecialchars(strip_tags($input, ENT_NOQUOTES));
    }
}
