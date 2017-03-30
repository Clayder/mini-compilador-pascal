<?php

namespace App\Lexico;

/**
 * Description of Letra
 *
 */
class Letra
{
    public static function ehLetra($letra){
        if(ord($letra) >= ord("a") && ord($letra) <= ord("z") || ord($letra) >= ord("A") && ord($letra) <= ord("Z")){
            return true;
        }else{
            return false;
        }
    }
    
}
